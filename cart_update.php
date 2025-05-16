<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST["item_id"] ?? null;

    if (isset($_POST["delete"])) {
        $stmt = $db->prepare("DELETE FROM shopping_cart_items WHERE id = ?");
        $stmt->execute([$item_id]);
        echo "silindi";
        exit;
    }

    $quantity = $_POST["quantity"] ?? 1;
    $stmt = $db->prepare("UPDATE shopping_cart_items SET quantity = ? WHERE id = ?");
    $stmt->execute([$quantity, $item_id]);
    echo "güncellendi";
}
