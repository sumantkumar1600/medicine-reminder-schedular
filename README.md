# 💊 MediReminder

**MediReminder** is a responsive web application that helps users manage their medication schedule and receive automatic email reminders. Built with PHP, JavaScript, Tailwind CSS, and SMTP (via PHPMailer).

## 🚀 Features

- 🧾 User registration and login
- 💊 Add & manage medicine schedules
- 📬 Automatic email reminders using SMTP
- 📅 Medicine history tracking
- 📱 Responsive UI using Tailwind CSS
- ⏰ Real-time frontend clock and reminders

## 🛠 Tech Stack

- **Frontend**: HTML, Tailwind CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Email**: SMTP via PHPMailer
- **Scheduler**: Cron Jobs (for sending timely reminders)

## 📬 How Email Reminders Work

A background PHP script runs via cron job to:
1. Check current time and match with medicine schedule
2. Send email reminders using PHPMailer and SMTP

## 📂 Folder Structure

