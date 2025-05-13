<?php
require 'db.php';
if($_SESSION['user_role']!=='market'){
  exit('Yetkisiz erişim');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id){
  $db->prepare("
    DELETE FROM products 
    WHERE id = ? AND market_id = ?
  ")->execute([$id, $_SESSION['user_id']]);
}

header('Location: dashboard.php');
exit;
