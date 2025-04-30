<?php
// Fonction pour vérifier si un utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['admin']);
}

// Fonction pour rediriger vers une page spécifique
function redirect($url) {
    header("Location: $url");
    exit();
}

// Fonction pour échapper les caractères spéciaux dans une chaîne
function escape($string) {
    global $conn;
    return mysqli_real_escape_string($conn, $string);
}

// Fonction pour afficher un message d'erreur
function displayError($message) {
    echo "<div class='error'>$message</div>";
}

// Fonction pour afficher un message de succès
function displaySuccess($message) {
    echo "<div class='success'>$message</div>";
}
?>

