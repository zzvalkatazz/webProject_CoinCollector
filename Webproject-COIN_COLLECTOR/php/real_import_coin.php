<?php
require_once("./db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["csv_file"])) {
    $file = $_FILES["csv_file"]["tmp_name"];

    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle); 

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($data) < 8 || empty(trim($data[0]))) continue;

            $name = htmlspecialchars(trim($data[0]));
            $year = (int) trim($data[1]);
            $value = (float) trim($data[2]);
            $country = htmlspecialchars(trim($data[3]));
            $continent = htmlspecialchars(trim($data[4]));
            $collection_name = htmlspecialchars(trim($data[5]));
            $front_image = htmlspecialchars(trim($data[6]));
            $back_image = htmlspecialchars(trim($data[7]));
            $user_id = $_SESSION["user_id"] ?? 1;


            $uploads_dir = "../uploads/"; 
            if (!file_exists($uploads_dir . $front_image) || !file_exists($uploads_dir . $back_image)) {
                echo "Грешка: Изображението $front_image или $back_image липсва в uploads/.";
                continue;
            }

            
            $stmt = $conn->prepare("SELECT id FROM collections WHERE name = ? AND user_id = ?");
            $stmt->execute([$collection_name, $user_id]);
            $collection = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$collection) {
                $stmt = $conn->prepare("INSERT INTO collections (user_id, name) VALUES (?, ?)");
                $stmt->execute([$user_id, $collection_name]);
                $collection_id = $conn->lastInsertId();
            } else {
                $collection_id = $collection["id"];
            }

           
            $stmt = $conn->prepare("SELECT id FROM coins WHERE name = ? AND year = ? AND user_id = ?");
            $stmt->execute([$name, $year, $user_id]);
            if ($stmt->rowCount() > 0) continue; 

            
            $stmt = $conn->prepare("INSERT INTO coins (collection_id, user_id, name, year, value, country, continent, front_image, back_image) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$collection_id, $user_id, $name, $year, $value, $country, $continent, $front_image, $back_image]);
        }
        fclose($handle);
        
   
        header("Location: ../html/my_collection.html");
        exit();
    } else {
        echo "Грешка при отварянето на файла!";
    }
} else {
    echo "Няма качен файл!";
}
?>
