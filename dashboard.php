<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';




// Eğer kullanıcı giriş yapmadıysa, login sayfasına yönlendir
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Oturumdan kullanıcı bilgilerini al
$email = $_SESSION['email'];
$type = $_SESSION['user_role']; // "market" veya "consumer"
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>

    <h2>Welcome, <?php echo htmlspecialchars($email); ?></h2>

    <?php if ($type === 'market'): ?>
        <p>🛒 This is the <strong>MARKET</strong> dashboard.</p>
        <a href="add_product.php">Add Product</a>
    <?php else: ?>
        <p>👤 This is the <strong>CONSUMER</strong> dashboard.</p>
        <a href="search.php">Search Products</a>
    <?php endif; ?>

    <br><br>
    <a href="logout.php">Log out</a>

</body>
</html>

<?php
// en üstte zaten db.php include var, $_SESSION dolu vs.
// Sadece marketse göster
if($_SESSION['user_role']==='market'){
  // O market’e ait ürünleri çek
  $stmt = $db->prepare("SELECT * FROM products WHERE market_id=? ORDER BY created_at DESC");
  $stmt->execute([$_SESSION['user_id']]);
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <h3>Ürün Yönetimi</h3>
  <a href="add_product.php">+ Yeni Ürün Ekle</a>
  <table>
    <tr><th>ID</th><th>Başlık</th><th>Stok</th><th>Fiyat</th><th>Bitiş</th><th>İşlem</th></tr>
    <?php foreach($products as $p): 
      $expired = (new DateTime($p['expiration_date']) < new DateTime());
    ?>
    <tr style="<?= $expired ? 'background:#fdd;' : '' ?>">
      <td><?= $p['id'] ?></td>
      <td><?= htmlspecialchars($p['title']) ?></td>
      <td><?= $p['stock'] ?></td>
      <td><?= $p['discounted_price'] ?>₺</td>
      <td><?= $p['expiration_date'] ?></td>
      <td>
        <a href="edit_product.php?id=<?= $p['id'] ?>">Düzenle</a> |
        <a href="delete_product.php?id=<?= $p['id'] ?>"
           onclick="return confirm('Silinsin mi?')">Sil</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
<?php
}
?>

<?php if($_SESSION['user_role']==='consumer'): ?>
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
$stmt->execute([$_SESSION['user_id']]);
$total = $stmt->fetchColumn();
?>


  <!-- My Cart link -->
  <p style="margin-top:1rem;">
  <a href="cart.php"
     style="display:inline-block; padding:8px 12px; background:#28a745; color:#fff; text-decoration:none; border-radius:4px;">
    🛒 Sepetim (<?= $total ?>)
  </a>
</p>

<?php endif; ?>




