<?php

use Model\Admin;

$header = 'Ajouter un PC';

$adminModel = new Admin();
$categories = $adminModel->getAllCategories();

// Récupérer TOUS les composants avec leur catégorie
$composants = $adminModel->getAllComposants();

view('admin/create.view.php', compact('header', 'categories', 'composants'));