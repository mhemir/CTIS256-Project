<?php
require 'db.php';
session_start();
// DEV_MODE girişi var, type kontrolü
if ($_SESSION['user']['type'] !== 'market') exit('Yetkisiz');
// Post geldiyse işle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // filtrasyon vs.
  $title = $_POST['title'];
  $stock = (int)$_POST['stock'];
  $norm  = (float)$_POST['normal_price'];
  $disc  = (float)$_POST['discounted_price'];
  $exp   = $_POST['expiration_date'];
  // image upload vs. (sonra ekle)
  $stmt = $db->prepare("INSERT INTO products
    (market_id,title,stock,normal_price,discounted_price,expiration_date)
    VALUES (?,?,?,?,?,?)");
  $stmt->execute([$_SESSION['user']['id'], $title, $stock, $norm, $disc, $exp]);
  header('Location: dashboard.php');
  exit;
}
?>
<form method="POST">
  Name: <input name="title"><br>
  stock: <input name="stock" type="number"><br>
  Price: <input name="normal_price" type="number" ><br>
  Discounted Price: <input name="discounted_price" type="number"><br>
  Expiration Date: <input name="expiration_date" type="date"><br>
  <button>Add</button>
</form>