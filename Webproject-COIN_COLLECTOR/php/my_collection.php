<?php
require_once("./db.php");
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Content-Type:application/json");
    echo json_encode(["error"=>"Не сте влезли в системата"]);
    exit();
}

$user_id=$_SESSION['user_id'];

if(!$conn)
{
    header("Content-Type:application/json");
    echo json_encode(["error"=>"Грешка при свързването с базата"]);
    exit();
}
$search=isset($_GET['search']) ? "%".$_GET['search']."%" : "%";
$continent=isset($_GET['continent']) && $_GET['continent'] !=="" ? $_GET['continent'] : null;
$collection = isset($_GET['collection']) && $_GET['collection'] !=="" ? $_GET['collection'] : null;
$sort= isset($_GET['sort']) ? $_GET['sort'] : "year-desc";

$query="SELECT coins.id, coins.name,coins.year,coins.value,coins.country,coins.continent,coins.front_image,coins.back_image, collections.name AS collection_name
FROM coins
JOIN collections ON coins.collection_id=collections.id
WHERE collections.user_id= ? AND(coins.name LIKE ? OR coins.country LIKE ?)";

$parameters=[$user_id,$search,$search];
if($continent)
{
    $query.=" AND continent = ?";
    $parameters[]=$continent;
}
if($collection)
{
    $query.=" AND collections.name = ?";
    $parameters[]=$collection;
}
switch($sort)
{
    case "year-asc":
        $query.="ORDER BY coins.year ASC";
        break;
    case "value-desc":
        $query.="ORDER BY CAST(value AS DECIMAL(10,2)) DESC";
        break;
    case "value-asc":
        $query.="ORDER BY CAST(value AS DECIMAL(10,2)) ASC";
        break;
    default:
    $query.="ORDER BY coins.year DESC";
}

try
{
    $stmt=$conn->prepare($query);
    $stmt->execute($parameters);
    $coins= $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(["coins"=>$coins],JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch(Exception $e){
  header("Content-Type:application/json");
  echo json_encode(["error"=>"Грешка при изпълнение на заявката","details"=> $e->getMessage()]);
}