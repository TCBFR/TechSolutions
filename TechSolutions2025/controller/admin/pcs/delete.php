<?php

use Model\Admin;

$header = 'Supprimer un PC';

// ✅ Extraire l'ID directement de l'URL
$uri = $_SERVER['REQUEST_URI'];
$uri = parse_url($uri, PHP_URL_PATH);
$segments = explode('/', trim($uri, '/'));
$id = $segments[count($segments) - 2]; // /mes-pcs/123/delete → avant-dernier = 123

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

view('admin/delete.view.php', compact('header', 'PC', 'id'));