<footer>
    <div class="footer-content">
        <div class="footer-section">
            <h3>🌿 GreenShop</h3>
            <p>Votre boutique de plantes d'intérieur</p>
        </div>
        <div class="footer-section">
            <h4>Liens utiles</h4>
            <ul>
                <li><a href="shop.php">Boutique</a></li>
                <li><a href="auth.php">Connexion</a></li>
                <li><a href="panier.php">Panier</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Contact</h4>
            <p>📧 contact@greenshop.com</p>
            <p>📞 01 23 45 67 89</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 GreenShop - Tous droits réservés</p>
        <p>Livraison offerte dès 50€ d'achat</p>
    </div>
</footer>

<style>
footer {
    background: #2d3e2b;
    color: white;
    margin-top: 3rem;
}
.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}
.footer-section h3, .footer-section h4 {
    margin-bottom: 1rem;
}
.footer-section ul {
    list-style: none;
}
.footer-section ul li {
    margin-bottom: 0.5rem;
}
.footer-section a {
    color: #c8e6c9;
    text-decoration: none;
}
.footer-section a:hover {
    text-decoration: underline;
}
.footer-bottom {
    text-align: center;
    padding: 1rem;
    border-top: 1px solid #4caf50;
    font-size: 0.9rem;
}
</style>
<script src="script.js"></script>
</body>
</html>