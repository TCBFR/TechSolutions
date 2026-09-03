<?php

use Core\Validator;
use Model\Admin;

// Vérifier que c'est bien une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /mes-pcs');
    exit;
}

// Extraire l'ID du POST ou de l'URL
$id = $_POST['id'] ?? null;

if (!$id) {
    $uri = $_SERVER['REQUEST_URI'];
    $uri = parse_url($uri, PHP_URL_PATH);
    $segments = explode('/', trim($uri, '/'));
    $id = $segments[count($segments) - 2];
}

if (!is_numeric($id)) {
    header('Location: /mes-pcs');
    exit;
}

$header = 'Modifier le PC';

$nom = $_POST['nom'] ?? '';
$service = $_POST['service'] ?? '';
$effectif = $_POST['effectif'] ?? 1;
$description = $_POST['description'] ?? '';
$image = $_POST['image'] ?? '';

$errors = [];

if (!Validator::string($nom, 1, 255)) {
    $errors['nom'] = 'Le nom du PC est requis (max 255 caractères)';
}

if (!Validator::string($service, 1, 255)) {
    $errors['service'] = 'Le service est requis (max 255 caractères)';
}

if (!is_numeric($effectif) || $effectif < 0) {
    $errors['effectif'] = 'L\'effectif doit être un nombre positif';
}

if (!empty($errors)) {
    $adminModel = new Admin();
    $PC = $adminModel->get($id);
    $composantsPC = $adminModel->getPCComposantsRaw($id);
    $tousComposants = $adminModel->getAllComposants();
    
    require_once base_path('view/admin/edit.view.php');
    exit();
}

$adminModel = new Admin();

// Mettre à jour les infos du PC
$success = $adminModel->update($id, [
    'nom' => $nom,
    'service' => $service,
    'effectif' => $effectif,
    'description' => $description,
    'image' => $image
]);

// Supprimer toutes les associations actuelles
$adminModel->removeAllComposantsFromPC($id);

// Ajouter les nouvelles associations
if (!empty($_POST['composants']) && is_array($_POST['composants'])) {
    $composants = $_POST['composants'];
    $quantites = $_POST['quantites'] ?? [];
    
    foreach ($composants as $composantId) {
        $quantite = isset($quantites[$composantId]) ? (int)$quantites[$composantId] : 1;
        if ($quantite > 0) {
            $adminModel->addComposantToPC($id, $composantId, $quantite);
        }
    }
}

if ($success) {
    header('Location: /mes-pcs/' . $id . '?success=updated');
} else {
    header('Location: /mes-pcs/' . $id . '/edit?error=update_failed');
}
exit;