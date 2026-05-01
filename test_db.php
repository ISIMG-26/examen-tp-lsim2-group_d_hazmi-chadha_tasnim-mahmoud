<?php
require_once 'config.php';

echo "<h2>Database Test</h2>";

// Test products table
$stmt = $pdo->query("SELECT COUNT(*) as count FROM produits");
$products = $stmt->fetch();
echo "✅ Products table: " . $products['count'] . " products found<br>";

// Test users table
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
$users = $stmt->fetch();
echo "✅ Users table: " . $users['count'] . " users found<br>";

// Test panier table
$stmt = $pdo->query("SELECT COUNT(*) as count FROM panier");
$cart = $stmt->fetch();
echo "✅ Cart table: " . $cart['count'] . " items found<br>";

echo "<br>🎉 Database is working perfectly!";
?>