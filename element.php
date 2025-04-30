<?php
require_once 'includes/config.php';

// Obtenir l'ID de l'élément depuis l'URL
$element_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer les informations de l'élément
$query = "SELECT elements.*, categories.name AS category_name FROM elements JOIN categories ON elements.category_id = categories.id WHERE elements.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $element_id);
$stmt->execute();
$element_result = $stmt->get_result();
$element = $element_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($element['title']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <h1><?php echo htmlspecialchars($element['title']); ?></h1>
        <p><strong>Catégorie:</strong> <?php echo htmlspecialchars($element['category_name']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($element['description'])); ?></p>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
