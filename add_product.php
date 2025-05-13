<?php
require 'db.php';
// DEV_MODE girişi var, role kontrolü
if($_SESSION['user_role']!=='market') exit('Yetkisiz');
// Post geldiyse işle
if($_SERVER['REQUEST_METHOD']==='POST'){
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
  $stmt->execute([$_SESSION['user_id'],$title,$stock,$norm,$disc,$exp]);
  header('Location: dashboard.php');
  exit;
}
?>
<form method="POST">
  Başlık: <input name="title"><br>
  Stok:   <input name="stock" type="number"><br>
  Fiyat:  <input name="discounted_price" type="number" step="0.01"><br>
  Bitiş:  <input name="expiration_date" type="date"><br>
  <button>Ekle</button>
</form>
