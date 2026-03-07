#!/usr/bin/env python3
"""
3D Lab queue worker (DB polling).
Polls knd_labs_3d_jobs, runs run_labs_3d_job.py (ComfyUI 3D integration).
"""
from __future__ import annotations

import json
import os
import subprocess
import sys
import time
from pathlib import Path


def _import_db():
    try:
        import pymysql
        return pymysql
    except Exception as e:
        raise RuntimeError("pip install pymysql") from e


def _connect(pym):
    return pym.connect(
        host=os.getenv("KND_DB_HOST", "127.0.0.1"),
        port=int(os.getenv("KND_DB_PORT", "3306")),
        user=os.getenv("KND_DB_USER", ""),
        password=os.getenv("KND_DB_PASS", ""),
        database=os.getenv("KND_DB_NAME", ""),
        autocommit=False,
        charset="utf8mb4",
        cursorclass=pym.cursors.DictCursor,
    )


def lease(conn):
    with conn.cursor() as c:
        c.execute(
            """SELECT id, public_id, input_image_path, quality, advanced_params_json, mode
               FROM knd_labs_3d_jobs
               WHERE status = 'queued' ORDER BY created_at ASC LIMIT 1 FOR UPDATE"""
        )
        row = c.fetchone()
        if not row:
            conn.rollback()
            return None
        c.execute(
            "UPDATE knd_labs_3d_jobs SET status = 'processing', processing_started_at = NOW() WHERE id = %s",
            (row["id"],),
        )
        conn.commit()
        return row


def process(conn, job, root):
    if not job.get("input_image_path"):
        with conn.cursor() as c:
            c.execute(
                "UPDATE knd_labs_3d_jobs SET status = 'failed', error_message = 'Image-to-3D mode required; text-only not supported yet', completed_at = NOW() WHERE id = %s",
                (job["id"],),
            )
        conn.commit()
        return

    run = root / "workers" / "run_labs_3d_job.py"
    if not run.exists():
        with conn.cursor() as c:
            c.execute(
                "UPDATE knd_labs_3d_jobs SET status = 'failed', error_message = 'Worker script missing', completed_at = NOW() WHERE id = %s",
                (job["id"],),
            )
        conn.commit()
        return

    seed = None
    if job.get("advanced_params_json"):
        try:
            adv = json.loads(job["advanced_params_json"])
            if isinstance(adv, dict) and "seed" in adv:
                seed = int(adv["seed"]) if adv["seed"] else None
        except (json.JSONDecodeError, TypeError, ValueError):
            pass
    payload = {
        "job_id": job["id"],
        "public_id": job["public_id"],
        "input_image_path": job.get("input_image_path"),
        "quality": job.get("quality") or "Standard",
        "seed": seed,
    }
    proc = subprocess.run(
        [sys.executable, str(run), "--payload", json.dumps(payload)],
        capture_output=True,
        text=True,
        cwd=str(root),
    )

    if proc.returncode != 0:
        err = (proc.stderr or proc.stdout or "Failed").strip()[:65535]
        with conn.cursor() as c:
            c.execute(
                "UPDATE knd_labs_3d_jobs SET status = 'failed', error_message = %s, completed_at = NOW() WHERE id = %s",
                (err, job["id"]),
            )
        conn.commit()
        return

    try:
        out = json.loads(proc.stdout.strip() or "{}")
    except json.JSONDecodeError:
        out = {"ok": False}

    if not out.get("ok"):
        with conn.cursor() as c:
            c.execute(
                "UPDATE knd_labs_3d_jobs SET status = 'failed', error_message = %s, completed_at = NOW() WHERE id = %s",
                (str(out.get("error", "Unknown"))[:65535], job["id"]),
            )
        conn.commit()
        return

    glb_rel = out.get("glb_path_rel")
    prev_rel = out.get("preview_path_rel")
    if not glb_rel and out.get("glb_path"):
        storage = root / "storage"
        try:
            glb_rel = str(Path(out["glb_path"]).resolve().relative_to(storage)).replace("\\", "/")
        except ValueError:
            pass
    if not prev_rel and out.get("preview_path"):
        storage = root / "storage"
        try:
            prev_rel = str(Path(out["preview_path"]).resolve().relative_to(storage)).replace("\\", "/")
        except ValueError:
            pass

    meta_json = None
    if out.get("meta"):
        try:
            meta_json = json.dumps(out["meta"])
        except (TypeError, ValueError):
            pass

    with conn.cursor() as c:
        c.execute(
            """UPDATE knd_labs_3d_jobs
               SET status = 'completed',
                   glb_path = COALESCE(%s, glb_path),
                   preview_path = COALESCE(%s, preview_path),
                   meta_json = COALESCE(%s, meta_json),
                   completed_at = NOW()
               WHERE id = %s""",
            (glb_rel, prev_rel, meta_json, job["id"]),
        )
    conn.commit()


def main():
    sleep_s = max(1, int(os.getenv("WORKER_SLEEP_SECONDS", "5")))
    root = Path(__file__).resolve().parents[1]
    pym = _import_db()
    conn = _connect(pym)
    print("[labs-3d-worker] started")
    try:
        while True:
            job = lease(conn)
            if job:
                print(f"[labs-3d-worker] job #{job['id']}")
                process(conn, job, root)
            else:
                time.sleep(sleep_s)
    except KeyboardInterrupt:
        print("\n[labs-3d-worker] stopped")
    finally:
        conn.close()


if __name__ == "__main__":
    main()
