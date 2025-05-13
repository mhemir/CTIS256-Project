<?php
session_start();

// DEV mod için, production’da false yapmayı unutma
define('DEV_MODE', true);

if (DEV_MODE) {
  // Test market hesabıyla otomatik giriş
  $_SESSION['user_id']   = 11;         // market tablosundaki test market ID’n
  $_SESSION['user_role'] = 'market';   // rolü market yap
  $_SESSION['email']     = 'test@dev'; // opsiyonel, eğer email check yapıyorsan
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
