<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}

$page_title = "Validation de commande - GreenShop";
include 'header.php';

// Récupérer les articles du panier
$stmt = $pdo->prepare("
    SELECT p.id, p.nom, p.prix, p.image, c.quantite 
    FROM panier c 
    JOIN produits p ON c.produit_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$cartItems = $stmt->fetchAll();

if (count($cartItems) == 0) {
    header('Location: shop.php');
    exit;
}

// Calculer le total
$total = 0;
foreach($cartItems as $item) {
    $total += $item['prix'] * $item['quantite'];
}
$livraison = $total >= 50 ? 0 : 5.99;
$total_final = $total + $livraison;

$success = false;
$error = false;

// Traitement de la commande
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adresse = trim($_POST['adresse']);
    $ville = trim($_POST['ville']);
    $code_postal = trim($_POST['code_postal']);
    $telephone = trim($_POST['telephone']);
    
    $adresse_complete = "$adresse, $code_postal $ville";
    
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("
            INSERT INTO commandes (user_id, total, adresse_livraison, telephone, statut) 
            VALUES (?, ?, ?, ?, 'en_attente')
        ");
        $stmt->execute([$_SESSION['user_id'], $total_final, $adresse_complete, $telephone]);
        $order_id = $pdo->lastInsertId();
        
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantite, prix) VALUES (?, ?, ?, ?)");
        foreach($cartItems as $item) {
            $stmt->execute([$order_id, $item['id'], $item['quantite'], $item['prix']]);
        }
        
        $stmt = $pdo->prepare("DELETE FROM panier WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        
        $pdo->commit();
        $success = true;
        
    } catch(Exception $e) {
        $pdo->rollBack();
        $error = "Erreur lors de la commande : " . $e->getMessage();
    }
}
?>

<main class="checkout-main">
    <div class="checkout-container">
        <?php if ($success): ?>
            <!-- Message de succès -->
            <div class="success-container">
                <div class="success-icon">✅</div>
                <h1>Commande validée !</h1>
                <p>Merci pour votre commande. Vous recevrez un email de confirmation.</p>
                <div class="success-actions">
                    <a href="commandes.php" class="btn-primary">📦 Voir mes commandes</a>
                    <a href="shop.php" class="btn-secondary">🛍️ Continuer mes achats</a>
                </div>
            </div>
        <?php else: ?>
            <div class="checkout-header">
                <h1>📋 Validation de commande</h1>
                <p>Finalisez votre commande en remplissant vos coordonnées</p>
            </div>

            <?php if ($error): ?>
                <div class="alert-error">❌ <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="checkout-grid">
                <div class="checkout-form">
                    <div class="form-card">
                        <h2>📍 Adresse de livraison</h2>
                        <form method="POST">
                            <div class="form-group">
                                <label>Adresse rue *</label>
                                <input type="text" name="adresse" required placeholder="123 rue des Plantes">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Ville *</label>
                                    <input type="text" name="ville" required placeholder="Paris">
                                </div>
                                <div class="form-group">
                                    <label>Code postal *</label>
                                    <input type="text" name="code_postal" required placeholder="75001">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Téléphone *</label>
                                <input type="tel" name="telephone" required placeholder="06 12 34 56 78">
                            </div>
                            <button type="submit" class="btn-confirm">✅ Confirmer ma commande</button>
                        </form>
                    </div>
                </div>
                
                <div class="checkout-summary">
                    <div class="summary-card">
                        <h2>🛒 Récapitulatif</h2>
                        <?php foreach($cartItems as $item): ?>
                            <div class="summary-item">
                                <span><?php echo htmlspecialchars($item['nom']); ?> x<?php echo $item['quantite']; ?></span>
                                <span><?php echo number_format($item['prix'] * $item['quantite'], 2); ?> €</span>
                            </div>
                        <?php endforeach; ?>
                        <div class="summary-total">
                            <strong>Total</strong>
                            <strong><?php echo number_format($total_final, 2); ?> €</strong>
                        </div>
                        <?php if($total >= 50): ?>
                            <p class="free-shipping">🎉 Livraison gratuite !</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.checkout-main {
    min-height: calc(100vh - 300px);
    background: #f5f7f0;
    padding: 2rem 1rem;
}
.checkout-container {
    max-width: 1000px;
    margin: 0 auto;
}
.checkout-header {
    text-align: center;
    margin-bottom: 2rem;
}
.checkout-header h1 {
    color: #2e7d32;
    font-size: 2rem;
}
.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2rem;
}
@media (max-width: 800px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }
}
.form-card, .summary-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.form-card h2, .summary-card h2 {
    color: #2e7d32;
    margin-bottom: 1rem;
}
.form-group {
    margin-bottom: 1rem;
}
.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}
.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.btn-confirm {
    width: 100%;
    padding: 14px;
    background: #4caf50;
    color: white;
    border: none;
    border-radius: 40px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 1rem;
}
.btn-confirm:hover {
    background: #2e7d32;
}
.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
}
.summary-total {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 2px solid #2e7d32;
}
.free-shipping {
    margin-top: 1rem;
    padding: 0.5rem;
    background: #e8f5e9;
    border-radius: 8px;
    text-align: center;
    color: #2e7d32;
}
.success-container {
    background: white;
    border-radius: 25px;
    padding: 3rem;
    text-align: center;
}
.success-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}
.success-container h1 {
    color: #2e7d32;
    margin-bottom: 0.5rem;
}
.success-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}
.btn-primary, .btn-secondary {
    padding: 12px 24px;
    border-radius: 30px;
    text-decoration: none;
}
.btn-primary {
    background: #4caf50;
    color: white;
}
.btn-secondary {
    background: #f5f5f5;
    color: #666;
}
.btn-secondary:hover {
    background: #e8f5e9;
}
.alert-error {
    background: #ffebee;
    color: #c62828;
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 1rem;
}
</style>

<?php include 'footer.php'; ?>