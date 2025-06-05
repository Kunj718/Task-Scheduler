@echo off
set SCRIPT_PATH=%~dp0cron.php
set TASK_NAME=RunCronPHP

REM Delete the task if it already exists
schtasks /Delete /TN %TASK_NAME% /F >nul 2>&1

REM Create the scheduled task to run every hour using the full PHP path
schtasks /Create /SC HOURLY /TN %TASK_NAME% /TR "\"C:\xampp\php\php.exe\" %SCRIPT_PATH%" /F

echo Task Scheduler job has been set up to run cron.php every hour. 