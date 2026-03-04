# InstantMesh 3D (Imagen → 3D) Setup

## Overview

The 3D integration uses **InstantMesh** as the generation engine. Users upload an image and receive a downloadable 3D model (OBJ/GLB). KND uses `TRIPOSR_API_URL` and `TRIPOSR_CALLBACK_SECRET` for compatibility with existing infrastructure.

## Database

1. Create the base table (if not exists):
   ```sql
   source sql/triposr_jobs.sql
   ```

2. Add quality column (V2):
   ```sql
   source sql/triposr_jobs_alter_quality.sql
   ```

## Configuration

1. Copy the example config:
   ```
   cp config/triposr_secrets.local.example.php config/triposr_secrets.local.php
   ```

2. Edit `config/triposr_secrets.local.php`:
   - `TRIPOSR_API_URL`: GPU server `/generate` endpoint (e.g. `https://gpu.example.com/generate`)
   - `TRIPOSR_CALLBACK_SECRET`: Shared secret for callback validation (`openssl rand -hex 32`)
   - `TRIPOSR_UPLOAD_DIR`: `triposr/uploads` (relative to storage/)
   - `TRIPOSR_OUTPUT_DIR`: `triposr/outputs` (relative to storage/)

## GPU Server Contract

The GPU server (InstantMesh) must:

1. **Accept POST** at `TRIPOSR_API_URL` with JSON:
   ```json
   {
     "job_id": "uuid",
     "image_url": "https://kndstore.com/api/triposr/image.php?t=uuid",
     "callback_url": "https://kndstore.com/api/triposr/callback.php",
     "secret": "shared_secret",
     "quality": "fast|balanced|high",
     "timestamp": 1234567890
   }
   ```

2. **Fetch the image** from `image_url`. KND serves images via `/api/triposr/image.php?t={job_uuid}` so the GPU can download them without exposing direct file paths.

3. **POST to callback_url** when done:
   - Success:
   ```json
   {
     "job_id": "uuid",
     "status": "completed",
     "model_url": "https://gpu-server/models/uuid.glb",
     "secret": "shared_secret"
   }
   ```
   - Failure:
   ```json
   {
     "job_id": "uuid",
     "status": "failed",
     "error_message": "Error description",
     "secret": "shared_secret"
   }
   ```

4. KND downloads the model from `model_url` and saves it to storage.

## Limits (Anti-abuse)

- **Active jobs**: Max 1 job in `pending` or `processing` per user.
- **Rate limit**: Max 10 submissions per hour per user (counted in `triposr_jobs`).
- **Image validation**: JPG/PNG/WebP only, max 10MB, max 4096×4096, MIME validated. SVG rejected.

## Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/triposr/submit.php` | POST | Submit image + quality, returns job_id |
| `/api/triposr/status.php` | GET | Poll job status (job_id) |
| `/api/triposr/cancel.php` | POST | Cancel pending job |
| `/api/triposr/download.php` | GET | Download generated model |
| `/api/triposr/image.php` | GET | Serve input image for GPU (t=job_uuid) |
| `/api/triposr/callback.php` | POST | Called by GPU server (validates secret) |

## Storage

- Uploads: `storage/triposr/uploads/{uuid}.jpg` (or .png, .webp)
- Outputs: `storage/triposr/outputs/{job_uuid}.glb` or `.obj`

## GPU Server (InstantMesh)

See `gpu-server/README_DEPLOY.md` for the InstantMesh implementation. Set `TRIPOSR_API_URL` to `https://your-gpu-host/generate`.
