<?php
require 'db.php';
if($_SESSION['user_role']!=='consumer') exit('Yetkisiz');

// Süresi geçenleri kaldır
$db->prepare("
  DELETE sci FROM shopping_cart_items sci
  JOIN products p ON p.id = sci.product_id
  JOIN shopping_cart sc ON sc.id = sci.cart_id
  WHERE sc.consumer_id = ? AND p.expiration_date < CURDATE()
")->execute([$_SESSION['user_id']]);

// Sepeti al
$stmt = $db->prepare("
  SELECT sci.id AS item_id,
         p.title,
         p.discounted_price,
         sci.quantity
  FROM shopping_cart_items sci
  JOIN shopping_cart sc ON sc.id = sci.cart_id
  JOIN products p      ON p.id  = sci.product_id
  WHERE sc.consumer_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Sepetim</title></head>
<body>
  <h2>🛒 Sepetim</h2>
  <?php if(!$items): ?>
    <p>Sepetin boş.</p>
  <?php else: ?>
    <table border="1" cellpadding="5" cellspacing="0">
      <tr>
        <th>Ürün</th><th>Adet</th><th>Birim Fiyat</th><th>Toplam</th><th>İşlem</th>
      </tr>
      <?php 
        $grand = 0;
        foreach($items as $it):
          $subtotal = $it['discounted_price'] * $it['quantity'];
          $grand    += $subtotal;
      ?>
      <tr>
        <td><?=htmlspecialchars($it['title'])?></td>
        <!-- Mevcut: <td><?=$it['quantity']?></td> -->
<td>
  <form class="update-item" style="display:inline;">
    <input type="number" name="quantity"
           value="<?=$it['quantity']?>" min="1" style="width:50px">
    <button data-id="<?=$it['item_id']?>">↻</button>
  </form>
</td>

        <td><?=$it['discounted_price']?>₺</td>
        <td><?=$subtotal?>₺</td>
        <td>
          <a href="cart_remove.php?id=<?=$it['item_id']?>"
             onclick="return confirm('Bu ürünü sepetten silmek istediğine emin misin?')">Sil</a>
        </td>
      </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="3" style="text-align:right"><strong>Genel Toplam:</strong></td>
        <td colspan="2"><strong><?=$grand?>₺</strong></td>
      </tr>
    </table>
    <form action="checkout.php" method="POST" style="margin-top:20px;">
      <button type="submit">Satın Al</button>
    </form>
  <?php endif; ?>
</body>
</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('.update-item').on('submit', function(e){
  e.preventDefault();
  var form   = $(this);
  var itemId = form.find('button').data('id');
  var qty    = form.find('[name=quantity]').val();
  $.ajax({
    url: 'cart_update.php',
    type: 'POST',
    data: { item_id: itemId, quantity: qty },
    success: function(res){
      alert(res);
      location.reload(); // yenile, yeni toplamları göster
    },
    error: function(xhr, status, err){
      alert('Güncellerken hata: ' + err);
    }
  });
});
</script>

