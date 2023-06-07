@echo off
cls

SET /A PORT=9980
IF "%~1" == "" skipset
SET /A PORT=%1
:skipset
SET PHP_COMMAND=C:\php\php-8.0.25-Win32-vs16-x64\php.exe
SET PROJECT=%~dp0

%PHP_COMMAND% -S localhost:%PORT% -t %PROJECT%