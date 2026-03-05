# KND Labs Worker - Ejecución en Windows

El worker procesa los jobs en cola y envía prompts a ComfyUI (local o RunPod).

## Requisitos

- PHP CLI (con extensions: pdo_mysql, curl, json)
- Acceso a MySQL (remoto, p.ej. Hostinger)
- ComfyUI accesible (localhost o RunPod según provider)

## Uso

```bash
# Un solo job (single-run)
php workers/labs_worker.php

# Loop continuo (recomendado para Task Scheduler)
php workers/labs_worker.php --loop --sleep=2 --worker-id=PC1
```

- `--loop` : Ejecutar en bucle hasta que lo detengas
- `--sleep=N` : Segundos entre iteraciones (default: 2)
- `--worker-id=ID` : Identificador del worker (default: hostname)

## Task Scheduler (Windows)

1. Abre **Task Scheduler** (Programador de tareas)
2. Crear tarea básica o tarea…
3. **Trigger**:
   - Al iniciar sesión, o
   - Al iniciar el equipo + Repetir cada 1 minuto (opcional si usas un .bat con loop)
4. **Acción**: Iniciar un programa
   - Programa: `php.exe` (ruta completa, p.ej. `C:\php\php.exe`)
   - Argumentos: `C:\ruta\kndstore\workers\labs_worker.php --loop --sleep=2 --worker-id=PC1`
   - Iniciar en: `C:\ruta\kndstore`

## Alternativa: .bat con loop

Crea `workers/run_labs_worker.bat`:

```batch
@echo off
cd /d "%~dp0.."
:loop
php workers/labs_worker.php --loop --sleep=2 --worker-id=PC1
timeout /t 5
goto loop
```

Ejecuta el .bat manualmente o desde Task Scheduler (acción: iniciar el .bat).

## Notas

- **Máx. 1 job en processing** a la vez: el worker no toma otro job hasta que el actual esté en cola de ComfyUI.
- **Reintentos**: 3 intentos; tras 3 fallos el job pasa a `failed`.
- **MySQL 8.0+** recomendado por `FOR UPDATE SKIP LOCKED`; en 5.7 fallará (quitar `SKIP LOCKED` si es necesario).
