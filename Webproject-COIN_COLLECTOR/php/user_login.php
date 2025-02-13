<?php include("./user_login_logic.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <form action="user_login.php" method="post" novalidate>
    <link rel="stylesheet" href="../css/login_and_registration.css">
    <title>Вход - Coin Collector</title>

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

    <section>
        <form action="user_login.php" method="post">
            <h2>Влезте в профила си</h2>

            <label for="Username">Потребителско име:</label><br>
            <input type="text" name="username" id="Username" required value="<?php echo htmlspecialchars($_POST['username'] ?? '');?>"><br>
            <?php if(!empty($errors['username'])):?>
                <span style="color:#8B0000;"><?php echo $errors['username'];?></span><br>
            <?php endif;?>

            <label for="Password">Парола:</label><br>
            <input type="password" name="password" id="Password" required value="<?php echo htmlspecialchars($_POST['password'] ?? '');?>"><br>
            <?php if(!empty($errors['password'])):?>
                <span style="color:#8B0000;"><?php echo $errors['password'];?></span><br>
            <?php endif;?>

            <?php if(!empty($errors['login'])):?>
                <span style="color:#8B0000;"><?php echo $errors['login'];?></span><br>
            <?php endif;?>

            <input type="submit" value="Влез">
        </form>

        <p>Нямате акаунт?<a href="user_registration.php"> Регистрирайте се тук </a></p>
    </section>

</div>
</body>
</html>
