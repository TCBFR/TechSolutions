<?php

session_start();

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/login');
    exit;
}

$header = 'Créer une actualité';

// Charger la vue - CHEMIN CORRIGÉ
require_once base_path('view/admin/actualites/create.view.php');
