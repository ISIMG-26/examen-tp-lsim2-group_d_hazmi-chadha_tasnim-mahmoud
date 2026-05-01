<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'GreenShop'; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional inline styles to ensure menu works */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        header {
            background: #2e7d32;
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .logo a {
            color: white;
            text-decoration: none;
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: #c8e6c9;
        }
        
        .cart-count {
            background: #ff9800;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 0.8rem;
            margin-left: 5px;
        }
        
        main {
            min-height: calc(100vh - 200px);
        }
    </style>
</head>
<body>
<header>
    <nav>
        <div class="logo"><a href="index.php">🌿 GreenShop</a></div>
        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="shop.php">Boutique</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="dashboard.php">Mon compte</a></li>
                <li><a href="panier.php">🛒 Panier<span class="cart-count"></span></a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="auth.php">Connexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>