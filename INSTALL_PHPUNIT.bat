@echo off
REM PHPUnit Installer - Composer se install karega

echo ========================================
echo PHPUnit Installation
echo ========================================
echo.

cd /d "%~dp0"

REM Check if composer exists
where composer >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Composer nahi mila!
    echo.
    echo Composer install karein:
    echo   1. Download: https://getcomposer.org/download/
    echo   2. Ya XAMPP ke bin folder mein check karein
    echo   3. Ya manually install karein
    echo.
    pause
    exit /b 1
)

echo [INFO] Composer found! Installing PHPUnit...
echo.

REM Install PHPUnit
composer install

if %errorlevel% equ 0 (
    echo.
    echo ========================================
    echo [SUCCESS] PHPUnit install ho gaya!
    echo ========================================
    echo.
    echo Ab aap tests run kar sakte hain:
    echo   - RUN_TESTS.bat double-click karein
    echo   - Ya: vendor\bin\phpunit tests\TestFormValidator.php
    echo.
) else (
    echo.
    echo [ERROR] Installation fail ho gaya!
    echo.
)

pause
