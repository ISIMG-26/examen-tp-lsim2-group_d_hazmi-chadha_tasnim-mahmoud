<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'redirect' => true, 'message' => 'Veuillez vous connecter']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT id FROM panier WHERE user_id = ? AND produit_id = ?");
$stmt->execute([$user_id, $product_id]);

if ($stmt->fetch()) {
    $update = $pdo->prepare("UPDATE panier SET quantite = quantite + 1 WHERE user_id = ? AND produit_id = ?");
    $update->execute([$user_id, $product_id]);
} else {
    $insert = $pdo->prepare("INSERT INTO panier (user_id, produit_id) VALUES (?, ?)");
    $insert->execute([$user_id, $product_id]);
}

$countStmt = $pdo->prepare("SELECT SUM(quantite) as total FROM panier WHERE user_id = ?");
$countStmt->execute([$user_id]);
$cartCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

echo json_encode(['success' => true, 'cart_count' => $cartCount]);
?>