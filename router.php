<<?php
// Démarrer la session
session_start();

// Inclure les fichiers nécessaires
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Définir les routes disponibles
$routes = [
    '/' => 'index.php',
    '/categorie' => 'categorie.php',
    '/element' => 'element.php',
    '/admin/login' => 'admin/login.php',
    '/admin/logout' => 'admin/logout.php',
    '/admin/categories' => 'admin/categories.php',
    '/admin/elements' => 'admin/elements.php',
    '/admin/medias' => 'admin/medias.php'
];

// Obtenir l'URL demandée
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Vérifier si la route existe
if (array_key_exists($requestUri, $routes)) {
    require $routes[$requestUri];
} else {
    // Si la route n'existe pas, afficher la page 404
    require '404.php';
}
?>

