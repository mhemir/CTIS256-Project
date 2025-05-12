<?php
session_start();

// Eğer kullanıcı giriş yapmadıysa, login sayfasına yönlendir
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Oturumdan kullanıcı bilgilerini al
$email = $_SESSION['email'];
$type = $_SESSION['user_type']; // "market" veya "consumer"
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>

    <h2>Welcome, <?php echo htmlspecialchars($email); ?></h2>

    <?php if ($type === 'market'): ?>
        <p>🛒 This is the <strong>MARKET</strong> dashboard.</p>
        <a href="add_product.php">Add Product</a>
    <?php else: ?>
        <p>👤 This is the <strong>CONSUMER</strong> dashboard.</p>
        <a href="search.php">Search Products</a>
    <?php endif; ?>

    <br><br>
    <a href="logout.php">Log out</a>

</body>
</html>
