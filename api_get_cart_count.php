<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['cart_count' => 0]);
    exit;
}

$stmt = $pdo->prepare("SELECT SUM(quantite) as total FROM panier WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['cart_count' => $result['total'] ?? 0]);
?>