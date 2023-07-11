<?php 

Session_start();
Session_destroy();
header('Location: front-end/index.php');
?>