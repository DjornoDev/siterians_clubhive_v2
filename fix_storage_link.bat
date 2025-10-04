@echo off
echo Creating storage link for Laravel...

cd /d "C:\inetpub\wwwroot\siterians_clubhive_v2"

echo Creating storage symbolic link...
php artisan storage:link

echo Checking if storage directory exists...
if not exist "storage\app\public" (
    echo Creating storage directories...
    mkdir "storage\app\public"
    mkdir "storage\app\public\images"
    mkdir "storage\app\public\uploads"
)

echo Setting permissions...
icacls "storage" /grant "IIS_IUSRS:(OI)(CI)F" /T
icacls "bootstrap\cache" /grant "IIS_IUSRS:(OI)(CI)F" /T

echo Done!
pause
