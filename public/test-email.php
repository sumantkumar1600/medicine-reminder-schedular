<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/mail.config.php';

// Load .env from config directory
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->load();

try {
    // Test email parameters
    if (sendMedicineReminder(
        'test@example.com', 
        'Test Medicine',
        '1 Tablet',
        date('h:i A'),
        'Self'
    )) {
        echo "Test email sent successfully! Check Mailtrap Inbox";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>