<?php
require 'db.php';  // Veritabanı bağlantısı
session_start();
// 1. Formdan gelen verileri al
$code = $_POST['code'];
// $type = $_POST['type'];  // "market" veya "consumer"
$email = $_SESSION["email"];
// 2. Hangi tabloya bakacağımızı belirle
// $table = ($type === 'market') ? 'markets' : 'consumers';
$table = "user";
// 3. Kullanıcıyı ve doğrulama kodunu kontrol et
$stmt = $db->prepare("SELECT * FROM $table WHERE email = ? AND verify_code = ?");
$stmt->execute([
    $email,
    $code
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // 4. Eşleşen kullanıcı bulunduysa, verified = 1 yap
    $update = $db->prepare("UPDATE $table SET verified = 1 WHERE email = :email");
    $update->execute([':email' => $email]);

    echo "✅ Doğrulama başarılı. Hesabınız artık aktif.";
      header("Location: login.php");
    exit;
} else {
    // 5. Kod yanlışsa veya kullanıcı bulunamazsa
    echo "❌ Hatalı kod ya da e-posta. Lütfen tekrar deneyin.";
}

session_destroy();
?>
