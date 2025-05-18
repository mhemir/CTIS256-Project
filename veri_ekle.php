<?php
// Database bağlantısı
require "db.php";

// Örnek veri seti oluşturma ve veritabanına ekleme
$marketIds = [29, 30, 32];

try {
    $stmt = $db->prepare("INSERT INTO products (market_id, title, stock, normal_price, discounted_price, expiration_date, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");

    for ($i = 1; $i <= 100; $i++) {
        $marketId = 34;
        $title = "Product $i";
        $stock = rand(10, 100); // 10 ile 100 arasında rastgele stok
        $normalPrice = rand(50, 500); // 50 ile 500 arasında rastgele normal fiyat
        $discountedPrice = $normalPrice * (rand(50, 90) / 100); // Normal fiyattan %10-%50 indirim
        $expirationDate = date('Y-m-d', strtotime("-" . rand(30, 365) . " days")); // Gelecekte rastgele tarih
        $imagePath = "images/product_$i.jpg";

        $stmt->execute([
            $marketId,
            $title,
            $stock,
            $normalPrice,
            number_format($discountedPrice, 2, '.', ''),
            $expirationDate,
            $imagePath
        ]);
    }

    echo "100 kayıt başarıyla eklendi.\n";
} catch (PDOException $e) {
    die("Veri ekleme başarısız: " . $e->getMessage());
}
?>