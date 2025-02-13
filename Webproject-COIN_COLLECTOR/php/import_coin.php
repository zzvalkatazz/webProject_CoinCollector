<?php
require_once("./db.php");
session_start();


if (!isset($_SESSION['user_id'])) {
    die("Трябва да сте влезли, за да добавите монета.");
}

$user_id = $_SESSION['user_id'];

if (!$conn) {
    die("Грешка при свързването с базата.");
}


if (empty($_POST['name']) || empty($_POST['year']) || empty($_POST['value']) || empty($_POST['continent']) || empty($_POST['collection_name']) || empty($_POST['country'])) {
    die("Всички полета са задължителни!");
}


$name = htmlspecialchars($_POST['name']);
$year = intval($_POST['year']);
$value = floatval($_POST['value']);
$continent = htmlspecialchars($_POST['continent']);
$collection_name = htmlspecialchars($_POST['collection_name']);
$country = htmlspecialchars($_POST['country']);


$upload_dir = "../uploads/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}


if (!isset($_FILES["front_image"]) || !isset($_FILES["back_image"])) {
    die("Файлът не е качен правилно!");
}

$front_image_name = basename($_FILES["front_image"]["name"]);
$back_image_name = basename($_FILES["back_image"]["name"]);

$front_image_path = $upload_dir . $front_image_name;
$back_image_path = $upload_dir . $back_image_name;


$allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
if (!in_array($_FILES["front_image"]["type"], $allowed_types) || !in_array($_FILES["back_image"]["type"], $allowed_types)) {
    die("Грешен формат на изображение! Разрешени са само JPG и PNG.");
}

if (!move_uploaded_file($_FILES["front_image"]["tmp_name"], $front_image_path) ||
    !move_uploaded_file($_FILES["back_image"]["tmp_name"], $back_image_path)) {
    die("Грешка при запазване на изображенията!");
}


$query = $conn->prepare("SELECT id FROM collections WHERE LOWER(name) = LOWER(?) AND user_id = ?");
$query->execute([$collection_name, $user_id]);
$collection = $query->fetch(PDO::FETCH_ASSOC);

if (!$collection) {
  
    $insertCollection = $conn->prepare("INSERT INTO collections (name, user_id) VALUES (?, ?)");
    if (!$insertCollection->execute([$collection_name, $user_id])) {
        die("Грешка при създаването на колекцията.");
    }
    $collection_id = $conn->lastInsertId();
} else {
    $collection_id = $collection['id'];
}


if (!$collection_id) {
    die("Грешка: `collection_id` не е зададен правилно!");
}


$query = $conn->prepare("INSERT INTO coins (name, year, value, country, continent, front_image, back_image, collection_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$success = $query->execute([$name, $year, $value, $country, $continent, $front_image_name, $back_image_name, $collection_id, $user_id]);

if ($success) {
    
    header("Location: ../html/my_collection.html");
    exit();
} else {
    echo "Грешка при добавянето на монетата.";
}
?>
