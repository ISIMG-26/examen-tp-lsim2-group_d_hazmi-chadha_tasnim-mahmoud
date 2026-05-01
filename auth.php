<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

// Traitement inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    if ($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        
        if ($check->fetch()) {
            $error = "Cet email est déjà utilisé";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (nom, email, password) VALUES (?, ?, ?)");
            if ($insert->execute([$nom, $email, $hashed])) {
                $success = "Inscription réussie ! Vous pouvez vous connecter.";
            } else {
                $error = "Erreur lors de l'inscription";
            }
        }
    }
}

// Traitement connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}

$page_title = "Connexion / Inscription";
include 'header.php';
?>

<main class="auth-main">
    <div class="auth-container">
        <?php if ($error): ?>
            <div class="alert alert-error">
                <span class="alert-icon">⚠️</span>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✅</span>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <div class="auth-tabs">
            <button class="tab-btn active" onclick="showTab('login')">🔐 Connexion</button>
            <button class="tab-btn" onclick="showTab('register')">📝 Inscription</button>
        </div>
        
        <!-- Formulaire Connexion -->
        <div id="login-form-container" class="form-container">
            <form method="POST" id="login-form">
                <h2>Bienvenue retour !</h2>
                <p class="form-subtitle">Connectez-vous pour accéder à votre compte</p>
                
                <div class="form-group">
                    <label>📧 Email</label>
                    <input type="email" name="email" id="login-email" required placeholder="exemple@email.com">
                    <div class="error-message"></div>
                </div>
                
                <div class="form-group">
                    <label>🔒 Mot de passe</label>
                    <input type="password" name="password" id="login-password" required placeholder="••••••">
                    <div class="error-message"></div>
                </div>
                
                <button type="submit" name="login" class="btn-submit">Se connecter</button>
            </form>
        </div>
        
        <!-- Formulaire Inscription -->
        <div id="register-form-container" class="form-container" style="display:none;">
            <form method="POST" id="register-form">
                <h2>Créer un compte</h2>
                <p class="form-subtitle">Rejoignez GreenShop et profitez de nos offres</p>
                
                <div class="form-group">
                    <label>👤 Nom complet</label>
                    <input type="text" name="nom" id="nom" required placeholder="Jean Dupont">
                    <div class="error-message"></div>
                </div>
                
                <div class="form-group">
                    <label>📧 Email</label>
                    <input type="email" name="email" id="reg-email" required placeholder="exemple@email.com">
                    <div class="error-message"></div>
                </div>
                
                <div class="form-row">
                    <div class="form-group half">
                        <label>🔒 Mot de passe</label>
                        <input type="password" name="password" id="reg-password" required placeholder="6 caractères min">
                        <div class="error-message"></div>
                    </div>
                    
                    <div class="form-group half">
                        <label>✓ Confirmer</label>
                        <input type="password" name="confirm_password" id="confirm_password" required placeholder="••••••">
                        <div class="error-message"></div>
                    </div>
                </div>
                
                <button type="submit" name="register" class="btn-submit">S'inscrire</button>
                
                <p class="form-footer">
                    En vous inscrivant, vous acceptez nos 
                    <a href="#">conditions d'utilisation</a>
                </p>
            </form>
        </div>
    </div>
</main>

<style>
/* Main styles */
.auth-main {
    min-height: calc(100vh - 300px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    background: linear-gradient(135deg, #f5f7f0 0%, #e8f5e9 100%);
}

.auth-container {
    max-width: 500px;
    width: 100%;
    background: white;
    border-radius: 25px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    padding: 2rem;
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

/* Alert messages */
.alert {
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.alert-error {
    background: #ffebee;
    color: #c62828;
    border-left: 4px solid #c62828;
}

.alert-success {
    background: #e8f5e9;
    color: #2e7d32;
    border-left: 4px solid #2e7d32;
}

.alert-icon {
    font-size: 1.25rem;
}

/* Tabs */
.auth-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    background: #f5f5f5;
    padding: 0.5rem;
    border-radius: 50px;
}

.tab-btn {
    flex: 1;
    padding: 12px 20px;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    background: transparent;
    cursor: pointer;
    border-radius: 40px;
    transition: all 0.3s ease;
    color: #666;
}

.tab-btn.active {
    background: #2e7d32;
    color: white;
    box-shadow: 0 4px 10px rgba(46,125,50,0.3);
}

.tab-btn:hover:not(.active) {
    background: #e0e0e0;
}

/* Form styles */
.form-container h2 {
    color: #2e7d32;
    margin-bottom: 0.5rem;
    font-size: 1.75rem;
}

.form-subtitle {
    color: #888;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.form-group input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-group input:focus {
    outline: none;
    border-color: #4caf50;
    box-shadow: 0 0 0 3px rgba(76,175,80,0.1);
}

.form-group input.error {
    border-color: #c62828;
}

.error-message {
    color: #c62828;
    font-size: 0.8rem;
    margin-top: 0.25rem;
    display: none;
}

.error-message.visible {
    display: block;
}

/* Two columns for register */
.form-row {
    display: flex;
    gap: 1rem;
}

.form-group.half {
    flex: 1;
}

/* Submit button */
.btn-submit {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
    color: white;
    border: none;
    border-radius: 40px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 0.5rem;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(46,125,50,0.3);
}

.btn-submit:active {
    transform: translateY(0);
}

/* Form footer */
.form-footer {
    text-align: center;
    margin-top: 1.5rem;
    color: #888;
    font-size: 0.8rem;
}

.form-footer a {
    color: #4caf50;
    text-decoration: none;
}

.form-footer a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 550px) {
    .auth-container {
        padding: 1.5rem;
    }
    
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    
    .auth-tabs {
        flex-direction: column;
        border-radius: 15px;
    }
    
    .tab-btn {
        border-radius: 10px;
    }
}
</style>

<script>
function showTab(tab) {
    const loginContainer = document.getElementById('login-form-container');
    const registerContainer = document.getElementById('register-form-container');
    const tabs = document.querySelectorAll('.tab-btn');
    
    if (tab === 'login') {
        loginContainer.style.display = 'block';
        registerContainer.style.display = 'none';
        tabs[0].classList.add('active');
        tabs[1].classList.remove('active');
    } else {
        loginContainer.style.display = 'none';
        registerContainer.style.display = 'block';
        tabs[0].classList.remove('active');
        tabs[1].classList.add('active');
    }
}

// Validation formulaire inscription
document.getElementById('register-form')?.addEventListener('submit', function(e) {
    let isValid = true;
    
    const nom = document.getElementById('nom');
    const email = document.getElementById('reg-email');
    const password = document.getElementById('reg-password');
    const confirm = document.getElementById('confirm_password');
    
    // Validation nom
    if (!nom.value.trim()) {
        showError(nom, 'Le nom est requis');
        isValid = false;
    } else {
        clearError(nom);
    }
    
    // Validation email
    const emailRegex = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
    if (!email.value.trim()) {
        showError(email, 'L\'email est requis');
        isValid = false;
    } else if (!emailRegex.test(email.value)) {
        showError(email, 'Email invalide');
        isValid = false;
    } else {
        clearError(email);
    }
    
    // Validation mot de passe
    if (!password.value) {
        showError(password, 'Mot de passe requis');
        isValid = false;
    } else if (password.value.length < 6) {
        showError(password, '6 caractères minimum');
        isValid = false;
    } else {
        clearError(password);
    }
    
    // Validation confirmation
    if (password.value !== confirm.value) {
        showError(confirm, 'Les mots de passe ne correspondent pas');
        isValid = false;
    } else {
        clearError(confirm);
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});

// Validation formulaire connexion
document.getElementById('login-form')?.addEventListener('submit', function(e) {
    let isValid = true;
    
    const email = document.getElementById('login-email');
    const password = document.getElementById('login-password');
    
    if (!email.value.trim()) {
        showError(email, 'Email requis');
        isValid = false;
    } else {
        clearError(email);
    }
    
    if (!password.value) {
        showError(password, 'Mot de passe requis');
        isValid = false;
    } else {
        clearError(password);
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});

function showError(input, message) {
    const formGroup = input.closest('.form-group');
    let errorDiv = formGroup.querySelector('.error-message');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        formGroup.appendChild(errorDiv);
    }
    errorDiv.textContent = message;
    errorDiv.classList.add('visible');
    input.classList.add('error');
}

function clearError(input) {
    const formGroup = input.closest('.form-group');
    const errorDiv = formGroup.querySelector('.error-message');
    if (errorDiv) {
        errorDiv.classList.remove('visible');
    }
    input.classList.remove('error');
}
</script>

<?php include 'footer.php'; ?>