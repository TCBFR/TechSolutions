<?php

use Model\Admin;

// Extraire l'ID directement de l'URL
$uri = $_SERVER['REQUEST_URI'];
$uri = parse_url($uri, PHP_URL_PATH);
$segments = explode('/', trim($uri, '/'));
$id = end($segments);

if (!is_numeric($id)) {
    header('Location: /mes-pcs');
    exit;
}

$adminModel = new Admin();
$PC = $adminModel->get($id);

if (!$PC) {
    header('Location: /mes-pcs');
    exit;
}

// ✅ IMPORTANT : Utiliser getPCComposants pour récupérer les composants avec leurs infos
$composants = $adminModel->getPCComposants($id);

$header = "PC #{$id} - {$PC['nom']}";

view('admin/show.view.php', compact('header', 'PC', 'composants', 'id'));