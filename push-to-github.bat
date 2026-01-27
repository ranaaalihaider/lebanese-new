@echo off
echo ========================================
echo Lebanese Marketplace - GitHub Push Script
echo ========================================
echo.

REM Check if Git is installed
where git >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Git is not found in PATH!
    echo.
    echo Please do one of the following:
    echo 1. Restart this terminal/PowerShell
    echo 2. Use Git Bash instead (search for "Git Bash" in Start menu)
    echo 3. Add Git to PATH manually
    echo.
    pause
    exit /b 1
)

echo Git found! Proceeding with push...
echo.

REM Navigate to project directory
cd /d "C:\xampp\htdocs\lebanese-marketplace"

REM Configure Git (only needed first time)
echo Configuring Git...
git config --global user.name "Rana Ali Haider"
git config --global user.email "ranaaalihaider@github.com"

REM Initialize Git repository
echo.
echo Initializing Git repository...
git init

REM Add all files
echo.
echo Adding all files...
git add .

REM Commit
echo.
echo Committing files...
git commit -m "Initial commit - Lebanese Marketplace ready for deployment"

REM Add remote
echo.
echo Adding GitHub remote...
git remote add origin https://github.com/ranaaalihaider/lebanese-marketplace.git

REM Set main branch
echo.
echo Setting main branch...
git branch -M main

REM Push to GitHub
echo.
echo Pushing to GitHub...
echo You may be asked for GitHub credentials:
echo - Username: ranaaalihaider
echo - Password: Use your Personal Access Token (NOT your GitHub password)
echo.
git push -u origin main

echo.
echo ========================================
echo Done! Check https://github.com/ranaaalihaider/lebanese-marketplace
echo ========================================
pause
