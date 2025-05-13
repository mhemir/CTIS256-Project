<?php
// Hata raporlamayı aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB ve session load
require 'db.php';

// (İstersen path kontrolü için)
// if (!isset($db)) { die('!! $db tanımsız'); }

// Geri kalan kod...

if($_SESSION['user_role']!=='consumer') exit('Yetkisiz');
$pid = (int)($_POST['product_id'] ?? 0);
$qty = max(1, (int)($_POST['quantity'] ?? 1));

// 1) Sepet tablosunda bu kullanıcıya ait sepet var mı?
$stmt = $db->prepare("SELECT id FROM shopping_cart WHERE consumer_id=?");
$stmt->execute([$_SESSION['user_id']]);
$cart = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$cart){
  $db->prepare("INSERT INTO shopping_cart (consumer_id) VALUES (?)")
     ->execute([$_SESSION['user_id']]);
  $cart_id = $db->lastInsertId();
} else {
  $cart_id = $cart['id'];
}

// 2) Ürün zaten varsa quantity güncelle, yoksa insert
$stmt = $db->prepare(
  "SELECT id, quantity FROM shopping_cart_items 
   WHERE cart_id=? AND product_id=?"
);
$stmt->execute([$cart_id,$pid]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if($item){
  $newQty = $item['quantity'] + $qty;
  $db->prepare(
    "UPDATE shopping_cart_items SET quantity=? 
     WHERE id=?"
  )->execute([$newQty, $item['id']]);
} else {
  $db->prepare(
    "INSERT INTO shopping_cart_items (cart_id,product_id,quantity)
     VALUES (?,?,?)"
  )->execute([$cart_id,$pid,$qty]);
}

echo 'Sepete eklendi!';
