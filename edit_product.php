<?php
session_start();
require 'db.php';

// Yetkisiz erişim kontrolü
if (!isset($_SESSION['user']['id']) || $_SESSION['user']['type'] !== 'market') {
    exit('Yetkisiz erişim');
}

$market_id = $_SESSION['user']['id'];
$product_id = $_GET['id'] ?? 0;

// Eğer form gönderildiyse
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $stock = $_POST['stock'];
    $normal_price = $_POST['normal_price'];
    $discounted_price = $_POST['discounted_price'];
    $expiration_date = $_POST['expiration_date'];

    $stmt = $db->prepare("
        UPDATE products SET 
            title = ?, stock = ?, normal_price = ?, discounted_price = ?, expiration_date = ?
        WHERE id = ? AND market_id = ?
    ");
    $stmt->execute([$title, $stock, $normal_price, $discounted_price, $expiration_date, $product_id, $market_id]);

    header("Location: dashboard.php?updated=1");
    exit;
}

// Ürünü getir
$stmt = $db->prepare("SELECT * FROM products WHERE id = ? AND market_id = ?");
$stmt->execute([$product_id, $market_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    exit("Ürün bulunamadı veya size ait değil.");
}
?>

<!-- HTML Form -->
<h2>Ürün Düzenle</h2>
<form method="POST">
    Başlık: <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>"><br>
    Stok: <input type="number" name="stock" value="<?= $product['stock'] ?>"><br>
    Normal Fiyat: <input type="text" name="normal_price" value="<?= $product['normal_price'] ?>"><br>
    İndirimli Fiyat: <input type="text" name="discounted_price" value="<?= $product['discounted_price'] ?>"><br>
    Son Kullanma Tarihi: <input type="date" name="expiration_date" value="<?= $product['expiration_date'] ?>"><br>
    <button type="submit">Güncelle</button>
</form>

<p><a href="dashboard.php">← Geri Dön</a></p>
