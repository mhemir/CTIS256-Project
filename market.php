 <?php
 require 'db.php';
 session_start();
 $stmt = $db->prepare("SELECT * FROM products WHERE market_id=? ORDER BY created_at DESC");
  $stmt->execute([$_SESSION['user']['id']]);
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
  <br><br>
  <a href="logout.php">Log out</a>
