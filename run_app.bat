@echo off
title Lebanese Marketplace Launcher
echo Starting Lebanese Marketplace...
echo.
echo Ensuring you are in the project directory...
cd /d "%~dp0"

echo.
echo Opening Google Chrome...
start chrome "http://127.0.0.1:8000"

echo.
echo Starting Laravel Server...
echo Press Ctrl+C to stop the server.
php artisan serve
