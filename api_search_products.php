<?php
require_once 'config.php';
header('Content-Type: application/json');

$search = isset($_GET['q']) ? $_GET['q'] : '';

if (strlen($search) < 2) {
    echo json_encode(['success' => true, 'products' => []]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, nom, prix FROM produits WHERE nom LIKE ? AND stock > 0 LIMIT 10");
$stmt->execute(["%$search%"]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'products' => $products]);
?>