<?php
require 'db.php';
if($_SESSION['user_role']!=='consumer') exit('Yetkisiz');

$id = (int)($_GET['id'] ?? 0);
if($id){
  $db->prepare("DELETE FROM shopping_cart_items WHERE id = ?")
     ->execute([$id]);
}

header('Location: cart.php');
exit;
