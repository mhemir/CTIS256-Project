<?php
include 'db.php';  // Veritabanı bağlantısı

// 1. Formdan gelen verileri al
$email = $_POST['email'];
$code = $_POST['code'];
$type = $_POST['type'];  // "market" veya "consumer"

// 2. Hangi tabloya bakacağımızı belirle
$table = ($type === 'market') ? 'markets' : 'consumers';

// 3. Kullanıcıyı ve doğrulama kodunu kontrol et
$stmt = $db->prepare("SELECT * FROM $table WHERE email = :email AND verify_code = :code");
$stmt->execute([
    ':email' => $email,
    ':code' => $code
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
?>
