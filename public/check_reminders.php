<?php
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../config/mail.config.php';

date_default_timezone_set('UTC');
$currentTime = date('H:i');
$today = date('Y-m-d');

$stmt = $pdo->prepare("SELECT m.*, u.email 
                      FROM medicines m
                      JOIN users u ON m.user_id = u.id
                      WHERE start_date <= ? 
                      AND end_date >= ? 
                      AND time = ?");
$stmt->execute([$today, $today, $currentTime]);

while ($medicine = $stmt->fetch()) {
    $to = $medicine['email'];
    $subject = "Medicine Reminder";
    $message = "Time to take {$medicine['name']}, {$medicine['dosage']}";
    $headers = "From: no-reply@medicine-reminder.com";
    
    mail($to, $subject, $message, $headers);
}
?>