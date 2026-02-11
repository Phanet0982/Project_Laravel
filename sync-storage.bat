@echo off
echo Syncing storage files to public directory...
xcopy /s /y "storage\app\public\*" "public\storage\"
echo Sync complete!
pause