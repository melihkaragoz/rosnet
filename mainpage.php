<?php
include('connect.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<?php
include('head.php');
?>

<body>

    <?php
    if (($_SESSION['USER']))$username = $_SESSION['USER'];
    else echo ("<script> location.replace('front-end/index.php'); </script>");
    ?>


    <?php
    if(isset($_POST)){
        if(isset($_POST['liked'])){
            $liked_check = false;
            $likedRosID = $_POST['likedRosId'];
            $likeRos = $baglanti->query("SELECT * FROM ros WHERE ros_id = $likedRosID");
            $likeRos_result = $likeRos->fetch_array();            
            $ros_liked_username = $likeRos_result['ros_liked_username'];
            $liked_users_list = explode(',',$ros_liked_username);
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
            $url = "Location: mainpage.php#".$likedRosID."profile";
            header($url);
        }elseif(isset($_POST['comment_send'])){
            $cmt_ros_id = $_POST['cmt_ros_id'];
            $cmt_content = $_POST['cmt_content'];
            $cmt_author = $_SESSION['USER'];
            $cmt_url = "Location: mainpage.php#".$cmt_ros_id."profile";
            if(!empty($cmt_content) && !empty($cmt_author)){
                echo("ok"."<br>");
                $baglanti->query("INSERT INTO `comments`(`cmt_ros_id`,`cmt_content`,`cmt_author`) VALUES ('$cmt_ros_id','$cmt_content','$cmt_author') ");
                echo("db ok ");
                $url = "Location: mainpage.php#".$cmt_ros_id."profile";
                header($url);
            }else header($cmt_url);
        }
    }
    ?>

    <?php
        $person_session = $baglanti->query("SELECT * FROM users WHERE username = '$username'");
        $total_person_session = $person_session->fetch_array();
        if($total_person_session['profile_pic'] == '-') $profile_session_url = "media/noprofile.png";
        else $profile_session_url = "PROFILE_PIC/".$total_person_session['profile_pic'];
    ?>

    <div class="add-ros" id="postMenu">
        <div class="middle_ros">
            <div class="closePost" onclick="unPost();">x</div>
            <form action="post.php" method="post" enctype="multipart/form-data">
                <img src="<?php echo($profile_session_url)?>" width="25" height="25" id="postPic">
                <textarea name="ros_new" placeholder="Ros ekle.." autocomplete="off"></textarea>
                <input type="file" name="ros_file" id="postFile">
                <label for="postFile"><i class="fa fa-paperclip atacPost"></i></label>
                <button><b>Gönder</b></button>
            </form>
        </div>
    </div>

    <div class="hashtags" id="hashtagsMenu">
        <div class="h-top"> </div>
        <div class="h-body">
            <ul>
                <li><b>#</b>AşılaYAmıyoruz</li>
                <li><b>#</b>Kerem</li>
                <li><b>#</b>Mehdinin İkinci Gaybeti</li>
                <li><b>#</b>13 Türk</li>
                <li><b>#</b>SinavdaYokuz</li>
                <li><b>#</b>Sevgililer Günü</li>
                <li><b>#</b>karyağıyor</li>
                <li><b>#</b>Galatasaray</li>
                <li><b>#</b>Sırların Bedeli</li>
                <li><b>#</b>Haram</li>
            </ul>
        </div>
    </div>

    <?php

    $last_ros = $baglanti->query("SELECT ros_id FROM ros ORDER BY ros_id DESC LIMIT 1");
    $last_total_ros = $last_ros->fetch_assoc();
    $last_ros_id = $last_total_ros['ros_id'];
    $final_id = intval($last_ros_id)+1;
    $pin_ros = $baglanti->query("UPDATE ros SET ros_id = '$final_id' WHERE ros_owner = 'sabitlendi' LIMIT 1");

    ?>

    <div class="main" id="mainMenu">
    <!-- <img src="media/octo.png" width="90"> // -->
        <div class="add-btn" onclick="post();"><i class="fa fa-beer"></i></div>
        <div class="roses">
            <?php
            $sonuc_ros = $baglanti->query("SELECT * FROM ros ORDER BY ros_id DESC LIMIT 20");
            while ($total_ros = $sonuc_ros->fetch_array()) {
                $date = date("d.m.Y");
                if ($date == $total_ros['ros_date']) $post_time = $total_ros['ros_time'];
                else $post_time = $total_ros['ros_date'];
                $ros_person = $total_ros['ros_owner'];
                $person = $baglanti->query("SELECT * FROM users WHERE username = '$ros_person'");
                $total_person = $person->fetch_array();
                $profile_url = "";
                $_SESSION['id'] = $total_person_session['id'];
            ?>
                <div class="person" id="<?php  echo($total_ros['ros_id']) ?>profile">
                    <div class="profile">
                        <?php
                        if($total_person['profile_pic'] == '-') $profile_url = "media/noprofile.png";
                        else $profile_url = "PROFILE_PIC/".$total_person['profile_pic'];
                        ?>
                        <div class="name"><img src='<?php echo($profile_url)?>' width="25" height="25"><b onclick='viewProfile(<?php echo($total_person['id']) ?>)' id="ros_owner_name"><?php echo ($total_ros['ros_owner'])  ?></b></div>
                        <div class="date"><?php echo ($post_time) ?></div>
                        <div class="delete_ros"><i <?php if($total_ros['ros_owner'] == $_SESSION['USER'] || $_SESSION['USER'] == 'admin' ) echo("class='fa fa-trash delete_ros'"); ?> onclick="deleteRos(<?php echo($total_ros['ros_id']); ?>);" ></i></div>
                    </div>
                    <div class="messages">
                        <strong><?php echo ($total_ros['ros_content']); ?></strong>
                        <?php
                        if($total_ros['ros_file']!='NULL'){
                            $file_url = $total_ros['ros_file'];
                            $media_ext = explode(".",$file_url);
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
                                $liked_users_list = explode(',',$ros_liked_username);
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
                   $cmt_author_query = $baglanti->query("SELECT profile_pic FROM users WHERE username = '$cmt_author_name'");
                   $cmt_author = $cmt_author_query->fetch_assoc();
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
                        $likers_users_list = explode(',',$likers);
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
    <div class="delete-ros-func" id="delete-ros-func">
        <p>Bu rosu silmek istediğinize emin misiniz?</p>
        <div class="buttons">
            <button name="deleteRos_yes" onclick="delete_yes();">Evet</button>
            <button name="deleteRos_no" onclick="delete_no();">Vazgeç</button>
        </div>  
    </div>

    <div class="navigator" id="navigatorMenu">
        <ul>
            <li onclick="mainPageAcc();"><i class="fa fa-home"></i>Anasayfa</li>
            <li onclick="viewProfile(<?php echo($_SESSION['id']); ?>)"><i class="fa fa-user" ></i> Profil</li>
            <li><i class="fa fa-bell"></i>Bildirimler</li>
            <li><i class="fa fa-envelope"></i>Mesajlar</li>
            <li onclick="post();"><i class="fa fa-beer"></i>Ros'la</li>
           <!-- <li onclick="newUser()"><i class="fa fa-user-plus"></i>Yeni Kullanıcı</li>-->
            <li onclick="exitAcc()"><i class="fa fa-sign-out"></i>Çıkış Yap</li>
        </ul>
    </div>

    <div class="profile-data" onclick="viewProfile(<?php echo($_SESSION['id']); ?>)">
        <img src="<?php echo($profile_session_url)?>" width="50" height="50">
        <strong><?php echo($_SESSION['USER']);?></strong>
    </div>



    <script src="js/index.js"></script>

    <?php
    $ch_ros_count = $baglanti->query("SELECT * FROM ros WHERE ros_owner = '$username'");
    $ch_ros_count-> fetch_array();
    $ch_ros_count = $ch_ros_count->num_rows;
    $baglanti->query("UPDATE users SET ros_count = '$ch_ros_count' WHERE username = '$username'");
    ?>
    <div class="toUp" id="top"><i class="fa fa-chevron-up"></i></div>

    <?php 

    $data_update = $baglanti->query("SELECT data_update FROM users WHERE username = '$username'");
    $data_update_check = $data_update->fetch_assoc();
    if($data_update_check['data_update'] != 1){
    ?>
    <div class="userForm">
        <form action="register_control.php" method="post">
            <input type="tel" name="userFormPhone" placeholder="Telefon No" autocomplete="off">
            <input type="text" name="userFormMail" placeholder="Mail adresi" autocomplete="off">
            <input type="text" name="userFormBio" placeholder="Biyografi" autocomplete="off">
            <button name="userFormSubmit">Güncelle</button>
        </form>
    </div>
    <?php } ?>

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
