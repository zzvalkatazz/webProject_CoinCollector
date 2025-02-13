
<?php
//връзка с базата данни
        $servername="localhost:3306";
        $username = "root";
        $password = "";
        $dbname = "users";

        $conn = null;
        try{
            $conn  = new PDO("mysql:host=localhost;dbname=users", $username, $password);
            $conn->exec("SET NAMES 'utf8'");
        }catch(PDOException $e)
        {
            die('Неуспешно свързване с база данни: ' . $e->getMessage());
        }

    ?>