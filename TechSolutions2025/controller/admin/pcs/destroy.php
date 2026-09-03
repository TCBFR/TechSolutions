<?php

use Model\Admin;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /mes-pcs');
    exit;
}

// ✅ Extraire l'ID du POST ou de l'URL
$id = $_POST['id'] ?? null;

if (!$id) {
    $uri = $_SERVER['REQUEST_URI'];
    $uri = parse_url($uri, PHP_URL_PATH);
    $segments = explode('/', trim($uri, '/'));
    $id = $segments[count($segments) - 2];
}

if (!is_numeric($id)) {
    header('Location: /mes-pcs?error=missing_id');
    exit;
}

$adminModel = new Admin();
$success = $adminModel->delete($id);

if ($success) {
    header('Location: /mes-pcs?success=deleted');
} else {
    header('Location: /mes-pcs?error=delete_failed');
}
exit;