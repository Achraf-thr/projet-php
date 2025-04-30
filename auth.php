<?php
session_start();
require_once 'config.php';

// Fonction pour vérifier les identifiants de connexion
function authenticate($username, $password) {
    global $conn;
    $query = "SELECT * FROM admins WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Vérifier si l'utilisateur est connecté
function checkLogin() {
    if (!isset($_SESSION['admin'])) {
        redirect('login.php');
    }
}

// Déconnexion de l'utilisateur
function logout() {
    session_unset();
    session_destroy();
    redirect('login.php');
}
?>
