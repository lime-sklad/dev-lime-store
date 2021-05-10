@echo off
color a
title Lime Store Launcher v0.6
set EXEC_CMD="httpd.exe"
wmic process where (name=%EXEC_CMD%) get commandline | findstr /i %EXEC_CMD%> NUL
if errorlevel 1 (
   Start "" "run_apache.vbs"
   Start "" "run_mysql.vbs"
   exit
)