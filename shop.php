<?php
require_once 'config.php';

$page_title = "Notre Boutique - GreenShop";
include 'header.php';
?>

<main>
    <section class="shop-header">
        <h1>🌿 Notre Collection de Plantes</h1>
        <p>Découvrez nos plantes d'intérieur soigneusement sélectionnées</p>
    </section>

    <div class="products-grid" id="all-products">
        <p style="text-align:center;">Chargement des produits...</p>
    </div>
</main>

<style>
.shop-header {
    text-align: center;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    margin-bottom: 2rem;
}
.shop-header h1 {
    color: #2e7d32;
    font-size: 2.5rem;
}
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem 2rem;
}
.product-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}
.product-card:hover {
    transform: translateY(-5px);
}
.product-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
.product-info {
    padding: 1.5rem;
}
.product-info h3 {
    color: #2e7d32;
    margin-bottom: 0.5rem;
}
.price {
    font-size: 1.5rem;
    font-weight: bold;
    color: #388e3c;
    margin: 0.5rem 0;
}
.product-card .btn-primary {
    width: 100%;
    padding: 10px;
    background: #4caf50;
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAllProducts();
});

function loadAllProducts() {
    fetch('api_get_products.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('all-products');
            if (container && data.success) {
                container.innerHTML = '';
                data.products.forEach(product => {
                    container.appendChild(createProductCard(product));
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('all-products').innerHTML = '<p style="text-align:center;color:red;">Erreur de chargement</p>';
        });
}

function createProductCard(product) {
    const div = document.createElement('div');
    div.className = 'product-card';
    
    // Chemin de l'image
    const imageUrl = `uploads/${product.image}`;
    
    div.innerHTML = `
        <img src="${imageUrl}" 
             alt="${product.nom}"
             onerror="this.src='https://placehold.co/300x200/4caf50/white?text=🌿+Plante'">
        <div class="product-info">
            <h3>${escapeHtml(product.nom)}</h3>
            <p>${escapeHtml((product.description || '').substring(0, 80))}...</p>
            <div class="price">${parseFloat(product.prix).toFixed(2)} €</div>
            <button class="btn-primary add-to-cart" data-id="${product.id}">Ajouter au panier</button>
        </div>
    `;
    
    const button = div.querySelector('.add-to-cart');
    button.addEventListener('click', () => addToCart(product.id));
    
    return div;
}

function addToCart(productId) {
    fetch('api_add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('✓ Produit ajouté au panier!', 'success');
        } else if (data.redirect) {
            window.location.href = 'auth.php';
        } else {
            showNotification(data.message || 'Erreur', 'error');
        }
    });
}

function showNotification(msg, type) {
    const notif = document.createElement('div');
    notif.textContent = msg;
    notif.style.cssText = `
        position: fixed; bottom: 20px; right: 20px; padding: 12px 24px;
        background: ${type === 'success' ? '#4caf50' : '#f44336'};
        color: white; border-radius: 8px; z-index: 1000;
    `;
    document.body.appendChild(notif);
    setTimeout(() => notif.remove(), 3000);
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        return m === '&' ? '&amp;' : m === '<' ? '&lt;' : '&gt;';
    });
}
</script>

<?php include 'footer.php'; ?>