<?php
require_once 'includes/config.php';

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier et créer les tables si nécessaire
$tables_check = $conn->query("SHOW TABLES LIKE 'categories'");
if ($tables_check->num_rows == 0) {
    $conn->query("CREATE TABLE categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        color VARCHAR(7) DEFAULT '#4CAF50',
        icon VARCHAR(10),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
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
}

$tables_check = $conn->query("SHOW TABLES LIKE 'elements'");
if ($tables_check->num_rows == 0) {
    $conn->query("CREATE TABLE elements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        image VARCHAR(255),
        content_type ENUM('texte','video','jeu','image') DEFAULT 'texte',
        content_path VARCHAR(255),
        difficulty ENUM('facile','moyen','difficile') DEFAULT 'facile',
        age_group VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id)
    )");
    
    // Message pour l'administrateur
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
        echo "<div class='info'>La table 'elements' a été créée. Vous pouvez maintenant ajouter des éléments.</div>";
    }
}

// Obtenir l'ID de la catégorie depuis l'URL
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer les informations de la catégorie
$query = "SELECT * FROM categories WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $category_id);
$stmt->execute();
$category_result = $stmt->get_result();
$category = $category_result->fetch_assoc();

if (!$category) {
    header('Location: index.php');
    exit;
}

// Récupérer les éléments de la catégorie
$query = "SELECT * FROM elements WHERE category_id = ? ORDER BY title";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $category_id);
$stmt->execute();
$elements_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category['name']) ?> - Site Éducatif</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="main-category" style="--category-color: <?= htmlspecialchars($category['color'] ?? '#4CAF50') ?>">
        <div class="category-header">
            <a href="index.php" class="back-button">← Retour</a>
            <h1><?= htmlspecialchars($category['name']) ?></h1>
            <?php if (!empty($category['icon'])): ?>
                <span class="category-icon"><?= htmlspecialchars($category['icon']) ?></span>
            <?php endif; ?>
        </div>
        
        <?php if ($elements_result->num_rows > 0): ?>
            <div class="elements-grid">
                <?php while ($element = $elements_result->fetch_assoc()): ?>
                    <div class="element-card">
                        <a href="element.php?id=<?= $element['id'] ?>">
                            <?php if (!empty($element['image'])): ?>
                                <img src="uploads/<?= htmlspecialchars($element['image']) ?>" alt="<?= htmlspecialchars($element['title']) ?>">
                            <?php else: ?>
                                <div class="placeholder-image"></div>
                            <?php endif; ?>
                            <h3><?= htmlspecialchars($element['title']) ?></h3>
                            <?php if (!empty($element['difficulty'])): ?>
                                <div class="difficulty difficulty-<?= htmlspecialchars($element['difficulty']) ?>">
                                    Niveau: <?= htmlspecialchars($element['difficulty']) ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-elements">
                <p>Aucun élément trouvé dans cette catégorie.</p>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <a href="admin/add_element.php?category_id=<?= $category_id ?>" class="add-button">+ Ajouter un élément</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>