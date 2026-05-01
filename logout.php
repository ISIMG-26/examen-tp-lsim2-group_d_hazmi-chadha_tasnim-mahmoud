<?php
session_start();

// If user confirmed logout
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    session_destroy();
    header('Location: index.php?logged_out=1');
    exit;
}

// If user cancelled
if (isset($_GET['confirm']) && $_GET['confirm'] === 'no') {
    header('Location: index.php');
    exit;
}

// Show confirmation page
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déconnexion - GreenShop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7f0 0%, #e8f5e9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logout-container {
            background: white;
            border-radius: 25px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: 1rem;
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
        
        .logout-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
        }
        
        h2 {
            color: #2e7d32;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }
        
        p {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .button-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .btn-yes {
            background: #4caf50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 40px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-yes:hover {
            background: #2e7d32;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46,125,50,0.3);
        }
        
        .btn-no {
            background: #f5f5f5;
            color: #666;
            padding: 12px 30px;
            border: none;
            border-radius: 40px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-no:hover {
            background: #ffebee;
            color: #c62828;
        }
        
        .countdown {
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logout-icon">🚪</div>
        <h2>Déconnexion</h2>
        <p>Êtes-vous sûr de vouloir vous déconnecter de votre compte ?</p>
        
        <div class="button-group">
            <a href="logout.php?confirm=yes" class="btn-yes">✅ Oui, me déconnecter</a>
            <a href="logout.php?confirm=no" class="btn-no">❌ Non, rester connecté</a>
        </div>
        
        <div class="countdown">
            <span id="timer">5</span> secondes avant déconnexion automatique...
        </div>
    </div>
    
    <script>
        // Auto logout after 10 seconds if no choice
        let seconds = 10;
        const timerElement = document.getElementById('timer');
        
        const interval = setInterval(() => {
            seconds--;
            timerElement.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = 'logout.php?confirm=yes';
            }
        }, 1000);
        
        // Clear timer if user clicks any button
        document.querySelectorAll('.btn-yes, .btn-no').forEach(btn => {
            btn.addEventListener('click', () => {
                clearInterval(interval);
            });
        });
    </script>
</body>
</html>