<?php
session_start();
include('connect.php');
$gecersiz_istek = "gecersiz istek hatasi";
$username = $_SESSION['USER'];

if(($_POST)){

    $login_username = $_POST['login_username'];
    $login_password = $_POST['login_password'];
    if(isset($_POST['giris'])){//giris yap
        $login_phone = $_POST['login_username'];
        $login_valid = false;

        if(isset($login_username) && isset($login_password)){
            if(!empty($login_username) && !empty($login_password)){
                $sonuc_login = $baglanti->query("SELECT * FROM users");
                while($total_login = $sonuc_login->fetch_array()){
                    $dbuser = $total_login['username'];
                    $dbpass = $total_login['password'];
                    //$dbphone = $total_login['phone'];
                    if($login_username == $dbuser && $login_password == $dbpass) $login_valid = true;
                }
                if($login_valid){
                    $_SESSION['USER'] = $login_username;
                    $GLOBALS['USER'] = $login_username;
                    $_SESSION['LOGGED'] = "yes";
                    header('Location: mainpage.php');
                }else header("Location: front-end/index.php?falseAuth=false");
            }else header("Location: front-end/index.php?falseAuth=empty");
        }else echo($gecersiz_istek);
    }
    elseif(($_POST['registerUsername'])){//kayit ol

        $register_username = $_POST['registerUsername'];
        $register_password = $_POST['registerPassword'];
        $register_date = date("d.m.Y");
        $register_date = str_replace('.','/',$register_date);
        //$register_phone = $_POST['register_phone'];
        $register_valid = true;

        if(isset($register_username) && isset($register_password)){
            if(!empty($register_username) && !empty($register_password)){
                $sonuc_register = $baglanti->query("SELECT * FROM users");
                while($total_register = $sonuc_register->fetch_array()){
                    $dbuser = $total_register['username'];
                    //$dbphone = $total_register['phone'];
                    if($register_username == $dbuser) $register_valid = false;
                }
                if($register_valid){
                    $baglanti->query("INSERT INTO users (name,username,password,register_date,profile_pic) VALUES('$register_username','$register_username','$register_password','$register_date','-')");
                    header("Location: register.php?reg=ok");
                }else header("Location: register.php?reg=err");
            }
        }
    }elseif(isset($_POST['change_password_submit'])){

        $old_pass = $_POST['oldpassword'];
        $new_pass = $_POST['newpassword'];
        $new_pass_again = $_POST['newpasswordagain'];

        if(isset($old_pass) && isset($new_pass) && isset($new_pass_again)){
            if(!empty($old_pass) && !empty($new_pass) && !empty($new_pass_again)){               
                if($new_pass == $new_pass_again){
                    if(!($old_pass == $new_pass)){
                        $username = $_SESSION['USER'];
                        $db_old_pass = $baglanti->query("SELECT * FROM users WHERE username = '$username'");
                        $db_array = $db_old_pass->fetch_array(); 
                        $db_password = $db_array['password'];
                        if($old_pass == $db_password){
                            $baglanti->query("UPDATE users SET password = '$new_pass' WHERE username = '$username'");
                            header('Location: changepass.php?cp=ok');
                        }else header('Location: changepass.php?cp=err0');//eski sifrenizi kontrol edin.
                    }else header('Location: changepass.php?cp=err1');// eski sifre ile yenisi ayni olamaz.
                }else header('Location: changepass.php?cp=err2');//girdiginiz sifreler uyusmuyor.
            }else header('Location: changepass.php?cp=err3');//lutfen tum alanlari doldurun.
        }else header('Location: changepass.php?cp=err3');//lutfen tum alanlari doldurun.
        
    }elseif(isset($_POST['cmt_content'])){
        $cmt_ros_id = $_POST['cmt_ros_id'];
        $cmt_content = $_POST['cmt_content'];
        $cmt_author = $_SESSION['USER'];
        $cmt_url = 'Location: mainpage.php#'.$cmt_ros_id;
        if(!empty($cmt_content) && !empty($cmt_author)){
            $baglanti->query("INSERT INTO `comments`(`cmt_ros_id`,`cmt_content`,`cmt_author`) VALUES ('$cmt_ros_id','$cmt_content','$cmt_author') ");
            $url = "Location: mainpage.php#".$cmt_ros_id;
            echo('yorum eklendi.');
            //header($url);
        }else header($cmt_url);
    }elseif(isset($_POST['userFormPhone'])){
        $userFormPhone = $_POST['userFormPhone'];
        $userFormMail = $_POST['userFormMail'];
        $userFormBio = $_POST['userFormBio'];

        if(!empty($userFormPhone) && !empty($userFormMail)){
            $baglanti->query("UPDATE users SET phone_number = '$userFormPhone', mail = '$userFormMail', bio = '$userFormBio' WHERE username = '$username' ");
            $baglanti->query("UPDATE users SET data_update = '1' WHERE username = '$username' ");
        }


        header('Location: mainpage.php');



    }else echo("post istegi yok");
}else header('Location: mainpage.php');

if(isset($_GET)) if(isset($_GET) && $_GET['exit'] == 'ok') echo("<script> location.replace('frond-end/index.php); </script>")

?>
