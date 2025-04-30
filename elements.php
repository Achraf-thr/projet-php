<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $query = "INSERT INTO elements (title, description, category_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssi', $title, $description, $category_id);
    $stmt->execute();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM elements WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

// Récupérer tous les éléments
$query = "SELECT elements.*, categories.name AS category_name FROM elements JOIN categories ON elements.category_id = categories.id";
$result = $conn->query($query);

// Récupérer toutes les catégories pour le formulaire d'ajout
$categories_query = "SELECT * FROM categories";
$categories_result = $conn->query($categories_query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Éléments</title>
</head>
<body>
    <h1>Gestion des Éléments</h1>
    <form method="POST" action="">
        <label for="title">Titre:</label>
        <input type="text" id="title" name="title" required>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <label for="category_id">Catégorie:</label>
        <select id="category_id" name="category_id" required>
            <?php while ($category = $categories_result->fetch_assoc()): ?>
                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="add">Ajouter</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td>
                        <a href="elements.php?delete=<?php echo $row['id']; ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
