<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']['id']) || $_SESSION['user']['type'] !== 'consumer') {
    http_response_code(403);
    echo "Yetkisiz erişim";
    exit;
}

$consumer_id = $_SESSION['user']['id'];
$product_id = $_POST['product_id'] ?? 0;
$quantity = $_POST['quantity'] ?? 1;

if (!$product_id || $quantity < 1) {
    http_response_code(400);
    echo "Geçersiz veri";
    exit;
}

// Sepeti al, yoksa oluştur
$stmt = $db->prepare("SELECT id FROM shopping_cart WHERE consumer_id = ?");
$stmt->execute([$consumer_id]);
$cart = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cart) {
    $stmt = $db->prepare("INSERT INTO shopping_cart (consumer_id) VALUES (?)");
    $stmt->execute([$consumer_id]);
    $cart_id = $db->lastInsertId();
} else {
    $cart_id = $cart['id'];
}

// Aynı ürün zaten sepette mi?
$stmt = $db->prepare("SELECT id FROM shopping_cart_items WHERE cart_id = ? AND product_id = ?");
$stmt->execute([$cart_id, $product_id]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    // Miktarı artır
    $stmt = $db->prepare("UPDATE shopping_cart_items SET quantity = quantity + ? WHERE id = ?");
    $stmt->execute([$quantity, $existing['id']]);
} else {
    // Yeni ekle
    $stmt = $db->prepare("INSERT INTO shopping_cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->execute([$cart_id, $product_id, $quantity]);
}



echo "Ürün sepete eklendi.";
