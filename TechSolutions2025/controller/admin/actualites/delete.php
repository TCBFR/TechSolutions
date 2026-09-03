<?php

session_start();

use Model\Actualite;

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

// CORRECTION: Utiliser $router->params() au lieu de $_GET
global $router;
$params = $router->params();
$id = (int)($params['id'] ?? 0);

if ($id === 0) {
    header('Location: /actualites?error=invalid_id');
    exit;
}

$actualiteModel = new Actualite();

// Vérifier que l'actualité existe
if (!$actualiteModel->exists($id)) {
    header('Location: /actualites?error=not_found');
    exit;
}

// Supprimer l'actualité
try {
    $actualiteModel->delete($id);
    header('Location: /actualites?success=deleted');
    exit;
} catch (Exception $e) {
    header('Location: /actualites?error=delete_failed');
    exit;
}
