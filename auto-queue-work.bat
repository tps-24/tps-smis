@echo off
cd /d C:\xampp\htdocs\tps-smis
C:\xampp\php\php.exe artisan queue:work
