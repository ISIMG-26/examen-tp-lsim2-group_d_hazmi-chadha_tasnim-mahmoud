<?php
require_once 'config.php';
header('Content-Type: application/json');

$stmt = $pdo->query("SELECT id, nom, description, prix, image FROM produits WHERE stock > 0 ORDER BY id DESC LIMIT 8");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'products' => $products]);
?>