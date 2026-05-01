

-- Table utilisateurs
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table produits
CREATE TABLE produits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    categorie VARCHAR(100)
);

-- Table panier (liaison user/produit)
CREATE TABLE panier (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    produit_id INT,
    quantite INT DEFAULT 1,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE
);

-- Insertion de produits exemple
INSERT INTO produits (nom, description, prix, image, stock, categorie) VALUES
('Monstera Deliciosa', 'Plante tropicale aux feuilles perforées', 29.99, 'monstera.jpg', 10, 'Plantes vertes'),
('Sansevieria', 'Plante résistante, parfaite pour débutants', 19.99, 'sansevieria.jpg', 25, 'Plantes vertes'),
('Cactus Boule', 'Petit cactus sans entretien', 9.99, 'cactus.jpg', 30, 'Cactus'),
('Ficus Elastica', 'Caoutchouc aux feuilles vertes brillantes', 24.99, 'ficus.jpg', 8, 'Plantes vertes'),
('Aloe Vera', 'Plante médicinale aux multiples vertus', 14.99, 'aloe.jpg', 15, 'Succulentes'),
('Calathea', 'Plante aux motifs uniques sur feuilles', 34.99, 'calathea.jpg', 5, 'Plantes vertes'),
('Pilea Peperomioides', 'La plante à pièces de monnaie', 17.99, 'pilea.jpg', 12, 'Plantes vertes'),
('Orchidée Phalaenopsis', 'Fleurs élégantes et durables', 39.99, 'orchidee.jpg', 6, 'Fleurs');