<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $query = "INSERT INTO categories (name) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $name);
    $stmt->execute();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

$query = "SELECT * FROM categories";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Catégories</title>
</head>
<body>
    <h1>Gestion des Catégories</h1>
    <form method="POST" action="">
        <label for="name">Nom de la catégorie:</label>
        <input type="text" id="name" name="name" required>
        <button type="submit" name="add">Ajouter</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <a href="categories.php?delete=<?php echo $row['id']; ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

