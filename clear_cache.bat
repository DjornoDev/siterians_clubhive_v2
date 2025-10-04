@echo off
echo Clearing Laravel Cache...

cd /d "C:\inetpub\wwwroot\siterians_clubhive_v2"

echo Clearing config cache...
php artisan config:clear

echo Clearing route cache...
php artisan route:clear

echo Clearing view cache...
php artisan view:clear

echo Clearing application cache...
php artisan cache:clear

echo Optimizing configuration...
php artisan config:cache

echo Done!
pause
