@echo off
REM ============================================================
REM SCD Lab - Multithreading Demo Runner (Windows / XAMPP)
REM
REM This script runs the PHP threading demo in both modes:
REM   1) NOLOCK  - without synchronization (no flock)
REM   2) LOCK    - with synchronization using flock(shared.lock)
REM
REM It does NOT touch any website routes or database.
REM ============================================================

cd /d "%~dp0"

echo ============================================================
echo  SCD Multithreading Demo - WanderLust Pakistan
echo ============================================================
echo.

REM ------------- MODE: NOLOCK -------------
echo [1/2] Running demo in NOLOCK mode (no synchronization) ...
php scripts\threading\thread_demo.php nolock
if errorlevel 1 (
    echo.
    echo [ERROR] PHP execution failed. Make sure php.exe from XAMPP
    echo is available in PATH or run this from XAMPP's shell.
    echo.
    pause
    goto :eof
)

REM Save a copy of this run's log
if exist scripts\threading\shared.log (
    copy /Y scripts\threading\shared.log scripts\threading\shared_nolock.log >nul
)

echo.
echo NOLOCK log saved as: scripts\threading\shared_nolock.log
echo.

REM ------------- MODE: LOCK -------------
echo [2/2] Running demo in LOCK mode (with flock synchronization) ...
php scripts\threading\thread_demo.php lock
if errorlevel 1 (
    echo.
    echo [ERROR] PHP execution failed in LOCK mode.
    echo.
    pause
    goto :eof
)

if exist scripts\threading\shared.log (
    copy /Y scripts\threading\shared.log scripts\threading\shared_lock.log >nul
)

echo.
echo LOCK log saved as   : scripts\threading\shared_lock.log
echo.

echo ============================================================
echo  DEMO COMPLETE
echo  - NOLOCK log: scripts\threading\shared_nolock.log
echo  - LOCK log  : scripts\threading\shared_lock.log
echo.
echo  Open these files in VS Code / Notepad to show:
echo    * Interleaving writes in NOLOCK mode
echo    * Synchronized behaviour in LOCK mode
echo ============================================================
echo.
pause

