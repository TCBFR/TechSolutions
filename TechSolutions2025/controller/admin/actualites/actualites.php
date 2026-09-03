<?php

session_start();

use Model\Actualite;

$header = 'Actualités';

// Récupérer toutes les actualités
$actualiteModel = new Actualite();
$actualites = $actualiteModel->getAll();

// Charger la vue - CHEMIN CORRIGÉ
require_once base_path('view/admin/actualites/actualites.view.php');

