#!/usr/bin/env python3
"""
3D Lab single-job runner.
TODO: Invoke dedicated ComfyUI 3D pipeline. Placeholder creates stub GLB for flow testing.
"""
from __future__ import annotations

import argparse
import json
from pathlib import Path


def run(payload: dict) -> dict:
    pid = str(payload.get("public_id", "unknown"))
    root = Path(__file__).resolve().parents[1]
    out_dir = root / "storage" / "labs" / "3d-lab" / "output"
    prev_dir = root / "storage" / "labs" / "3d-lab" / "preview"
    out_dir.mkdir(parents=True, exist_ok=True)
    prev_dir.mkdir(parents=True, exist_ok=True)

    glb = out_dir / f"{pid}.glb"
    prev = prev_dir / f"{pid}.webp"

    # Placeholder GLB (minimal valid header)
    glb.write_bytes(b"glTF")
    prev.write_bytes(b"RIFFxxxxWEBPVP8 ")

    return {"ok": True, "glb_path": str(glb), "preview_path": str(prev)}


def main():
    p = argparse.ArgumentParser()
    p.add_argument("--payload", required=True)
    args = p.parse_args()
    payload = json.loads(args.payload)
    result = run(payload)
    print(json.dumps(result))
    return 0 if result.get("ok") else 1


if __name__ == "__main__":
    raise SystemExit(main())
