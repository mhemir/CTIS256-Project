<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form action="register_process.php" method="POST">
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="text" name="name" placeholder="Full Name / Market Name" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="text" name="city" placeholder="City" required><br><br>
        <input type="text" name="district" placeholder="District" required><br><br>

        <label for="type">User Type:</label>
        <select name="type" required>
            <option value="market">Market</option>
            <option value="consumer">Consumer</option>
        </select><br><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
