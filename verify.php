<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
</head>
<body>
    <h2>Enter the 6-digit code sent to your email</h2>

    <form action="verify_process.php" method="POST">
        <label>Verification Code:</label><br>
        <input type="text" name="code" required><br><br>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
