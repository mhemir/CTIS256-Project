<?php
require 'db.php';
if($_SESSION['user_role']!=='consumer') exit('Yetkisiz');

// Kullanıcının sepet id'sini al
$stmt = $db->prepare("SELECT id FROM shopping_cart WHERE consumer_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cart = $stmt->fetch(PDO::FETCH_ASSOC);

if($cart){
  // Sepet içeriğini sil
  $db->prepare("DELETE FROM shopping_cart_items WHERE cart_id = ?")
     ->execute([$cart['id']]);
  // İstersen sepet kaydını da sil:
  // $db->prepare("DELETE FROM shopping_cart WHERE id = ?")
  //    ->execute([$cart['id']]);
}

echo '<p>Satın alma başarılı! 🥳</p>';
echo '<p><a href="dashboard.php">Anasayfaya dön</a></p>';
