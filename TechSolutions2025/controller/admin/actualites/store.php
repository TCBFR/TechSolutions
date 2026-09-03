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

$actualiteModel = new Actualite();

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
    header('Location: /actualites/create');
    exit;
}

// Préparer les données
$data = [
    'titre' => trim($_POST['titre']),
    'contenu' => trim($_POST['contenu']),
    'auteur' => trim($_POST['auteur'] ?? 'Admin'),
    'image' => trim($_POST['image'] ?? '')
];

// Créer l'actualité
try {
    $actualiteModel->store($data);
    header('Location: /actualites?success=created');
    exit;
} catch (Exception $e) {
    $_SESSION['errors'] = ['Une erreur est survenue lors de la création'];
    $_SESSION['old'] = $_POST;
    header('Location: /actualites/create');
    exit;
}

