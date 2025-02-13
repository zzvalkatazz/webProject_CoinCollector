<?php
require_once("db.php");

if (!isset($_GET['coin_id'])) {
    die("Не е посочена монета за изтриване.");
}

$coin_id = intval($_GET['coin_id']);

if (!$conn) {
    die("Грешка при свързването с базата.");
}

// Подготвяме SQL заявката за изтриване на монетата
$query = $conn->prepare("DELETE FROM coins WHERE id = ?");
$query->execute([$coin_id]);

// Проверяваме дали заявката е изпълнена успешно
if ($query->execute([$coin_id])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Не беше намерена монетата за изтриване.']);
}

?>
