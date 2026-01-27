@echo off
REM JUnit Testing Concept - PHPUnit Test Runner
REM Ye file tests ko run karne ke liye hai

echo ========================================
echo JUnit Testing - PHPUnit Implementation
echo ========================================
echo.

cd /d "%~dp0"

REM Check if vendor folder exists (PHPUnit installed hai ya nahi)
if not exist "vendor\bin\phpunit.bat" (
    echo [ERROR] PHPUnit install nahi hai!
    echo.
    echo Pehle ye command run karein:
    echo   composer install
    echo.
    echo Agar composer nahi hai, to:
    echo   1. Composer download karein: https://getcomposer.org/download/
    echo   2. Ya XAMPP ke saath composer aata hai
    echo.
    pause
    exit /b 1
)

echo [INFO] PHPUnit found! Running tests...
echo.

REM Method 1: Run FormValidator tests (JUnit example jaisa)
echo ========================================
echo Running FormValidator Tests...
echo ========================================
call vendor\bin\phpunit tests\TestFormValidator.php

echo.
echo ========================================
echo Tests Complete!
echo ========================================
echo.
pause
