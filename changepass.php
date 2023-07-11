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
    if (($_SESSION['USER']))$username = $_SESSION['USER'];
    else echo ("<script> location.replace('front-end/index.php'); </script>");
    ?>

<span onclick="mainPageAcc();" id="homepage"><i class="fa fa-home"></i>Anasayfa</span>

    <div class="main" id="mainMenu">
        <div class="add_user">
            <form action="register_control.php" method="post">
                <input type="password" name="oldpassword" placeholder="Eski şifre">
                <input type="password" name="newpassword" placeholder="Yeni şifre">
                <input type="password" name="newpasswordagain" placeholder="Yeni şifre tekrar">
                <button name="change_password_submit">Şifreyi değiştir</button>
                <?php/*
                    if(isset($_GET)){
                        if(isset($_GET['cp'])){
                            if($_GET['cp'] = 'ok') echo('Sifre degistirme islemi basarili');
                            elseif($_GET['cp'] = 'err0') $msg = 'Eski sifrenizi kontrol edin';
                            elseif($_GET['cp'] = 'err1') $msg = 'Eski sifre ile yenisi ayni olamaz';
                            elseif($_GET['cp'] = 'err2') $msg = 'Girdiginiz sifreler uyusmuyor';
                            elseif($_GET['cp'] = 'err3') $msg = 'Lutfen tum alanlari doldurun.';
                        }
                    }*/
                ?>
                <p>
                    <?php if(isset($_GET['cp'])){
                        if($_GET['cp'] == 'ok') echo('Sifre degistirme islemi basarili');
                        elseif($_GET['cp'] == 'err0') echo('Eski sifrenizi kontrol edin');
                        elseif($_GET['cp'] == 'err1') echo('Eski sifre ile yenisi ayni olamaz');
                        elseif($_GET['cp'] == 'err2') echo('Girdiginiz sifreler uyusmuyor');
                        elseif($_GET['cp'] == 'err3') echo('Lutfen tum alanlari doldurun');
                        else echo(" ");

                    } ?>
                    
                </p>
                <?php if(isset($_GET)){
                    if($_GET['chg']=='ok') echo("<p id='reg_ok'>İşlem başarılı.</p>");
                    elseif($_GET['chg']=='err') echo("<p id='reg_err'>İşlem başarısız.</p>");
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