// ==========================================
// controller/admin/actualites/update.php
// ==========================================
<?php

session_start();

use Model\Actualite;

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /actualites');
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

// Valider les données
$errors = [];

if (empty($_POST['titre'])) {
    $errors[] = 'Le titre est requis';
}

if (empty($_POST['contenu'])) {
    $errors[] = 'Le contenu est requis';
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $_POST;
    header("Location: /actualites/$id/edit");
    exit;
}

// Préparer les données
$data = [
    'titre' => trim($_POST['titre']),
    'contenu' => trim($_POST['contenu']),
    'auteur' => trim($_POST['auteur']),
    'image' => trim($_POST['image'] ?? '')
];

// Mettre à jour l'actualité
try {
    $actualiteModel->update($id, $data);
    header('Location: /actualites?success=updated');
    exit;
} catch (Exception $e) {
    $_SESSION['errors'] = ['Une erreur est survenue lors de la mise à jour'];
    $_SESSION['old'] = $_POST;
    header("Location: /actualites/$id/edit");
    exit;
}