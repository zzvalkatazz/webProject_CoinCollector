
 <!DOCTYPE html>
    <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <form action="./user_registration.php" method="post" novalidate>
    <link rel="stylesheet" href="../css/login_and_registration.css">
    <title>Вход- Coin Collector</title>

    <style>
        body {
            background-color: #FFFFFF; 
            background-image: url('./2076f4df-b05a-40d6-a0ac-fdb9f3edc526.jpg'); 
            background-size: 47%; 
            background-position: center center; 
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif; 
        }

        .login-container {
            background-color:rgba(255, 255, 255, 0.9); 
            padding: 20px;
            border-radius: 10px;
            max-width: 400px;
            margin: 50px auto; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        }
    </style>
</head>
<body>
   <div class="login-container">
     <header>
        <h1>Coin Collector</h1>
     </header>

     <?php include("user_registration_logic.php");?>


     <section>
    
    <form action="user_registration.php" method="post">
       <fieldset>
       <legend> Създайте нов профил</legend>
       
       <label for="Username"> Потребителско име:</label><br>
       <input type="text" name="username" id="Username" required><br>

       <label for="Email"> Имейл:</label><br>
       <input type="email" name="email" id="Email" required><br>

       <label for="Password"> Парола:</label><br>
       <input type="password" name="password" id="Password" required><br>

       <label for="ConfirmedPassword"> Потвърждение на парола:</label><br>
       <input type="password" name="confirmedPassword" id="ConfirmedPassword" required><br>

      <input type="submit" value ="Създай акаунт">
     </fieldset>
    </form>
  </section>
</div>
   </body>
   </html>

   