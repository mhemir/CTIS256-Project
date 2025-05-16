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
  // image upload
   $imagePath = null;
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
      $newName = uniqid('img_', true) . '.' . $ext;
      if (!is_dir('photos')) mkdir('photos');
      $target = 'photos/' . $newName;
      move_uploaded_file($_FILES['image']['tmp_name'], $target);
      $imagePath = $target;
  }
  $stmt = $db->prepare("INSERT INTO products
    (market_id,title,stock,normal_price,discounted_price,expiration_date,image_path)
    VALUES (?,?,?,?,?,?,?)");
  $stmt->execute([$_SESSION['user']['id'], $title, $stock, $norm, $disc, $exp,$imagePath]);
  header('Location: dashboard.php');
  exit;
}
?>
<form method="POST" enctype="multipart/form-data">
  Name: <input name="title"><br>
  stock: <input name="stock" type="number"><br>
  Price: <input name="normal_price" type="number" ><br>
  Discounted Price: <input name="discounted_price" type="number"><br>
  Expiration Date: <input name="expiration_date" type="date"><br>
  Image: <input type="file" name="image"><br>
  <button>Add</button>
</form>
  <p><a href="market.php">← Turn Back</a></p>
