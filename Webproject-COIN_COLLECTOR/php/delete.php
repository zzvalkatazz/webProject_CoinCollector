<?php
require_once("./db.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Трябва да сте влезли, за да изтриете монета.");
}

$user_id = $_SESSION['user_id'];

if (!$conn) {
    die("Грешка при свързването с базата.");
}

if (empty($_POST['id'])) {
    die("Трябва да посочите ID на монетата за изтриване.");
}

$coin_id = intval($_POST['id']);


$query = $conn->prepare("SELECT front_image, back_image FROM coins WHERE id = ? AND user_id = ?");
$query->execute([$coin_id, $user_id]);
$coin = $query->fetch(PDO::FETCH_ASSOC);

if (!$coin) {
    die("Монетата не е намерена или нямате права за изтриване.");
}


$upload_dir = "../uploads/";
$front_image_path = $upload_dir . $coin['front_image'];
$back_image_path = $upload_dir . $coin['back_image'];

if (file_exists($front_image_path)) {
    unlink($front_image_path);
}
if (file_exists($back_image_path)) {
    unlink($back_image_path);
}


$query = $conn->prepare("DELETE FROM coins WHERE id = ? AND user_id = ?");
$success = $query->execute([$coin_id, $user_id]);

if ($success) {
    header("Location: ../html/my_collection.html");
    exit();
} else {
    echo "Грешка при изтриването на монетата.";
}
?>
