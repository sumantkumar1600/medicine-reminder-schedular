<?php
function addMedicine($userId, $name, $startDate, $endDate, $time, $dosage) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO medicines (user_id, name, start_date, end_date, time, dosage)
                          VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$userId, $name, $startDate, $endDate, $time . ':00', $dosage]);
}

function getMedicines($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM medicines WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function deleteMedicine($medicineId, $userId) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM medicines WHERE id = ? AND user_id = ?");
    return $stmt->execute([$medicineId, $userId]);
}
?>