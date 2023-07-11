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
    <link rel="stylesheet" href="css/profile.css">
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

    <div class="hashtags" id="hashtagsMenu">
        <div class="h-top"> </div>
        <div class="h-body">
            <ul>
                <li><b class="hash-animation">#</b>AşılaYAmıyoruz</li>
                <li><b class="hash-animation">#</b>Kerem</li>
                <li><b class="hash-animation">#</b>Mehdinin İkinci Gaybeti</li>
                <li><b class="hash-animation">#</b>13 Türk</li>
                <li><b class="hash-animation">#</b>SinavdaYokuz</li>
                <li><b class="hash-animation">#</b>Sevgililer Günü</li>
                <li><b class="hash-animation">#</b>karyağıyor</li>
                <li><b class="hash-animation">#</b>Galatasaray</li>
                <li><b class="hash-animation">#</b>Sırların Bedeli</li>
                <li><b class="hash-animation">#</b>Haram</li>
            </ul>
        </div>
    </div>

    <?php
        $person = $baglanti->query("SELECT * FROM users WHERE username = '$username'");
        $total_person = $person->fetch_array();
        if($total_person['profile_pic'] == '-')$profile_url = "media/noprofile.png";
        else$profile_url = "PROFILE_PIC/".$total_person['profile_pic'];
    ?>

    <div class="main-profile" id="main-profile"> 
        <div class="profile-body">
            <div class="profile-pic">
                <img src=<?php echo($profile_url)?> >
                <form action="post.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="changeProfilePic" id="profilePic">
                    <label for="profilePic"><i class="fa fa-paperclip atacPost"></i></label>
                    <button>Fotoğrafı güncelle</button>
                </form>

            </div>
            <div class="profile-name">
                <strong><?php echo($_SESSION['USER']); ?></strong>
            </div>
            <div class="profile-other">
                <strong>Telefon : <b> <?php echo($total_person['phone_number']) ?> </b> </strong>
                <strong>E-mail : <b id="data-mail"> <?php echo($total_person['mail']) ?> </b> </strong>
                <strong>Kayıt Tarihi : <b> <?php echo($total_person['register_date']) ?> </b> </strong>
                <strong>Ros Sayısı : <b> <?php echo($total_person['ros_count']) ?> </b> </strong>

                <!--- MOBİL İÇİN -->
                <div class="adduserbtn" onclick="newUser();"><button>Kullanıcı Ekle</button></div>
                <div class="changepassbtn" onclick="changePassword();"><button>Şifreyi değiştir</button></div>
                <div class="backtomain"  onclick="mainPageAcc();"><button>Anasayfa'ya dön</button></div>
                <div class="backtologin last-btn" onclick="exitAcc();"><button>Çıkış</button></div>
                <!---------------->
            </div>
        </div>
    </div>

    <div class="gif"></div>

    <div class="navigator" id="navigatorMenu">
        <ul>
            <li onclick="mainPageAcc();"><i class="fa fa-home"></i>Anasayfa</li>
            <li onclick="viewProfile()"><i class="fa fa-user" ></i> Profil</li>
            <li><i class="fa fa-bell"></i>Bildirimler</li>
            <li><i class="fa fa-envelope"></i>Mesajlar</li>
            <li onclick="changePassword()"><i class="fa fa-key"></i>Şifreyi Değiştir</li>
            <li onclick="exitAcc()"><i class="fa fa-sign-out"></i>Çıkış Yap</li>
        </ul>
    </div>







    <script src="js/index.js"></script>

</body>

</html>
