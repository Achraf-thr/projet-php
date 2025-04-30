-- Création de la table des administrateurs
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Création de la table des catégories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Création de la table des éléments
CREATE TABLE elements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Insertion de données initiales dans la table des administrateurs
INSERT INTO admins (username, password) VALUES ('admin', 'password123');

-- Insertion de données initiales dans la table des catégories
INSERT INTO categories (name) VALUES ('Mathématiques'), ('Sciences'), ('Langues');

-- Insertion de données initiales dans la table des éléments
INSERT INTO elements (title, description, category_id) VALUES 
('Addition', 'Apprendre à additionner des nombres.', 1),
('Les plantes', 'Découvrir les différentes plantes.', 2),
('Alphabet', 'Apprendre l\'alphabet.', 3);
