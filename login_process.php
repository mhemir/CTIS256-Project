<?php
session_start();
include 'db.php';

// 1. Form verilerini al
$email = $_POST['email'];
$password = $_POST['password'];
$type = $_POST['type'];  // "market" veya "consumer"

// 2. Hangi tabloya bakacağımızı belirle
$table = ($type === 'market') ? 'markets' : 'consumers';

// 3. Kullanıcıyı bul
$stmt = $db->prepare("SELECT * FROM $table WHERE email = :email");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // 4. Şifre doğru mu ve verified = 1 mi?
    if ($password === $user['password']) {
        if ($user['verified'] == 1) {
            // 5. Giriş başarılı → session başlat
            $_SESSION['email'] = $email;
            $_SESSION['user_type'] = $type;
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
