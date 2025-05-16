 <?php
  require 'db.php';
  session_start();
  $stmt = $db->prepare("SELECT * FROM products WHERE market_id=? ORDER BY created_at DESC");
  $stmt->execute([$_SESSION['user']['id']]);
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>


 <?php
  // Profil güncelleme işlemi
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $city = trim($_POST['city']);
    $district = trim($_POST['district']);
    $params = [$name, $city, $district];
    $sql = "UPDATE user SET name=?, city=?, district=?";
    // Şifre değişikliği isteniyorsa
    if (!empty($_POST['password'])) {
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $sql .= ", password=?";
      $params[] = $password;
    }
    $sql .= " WHERE id=?";
    $params[] = $_SESSION['user']['id'];
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    // Oturum bilgisini de güncelle
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['city'] = $city;
    $_SESSION['user']['district'] = $district;
    header("Location: market.php?edit_profile=1&updated=1");
    exit;
  }

  ?>

 <?php if (isset($_GET['edit_profile'])): ?>
   <!-- Güncelleme formunu buraya koy -->
   <h3>Update Your Profile</h3>
   <form method="POST">
     <input type="hidden" name="update_profile" value="1">
     Email: <input name="email" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>"><br>
     Name: <input name="name" value="<?= htmlspecialchars($_SESSION['user']['name']) ?>"><br>
     City: <input name="city" value="<?= htmlspecialchars($_SESSION['user']['city']) ?>"><br>
     District: <input name="district" value="<?= htmlspecialchars($_SESSION['user']['district']) ?>"><br>
     Password <input name="password" type="password" placeholder="write your new password"><br>
     <button>Update</button>
   </form>
   <p><a href="market.php">← Turn Back</a></p>
 <?php else: ?>
   <!-- Sadece buton ve ürün yönetimi tablosu burada -->
   <a href="market.php?edit_profile=1">
     <button type="button">Update Your Profile</button>
   </a>







   <!-- Ürün yönetimi tablosu burada -->
 <?php endif; ?>
 <h3>Ürün Yönetimi</h3>
 <a href="add_product.php">+ Yeni Ürün Ekle</a>
 <table>
   <tr>
     <th>Title</th>
     <th>Stock</th>
     <th>Price</th>
     <th>Expiration Date</th>
     <th>Photo</th>
     <th>Edit/Delete</th>
   </tr>
   <?php foreach ($products as $p):
      $expired = (new DateTime($p['expiration_date']) < new DateTime());
    ?>
     <tr style="<?= $expired ? 'background:#fdd;' : '' ?>">
       <td><?= htmlspecialchars($p['title']) ?></td>
       <td><?= $p['stock'] ?></td>
       <td><?= $p['discounted_price'] ?>₺</td>
       <td><?= $p['expiration_date'] ?></td>
       <td> <?php if (!empty($p['image_path'])) : ?>
           <img src="<?= htmlspecialchars($p['image_path']) ?>" width="60" alt="Product Image">
         <?php else: ?>
           <span>No Image</span>
         <?php endif; ?>
       </td>
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