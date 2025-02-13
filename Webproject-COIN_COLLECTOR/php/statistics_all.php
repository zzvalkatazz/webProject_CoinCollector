<?php
require_once("./db.php");
session_start();

if(!$conn)
{
    header("Content-Type:application/json");
    echo json_encode(["error"=>"Грешка при свързването с базата"],JSON_UNESCAPED_UNICODE);
    exit();
}
$query=$conn->prepare ("SELECT COUNT(*) AS total_coins,SUM(CAST(value as DECIMAL(10,2))) AS total_value
FROM coins"
);

$query->execute();
$statistics = $query->fetch(PDO::FETCH_ASSOC);
$total_coins=$statistics['total_coins'] ?? 0;
$total_value = isset($statistics['total_value']) ? (float)$statistics['total_value'] : 0.00;


$query=$conn->prepare(
    "SELECT continent,COUNT(*) AS count FROM coins
    GROUP BY continent");

$query->execute();
$continent_data = $query->fetchAll(PDO::FETCH_ASSOC)?:[];

$query=$conn->prepare(
    "SELECT year,COUNT(*) AS count FROM coins
    GROUP BY year ORDER BY year ASC"
);
$query->execute();
$year_data=$query->fetchAll(PDO::FETCH_ASSOC) ?:[];

$query=$conn->prepare(
  "SELECT country, COUNT(*) AS count FROM coins
  GROUP BY country ORDER BY count DESC LIMIT 7"
);

$query->execute();
$country_data=$query->fetchAll(PDO::FETCH_ASSOC) ?:[];

$query=$conn->prepare("
SELECT users.Username as username,COUNT(coins.id) AS count
FROM users
JOIN collections ON users.id=collections.user_id
JOIN coins ON collections.id=coins.collection_id
GROUP BY users.id
ORDER BY count DESC
LIMIT 7"
);

$query->execute();
$user_data=$query->fetchAll(PDO::FETCH_ASSOC) ?:[];

$query=$conn->prepare("
SELECT FLOOR(year/10)*10 AS decade,COUNT(*) AS count
FROM coins
GROUP BY decade
ORDER BY count DESC
LIMIT 5
");
$query->execute();
$top_decades_all=$query->fetchAll(PDO::FETCH_ASSOC) ?:[];

if($total_coins==0)
{
    header('Content-Type:application/json');
    echo json_encode(["empty"=>true],JSON_UNESCAPED_UNICODE);
    exit();
}
header('Content-Type: application/json');
echo json_encode([
    'total_coins'=>$total_coins,
    'total_value'=>$total_value,
    'continent_data'=>$continent_data,
    'year_data'=>$year_data,
    'country_data'=>$country_data,
    'top_decades_all'=>$top_decades_all,
    'user_data'=>$user_data
],JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>

