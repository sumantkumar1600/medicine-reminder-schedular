

@echo off
title MedCare Reminder System
echo Starting reminder system...
:loop
C:\xampp\php\php.exe -c C:\xampp\php\php.ini C:\xampp\htdocs\medicine-reminder\public\check_reminders.php >> C:\xampp\htdocs\medicine-reminder\reminder_log.txt 2>&1
timeout /t 1 /nobreak > nul
goto loop
