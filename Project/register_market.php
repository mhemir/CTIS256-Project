<link rel="stylesheet" href="./css/register_form.css">


<?php
// register_market.php
session_start();
require 'db.php';
require 'libraries/PHPMailer-master/src/Exception.php';
require 'libraries/PHPMailer-master/src/PHPMailer.php';
require 'libraries/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "./config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['register_data'] = [
        'email'       => $_POST['email'],
        'password'    => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'role'        => 'market',
        'market_name' => $_POST['market_name'],
        'city'        => $_POST['city'],
        'district'    => $_POST['district']
    ];

    $code = random_int(100000, 999999);
    $_SESSION['verification_code'] = $code;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'asmtp.bilkent.edu.tr';
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL;
        $mail->Password = PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(EMAIL, 'Cheap Market');
        $mail->addAddress($_SESSION['register_data']['email']);
        $mail->isHTML(true);
        $mail->Subject = 'Email Confirmation';
        $mail->Body    = "<p>Your confirmation code is: <strong>$code</strong></p>";

        $mail->send();
        header("Location: verification.php");
        exit;
    } catch (Exception $e) {
        echo "<p>Error sending email: {$mail->ErrorInfo}</p>";
    }
}
?>

<div class="form-container">
    <h2>Register as Market</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="market_name" placeholder="Market Name" required>
        <input type="text" name="city" placeholder="City" required>
        <input type="text" name="district" placeholder="District" required>
        <button type="submit">Register</button>
    </form>
</div>