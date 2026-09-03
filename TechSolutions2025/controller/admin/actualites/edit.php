<?php

session_start();

use Model\Actualite;

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

$header = 'Modifier une actualité';

// CORRECTION: Utiliser $router->params() au lieu de $_GET
global $router;
$params = $router->params();
$id = (int)($params['id'] ?? 0);

if ($id === 0) {
    header('Location: /actualites?error=invalid_id');
    exit;
}

$actualiteModel = new Actualite();
$actualite = $actualiteModel->get($id);

if (!$actualite) {
    header('Location: /actualites?error=not_found');
    exit;
}

// Charger la vue
require_once base_path('view/admin/actualites/edit.view.php');

