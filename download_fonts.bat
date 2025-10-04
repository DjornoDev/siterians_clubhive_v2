@echo off
echo Downloading Poppins fonts locally...

cd /d "C:\inetpub\wwwroot\siterians_clubhive_v2"

echo Creating fonts directory...
if not exist "public\fonts" mkdir "public\fonts"
if not exist "public\fonts\poppins" mkdir "public\fonts\poppins"

echo Downloading font CSS...
curl -o "public\fonts\poppins\poppins.css" "https://fonts.bunny.net/css?family=poppins:400,500,600"

echo Creating local font CSS...
echo /* Local Poppins Font */ > "public\fonts\local-poppins.css"
echo @font-face { >> "public\fonts\local-poppins.css"
echo   font-family: 'Poppins'; >> "public\fonts\local-poppins.css"
echo   font-style: normal; >> "public\fonts\local-poppins.css"
echo   font-weight: 400; >> "public\fonts\local-poppins.css"
echo   font-display: swap; >> "public\fonts\local-poppins.css"
echo   src: url('./poppins/poppins-400.woff2') format('woff2'); >> "public\fonts\local-poppins.css"
echo } >> "public\fonts\local-poppins.css"
echo. >> "public\fonts\local-poppins.css"
echo @font-face { >> "public\fonts\local-poppins.css"
echo   font-family: 'Poppins'; >> "public\fonts\local-poppins.css"
echo   font-style: normal; >> "public\fonts\local-poppins.css"
echo   font-weight: 500; >> "public\fonts\local-poppins.css"
echo   font-display: swap; >> "public\fonts\local-poppins.css"
echo   src: url('./poppins/poppins-500.woff2') format('woff2'); >> "public\fonts\local-poppins.css"
echo } >> "public\fonts\local-poppins.css"
echo. >> "public\fonts\local-poppins.css"
echo @font-face { >> "public\fonts\local-poppins.css"
echo   font-family: 'Poppins'; >> "public\fonts\local-poppins.css"
echo   font-style: normal; >> "public\fonts\local-poppins.css"
echo   font-weight: 600; >> "public\fonts\local-poppins.css"
echo   font-display: swap; >> "public\fonts\local-poppins.css"
echo   src: url('./poppins/poppins-600.woff2') format('woff2'); >> "public\fonts\local-poppins.css"
echo } >> "public\fonts\local-poppins.css"

echo Done! Check public\fonts\ directory
pause
