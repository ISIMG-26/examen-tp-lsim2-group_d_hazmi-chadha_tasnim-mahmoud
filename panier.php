<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}

$page_title = "Mon Panier - GreenShop";
include 'header.php';
?>

<main class="cart-main">
    <div class="cart-container">
        <div class="cart-header">
            <h1>🛒 Mon Panier</h1>
            <p>Vos plantes préférées vous attendent</p>
        </div>

        <?php
        // Get cart items
        $stmt = $pdo->prepare("
            SELECT p.id, p.nom, p.prix, p.image, c.quantite, p.stock
            FROM panier c 
            JOIN produits p ON c.produit_id = p.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $cartItems = $stmt->fetchAll();
        
        if (count($cartItems) == 0):
        ?>
            <!-- Empty Cart -->
            <div class="empty-cart">
                <div class="empty-cart-icon">🌱</div>
                <h2>Votre panier est vide</h2>
                <p>Découvrez nos magnifiques plantes et ajoutez-les à votre panier</p>
                <a href="shop.php" class="btn-shop">🛍️ Découvrir nos plantes</a>
            </div>
        <?php else: ?>
            <!-- Cart with items -->
            <div class="cart-content">
                <div class="cart-items">
                    <div class="cart-items-header">
                        <div>Produit</div>
                        <div>Prix</div>
                        <div>Quantité</div>
                        <div>Total</div>
                        <div></div>
                    </div>
                    
                    <?php 
                    $total = 0;
                    foreach($cartItems as $item): 
                        $subtotal = $item['prix'] * $item['quantite'];
                        $total += $subtotal;
                    ?>
                        <div class="cart-item" data-id="<?php echo $item['id']; ?>">
                            <div class="cart-item-product">
                                <div class="cart-item-image">
                                    <img src="uploads/<?php echo $item['image']; ?>" alt="<?php echo $item['nom']; ?>" onerror="this.src='https://placehold.co/80x80/4caf50/white?text=🌿'">
                                </div>
                                <div class="cart-item-info">
                                    <h3><?php echo htmlspecialchars($item['nom']); ?></h3>
                                    <p>Plante d'intérieur</p>
                                </div>
                            </div>
                            <div class="cart-item-price"><?php echo number_format($item['prix'], 2); ?> €</div>
                            <div class="cart-item-quantity">
                                <button class="qty-btn qty-minus" data-id="<?php echo $item['id']; ?>">-</button>
                                <input type="number" class="qty-input" value="<?php echo $item['quantite']; ?>" min="1" max="<?php echo $item['stock']; ?>" data-id="<?php echo $item['id']; ?>">
                                <button class="qty-btn qty-plus" data-id="<?php echo $item['id']; ?>">+</button>
                            </div>
                            <div class="cart-item-total"><?php echo number_format($subtotal, 2); ?> €</div>
                            <div class="cart-item-remove">
                                <button class="remove-btn" data-id="<?php echo $item['id']; ?>" title="Supprimer">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h3>Récapitulatif</h3>
                    <div class="summary-row">
                        <span>Sous-total</span>
                        <span><?php echo number_format($total, 2); ?> €</span>
                    </div>
                    <div class="summary-row">
                        <span>Livraison</span>
                        <span><?php echo $total >= 50 ? 'Gratuite' : '5.99 €'; ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Total TTC</span>
                        <span class="summary-total"><?php echo number_format($total >= 50 ? $total : $total + 5.99, 2); ?> €</span>
                    </div>
                    <div class="free-shipping">
                        <?php if($total < 50): ?>
                            <div class="shipping-progress">
                                <div class="shipping-bar" style="width: <?php echo min(100, ($total / 50) * 100); ?>%"></div>
                            </div>
                            <p>Plus que <?php echo number_format(50 - $total, 2); ?> € pour la livraison gratuite !</p>
                        <?php else: ?>
                            <p>🎉 Livraison gratuite offerte !</p>
                        <?php endif; ?>
                    </div>
                    <a href="checkout.php" class="btn-checkout">✔️ Valider ma commande</a>
                    <a href="shop.php" class="btn-continue">🔄 Continuer mes achats</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
/* Main Container */
.cart-main {
    min-height: calc(100vh - 300px);
    background: linear-gradient(135deg, #f5f7f0 0%, #e8f5e9 100%);
    padding: 2rem 1rem;
}

.cart-container {
    max-width: 1200px;
    margin: 0 auto;
}

/* Header */
.cart-header {
    text-align: center;
    margin-bottom: 2rem;
}

.cart-header h1 {
    color: #2e7d32;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.cart-header p {
    color: #666;
    font-size: 1rem;
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.empty-cart-icon {
    font-size: 5rem;
    margin-bottom: 1rem;
}

.empty-cart h2 {
    color: #333;
    margin-bottom: 0.5rem;
}

.empty-cart p {
    color: #666;
    margin-bottom: 2rem;
}

.btn-shop {
    display: inline-block;
    background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
    color: white;
    padding: 12px 30px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-shop:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(46,125,50,0.3);
}

/* Cart Content */
.cart-content {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2rem;
}

@media (max-width: 900px) {
    .cart-content {
        grid-template-columns: 1fr;
    }
}

/* Cart Items */
.cart-items {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.cart-items-header {
    display: grid;
    grid-template-columns: 3fr 1fr 1.5fr 1fr 0.5fr;
    background: #2e7d32;
    color: white;
    padding: 1rem;
    font-weight: 600;
}

@media (max-width: 768px) {
    .cart-items-header {
        display: none;
    }
}

/* Cart Item */
.cart-item {
    display: grid;
    grid-template-columns: 3fr 1fr 1.5fr 1fr 0.5fr;
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
    align-items: center;
    transition: background 0.3s ease;
}

.cart-item:hover {
    background: #f9f9f9;
}

@media (max-width: 768px) {
    .cart-item {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        text-align: center;
        position: relative;
        padding: 1.5rem;
    }
}

.cart-item-product {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.cart-item-image {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    overflow: hidden;
    background: #f5f5f5;
}

.cart-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cart-item-info h3 {
    color: #333;
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.cart-item-info p {
    color: #888;
    font-size: 0.85rem;
}

.cart-item-price {
    color: #2e7d32;
    font-weight: 600;
}

.cart-item-quantity {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.qty-btn {
    width: 32px;
    height: 32px;
    background: #f0f0f0;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.2rem;
    font-weight: bold;
    color: #333;
    transition: all 0.3s ease;
}

.qty-btn:hover {
    background: #4caf50;
    color: white;
}

.qty-input {
    width: 50px;
    height: 32px;
    text-align: center;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
}

.qty-input:focus {
    outline: none;
    border-color: #4caf50;
}

.cart-item-total {
    font-weight: bold;
    color: #2e7d32;
    font-size: 1.1rem;
}

.remove-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.2rem;
    opacity: 0.6;
    transition: all 0.3s ease;
}

.remove-btn:hover {
    opacity: 1;
    transform: scale(1.1);
}

/* Cart Summary */
.cart-summary {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    position: sticky;
    top: 20px;
    height: fit-content;
}

.cart-summary h3 {
    color: #2e7d32;
    margin-bottom: 1.5rem;
    font-size: 1.3rem;
    border-bottom: 2px solid #e8f5e9;
    padding-bottom: 0.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.summary-row:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
}

.summary-total {
    font-size: 1.3rem;
    font-weight: bold;
    color: #2e7d32;
}

.free-shipping {
    margin: 1rem 0;
    padding: 1rem;
    background: #e8f5e9;
    border-radius: 15px;
    text-align: center;
}

.shipping-progress {
    height: 8px;
    background: #e0e0e0;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.shipping-bar {
    height: 100%;
    background: linear-gradient(90deg, #4caf50, #2e7d32);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.free-shipping p {
    color: #2e7d32;
    font-size: 0.85rem;
    font-weight: 500;
}

.btn-checkout {
    display: block;
    width: 100%;
    text-align: center;
    padding: 14px;
    background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
    color: white;
    border: none;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 0.75rem;
    transition: all 0.3s ease;
}

.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(46,125,50,0.3);
}

.btn-continue {
    display: block;
    text-align: center;
    padding: 12px;
    background: #f5f5f5;
    color: #666;
    border: none;
    border-radius: 40px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-continue:hover {
    background: #e8f5e9;
    color: #2e7d32;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity update functions
    document.querySelectorAll('.qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const input = document.querySelector(`.qty-input[data-id="${id}"]`);
            let newQty = parseInt(input.value) + 1;
            const max = parseInt(input.max) || 99;
            if (newQty <= max) {
                updateQuantity(id, newQty);
            }
        });
    });
    
    document.querySelectorAll('.qty-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const input = document.querySelector(`.qty-input[data-id="${id}"]`);
            let newQty = parseInt(input.value) - 1;
            if (newQty >= 1) {
                updateQuantity(id, newQty);
            }
        });
    });
    
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            const id = this.dataset.id;
            let newQty = parseInt(this.value);
            const max = parseInt(this.max) || 99;
            if (isNaN(newQty) || newQty < 1) {
                newQty = 1;
            }
            if (newQty > max) {
                newQty = max;
            }
            updateQuantity(id, newQty);
        });
    });
    
    // Remove item
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Supprimer ce produit du panier ?')) {
                removeFromCart(id);
            }
        });
    });
});

function updateQuantity(productId, quantity) {
    fetch('api_update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            product_id: productId, 
            quantity: quantity 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function removeFromCart(productId) {
    fetch('api_remove_from_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>

<?php include 'footer.php'; ?>