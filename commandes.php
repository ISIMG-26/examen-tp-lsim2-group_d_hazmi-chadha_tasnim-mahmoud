<?php
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}

$page_title = "Mes Commandes - GreenShop";
include 'header.php';
?>

<main class="orders-main">
    <div class="orders-container">
        <div class="page-header">
            <h1>📦 Mes Commandes</h1>
            <p>Retrouvez l'historique de toutes vos commandes</p>
        </div>

        <?php
        try {
            // Check if commandes table exists
            $check = $pdo->query("SHOW TABLES LIKE 'commandes'");
            
            if ($check->rowCount() > 0) {
                // Get user's orders
                $stmt = $pdo->prepare("SELECT * FROM commandes WHERE user_id = ? ORDER BY date_commande DESC");
                $stmt->execute([$_SESSION['user_id']]);
                $orders = $stmt->fetchAll();
                
                if (count($orders) > 0) {
                    // Display orders
                    foreach($orders as $order):
                    ?>
                        <div class="order-card">
                            <div class="order-header">
                                <span>Commande #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></span>
                                <span>Date: <?php echo date('d/m/Y', strtotime($order['date_commande'])); ?></span>
                                <span>Total: <?php echo number_format($order['total'], 2); ?> €</span>
                            </div>
                        </div>
                    <?php
                    endforeach;
                } else {
                    echo '<div class="empty-state">';
                    echo '<h3>Aucune commande pour le moment</h3>';
                    echo '<p>Vous n\'avez pas encore passé de commande.</p>';
                    echo '<a href="shop.php" class="btn-primary">Commencer mes achats</a>';
                    echo '</div>';
                }
            } else {
                echo '<div class="empty-state">';
                echo '<h3>Pas encore de commandes</h3>';
                echo '<p>Commencez à acheter nos plantes!</p>';
                echo '<a href="shop.php" class="btn-primary">Voir la boutique</a>';
                echo '</div>';
            }
        } catch(PDOException $e) {
            echo '<div class="empty-state">';
            echo '<h3>Bienvenue sur GreenShop!</h3>';
            echo '<p>Commencez à magasiner pour voir vos commandes ici.</p>';
            echo '<a href="shop.php" class="btn-primary">Découvrir nos plantes</a>';
            echo '</div>';
        }
        ?>
    </div>
</main>

<style>
.orders-main {
    min-height: calc(100vh - 300px);
    background: #f5f7f0;
    padding: 2rem 1rem;
}
.orders-container {
    max-width: 1000px;
    margin: 0 auto;
}
.page-header {
    text-align: center;
    margin-bottom: 2rem;
}
.page-header h1 {
    color: #2e7d32;
    font-size: 2rem;
}
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.empty-state h3 {
    color: #333;
    margin-bottom: 1rem;
}
.empty-state p {
    color: #666;
    margin-bottom: 1.5rem;
}
.btn-primary {
    display: inline-block;
    background: #4caf50;
    color: white;
    padding: 12px 30px;
    border-radius: 30px;
    text-decoration: none;
}
.order-card {
    background: white;
    border-radius: 15px;
    padding: 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.order-header {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem;
    border-bottom: 1px solid #eee;
}
</style>

<?php include 'footer.php'; ?>