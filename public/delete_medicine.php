<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/medicine_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $medicineId = filter_input(INPUT_POST, 'medicine_id', FILTER_SANITIZE_NUMBER_INT);
    
    try {
        // Add proper deletion with user verification
        $stmt = $pdo->prepare("DELETE FROM medicines WHERE id = ? AND user_id = ?");
        $stmt->execute([$medicineId, $_SESSION['user_id']]);
        
        // Check if deletion was successful
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
            exit;
        }
    } catch(PDOException $e) {
        error_log("Delete error: " . $e->getMessage());
    }
}

echo json_encode(['success' => false]);
?>

