<?php
require 'db.php';
if($_SESSION['user']['type']!=='consumer') exit('Yetkisiz');

$id = ($_GET['id']);
if($id){
  $db->prepare("DELETE FROM shopping_cart_items WHERE id = ?")
     ->execute([$id]);
}

header('Location: cart.php');
exit;
