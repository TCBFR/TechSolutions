<?php

use Model\Admin;

$header = 'Gestion des PC';

// Récupérer tous les PC
$adminModel = new Admin();
$pcs = $adminModel->getAllPCs();

// Afficher la vue
view('admin/list.view.php', compact('header', 'pcs'));