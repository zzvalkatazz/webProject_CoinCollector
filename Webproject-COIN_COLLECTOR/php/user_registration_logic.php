
        <?php
        require_once("./db.php");
        
        function secure_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $username = secure_input($_POST["username"]);
            $email = secure_input($_POST["email"]);
            $password = secure_input($_POST["password"]);
            $confirmedPassword = secure_input($_POST["confirmedPassword"]);

            $errors = [];

            if(empty($username))
            {
                $errors[]="Потребителското име е задължително.";
            }

            if(mb_strlen($username)< 3 || mb_strlen($username) > 40)
            {
                $errors[]="Потребителското име трябва да е с правилна дължнина.";
            }
            if(empty($email))
            {
                $errors[]="Имейл е задължителен.";
            }

            if(!filter_var($email,FILTER_VALIDATE_EMAIL))
            {
                $errors[]="Форматът на имейл не е валиден!.";
            }

            if(empty($password))
            {
                $errors[]="Паролата е задължителна!";
            }

            if(mb_strlen($password)< 6)
            {
                $errors[]="Паролата трябва да е с поне 6 символа.";
            }
            
            if(empty($confirmedPassword))
            {
                $errors[]="Потрвърждението за парола е задължително!";
            }

            if($password !== $confirmedPassword)
            {
                $errors[]="Паролите не съвпадат.";
            }

          // проверка за дублирано потребителско име
          $query = "SELECT * FROM users WHERE Username = :username";
          $stmt = $conn->prepare($query);
          $stmt -> bindParam(':username',$username);
          $stmt->execute();

          if($stmt->rowCount()> 0)
          {
            $errors[]='Вече има потребител с такова име!';
          }

          // показване на грешки или запис на потребител
          if(!empty($errors))
          {
            echo '<ul style="color: #8B0000;">';
           foreach($errors as $error)
           {
            echo "<li>$error</li>";
           }
           echo '</ul>';
          }
          else{
            $hashedPassword= password_hash($password,PASSWORD_DEFAULT);
            $query = $conn->prepare("INSERT INTO users (Username,Email,Password) VALUES (:username,:email,:password)");
            $query->bindParam(':username',$username);
            $query->bindParam(':email',$email);
            $query->bindParam(':password',$hashedPassword);

            try
            {
                $query->execute();
                //echo '<p style="color:green;">Успешна регистрация!';C
                header("Location:user_login.php");
                exit();
            }
            catch(Exception $e)
            {
                echo'<p style="color:dark-red;"> Грешка при регистрацията. Моля, опитайте отново.</p>';
            }
          }
        }

   ?>






































       
        
