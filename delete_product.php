<?php
session_start();
require 'db.php';

// Güvenlik kontrolü
if (!isset($_SESSION['user']['id']) || $_SESSION['user']['type'] !== 'market') {
  exit('Yetkisiz erişim');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
  // Sadece o markete ait ürün silinsin
  $stmt = $db->prepare("DELETE FROM products WHERE id = ? AND market_id = ?");
  $stmt->execute([$id, $_SESSION['user']['id']]);

  // Silindi mi kontrol (isteğe bağlı log)
  if ($stmt->rowCount() > 0) {
    header('Location: dashboard.php?deleted=1');
    exit;
  } else {
    echo "Ürün bulunamadı ya da size ait değil.";
    
    exit;
  }
} else {
  echo "Geçersiz ürün ID.";
  exit;
}
