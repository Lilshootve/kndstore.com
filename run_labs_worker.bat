@echo off
cd /d "%~dp0"
C:\xampp\php\php.exe workers\labs_worker.php --loop --sleep=2 --worker-id=PC1
pause