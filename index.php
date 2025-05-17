<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: dashboard.php");
    exit;
}

require "check.php";

if (isset($_COOKIE["access_token"])) {
    $user = getUserByToken($_COOKIE["access_token"]);
    if ($user) {
        $_SESSION["user"] = $user;
        header("Location: dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sustainable Market</title>
    <!-- Bootstrap 5.3.6 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
          crossorigin="anonymous">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow p-5 text-center" style="max-width: 500px; width: 100%;">
        <h1 class="mb-4">Welcome to Sustainability Market</h1>
        <a href="login.php" class="btn btn-success mb-2 w-100">Login</a>
        <a href="register.php" class="btn btn-outline-secondary w-100">Register</a>
    </div>
</div>

<!-- Bootstrap 5.3.6 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
</body>
</html>
