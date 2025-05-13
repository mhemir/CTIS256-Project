<?php
require 'db.php';
if($_SESSION['user_role']!=='consumer') exit('Yetkisiz');

$itemId = (int)($_POST['item_id'] ?? 0);
$qty    = max(1,(int)($_POST['quantity'] ?? 1));

if($itemId){
  $db->prepare(
    "UPDATE shopping_cart_items 
     SET quantity=? 
     WHERE id=?"
  )->execute([$qty,$itemId]);
}

echo 'Adet güncellendi';
