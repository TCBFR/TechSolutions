<?php

use Core\Validator;
use Model\Admin;

$header = 'Nouveau PC';

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
    view('admin/create.view.php', compact('header', 'errors'));
    exit();
}

$adminModel = new Admin();

// Créer le PC
$pcId = $adminModel->store([
    'nom' => $nom,
    'service' => $service,
    'effectif' => $effectif,
    'description' => $description,
    'image' => $image
]);

// Associer les composants sélectionnés
if (!empty($_POST['composants']) && is_array($_POST['composants'])) {
    $composants = $_POST['composants'];
    $quantites = $_POST['quantites'] ?? [];
    
    foreach ($composants as $composantId) {
        $quantite = isset($quantites[$composantId]) ? (int)$quantites[$composantId] : 1;
        if ($quantite > 0) {
            $adminModel->addComposantToPC($pcId, $composantId, $quantite);
        }
    }
}

header('Location: /mes-pcs/' . $pcId . '?success=created');
exit;