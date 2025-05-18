<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']['id']) || $_SESSION['user']['type'] !== 'consumer') {
  http_response_code(403);
  echo json_encode(["error" => "Yetkisiz"]);
  exit;
}

$stmt = $db->prepare("
  SELECT COALESCE(SUM(sci.quantity), 0) AS total
  FROM shopping_cart_items sci
  JOIN shopping_cart sc ON sc.id = sci.cart_id
  WHERE sc.consumer_id = ?
");
$stmt->execute([$_SESSION['user']['id']]);
$total = $stmt->fetchColumn();

echo json_encode(["total" => $total]);
