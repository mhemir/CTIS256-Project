<?php
require 'db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
          crossorigin="anonymous">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow p-4 mb-4">
        <h3 class="mb-3">Ürün Ara</h3>
        <form action="search.php" method="GET" class="d-flex">
            <input type="text" name="q" class="form-control me-2" placeholder="Anahtar kelime gir" required>
            <button type="submit" class="btn btn-success">Ara</button>
        </form>
    </div>

    <?php
    // consumer bloğun içinde
    $stmt = $db->prepare("
      SELECT COALESCE(SUM(sci.quantity),0) 
        AS total 
      FROM shopping_cart_items sci
      JOIN shopping_cart sc ON sc.id = sci.cart_id
      WHERE sc.consumer_id = ?
    ");
    $stmt->execute([$_SESSION['user']['id']]);
    $total = $stmt->fetchColumn();
    ?>

    <!-- My Cart link -->
    <p class="mt-4">
        <a href="cart.php" class="btn btn-primary">
            🛒 Sepetim (<span id="cart-count">...</span>)
        </a>
    </p>

    <p class="mt-3">
        <a href="logout.php" class="btn btn-outline-danger">Log out</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

<script>
function updateCartTotal() {
  fetch("cart_total.php")
    .then(res => res.json())
    .then(data => {
      document.getElementById("cart-count").textContent = data.total;
    })
    .catch(err => {
      document.getElementById("cart-count").textContent = "?";
    });
}

updateCartTotal(); // sayfa yüklenince hemen çağır
</script>

</body>
</html>
