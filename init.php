<?php
require_once '../includes/config.php';

// Fonction pour exécuter les requêtes SQL depuis un fichier
function executeSQLFile($filePath, $conn) {
    $sql = file_get_contents($filePath);
    $queries = explode(';', $sql);
    foreach ($queries as $query) {
        if (trim($query) != '') {
            $conn->query($query);
        }
    }
}

// Initialiser la base de données
executeSQLFile('../database/schema.sql', $conn);

echo "Base de données initialisée avec succès!";
?>
