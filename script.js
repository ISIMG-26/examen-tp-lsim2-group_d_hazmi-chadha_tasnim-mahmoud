// DOM manipulation et AJAX
document.addEventListener('DOMContentLoaded', function() {
    
    // ========= AFFICHAGE PRODUITS EN ACCUEIL (AJAX) =========
    if (document.getElementById('featured-products')) {
        fetchProducts();
    }
    
    // ========= VALIDATION FORMULAIRE INSCRIPTION =========
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            if (!validateRegisterForm()) {
                e.preventDefault();
            }
        });
    }
    
    // ========= VALIDATION FORMULAIRE CONNEXION =========
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (!validateLoginForm()) {
                e.preventDefault();
            }
        });
    }
    
    // ========= AJOUT AU PANIER (AJAX) =========
    setupAddToCartButtons();
    
    // ========= RECHERCHE AJAX =========
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchProducts(this.value);
        });
    }
});

// Fonction AJAX pour récupérer les produits en accueil
function fetchProducts() {
    fetch('api_get_products.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('featured-products');
            if (container && data.success) {
                container.innerHTML = '';
                data.products.slice(0, 4).forEach(product => {
                    const card = createProductCard(product);
                    container.appendChild(card);
                });
            }
        })
        .catch(error => console.error('Erreur:', error));
}

// Création dynamique de carte produit
function createProductCard(product) {
    const div = document.createElement('div');
    div.className = 'product-card';
    div.innerHTML = `
        <img src="uploads/${product.image}" alt="${product.nom}" onerror="this.src='https://via.placeholder.com/300'">
        <div class="product-info">
            <h3>${product.nom}</h3>
            <p>${product.description.substring(0, 80)}...</p>
            <div class="price">${product.prix} €</div>
            <button class="btn-primary add-to-cart" data-id="${product.id}">Ajouter au panier</button>
        </div>
    `;
    
    const button = div.querySelector('.add-to-cart');
    button.addEventListener('click', () => addToCart(product.id));
    
    return div;
}

// AJAX ajout au panier
function addToCart(productId) {
    fetch('api_add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.cart_count);
            showNotification('Produit ajouté au panier !', 'success');
        } else {
            if (data.redirect) {
                window.location.href = 'auth.php';
            } else {
                showNotification(data.message || 'Erreur', 'error');
            }
        }
    });
}

// Mise à jour dynamique du compteur panier
function updateCartCount(count) {
    let cartSpan = document.querySelector('.cart-count');
    if (!cartSpan) {
        const cartLink = document.querySelector('a[href="panier.php"]');
        if (cartLink) {
            cartSpan = document.createElement('span');
            cartSpan.className = 'cart-count';
            cartLink.appendChild(cartSpan);
        }
    }
    if (cartSpan) {
        cartSpan.textContent = count;
        cartSpan.style.display = count > 0 ? 'inline' : 'none';
    }
}

// Validation formulaire inscription
function validateRegisterForm() {
    let isValid = true;
    
    const nom = document.getElementById('nom');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
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
    
    // Validation mot de passe (min 6 caractères)
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
    
    return isValid;
}

// Validation formulaire connexion
function validateLoginForm() {
    let isValid = true;
    
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    
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
    
    return isValid;
}

// Fonction recherche AJAX
function searchProducts(term) {
    if (term.length < 2) return;
    
    fetch(`api_search_products.php?q=${encodeURIComponent(term)}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('search-results');
            if (container && data.success) {
                container.innerHTML = '';
                if (data.products.length === 0) {
                    container.innerHTML = '<p>Aucun produit trouvé</p>';
                } else {
                    data.products.forEach(product => {
                        const result = document.createElement('div');
                        result.className = 'search-result-item';
                        result.innerHTML = `
                            <strong>${product.nom}</strong> - ${product.prix} €
                        `;
                        container.appendChild(result);
                    });
                }
                container.style.display = 'block';
            }
        });
}

// Helper functions
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
    input.style.borderColor = '#d32f2f';
}

function clearError(input) {
    const formGroup = input.closest('.form-group');
    const errorDiv = formGroup.querySelector('.error-message');
    if (errorDiv) {
        errorDiv.classList.remove('visible');
    }
    input.style.borderColor = '#ddd';
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 24px;
        background: ${type === 'success' ? '#4caf50' : '#f44336'};
        color: white;
        border-radius: 8px;
        z-index: 1000;
        animation: fadeIn 0.3s;
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

function setupAddToCartButtons() {
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            addToCart(this.dataset.id);
        });
    });
}