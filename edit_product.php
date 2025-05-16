<?php

require 'db.php';  

// DEV_MODE’da market olarak giriş yapılıyor, session zaten db.php’de setli


// 1) Gelen id’yi al
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if(!$id){
  exit('Ürün ID’si gerekli');
}

// 2) POST geldiyse güncelle
if($_SERVER['REQUEST_METHOD']==='POST'){
  $title = trim($_POST['title']);
  $stock = (int)$_POST['stock'];
  $disc  = (float)$_POST['discounted_price'];
  $exp   = $_POST['expiration_date'];

  $sql = "UPDATE products 
          SET title=?, stock=?, discounted_price=?, expiration_date=?
          WHERE id=? AND market_id=?";
  $stmt = $db->prepare($sql);
  $stmt->execute([$title, $stock, $disc, $exp, $id, $_SESSION['user']['id']]);

  header('Location: dashboard.php');
  exit;
}

// 3) Mevcut ürünü çek
$stmt = $db->prepare("
  SELECT * FROM products 
  WHERE id = ? AND market_id = ?
");
$stmt->execute([$id, $_SESSION['user']['id']]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Ürün Düzenle</title></head>
<body>
  <h2>Ürün Düzenle</h2>
  <form method="POST">
    Başlık:<br>
    <input name="title" value="<?=htmlspecialchars($p['title'])?>" style="width:300px"><br><br>

    Stok:<br>
    <input name="stock" type="number" value="<?=$p['stock']?>" min="0"><br><br>

    İndirimli Fiyat:<br>
    <input name="discounted_price" type="number" step="0.01" 
           value="<?=$p['discounted_price']?>"><br><br>

    Son Kullanma Tarihi:<br>
    <input name="expiration_date" type="date" 
           value="<?=$p['expiration_date']?>"><br><br>

    <button type="submit">Güncelle</button>
    <a href="dashboard.php" style="margin-left:1rem;">Vazgeç</a>
  </form>
</body>
</html>
