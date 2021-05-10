
@echo off
color a
title Lime Store Launcher v0.6
set EXEC_CMD="xampp-control.exe"
wmic process where (name=%EXEC_CMD%) get commandline | findstr /i %EXEC_CMD%> NUL
if errorlevel 1 (
   Start "d:\xampp\apache\bin\httpd.exe"

   Start  "d:\xampp\mysql\bin\mysqld.exe" --defaults-file="d:\xampp\mysql\bin\my.ini" --standalone"
   cls
   timeout /t 5
   start chrome localhost
   exit
) else (
   cls
   timeout /t 3
   start chrome localhost
   exit
)