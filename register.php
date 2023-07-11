<?php
include('connect.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROSEN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap" rel="stylesheet">
</head>

<body>

    <?php
    if (($_SESSION['USER'])){
        if($_SESSION['USER'] == 'admin'){
            $username = $_SESSION['USER'];
        }else header('Location: mainpage.php');
        
    }
    else header('Location: front-end/index.php');
    ?>

<span onclick="mainPageAcc();" id="homepage"><i class="fa fa-home"></i>Anasayfa</span>

    <div class="main" id="mainMenu">
        <div class="add_user">
            <form action="register_control.php" method="post">
                <input type="text" name="registerName" placeholder="İsim">
                <input type="text" name="registerUsername" placeholder="Kullanıcı Adı">
                <input type="password" name="registerPassword" placeholder="Şifre">
                <button name="add_user_submit">Kullanıcıyı Ekle</button>
                <?php if(isset($_GET)){
                    if($_GET['reg']=='ok') echo("<p id='reg_ok'>Kayıt işlemi başarılı.</p>");
                    elseif($_GET['reg']=='err') echo("<p id='reg_err'>Kayıt işlemi başarısız.</p>");
                }
                 ?>
            </form>
        </div>

    </div>

    <div class="gif"></div>

    <div class="navigator" id="navigatorMenu">
        <ul>
            <li onclick="mainPageAcc();"><i class="fa fa-home"></i>Anasayfa</li>
            <li onclick="viewProfile()"><i class="fa fa-user" ></i> Profil</li>
            <li><i class="fa fa-bell"></i>Bildirimler</li>
            <li><i class="fa fa-envelope"></i>Mesajlar</li>
            <li onclick="post();"><i class="fa fa-beer"></i>Ros'la</li>
            <li onclick="exitAcc()"><i class="fa fa-sign-out"></i>Çıkış Yap</li>
        </ul>
    </div>

    <script src="js/index.js"></script>


</body>
</html>