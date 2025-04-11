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
    $message = "Time to take {$medicine['name']}, Dosage: {$medicine['dosage']}";
    $headers = "From: no-reply@medicine-reminder.com";
    
    mail($to, $subject, $message, $headers);
}

function addMedicine($user_id, $name, $startDate, $endDate, $time, $dosage) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO medicines (user_id, name, start_date, end_date, time, dosage) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $name, $startDate, $endDate, $time, $dosage]);
}

function getMedicines($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM medicines WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



?>