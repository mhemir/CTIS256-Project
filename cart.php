<?php
session_start();
require_once "db.php"; // veritabanı bağlantısı

$consumer_id = $_SESSION['user']['id'] ?? 0;

// Kullanıcının aktif sepetini al
$stmt = $db->prepare("SELECT id FROM shopping_cart WHERE consumer_id = ?");
$stmt->execute([$consumer_id]);
$cart = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cart) {
    echo "Sepetiniz boş.";
    exit;
}

$cart_id = $cart['id'];

// Sepetteki ürünleri çek
$stmt = $db->prepare("
    SELECT sci.id as item_id, p.title, p.discounted_price, sci.quantity
    FROM shopping_cart_items sci
    JOIN products p ON sci.product_id = p.id
    WHERE sci.cart_id = ?
");
$stmt->execute([$cart_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sepet</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<h2>Sepetiniz</h2>
<table border="1">
    <tr>
        <th>Ürün</th>
        <th>Adet</th>
        <th>Fiyat</th>
        <th>Toplam</th>
        <th>İşlem</th>
    </tr>
    <?php foreach ($items as $item): ?>
    <tr data-id="<?= $item['item_id'] ?>">
        <td><?= $item['title'] ?></td>
        <td><input type="number" value="<?= $item['quantity'] ?>" class="quantity"></td>
        <td><?= $item['discounted_price'] ?> TL</td>
        <td class="subtotal"><?= $item['discounted_price'] * $item['quantity'] ?> TL</td>
        <td><button class="remove">Sil</button></td>
    </tr>
    <?php endforeach; ?>
</table>
<h3>Genel Toplam: <span id="total"></span> TL</h3>
<button id="purchase">Satın Al</button>

<script>
function updateTotal() {
    let total = 0;
    $(".subtotal").each(function () {
        total += parseFloat($(this).text());
    });
    $("#total").text(total.toFixed(2));
}
updateTotal();

// Miktar değişince
$(".quantity").on("change", function () {
    const tr = $(this).closest("tr");
    const itemId = tr.data("id");
    const quantity = $(this).val();

    $.post("cart_update.php", { item_id: itemId, quantity }, function (data) {
        const price = parseFloat(tr.find("td:nth-child(3)").text());
        tr.find(".subtotal").text((price * quantity).toFixed(2) + " TL");
        updateTotal();
    });
});

// Ürün sil
$(".remove").on("click", function () {
    const tr = $(this).closest("tr");
    const itemId = tr.data("id");

    $.post("cart_update.php", { item_id: itemId, delete: true }, function () {
        tr.remove();
        updateTotal();
    });
});

// Satın al
$("#purchase").on("click", function () {
    $.post("checkout.php", {}, function (response) {
        alert(response);
        location.reload();
    });
});
</script>
</body>
</html>
