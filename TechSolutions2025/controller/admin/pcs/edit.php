<?php

session_start(); // ← AJOUTEZ CETTE LIGNE EN PREMIER !

use Model\Admin;

// Vérifier que l'admin est connecté
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /login');
    exit;
}

// Extraire l'ID de l'URL
$uri = $_SERVER['REQUEST_URI'];
$uri = parse_url($uri, PHP_URL_PATH);
$segments = explode('/', trim($uri, '/'));
$id = $segments[count($segments) - 2];

if (!is_numeric($id)) {
    header('Location: /mes-pcs');
    exit;
}

$adminModel = new Admin();
$PC = $adminModel->get($id);

if (!$PC) {
    header('Location: /mes-pcs?error=not_found');
    exit;
}

// Récupérer les composants actuels du PC
$composantsPC = $adminModel->getPCComposantsRaw($id);

// Récupérer TOUS les composants disponibles
$tousComposants = $adminModel->getAllComposants();

$header = "Modifier le PC #{$id}";

// Charger la vue
require_once base_path('view/admin/edit.view.php');