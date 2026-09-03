<?php
/**
 * Point d'entrée principal de l'application
 * public/index.php
 */

// Définir le chemin de base du projet
const BASE_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

// Activer les erreurs pour le développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Si c'est un fichier statique qui existe, le servir directement
$request_uri = $_SERVER['REQUEST_URI'];
$file_path = __DIR__ . parse_url($request_uri, PHP_URL_PATH);

if ($request_uri !== '/' && file_exists($file_path) && is_file($file_path)) {
    return false; // Laisser le serveur PHP built-in servir le fichier
}

// Charger les fonctions utilitaires
require_once BASE_PATH . 'Core/function.php';

// Charger l'autoloader personnalisé
require_once BASE_PATH . 'autoload.php';

// Instancier le router AVANT de charger les routes
$router = new Core\Router();

// Charger les routes
require_once BASE_PATH . 'config/routes.php';

// Démarrer le routing et rendre le router accessible globalement
$GLOBALS['router'] = $router;
require $router->start();