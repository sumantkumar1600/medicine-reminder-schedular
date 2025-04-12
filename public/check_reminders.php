<?php
// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load environment
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->load();

// Database connection
try {
    $pdo = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}",
        $_ENV['DB_USER'],
        $_ENV['DB_PASS']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET time_zone = '+05:30';"); // IST
} catch(PDOException $e) {
    file_put_contents('php://stderr', "Database error: " . $e->getMessage());
    exit(1);
}

// Email template with inline styling
function sendMedicineEmail($to, $medicine) {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASS'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->Timeout = 30;

        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($to);
        $mail->Subject = "Reminder: Time for {$medicine['name']}";

        // HTML Email Template
        $mail->isHTML(true);
        $mail->Body = <<<HTML
        <div style="max-width: 600px; margin: 20px auto; font-family: 'Arial', sans-serif;">
            <div style="background: #3B82F6; color: white; padding: 20px; border-radius: 10px 10px 0 0;">
                <h2 style="margin: 0;">💊 Medication Reminder</h2>
            </div>
            <div style="background: #f8fafc; padding: 30px; border-radius: 0 0 10px 10px;">
                <h3 style="color: #1e40af; margin-top: 0;">{$medicine['name']}</h3>
                <p><strong>Dosage:</strong> {$medicine['dosage']}</p>
                <p><strong>Time:</strong> {$medicine['time']}</p>
                <hr style="border: 1px solid #e5e7eb; margin: 20px 0;">
                <p style="color: #6b7280;">This is an automated reminder from MedCare System</p>
            </div>
        </div>
        HTML;

        $mail->AltBody = "Time to take {$medicine['name']} ({$medicine['dosage']}) now";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: " . $mail->ErrorInfo);
        return false;
    }
}

// Main reminder logic with time debugging
date_default_timezone_set($_ENV['APP_TIMEZONE']);
$current_time = date('H:i:s');
$today_date = date('Y-m-d');

try {
    error_log("\n\n=== Checking reminders at $today_date $current_time ===");
    
    // Get matching medicines
    $stmt = $pdo->prepare("
        SELECT m.*, u.email 
        FROM medicines m
        JOIN users u ON m.user_id = u.id
        WHERE m.start_date <= :today 
          AND m.end_date >= :today 
          AND TIME(m.time) = :current_time
    ");
    
    $stmt->execute([
        ':today' => $today_date,
        ':current_time' => $current_time
    ]);

    $reminders = $stmt->fetchAll();
    $remindersSent = 0;

    error_log("Found " . count($reminders) . " medicines scheduled for $current_time");
    
    foreach ($reminders as $medicine) {
        error_log("Processing: {$medicine['name']} for {$medicine['email']}");
        
        if (sendMedicineEmail($medicine['email'], [
            'name' => $medicine['name'],
            'dosage' => $medicine['dosage'],
            'time' => date('h:i A', strtotime($medicine['time']))
        ])) {
            $remindersSent++;
            error_log("Successfully sent reminder for {$medicine['name']}");
        }
    }

    error_log("Total reminders sent: $remindersSent\n");
    
} catch (Exception $e) {
    error_log("Fatal Error: " . $e->getMessage());
}
?>