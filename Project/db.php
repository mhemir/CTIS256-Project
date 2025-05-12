<?php
  $dsn = 'mysql:host=localhost;dbname=market_db;charset=utf8mb4';
  $user = 'root';
  $pass = '';

  try {
      $db = new PDO($dsn, $user, $pass);
  } catch (Exception $ex) {
      echo '<p>DB Connect Error: ' . $ex->getMessage() . '</p>';
      exit;
  }
?>
