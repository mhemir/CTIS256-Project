<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require 'db.php';

session_start();


// Eğer kullanıcı giriş yapmadıysa, login sayfasına yönlendir
// if (!isset($_SESSION['email'])) {
//     header("Location: index.php");
//     exit;
// }

// Oturumdan kullanıcı bilgilerini al
$email = $_SESSION['user']['email'];
$type = $_SESSION['user']['type']; // "market" veya "consumer"
?>
<!-- 
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>

    <h2>Welcome, <?php echo htmlspecialchars($email); ?></h2> -->

   <?php
if ($_SESSION['user']['type'] === 'market') {
    header('Location: market.php');
    exit;
} else {
    header('Location: customer.php');
    exit;
} ?>
<!-- 
    <br><br>
    <a href="logout.php">Log out</a>

</body>
</html> -->







