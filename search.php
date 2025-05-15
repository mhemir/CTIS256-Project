<?php
require 'db.php';
session_start();
if($_SESSION['user']['type']!=='consumer') exit('Yetkisiz');

$searchQuery = trim($_GET['q'] ?? '');
if(!$searchQuery) exit('Kelime gir');

$user_city     = $_SESSION['user']['city'];  
$user_district = $_SESSION['user']['district'];

$sql = "
  SELECT p.*, m.city, m.district 
  FROM products p
  JOIN markets m ON m.id = p.market_id
  WHERE p.expiration_date >= CURDATE()
    AND p.title LIKE :kw
    AND m.city = :city
  ORDER BY (m.district = :district) DESC
  LIMIT 20
";

$stmt = $db->prepare($sql);
$stmt->execute([
  ':kw'       => "%$searchQuery%",
  ':city'     => $user_city,
  ':district' => $user_district
]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<h2>Arama Sonuçları (<?=htmlspecialchars($searchQuery)?>)</h2>
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

