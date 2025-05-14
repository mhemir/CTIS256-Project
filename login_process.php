<?php
require "db.php";
session_start();

// 1. Form verilerini al
$email = $_POST['email'];
$password = $_POST['password'];
$type = $_POST['type'];  // "market" veya "consumer"

$table = "user";

// 3. Kullanıcıyı bul
$stmt = $db->prepare("SELECT * FROM $table WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();
$_SESSION['user'] = $user;

if ($user) {
    // 4. Şifre doğru mu ve verified = 1 mi?
    require "check.php";
    if (checkUser($email, $password, $user)) {
        if ($user['verified'] == 1) {
            echo "✅ Giriş başarılı. Hoş geldin, $email";
            header("Location: dashboard.php"); 
        } else {
            echo "⚠️ Hesabınız henüz doğrulanmamış. Lütfen e-postanızı kontrol edin.";
        }
    } else {
        echo "❌ Şifre yanlış.";
    }
} else {
    echo "❌ Bu e-posta ile kayıtlı kullanıcı bulunamadı.";
}
?>