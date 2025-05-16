<?php
session_start();
require 'db.php';

if ($_SESSION['user']['type'] !== 'consumer') exit('Yetkisiz');

$q = trim($_GET['q'] ?? '');
if (!$q) exit('Kelime gir');

$sql = "
  SELECT p.*, m.name AS market_name 
  FROM products p
  JOIN markets m ON m.id = p.market_id
  WHERE p.expiration_date >= CURDATE()
    AND p.title LIKE :kw
  ORDER BY p.title ASC
  LIMIT 20
";

$stmt = $db->prepare($sql);
$stmt->execute([
  ':kw' => "%$q%"
]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<h2>Arama Sonuçları (<?=htmlspecialchars($q)?>)</h2>
<?php if(!$results): ?>
  <p>Ürün bulunamadı.</p>
<?php else: ?>
  <ul>
  <?php foreach($results as $p): ?>
    <li>
      <?=htmlspecialchars($p['title'])?> — <?= $p['discounted_price']?>₺
      <button class="add-to-cart" data-id="<?=$p['id']?>">Sepete Ekle</button>
    </li>
  <?php endforeach; ?>
  </ul>
<?php endif; ?>

<p style="margin: 1rem 0;">
  <a href="dashboard.php"
     style="text-decoration:none; color:#007bff; font-weight:bold;">
    ← Anasayfaya Dön
  </a>
</p>

<script>
document.querySelectorAll('.add-to-cart').forEach(btn=>{
  btn.addEventListener('click',()=>{
    const pid = btn.dataset.id;
    fetch('cart_add.php', {
      method: 'POST',
      headers: {'Content-Type':'application/x-www-form-urlencoded'},
      body: 'product_id='+pid+'&quantity=1'
    })
    .then(res => {
      console.log('HTTP', res.status, res.statusText);
      return res.text();
    })
    .then(txt => {
      console.log('🏷 cart_add response:', txt);
      alert(txt || '[boş yanıt]');
    })
    .catch(err => {
      console.error('🔥 fetch error:', err);
      alert('Fetch hatası, konsolu kontrol et');
    });
  });
});
</script>

