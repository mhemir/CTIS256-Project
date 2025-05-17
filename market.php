<?php
require 'db.php';
session_start();

$stmt = $db->prepare("SELECT * FROM products WHERE market_id=? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user']['id']]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Profil güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $city = trim($_POST['city']);
    $district = trim($_POST['district']);
    $params = [$name, $city, $district];
    $sql = "UPDATE user SET name=?, city=?, district=?";
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql .= ", password=?";
        $params[] = $password;
    }
    $sql .= " WHERE id=?";
    $params[] = $_SESSION['user']['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['city'] = $city;
    $_SESSION['user']['district'] = $district;
    header("Location: market.php?edit_profile=1&updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Market Paneli</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">

  <?php if (isset($_GET['edit_profile'])): ?>
    <div class="card shadow p-4 mb-4">
      <h3 class="mb-4">Profil Güncelle</h3>
      <form method="POST">
        <input type="hidden" name="update_profile" value="1">
        <div class="mb-3">
          <label>Email</label>
          <input name="email" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>" disabled>
        </div>
        <div class="mb-3">
          <label>Ad</label>
          <input name="name" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['name']) ?>" required>
        </div>
        <div class="mb-3">
          <label>Şehir</label>
          <input name="city" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['city']) ?>" required>
        </div>
        <div class="mb-3">
          <label>İlçe</label>
          <input name="district" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['district']) ?>" required>
        </div>
        <div class="mb-4">
          <label>Yeni Şifre (opsiyonel)</label>
          <input name="password" type="password" class="form-control" placeholder="Yeni şifre">
        </div>
        <button class="btn btn-success w-100">Güncelle</button>
      </form>
      <div class="text-center mt-3">
        <a href="market.php" class="btn btn-outline-secondary">← Geri Dön</a>
      </div>
    </div>

  <?php else: ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <a href="market.php?edit_profile=1" class="btn btn-primary">Profil Bilgilerini Güncelle</a>
      <a href="logout.php" class="btn btn-outline-danger">Çıkış Yap</a>
    </div>

    <h3 class="mb-3">Ürün Yönetimi</h3>
    <a href="add_product.php" class="btn btn-success mb-3">+ Yeni Ürün Ekle</a>

    <div class="table-responsive">
      <table class="table table-bordered align-middle bg-white">
        <thead class="table-light">
          <tr>
            <th>Başlık</th>
            <th>Stok</th>
            <th>Fiyat</th>
            <th>Son Kullanma</th>
            <th>Fotoğraf</th>
            <th>İşlem</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p): 
          $expired = (new DateTime($p['expiration_date']) < new DateTime());
        ?>
          <tr class="<?= $expired ? 'table-danger' : '' ?>">
            <td><?= htmlspecialchars($p['title']) ?></td>
            <td><?= $p['stock'] ?></td>
            <td><?= $p['discounted_price'] ?>₺</td>
            <td><?= $p['expiration_date'] ?></td>
            <td>
              <?php if (!empty($p['image_path'])): ?>
                <img src="<?= htmlspecialchars($p['image_path']) ?>" width="60" class="img-thumbnail">
              <?php else: ?>
                <span class="text-muted">Fotoğraf yok</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="edit_product.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Düzenle</a>
              <a href="delete_product.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğine emin misin?')">Sil</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
