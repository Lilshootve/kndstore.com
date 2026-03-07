# 3D Lab

Unified 3D generation: text, image, text+image, or recent creations. Safe mode only.

## Routes

- `GET /labs/3d-lab` or `/labs/3D-Lab.php` → 3D Lab page
- `POST /api/labs/3d-lab/create.php` → Create job
- `GET /api/labs/3d-lab/status.php?id={id}` → Job status
- `GET /api/labs/3d-lab/history.php` → User's recent creations
- `GET /api/labs/3d-lab/download.php?id={id}&format=glb|preview` → Download

## Database

```bash
mysql < sql/knd_labs_3d_jobs.sql
mysql < sql/points_ledger_add_3d_lab.sql
```

## Worker

```bash
python workers/labs_3d_worker.py
```

Env: KND_DB_HOST, KND_DB_PORT, KND_DB_NAME, KND_DB_USER, KND_DB_PASS

## TODO

- Connect `run_labs_3d_job.py` to dedicated ComfyUI 3D pipeline (separate from text2img/upscale)
- Implement wireframe, stats, texture toggle in viewer (model-viewer has limited support; may need Three.js)
- Smart presets by category (currently stored in LABS_3D_CATEGORY_PRESETS; worker to apply)
