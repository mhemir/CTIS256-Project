<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
</head>
<body>
    <h2>Enter the 6-digit code sent to your email</h2>

    <form action="verify_process.php" method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Verification Code:</label><br>
        <input type="text" name="code" required><br><br>

        <label>User Type:</label><br>
        <select name="type" required>
            <option value="market">Market</option>
            <option value="consumer">Consumer</option>
        </select><br><br>

        <button type="submit">Verify</button>
    </form>
</body>
</html>
