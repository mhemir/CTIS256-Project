<?php
session_start();

// DEV mod için, production’da false yapmayı unutma
define('DEV_MODE', true);

if(DEV_MODE){
  // 1 nolu consumer hesabıyla logged in say
  $_SESSION['user_id'] = 4;
  $_SESSION['user_role'] = 'consumer';
  $_SESSION['email'] = 'test@dev';

}

$dsn  = "mysql:host=localhost;dbname=project;charset=utf8mb4";
$user = "root";
$pass = "";

try {
    $db = new PDO($dsn, $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $ex) {
   echo "DB Connection Error : " .  $ex->getMessage();
}
