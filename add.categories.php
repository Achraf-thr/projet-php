<?php
require_once 'includes/config.php';

// Ajouter des catégories adaptées aux enfants
$query = "INSERT INTO categories (name) VALUES ('Mathématiques', 'Sciences', 'Langues', 'Histoire', 'Géographie', 'Arts', 'Musique')";
if ($conn->query($query) === TRUE) {
    echo "Catégories ajoutées avec succès!";
} else {
    echo "Erreur lors de l'ajout des catégories: " . $conn->error;
}
?>
