<?php
include('connect.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ROSEN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/userprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.0.min.js"></script>
</head>

<body>
<!--  -->

    <?php
    if (($_SESSION['USER']))$username = $_SESSION['USER'];
    else echo ("<script> location.replace('front-end/index.php'); </script>");
    ?>

    <?php
    if(isset($_GET)) $get_id = $_GET['id'];
    else header('Location: mainpage.php');
    $user = $baglanti->query("SELECT * FROM users WHERE id = '$get_id'");
    $user_data = $user->fetch_array();
    $user_name = $user_data['username'];
    ?>

    <?php

    if(isset($_POST)){
        if(isset($_POST['liked'])){
            $liked_check = false;
            $likedRosID = $_POST['likedRosId'];
            $likeRos = $baglanti->query("SELECT * FROM ros WHERE ros_id = $likedRosID");
            $likeRos_result = $likeRos->fetch_array();
            $ros_liked_username = $likeRos_result['ros_liked_username'];
            $liked_users_list = explode(',',$ros_liked_username ?? '');
            $myUserName = $_SESSION['USER'];
            for($i=0; $i<count($liked_users_list); $i++){
                if($liked_users_list[$i] == $_SESSION['USER']) $liked_check = true;
            }
            if(!$liked_check){
                $new_ros_liked_username = $ros_liked_username . $_SESSION['USER'] . ",";
                $new_ros_like = $likeRos_result['ros_like'] + 1;
                $baglanti->query("UPDATE ros SET ros_liked_username = '$new_ros_liked_username' WHERE ros_id = $likedRosID");
                $baglanti->query("UPDATE ros SET ros_like = $new_ros_like WHERE ros_id = $likedRosID");
            }elseif($liked_check){
                $new_ros_liked_username = str_replace($myUserName.",","",$ros_liked_username);
                $new_ros_like = $likeRos_result['ros_like']-1;
                $baglanti->query("UPDATE ros SET ros_liked_username = '$new_ros_liked_username' WHERE ros_id = $likedRosID");
                $baglanti->query("UPDATE ros SET ros_like = $new_ros_like WHERE ros_id = $likedRosID");
            }
            $url = "Location: userprofile.php?"."id=".$get_id."#".$likedRosID."profile";
            header($url);
        }elseif(isset($_POST['comment_send'])){
            $cmt_ros_id = $_POST['cmt_ros_id'];
            $cmt_content = $_POST['cmt_content'];
            $cmt_author = $_SESSION['USER'];
            $cmt_url = "Location: userprofile.php?"."id=".$get_id."#".$cmt_ros_id."profile";
            if(!empty($cmt_content) && !empty($cmt_author)){
                echo("ok"."<br>");
                $baglanti->query("INSERT INTO `comments`(`cmt_ros_id`,`cmt_content`,`cmt_author`) VALUES ('$cmt_ros_id','$cmt_content','$cmt_author') ");
                echo("db ok ");
                $url = "Location: userprofile.php?"."id=".$get_id."#".$cmt_ros_id."profile";
                header($url);
            }else header($cmt_url);
    }
}
    ?>
    <div class="main">

    <?php
    if(isset($_GET)) $get_id = $_GET['id'];
    else header('Location: mainpage.php');
    $user = $baglanti->query("SELECT * FROM users WHERE id = '$get_id'");
    $user_data = $user->fetch_array();
    $user_name = $user_data['username'];
    ?>

    <div class="userBody">
        <div class="userPhoto">
            <?php
            if($user_data['profile_pic'] == "-") $user_photo = 'media/noprofile.png';
            else $user_photo = "PROFILE_PIC/".$user_data['profile_pic'];
            ?>
            <img src=<?php echo($user_photo) ?> width="100" height="100">
        </div>

        <div class="userData">
            <div class="userName"><b>@<?php echo($user_data['name']) ?></b></div>
            <div class="userOther">
                <ul>
                    <li><?php echo($user_data['phone_number']) ?></li>
                    <li>Mail</li>
                </ul>
            </div>
            <div class="userBio" ><?php echo($user_data['bio']) ?></div>
            <!-- contenteditable="true" = tıklayınca düzenlenebilir olması için -->
        </div>
        <div class="userMessage"></div>
    </div>

    <?php

    if($user_data['username'] == $username){

    ?>
    <div id="options">
        <ul>
            <li onclick="changePassword()" id="optionsPass"><i class="fa fa-key"></i>Şifreyi Değiştir</li>
            <li onclick="exitAcc()" id="optionsExit"><i class="fa fa-sign-out"></i>Çıkış Yap</li>
        </ul>
    </div>

    <?php } ?>

    <div class="roses">
            <?php
            $sonuc_ros = $baglanti->query("SELECT * FROM ros WHERE ros_owner = '$user_name' ORDER BY ros_id DESC LIMIT 50");
            while ($total_ros = $sonuc_ros->fetch_array()) {
                $date = date("d.m.Y");
                if ($date == $total_ros['ros_date']) $post_time = $total_ros['ros_time'];
                else $post_time = $total_ros['ros_date'];
                $ros_person = $total_ros['ros_owner'];
                $person = $baglanti->query("SELECT * FROM users WHERE username = '$ros_person'");
                $total_person = $person->fetch_array();
                $profile_url = "";
            ?>
                <div class="person" id="<?php  echo($total_ros['ros_id']) ?>profile">
                    <div class="profile">
                        <?php
                        if($total_person['profile_pic'] == '-') $profile_url = "media/noprofile.png";
                        else $profile_url = "PROFILE_PIC/".$total_person['profile_pic'];
                        ?>
                        <div class="name"><img src='<?php echo($profile_url)?>' width="25" height="25"><b onclick="viewProfile(<?php echo($total_person['id']) ?>)"><?php echo ($total_ros['ros_owner'])  ?></b></div>
                        <div class="date"><?php echo ($post_time) ?></div>
                        <div class="delete_ros"><i <?php if($total_ros['ros_owner'] == $_SESSION['USER'] || $_SESSION['USER'] == 'admin' ) echo("class='fa fa-trash delete_ros'"); ?> onclick="deleteRos(<?php echo($total_ros['ros_id']); ?>);" ></i></div>
                    </div>
                    <div class="messages">
                        <strong><?php echo ($total_ros['ros_content']); ?></strong>
                        <?php
                        if($total_ros['ros_file']!='NULL'){
                            $file_url = $total_ros['ros_file'];
                            $media_ext = explode(".",$file_url ?? '');
                            $ext = $media_ext[count($media_ext)-1];
                            if($ext == "mp4" || $ext == "wav") echo("<video width=400 height=400 controls class='ros_img'> <source src='ROS_FILES/$file_url' type='video/$ext'></video>");
                            else echo("<img class='ros_img' src='ROS_FILES/$file_url' width=400 height=400>");
                        }
                        ?>
                    </div>
                    <div class="actions">
                        <?php ?>
                        <form action="" method="post">
                            <input type="hidden" name="likedRosId" value="<?php echo($total_ros['ros_id']); ?>">
                            <?php
                                $haveIliked = false;
                                $ros_liked_username = $total_ros['ros_liked_username'];
                                $liked_users_list = explode(',',$ros_liked_username ?? '');
                                for($i=0; $i<count($liked_users_list); $i++){
                                    if($liked_users_list[$i] == $_SESSION['USER']) $haveIliked = true;
                                }
                                if(!$haveIliked) echo("<button class='likeBtn' name='liked' ><i class='fa fa-heart act act-like'></i></button>");
                                else echo("<button class='likeBtn' name='liked' ><i class='fa fa-heart act act-liked'></i></button>");
                            ?>
                        </form>
                        <i onclick="viewLikes(<?php echo($total_ros['ros_id']) ?>);" id='likeCount'><?php echo($total_ros['ros_like']) ?></i>
                        <?php
                        $cmt_ros_id = $total_ros['ros_id'];
                        $comment_db = $baglanti->query("SELECT * FROM comments WHERE cmt_ros_id = $cmt_ros_id ");
                        ?>
                        <i onclick="viewComments(<?php echo($total_ros['ros_id']) ?>)" class="fa fa-comment act act-comment">&nbsp;&nbsp; <?php echo($comment_db->num_rows) ?></i>
                        <!--<meta http-equiv="refresh" content="5";/>-->
                    </div>
                </div>
                <div class="comments" id="<?php echo($total_ros['ros_id']) ?>">
                <?php
                while($comment_array = $comment_db->fetch_array()){
                   $cmt_author_name = $comment_array['cmt_author'];
                   $cmt_author_query = $baglanti->query("SELECT * FROM users WHERE username = '$cmt_author_name'");
                   $cmt_author = $cmt_author_query->fetch_array();
                   $cmt_author_photo = $cmt_author['profile_pic'];
                   if($cmt_author_photo == '-') $cmt_author_photo_final = "media/noprofile.png";
                   else $cmt_author_photo_final = "PROFILE_PIC/".$cmt_author_photo;
                ?>
                <div class="comment">
                    <div class="comment_photo"><img src="<?php echo($cmt_author_photo_final) ?>" width="20" height="20"></div>
                    <?php
                        $for_cmt_author_id = $baglanti->query("SELECT id FROM users WHERE username = '$cmt_author_name'");
                        $cmt_author_id = $for_cmt_author_id->fetch_assoc();
                    ?>
                    <div class="comment_name" onclick="viewProfile(<?php echo($cmt_author_id['id']) ?>)"><?php echo($comment_array['cmt_author']) ?></div>
                    <div class="comment_content"><?php echo($comment_array['cmt_content']) ?></div>
                </div>
                <?php } ?>
                <div class="comment">
                    <form action="" method="post">
                        <input class="comment_input" type="text" name="cmt_content" autocomplete="off"  placeholder="Yorum yazın.">
                        <input type="hidden" name="cmt_ros_id" value="<?php echo($total_ros['ros_id']) ?>">
                        <button class="comment_send" name="comment_send"><i class="fa fa-paper-plane"></i></button>
                    </form>
                </div>
                </div>

                <div class="likers" id="likers_<?php echo($total_ros['ros_id']) ?>">
                <h4>Yorumu beğenenler </h4>
                    <p class="exitLike" onclick="hideLikes(<?php echo($total_ros['ros_id']) ?>);">X</p>
                    <ul class="likerUser">
                        <?php
                        $this_ros = $total_ros['ros_id'];
                        $likerUsers_db = $baglanti->query("SELECT * FROM ros WHERE ros_id = '$this_ros'");
                        $likerUsers = $likerUsers_db->fetch_array();
                        $likers = $likerUsers['ros_liked_username'];
                        $likers_users_list = explode(',',$likers ?? '');
                        for($i=0; $i<count($likers_users_list); $i++){
                        ?>
                        <li><?php echo($likers_users_list[$i]) ?></li>
                        <?php } ?>
                    </ul>
                </div>

            <?php } ?>
        </div>

    </div>

    <div class="gif"></div>
    <div class="navigator" id="navigatorMenu">
        <ul>
            <li onclick="mainPageAcc();"><i class="fa fa-home"></i>Anasayfa</li>
            <li onclick="viewProfile(<?php echo($_SESSION['id']); ?>)"><i class="fa fa-user" ></i> Profil</li>
            <li><i class="fa fa-bell"></i>Bildirimler</li>
            <li><i class="fa fa-envelope"></i>Mesajlar</li>
            <li onclick="changePassword()"><i class="fa fa-key"></i>Şifreyi Değiştir</li>
            <li onclick="exitAcc()"><i class="fa fa-sign-out"></i>Çıkış Yap</li>
        </ul>
    </div>
    <script src="js/index.js"></script>
    <script type="text/javascript">
        function goToByScroll(id) {
            $('html,body').animate({ scrollTop: $("#" + id).offset().top }, 'slow');
        }
    </script>
    <div class="toBack" onclick="mainPageAcc()"><i class="fa fa-arrow-left"></i></div>
    <div class="toUp" id="top"><i class="fa fa-chevron-up"></i></div>
    <script>

          $(function(){
            $('#top').click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 600);
                return false;
            });

            $(window).scroll(function () {
                if ($(this).scrollTop() > 15) {
                    $('.toUp').fadeIn(500);
                } else {
                    $('.toUp').fadeOut(500);
                }
                });
        });
    </script>

</body>
</html>
