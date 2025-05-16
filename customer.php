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
</head>
<body>

  <h3>Ürün Ara</h3>
  <form action="search.php" method="GET">
    <input type="text" name="q" placeholder="Anahtar kelime gir" required>
    <button type="submit">Ara</button>
  </form>

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
  <p style="margin-top:1rem;">
  <a href="cart.php"
     style="display:inline-block; padding:8px 12px; background:#28a745; color:#fff; text-decoration:none; border-radius:4px;">
    🛒 Sepetim (<span id="cart-count">...</span>)
  </a>
</p>



<br><br>
<a href="logout.php">Log out</a>
</body>
</html>

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


