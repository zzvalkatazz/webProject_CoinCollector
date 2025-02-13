<?php
session_start();

if(!isset($_SESSION['username']))
{
    header("Location:user_login.php");
    echo json_encode(['error'=>"Потребител не е логнат"]);
    exit();
}

echo json_encode(['username'=>$_SESSION['username']]);
?>