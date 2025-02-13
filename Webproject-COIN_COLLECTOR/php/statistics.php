<?php
require_once("./db.php");
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Content-Type:application/json");
    echo json_encode(["error"=>"Не сте влезли в системата"],JSON_UNESCAPED_UNICODE);
    exit();
}
$user_id=$_SESSION['user_id'];
if(!$conn)
{
    header("Content-Type:application/json");
    echo json_encode(["error"=>"Грешка при свързването с базата"],JSON_UNESCAPED_UNICODE);
    exit();
}
$query=$conn->prepare ("SELECT COUNT(*) AS total_coins,SUM(value) AS total_value
FROM coins 
JOIN collections ON coins.collection_id=collections.id
WHERE  collections.user_id = ?");

$query->execute([$user_id]);
$statistics = $query->fetch(PDO::FETCH_ASSOC);
$total_coins=$statistics['total_coins'] ?? 0;
$total_value = isset($statistics['total_value']) ? (float)$statistics['total_value'] : 0.00;

$query=$conn->prepare(
    "SELECT continent,COUNT(*) AS count FROM coins
    JOIN collections ON coins.collection_id=collections.id
    WHERE collections.user_id=?
    GROUP BY continent");

$query->execute([$user_id]);
$continent_data = $query->fetchAll(PDO::FETCH_ASSOC)?:[];

$query=$conn->prepare(
    "SELECT year,COUNT(*) AS count FROM coins
    JOIN collections ON coins.collection_id=collections.id
    WHERE collections.user_id=?
    GROUP BY year ORDER BY year ASC"
);
$query->execute([$user_id]);
$year_data=$query->fetchAll(PDO::FETCH_ASSOC) ?:[];

$query=$conn->prepare(
  "SELECT country, COUNT(*) AS count FROM coins
  JOIN collections ON  coins.collection_id=collections.id
  WHERE collections.user_id=?
  GROUP BY country ORDER BY count DESC LIMIT 7"
);

$query->execute([$user_id]);
$country_data=$query->fetchAll(PDO::FETCH_ASSOC) ?:[];

$query=$conn->prepare("
SELECT FLOOR(year/10)*10 AS decade,COUNT(*) AS count
FROM coins
JOIN collections ON coins.collection_id=collections.id
WHERE collections.user_id=?
GROUP BY decade
ORDER BY count DESC
LIMIT 5
");
$query->execute([$user_id]);
$top_decades_user=$query->fetchAll(PDO::FETCH_ASSOC) ?:[];
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
    'top_decades_user'=>$top_decades_user
],JSON_UNESCAPED_UNICODE| JSON_PRETTY_PRINT );
?>

