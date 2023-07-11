<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gönderi oluştur</title>
    <link rel="stylesheet" href="css/post.css">
</head>
<body>

<?php
include("connect.php");
if(($_SESSION['USER'])){
$baglanti = $GLOBALS['baglanti'];
$last_ros_id = $baglanti->query("SELECT ros_id FROM ros ORDER BY ros_id DESC LIMIT 1");
$last_ros_id = $last_ros_id->fetch_assoc();
$newTMP = "";
if(isset($_POST) && $_SESSION['USER']){

    if(isset($_POST['ros_new'])){
      $ros_new = $_POST['ros_new'];
      $ros_new = str_replace("'"," ",$ros_new);
      $blocked_chars = [';','<'];
      $ros_new = str_replace($blocked_chars,"",$ros_new);
      $ros_new = htmlspecialchars($ros_new);
    }
    $ros_owner = $_SESSION['USER'];
    $newTMP = "";
    $ros_date = date("d.m.Y");
    $ros_hour = intval(date('H'))+3;
    if($ros_hour <= 0) $ros_hour = 0;
    elseif($ros_hour == 24) $ros_hour = intval("00");
    elseif($ros_hour > 24) $ros_hour -= 24;
    $ros_min = intval(date('i'));
    if($ros_min < 10) $ros_min = "0". strval($ros_min);
    if($ros_hour < 10) $ros_hour = "0". strval($ros_hour);
    $ros_time = $ros_hour. "." . $ros_min;

    if(isset($_FILES['ros_file'])){

      function compressImage($source, $destination, $quality) {
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source);
        elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source);
        elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source);
        imagejpeg($image, $destination, $quality);
      }
    
      $hata = $_FILES['ros_file']['error'];
    
      if($hata != 0) {
        $newTMP = 'NULL';
        echo 'Yüklenirken bir hata gerçekleşmiş.';
        $imgErr = true;
        header("Location: mainpage.php");
        print_r($_FILES);
     }else{
        $boyut = $_FILES['ros_file']['size'];
    
        if($boyut > (1024*1024*50)){
          echo 'Dosya 50MB den büyük olamaz.';
        }else{
          $tip = $_FILES['ros_file']['type'];
          $isim = $_FILES['ros_file']['name'];
          $dosya = $_FILES['ros_file']['tmp_name'];
          $uzanti = explode('.', $isim);
          $uzanti = $uzanti[count($uzanti)-1];
          if($tip == 'image/png' || $tip == 'image/jpeg' || $tip == 'image/jpg' || $tip == 'video/mp4' || $tip == 'video/wav'){
            $idNO = $last_ros_id['ros_id'] +1;
            $newTMP = $_SESSION['USER'] . "-" . $idNO . "." . $uzanti;
            if($tip == 'image/png' || $tip == 'image/jpeg' || $tip == 'image/jpg'){
    
              $filename = $_FILES['ros_file']['name'];
              $valid_ext = array('png','jpeg','jpg');
              $location = 'ROS_FILES/' . $newTMP;
              $file_extension = pathinfo($location, PATHINFO_EXTENSION);
              $file_extension = strtolower($file_extension);
              if(in_array($file_extension,$valid_ext)) compressImage($_FILES['ros_file']['tmp_name'],$location,50);
              else echo "Bilinmeyen dosya uzantısı.";
              
            }elseif($tip == 'video/mp4' || $tip == 'video/wav'){
              $uploadCheck = move_uploaded_file($dosya, 'ROS_FILES/' . $newTMP);
              echo $uploadCheck ? 'Dosyanız upload edildi!' : "dosya upload edilemedi.";
            }
            header('Location: mainpage.php');
          }
        }
      }
    }
  if(isset($_FILES['changeProfilePic'])){
    $username = $_SESSION['USER'];
    print_r($_FILES);
      $hata1 = $_FILES['changeProfilePic']['error'];
      if($hata1 != 0) {
         $newTMP = 'NULL';
         echo 'Yüklenirken bir hata gerçekleşmiş.';
         $imgErr = true;        
         header('Location: profile.php');
      }else {
         $boyut = $_FILES['changeProfilePic']['size'];
         if($boyut > (1024*1024*6)) echo 'Dosya 5MB den büyük olamaz.';
          else{
            $tip = $_FILES['changeProfilePic']['type'];
            $isim = $_FILES['changeProfilePic']['name'];
            $dosya = $_FILES['changeProfilePic']['tmp_name'];
            $uzanti = explode('.', $isim);
            $uzanti = $uzanti[count($uzanti)-1];
            if($tip == 'image/png' || $tip == 'image/jpeg' || $tip == 'image/jpg' || $tip = 'application/octet-stream'){
              $newTMP = $username . "." . $uzanti;
              $uploadCheck = move_uploaded_file($dosya, 'PROFILE_PIC/' . $newTMP);
              $baglanti->query("UPDATE users SET profile_pic = '$newTMP' WHERE username = '$username'");
              if ($baglanti->connect_errno > 0) die("<b>Bağlantı Hatası:</b> " . $baglanti->connect_error);
              echo $uploadCheck ? 'Dosyanız upload edildi!' : "dosya upload edilemedi.";
              header('Location: profile.php');
            }else echo("dosya tipi hatası");
          }
        }
      }
    }
    if((isset($ros_new) || isset($ros_file)) && isset($ros_owner)){
        if((!empty($ros_new) || !empty($ros_file)) && !empty($ros_owner)){
            $baglanti->query("INSERT INTO ros (ros_content,ros_file,ros_owner,ros_date,ros_time) VALUES('$ros_new','$newTMP','$ros_owner','$ros_date',$ros_time)");
            header('Location: mainpage.php');
        }
    }
}else header('Location: front-end/index.php');
?>
</body>
</html>

