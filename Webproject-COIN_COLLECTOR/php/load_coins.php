<?php
require_once("db.php");

$query = $conn->query("SELECT * FROM coins");
$coins = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($coins as $coin) {
    $coinId = $coin['id'];
    $frontImage = $coin['front_image'];
    $backImage = $coin['back_image'];

    // Извличане на броя на лайковете
    $stmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE coin_id = ? AND image_type = 'front'");
    $stmt->execute([$coinId]);
    $frontLikes = $stmt->fetchColumn();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE coin_id = ? AND image_type = 'back'");
    $stmt->execute([$coinId]);
    $backLikes = $stmt->fetchColumn();

    echo "
    <div class='coin-item'>
        <h3>{$coin['name']}</h3>
        <p>Година: {$coin['year']}</p>
        <p>Стойност: {$coin['value']} лв</p>

        <div>
            <img src='uploads/$frontImage' alt='Лице'>
            <button class='like-button' data-coin-id='$coinId' data-image-type='front'>👍 <span class='like-count'>$frontLikes</span></button>
        </div>

        <div>
            <img src='uploads/$backImage' alt='Гръб'>
            <button class='like-button' data-coin-id='$coinId' data-image-type='back'>👍 <span class='like-count'>$backLikes</span></button>
        </div>
    </div>";
}
?>
