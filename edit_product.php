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

     // update photo
    $imagePath = $product['image_path'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newName = uniqid('img_', true) . '.' . $ext;
        if (!is_dir('photos')) mkdir('photos');
        $target = 'photos/' . $newName;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $imagePath = $target;
    }

    $stmt = $db->prepare("
        UPDATE products SET 
            title = ?, stock = ?, normal_price = ?, discounted_price = ?, expiration_date = ?, image_path= ?
        WHERE id = ? AND market_id = ?
    ");
    $stmt->execute([$title, $stock, $normal_price, $discounted_price, $expiration_date,$imagePath, $product_id, $market_id]);

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
<form method="POST" enctype="multipart/form-data">
    Title: <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>"><br>
    Stock: <input type="number" name="stock" value="<?= $product['stock'] ?>"><br>
    Normal Price: <input type="text" name="normal_price" value="<?= $product['normal_price'] ?>"><br>
    Discuonted Price: <input type="text" name="discounted_price" value="<?= $product['discounted_price'] ?>"><br>
    Expiration Date: <input type="date" name="expiration_date" value="<?= $product['expiration_date'] ?>"><br>
     Existing Photo: 
    <?php if ($product['image_path']): ?>
        <img src="<?= htmlspecialchars($product['image_path']) ?>" width="80"><br>
    <?php endif; ?>
    New Photo: <input type="file" name="image"><br>
    <button type="submit">Update</button>
</form>

<p><a href="dashboard.php">← Geri Dön</a></p>
