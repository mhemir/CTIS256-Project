<?php
  session_start();

  // if the user has already logged in, don't show login form
  if ( isset($_SESSION["user"])) {
      header("Location: dashboard.php") ; // auto login
      exit ;
   } 


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Sustainable Market</title>
    <style>
        body{
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Sustainability Market App</h1>
  
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
   
</body>
</html>