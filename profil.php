<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}

$success = '';
$error = '';

// Get user information
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Update profile - FIXED
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    
    if (empty($nom) || empty($email)) {
        $error = "Tous les champs sont requis";
    } else {
        // Check if email already exists for another user
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check->execute([$email, $_SESSION['user_id']]);
        
        if ($check->fetch()) {
            $error = "Cet email est déjà utilisé par un autre compte";
        } else {
            $update = $pdo->prepare("UPDATE users SET nom = ?, email = ? WHERE id = ?");
            if ($update->execute([$nom, $email, $_SESSION['user_id']])) {
                $_SESSION['user_nom'] = $nom;
                $success = "Profil mis à jour avec succès !";
                // Refresh user data
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch();
            } else {
                $error = "Erreur lors de la mise à jour";
            }
        }
    }
}

// Update password - FIXED
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_password') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "Tous les champs sont requis";
    } elseif ($new_password !== $confirm_password) {
        $error = "Les nouveaux mots de passe ne correspondent pas";
    } elseif (strlen($new_password) < 6) {
        $error = "Le nouveau mot de passe doit contenir au moins 6 caractères";
    } else {
        // Verify current password
        if (!password_verify($current_password, $user['password'])) {
            $error = "Mot de passe actuel incorrect";
        } else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($update->execute([$hashed, $_SESSION['user_id']])) {
                $success = "Mot de passe modifié avec succès !";
            } else {
                $error = "Erreur lors de la modification du mot de passe";
            }
        }
    }
}

$page_title = "Mon Profil - GreenShop";
include 'header.php';
?>

<main class="profile-main">
    <div class="profile-container">
        <div class="profile-header">
            <h1>👤 Mon Profil</h1>
            <p>Gérez vos informations personnelles</p>
        </div>

        <?php if ($success): ?>
            <div class="alert-success">
                ✅ <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert-error">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="profile-cards">
            <!-- Card 1: Informations personnelles -->
            <div class="card">
                <div class="card-title">
                    <span>📋</span>
                    <h2>Informations personnelles</h2>
                </div>
                
                <form method="POST" class="form">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="input-group">
                        <label>Nom complet</label>
                        <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                    </div>
                    
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="input-group">
                        <label>Membre depuis</label>
                        <input type="text" value="<?php echo date('d/m/Y', strtotime($user['date_inscription'])); ?>" disabled>
                    </div>
                    
                    <button type="submit" class="btn-green">
                        💾 Enregistrer
                    </button>
                </form>
            </div>

            <!-- Card 2: Changer mot de passe -->
            <div class="card">
                <div class="card-title">
                    <span>🔒</span>
                    <h2>Changer mot de passe</h2>
                </div>
                
                <form method="POST" class="form">
                    <input type="hidden" name="action" value="update_password">
                    
                    <div class="input-group">
                        <label>Mot de passe actuel</label>
                        <input type="password" name="current_password" required>
                    </div>
                    
                    <div class="input-group">
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="new_password" id="new_password" required>
                        <small>Minimum 6 caractères</small>
                    </div>
                    
                    <div class="input-group">
                        <label>Confirmer nouveau mot de passe</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn-blue">
                        🔄 Changer mot de passe
                    </button>
                </form>
            </div>

            <!-- Card 3: Actions rapides -->
            <div class="card">
                <div class="card-title">
                    <span>⚡</span>
                    <h2>Actions rapides</h2>
                </div>
                
                <div class="actions">
                    <a href="commandes.php" class="action-link">📦 Mes commandes</a>
                    <a href="panier.php" class="action-link">🛒 Mon panier</a>
                    <a href="shop.php" class="action-link">🛍️ Continuer mes achats</a>
                    <a href="logout.php" class="action-link logout">🚪 Se déconnecter</a>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* Main container */
.profile-main {
    min-height: calc(100vh - 300px);
    background: #f5f7f0;
    padding: 2rem 1rem;
}

.profile-container {
    max-width: 1000px;
    margin: 0 auto;
}

/* Header */
.profile-header {
    text-align: center;
    margin-bottom: 2rem;
}

.profile-header h1 {
    color: #2e7d32;
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.profile-header p {
    color: #666;
}

/* Alerts */
.alert-success {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #2e7d32;
}

.alert-error {
    background: #ffebee;
    color: #c62828;
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #c62828;
}

/* Cards grid */
.profile-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

/* Card */
.card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card-title {
    background: #2e7d32;
    color: white;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-title span {
    font-size: 1.5rem;
}

.card-title h2 {
    font-size: 1.2rem;
    margin: 0;
}

/* Form */
.form {
    padding: 1.5rem;
}

.input-group {
    margin-bottom: 1rem;
}

.input-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.input-group input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
}

.input-group input:focus {
    outline: none;
    border-color: #4caf50;
}

.input-group input:disabled {
    background: #f5f5f5;
    color: #888;
}

.input-group small {
    display: block;
    margin-top: 0.25rem;
    color: #888;
    font-size: 0.75rem;
}

/* Buttons */
.btn-green {
    width: 100%;
    padding: 10px;
    background: #4caf50;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 0.5rem;
}

.btn-green:hover {
    background: #2e7d32;
}

.btn-blue {
    width: 100%;
    padding: 10px;
    background: #2196f3;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 0.5rem;
}

.btn-blue:hover {
    background: #1976d2;
}

/* Actions */
.actions {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.action-link {
    display: block;
    padding: 12px;
    background: #f5f5f5;
    color: #333;
    text-decoration: none;
    border-radius: 8px;
    text-align: center;
    transition: all 0.3s;
}

.action-link:hover {
    background: #e8f5e9;
    color: #2e7d32;
}

.action-link.logout:hover {
    background: #ffebee;
    color: #c62828;
}
</style>

<script>
// Simple password match validation
document.querySelector('form[action*="update_password"]')?.addEventListener('submit', function(e) {
    const newPw = document.getElementById('new_password');
    const confirmPw = document.getElementById('confirm_password');
    
    if (newPw.value !== confirmPw.value) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas !');
        confirmPw.style.borderColor = 'red';
    }
});
</script>

<?php include 'footer.php'; ?>