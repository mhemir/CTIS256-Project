

<?php
// login_consumer.php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // sadece consumer'ları çekiyoruz
    $stmt = $db->prepare("SELECT u.id, u.password 
                          FROM users u 
                          WHERE u.email = :email AND u.role = 'consumer'");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // başarılı
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_role'] = 'consumer';
        header('Location: index.php'); // ya da consumer panelin
        exit;
    } else {
        $error = "❌ Email veya şifre yanlış.";
    }
}
?>

<div class="form-container">
  <h2>Login as Consumer</h2>
  <?php if (!empty($error)): ?>
    <p class="error"><?= $error ?></p>
  <?php endif; ?>
  <form method="POST">
    <input type="email"    name="email"    placeholder="Email"    required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Giriş Yap</button>
  </form>
  <p class="alternate">
    Henüz hesabın yok mu? 
    <a href="register_consumer.php">Kayıt ol</a>
  </p>
</div>
