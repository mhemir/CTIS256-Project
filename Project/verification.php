<link rel="stylesheet" href="verification.css">

<?php
session_start();
require 'db.php';

// Проверка: есть ли нужные данные в сессии
if (!isset($_SESSION['register_data'], $_SESSION['verification_code'])) {
    echo "<p class='error'>Session expired or invalid access.</p>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styles/verification.css">
</head>
<body>
<div class="form-container">
    <h2>Email Verification</h2>
    <p>Please enter the 6-digit code sent to your email.</p>

    <form method="POST">
        <input type="text" name="code" placeholder="Enter code" maxlength="6" required>
        <button type="submit">Verify</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $enteredCode = $_POST['code'];
        $realCode = $_SESSION['verification_code'];

        if ($enteredCode == $realCode) {
            $data = $_SESSION['register_data'];

            try {
                $stmt = $db->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
                $stmt->execute([$data['email'], $data['password'], $data['role']]);
                $userId = $db->lastInsertId();

                if ($data['role'] === 'market') {
                    $stmt = $db->prepare("INSERT INTO markets (user_id, market_name, city, district) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$userId, $data['market_name'], $data['city'], $data['district']]);
                } elseif ($data['role'] === 'consumer') {
                    $stmt = $db->prepare("INSERT INTO consumers (user_id, fullname, city, district) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$userId, $data['fullname'], $data['city'], $data['district']]);
                }

                unset($_SESSION['register_data'], $_SESSION['verification_code']);
                echo "<p class='success'>✅ Email confirmed! <a href='login.php'>Log in</a></p>";
            } catch (PDOException $e) {
                echo "<p class='error'>Database error: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p class='error'>❌ Invalid confirmation code. Try again.</p>";
        }
    }
    ?>
</div>

</body>
</html>
