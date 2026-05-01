<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}

$page_title = "Mon Compte - GreenShop";
include 'header.php';
?>

<main class="dashboard-main">
    <div class="dashboard-container">
        <div class="welcome-section">
            <h1>🌿 Bonjour, <?php echo htmlspecialchars($_SESSION['user_nom']); ?> !</h1>
            <p>Bienvenue sur votre espace personnel GreenShop</p>
        </div>

        <div class="dashboard-grid">
            <a href="commandes.php" class="dashboard-card">
                <div class="card-icon">📦</div>
                <h3>Mes commandes</h3>
                <p>Consultez l'historique de vos commandes</p>
                <span class="card-link">Voir mes commandes →</span>
            </a>
            
            <a href="profil.php" class="dashboard-card">
                <div class="card-icon">👤</div>
                <h3>Mon profil</h3>
                <p>Modifiez vos informations personnelles</p>
                <span class="card-link">Modifier mon profil →</span>
            </a>
            
            <a href="panier.php" class="dashboard-card">
                <div class="card-icon">🛒</div>
                <h3>Mon panier</h3>
                <p>
                    <?php 
                        $stmt = $pdo->prepare("SELECT SUM(quantite) as total FROM panier WHERE user_id = ?");
                        $stmt->execute([$_SESSION['user_id']]);
                        $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
                        echo "Vous avez $count article(s) dans votre panier";
                    ?>
                </p>
                <span class="card-link">Voir mon panier →</span>
            </a>
            
            <a href="logout.php" class="dashboard-card logout-card">
                <div class="card-icon">🚪</div>
                <h3>Déconnexion</h3>
                <p>Vous déconnecter de votre compte</p>
                <span class="card-link">Se déconnecter →</span>
            </a>
        </div>
    </div>
</main>

<style>
.dashboard-main {
    min-height: calc(100vh - 300px);
    background: #f5f7f0;
    padding: 2rem 1rem;
}

.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
}

.welcome-section {
    text-align: center;
    margin-bottom: 3rem;
}

.welcome-section h1 {
    color: #2e7d32;
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.welcome-section p {
    color: #666;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.dashboard-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    text-align: center;
    display: block;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.card-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.dashboard-card h3 {
    color: #2e7d32;
    margin-bottom: 0.5rem;
    font-size: 1.25rem;
}

.dashboard-card p {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.card-link {
    color: #4caf50;
    font-weight: 500;
    font-size: 0.9rem;
}

.logout-card:hover {
    background: #ffebee;
}

.logout-card .card-icon,
.logout-card h3 {
    color: #c62828;
}
</style>

<?php include 'footer.php'; ?>