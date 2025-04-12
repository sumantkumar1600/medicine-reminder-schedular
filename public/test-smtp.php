<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/mail.config.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->load();

try {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = $_ENV['MAIL_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['MAIL_USER'];
    $mail->Password = $_ENV['MAIL_PASS'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = $_ENV['MAIL_PORT'];
    $mail->SMTPDebug = 2; // Enable verbose output

    // Email Content
    $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
    $mail->addAddress('test@example.com');
    $mail->Subject = 'SMTP Test from MedCare';
    $mail->Body = 'This is a test email sent via SMTP';

    $mail->send();
    echo "Email sent successfully! Check Mailtrap Inbox";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>