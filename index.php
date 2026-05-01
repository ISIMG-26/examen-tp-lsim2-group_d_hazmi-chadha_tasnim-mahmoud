<?php
session_start(); // Make sure session is started
require_once 'config.php';

$page_title = "GreenShop - Plantes d'intérieur";
include 'header.php';
?>

<main>
    <section class="hero">
        <div class="hero-content">
            <h1>Bienvenue chez GreenShop</h1>
            <p>Des plantes d'intérieur pour purifier votre air et embellir votre quotidien</p>
            <a href="shop.php" class="btn-primary">Découvrir nos plantes</a>
        </div>
    </section>

    <section class="features">
        <h2>Pourquoi choisir GreenShop ?</h2>
        <div class="features-grid">
            <div class="feature">
                <h3>🚚 Livraison rapide</h3>
                <p>Réception sous 2-3 jours ouvrables</p>
            </div>
            <div class="feature">
                <h3>💪 Plantes résistantes</h3>
                <p>Sélectionnées pour leur robustesse</p>
            </div>
            <div class="feature">
                <h3>🌱 Emballage eco-friendly</h3>
                <p>Engagement écologique</p>
            </div>
        </div>
    </section>
</main>

<style>
.hero {
    background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1464457312035-3d7d0e0c058e');
    background-size: cover;
    background-position: center;
    height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
}
.hero-content h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
}
.features {
    max-width: 1200px;
    margin: 3rem auto;
    padding: 0 1rem;
    text-align: center;
}
.features h2 {
    color: #2e7d32;
    margin-bottom: 2rem;
}
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}
.feature {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.feature h3 {
    color: #2e7d32;
    margin-bottom: 1rem;
}
.btn-primary {
    display: inline-block;
    background: #4caf50;
    color: white;
    padding: 12px 30px;
    border-radius: 30px;
    text-decoration: none;
    transition: background 0.3s;
}
.btn-primary:hover {
    background: #2e7d32;
}
</style>

<?php include 'footer.php'; ?>