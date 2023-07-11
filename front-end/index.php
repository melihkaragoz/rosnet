<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ros Net</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="main">

        <div class="left">
            <img src="photo-1.gif" width="500" alt="">
        </div>

        <div class="right">

            <div class="box">
                <form action="../register_control.php" method="post">
                <input type="text" name="login_username" placeholder="Kullanıcı adı veya Telefon" autocomplete="off">
                <input type="password" name="login_password" placeholder="Şifre">
                <button name="giris" class="sign_in">Giriş Yap</button></form>
            </div>
            
            <div class="banner">
                <div id="ros">
                    <h1>ROS NETWORK</h1>
                </div>
            </div>

        </div>

    </div>
    
</body>
</html>