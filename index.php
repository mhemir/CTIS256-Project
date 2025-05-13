<?php
require_once __DIR__ . '/db.php';

// Kullanıcı zaten giriş yaptıysa → dashboard'a yönlendir
if (isset($_SESSION['email'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>
<body>
    <h2>Welcome to the Sustainability Market App</h2>

    <p>Please login or register to continue:</p>

    <a href="login.php">Login</a> |
    <a href="register.php">Register</a>
</body>
</html>
