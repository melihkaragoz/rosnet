<?php
session_start();
include('connect.php');

    if(isset($_GET)){
        $rosID = $_GET['ros'];
        $username = $_SESSION['USER'];
        $allData = $baglanti->query("SELECT * FROM ros WHERE ros_id = '$rosID'");
        $allData = $allData->fetch_array();
        if($allData['ros_owner'] == $username || $username == 'admin' ){
            $baglanti->query("DELETE FROM ros WHERE ros_id = $rosID");
            header('Location: mainpage.php');
        }header('Location: mainpage.php');
    }
    
?>