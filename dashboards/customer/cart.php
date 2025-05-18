<?php
session_start();
require_once "../../db.php"; // veritabanı bağlantısı

if(!isset($_SESSION["user"])){
    header("Location: ../../index.php");
    exit;
}

$consumer_id = $_SESSION['user']['id'];

//Get card
$stmt = $db->prepare("SELECT id FROM shopping_cart WHERE consumer_id = ?");
$stmt->execute([$consumer_id]);
$card = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$card) {
    // Create shopping card if doesn't exist.
    $stmt = $db->prepare("INSERT INTO shopping_cart(consumer_id) VALUES(?)");
    $stmt->execute([$consumer_id]);
}

// Get card after creation
$stmt = $db->prepare("SELECT id FROM shopping_cart WHERE consumer_id = ?");
$stmt->execute([$consumer_id]);
$card = $stmt->fetch(PDO::FETCH_ASSOC);


$cart_id = $card['id'];

// Sepetteki ürünleri çek
$stmt = $db->prepare("
SELECT
    p.id AS product_id,
    p.title AS title,            
    p.discounted_price AS discounted_price, 
    i.quantity,
    p.expiration_date AS expiration_date, 
    p.image_path AS image
FROM 
    products p, 
    shopping_cart_items i,
    shopping_cart c
WHERE
    i.cart_id = ? 
AND
	p.id = i.product_id
AND 
    c.id = i.cart_id
");
$stmt->execute([$cart_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count = count($items);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Card</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
          crossorigin="anonymous">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">Your Card</h2>
    <div class="table-responsive">
        <table class="table table-bordered align-middle bg-white">
        <thead class="table-light">
            <tr>
                <th>Product Title</th>
                <th>Discounted Price</th>
                <th>Quantity</th>
                <th>Expiration Date</th>
                <th>Image</th>
                <th>Operation</th>
            </tr>
        </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr data-id="<?= $item['product_id'] ?>">
                    <td><?= $item['title'] ?></td>
                    <td class="subtotal"><?= $item['discounted_price'] ?> TL</td>
                    <td><input type="number" value="<?= $item['quantity'] ?>" class="form-control quantity"></td>                    <td><?= $item['expiration_date'] ?></td>
                    <td><?= $item['image'] ?></td>
                    <td><button class="btn btn-sm btn-danger remove">Remove</button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <h4 class="mt-4">Overall: <span id="total"></span> TL</h4>
    <button id="purchase" class="btn btn-success mt-3">Purchase</button>
    <a href="../dashboard.php" class="btn btn-outline-secondary">← Turn Back</a>
</div>

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
    if (!confirm("Satın almak istediğine emin misin?")) return;

    $.post("checkout.php", {}, function (response) {
        alert(response);

        // 🧼 Sepetteki tüm satırları DOM'dan sil
        $("tr[data-id]").remove();

        // 🧮 Genel toplamı sıfırla
        $("#total").text("0.00");

        // (isteğe bağlı) "Sepet boş" mesajı
        $("table").after("<p>Sepetiniz boş 😢</p>");
    });
});

    updateCartTotal(); // sayfa yüklenince hemen çağır
</script>
</body>
</html>
