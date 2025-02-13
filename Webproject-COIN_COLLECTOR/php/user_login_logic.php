<?php
        require_once("./db.php");
        session_start();

        function secure_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $errors=[];
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $username = secure_input($_POST["username"]);
            $password = secure_input($_POST["password"]);
          
            
            if(empty($username))
            {
                $errors[]='Потребителското име е задължително.';
            }

            if(empty($password))
            {
                $errors[]='Паролата е задължителна!';
            }
            if(!empty($errors))
            {
              echo '<ul style="color:#8B0000;">';
             foreach($errors as $error)
             {
              echo "<li>$error</li>";
             }
             echo '</ul>';
            }
           if(empty($errors))
           {
             
              $query="SELECT id, Username,Password FROM users  WHERE Username = :username";
              $stmt= $conn->prepare($query);
              $stmt->bindParam(':username',$username);
              $stmt->execute();
             
             $user=$stmt->fetch(PDO::FETCH_ASSOC);
            if($user && password_verify($password,$user['Password']))
            {
                $_SESSION["username"]=$username;
                $_SESSION["user_id"]=$user['id']; 
                header("Location:../html/Coin_Collector.html");
                exit();
            }
            else {
              $errors['login']="Грешно потрбителско име или парола!";
             }
           }
        }
?>


































