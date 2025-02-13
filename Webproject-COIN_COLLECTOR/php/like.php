<?php
require_once("./db.php");
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["user_id"])) {
        echo json_encode(["success" => false, "message" => "Трябва да сте влезли в профила си!"]);
        exit();
    }

    $user_id = $_SESSION["user_id"];
    $coin_id = intval($_POST["coin_id"]);
    $image_type = $_POST["image_type"];

    file_put_contents("debug_like.log", print_r($_POST, true), FILE_APPEND);

    $query = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND coin_id = ? AND image_type = ?");
    $query->execute([$user_id, $coin_id, $image_type]);

    if ($query->rowCount() > 0) {
        echo json_encode(["success" => false, "message" => "Вече сте харесали тази снимка!"]);
        exit();
    }

    $insert = $conn->prepare("INSERT INTO likes (user_id, coin_id, image_type) VALUES (?, ?, ?)");
    $insert->execute([$user_id, $coin_id, $image_type]);

    $countQuery = $conn->prepare("SELECT COUNT(*) FROM likes WHERE coin_id = ? AND image_type = ?");
    $countQuery->execute([$coin_id, $image_type]);
    $likeCount = $countQuery->fetchColumn();

    echo json_encode(["success" => true, "likes" => $likeCount]);
    exit();
}

$query = $conn->query("SELECT coin_id, image_type, COUNT(*) AS likes FROM likes GROUP BY coin_id, image_type");
$likesData = $query->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($likesData);
?>
