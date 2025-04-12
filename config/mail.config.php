<?php
require_once __DIR__ . '/../vendor/autoload.php';

function sendMedicineReminder($to, $medicineName, $dosage, $time, $recipient) {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        // Enable verbose SMTP debugging
        $mail->SMTPDebug = 2; // Add this line after $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASS'];
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['MAIL_PORT'];

        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = "Reminder: Time for $medicineName";
        
        $htmlContent = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: 'Poppins', sans-serif; }
                .container { max-width: 600px; margin: 20px auto; padding: 20px; }
                .header { background: #3B82F6; color: white; padding: 20px; border-radius: 10px 10px 0 0; }
                .content { background: #f8fafc; padding: 30px; border-radius: 0 0 10px 10px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>💊 Medication Reminder</h2>
                </div>
                <div class="content">
                    <h3>$medicineName</h3>
                    <p><strong>Dosage:</strong> $dosage</p>
                    <p><strong>Time:</strong> $time</p>
                    <hr>
                    <p>This is an automated reminder from MedCare System</p>
                </div>
            </div>
        </body>
        </html>
        HTML;

        $mail->Body = $htmlContent;
        $mail->AltBody = "Time to take $medicineName ($dosage) at $time";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>