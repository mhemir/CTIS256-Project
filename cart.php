<?php
session_start();
require_once "db.php"; // veritabanı bağlantısı

$consumer_id = $_SESSION['user']['id'] ?? 0;

// Kullanıcının aktif sepetini al
$stmt = $db->prepare("SELECT id FROM shopping_cart WHERE consumer_id = ?");
$stmt->execute([$consumer_id]);
$cart = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cart) {
    echo '
    <!DOCTYPE html>
    <html lang="tr">
    <head>
        <meta charset="UTF-8">
        <title>Sepetiniz Boş</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
              rel="stylesheet"
              integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
              crossorigin="anonymous">
    </head>
    <body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow p-5 text-center" style="max-width: 500px; width: 100%;">
            <h2 class="mb-3">Sepetiniz boş</h2>
            <a href="search.php" class="btn btn-outline-secondary">Ürünlere Göz At</a>
        </div>
    </div>
    </body>
    </html>';
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
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sepet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
          crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">Sepetiniz</h2>
    <div class="table-responsive">
        <table class="table table-bordered align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Ürün</th>
                    <th>Adet</th>
                    <th>Fiyat</th>
                    <th>Toplam</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr data-id="<?= $item['item_id'] ?>">
                        <td><?= $item['title'] ?></td>
                        <td><input type="number" value="<?= $item['quantity'] ?>" class="form-control quantity"></td>
                        <td><?= $item['discounted_price'] ?> TL</td>
                        <td class="subtotal"><?= $item['discounted_price'] * $item['quantity'] ?> TL</td>
                        <td><button class="btn btn-sm btn-danger remove">Sil</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h4 class="mt-4">Genel Toplam: <span id="total"></span> TL</h4>
    <button id="purchase" class="btn btn-success mt-3">Satın Al</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

<script>
// Miktar değişince
function updateTotal() {
    let total = 0;
    $(".subtotal").each(function () {
        total += parseFloat($(this).text());
    });
    $("#total").text(total.toFixed(2));
}
updateTotal();

$(".quantity").on("change", function () {
    const tr = $(this).closest("tr");
    const itemId = tr.data("id");
    const quantity = $(this).val();

    $.post("cart_update.php", { item_id: itemId, quantity }, function () {
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
        $("tr[data-id]").remove();
        $("#total").text("0.00");
        $("table").after("<p class='mt-3 text-muted'>Sepetiniz boş 😢</p>");
    });
});

updateCartTotal(); // sayfa yüklenince hemen çağır
</script>
</body>
</html>
