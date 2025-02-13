<?php
require_once("db.php");

if (!isset($_GET['coin_id'])) {
    die("Не е посочена монета.");
}

$coin_id = intval($_GET['coin_id']);

if (!$conn) {
    die("Грешка при свързването с базата.");
}

// Извличаме информацията за монетата от базата
$query = $conn->prepare("SELECT c.name, c.year, c.value, c.country, c.continent, c.front_image, c.back_image, cl.name AS collection_name
                         FROM coins c
                         JOIN collections cl ON c.collection_id = cl.id
                         WHERE c.id = ?");
$query->execute([$coin_id]);
$coin = $query->fetch(PDO::FETCH_ASSOC);

if (!$coin) {
    die("Монетата не е намерена.");
}

// Заглавия на колоните
$headers = ['Name', 'Year', 'Value', 'Country', 'Continent', 'Collection Name', 'Front Image', 'Back Image'];

// Път до CSV файла
$csv_filename = 'coin_' . $coin_id . '.csv';

// Отваряме файл за писане
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $csv_filename . '"');
$output = fopen('php://output', 'w');

// Записваме заглавията в CSV файла
fputcsv($output, $headers);

// Записваме данните на монетата в CSV
fputcsv($output, [
    $coin['name'],
    $coin['year'],
    $coin['value'],
    $coin['country'],
    $coin['continent'],
    $coin['collection_name'],
    $coin['front_image'],
    $coin['back_image']
]);

// Затваряме файла
fclose($output);
exit();
?>
