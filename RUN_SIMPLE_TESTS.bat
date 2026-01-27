@echo off
REM Simple Test Runner - Bina Composer ke bhi kaam karega
REM Ye JUnit example ke "java TestRunner" jaisa hai

echo ========================================
echo JUnit Testing - Simple Test Runner
echo ========================================
echo.
echo Ye bina PHPUnit install kiye bhi kaam karega!
echo.

cd /d "%~dp0"

REM Check if PHP available hai
where php >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP nahi mila!
    echo XAMPP install karein ya PHP ko PATH mein add karein.
    pause
    exit /b 1
)

echo [INFO] Running simple tests...
echo.

REM Simple test runner run karein
php tests\SIMPLE_TEST_RUNNER.php

echo.
pause
