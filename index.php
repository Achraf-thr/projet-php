<?php
require_once 'includes/config.php';

// Activer l'affichage des erreurs (à désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier d'abord si la table existe
$table_check = $conn->query("SHOW TABLES LIKE 'categories'");

if ($table_check->num_rows == 0) {
    // Créer la table si elle n'existe pas
    $create_table = "CREATE TABLE categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        color VARCHAR(7) DEFAULT '#4CAF50',
        icon VARCHAR(10),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($create_table) === TRUE) {
        // Insérer des catégories par défaut
        $default_categories = [
            ['Mathématiques', '#FF9AA2', '🧮'],
            ['Sciences', '#FFB7B2', '🔬'],
            ['Langues', '#FFDAC1', '🗣️'],
            ['Histoire', '#E2F0CB', '🏛️'],
            ['Géographie', '#B5EAD7', '🌍'],
            ['Arts', '#C7CEEA', '🎨'],
            ['Musique', '#F8B195', '🎵']
        ];
        
        $stmt = $conn->prepare("INSERT INTO categories (name, color, icon) VALUES (?, ?, ?)");
        foreach ($default_categories as $cat) {
            $stmt->bind_param('sss', $cat[0], $cat[1], $cat[2]);
            $stmt->execute();
        }
    } else {
        die("Erreur lors de la création de la table: " . $conn->error);
    }
}

// Récupérer les catégories
$query = "SELECT * FROM categories ORDER BY name";
$result = $conn->query($query);

if (!$result) {
    die("Erreur lors de la récupération des catégories: " . htmlspecialchars($conn->error));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Site Éducatif pour Enfants</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="main-home">
        <div class="welcome-banner">
            <h1>Bienvenue sur notre site éducatif pour enfants!</h1>
            <p>Explorez les différentes catégories pour découvrir des éléments éducatifs intéressants.</p>
        </div>
        
        <div class="category-grid">
            <?php while ($category = $result->fetch_assoc()): ?>
                <a href="categorie.php?id=<?= htmlspecialchars($category['id']) ?>" 
                   class="category-card" 
                   style="background-color: <?= htmlspecialchars($category['color'] ?? '#4CAF50') ?>">
                    <?php if (!empty($category['icon'])): ?>
                        <span class="category-icon"><?= htmlspecialchars($category['icon']) ?></span>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($category['name']) ?></h3>
                </a>
            <?php endwhile; ?>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>