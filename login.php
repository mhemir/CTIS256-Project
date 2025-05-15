<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <form action="login_process.php" method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>User Type:</label><br>
        <select name="type" required>
            <option value="market">Market</option>
            <option value="consumer">Consumer</option>
        </select><br><br>

        <input type="checkbox" name="remember">

        <button type="submit">Login</button>
    </form>
    <a href="index.php">Go back to main page</a>

</body>
</html>









