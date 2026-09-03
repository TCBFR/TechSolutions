<?php

/**
 * @var Core\Router $router
 */

// ========================================
// ROUTES PRINCIPALES
// ========================================

$router->get('/', 'index.php', 'index');
$router->get('/contact', 'contact.php', 'contact');
$router->get('/pcs', 'pcs.php', 'pcs');



// ========================================
// ROUTES D'AUTHENTIFICATION
// ========================================

$router->get('/login', 'login.php', 'login');
$router->post('/login', 'login.php', 'login_post');
$router->get('/logout', 'logout.php', 'logout');



// ========================================
// ROUTES ADMIN (Dashboard)
// ========================================

$router->get('/admin', 'admin/index.php', 'admin_index');



// ========================================
// ROUTES USER (Dashboard personnel)
// ========================================

$router->get('/user', 'user/index.php', 'user_dashboard');
$router->get('/user/CRUD', 'user/CRUD.php', 'user_CRUD');
$router->post('/user/CRUD', 'user/CRUD.php', 'user_CRUD_post');



// ========================================
// ROUTES PC (CRUD COMPLET)
// ========================================

// Lister tous les PC
$router->get('/mes-pcs', 'admin/pcs/list.php', 'pcs_list');

// Créer un nouveau PC (DOIT ÊTRE AVANT /mes-pcs/[i:id])
$router->get('/mes-pcs/create', 'admin/pcs/create.php', 'pcs_create');
$router->post('/mes-pcs', 'admin/pcs/store.php', 'pcs_store');

// Modifier un PC (DOIT ÊTRE AVANT /mes-pcs/[i:id])
$router->get('/mes-pcs/[i:id]/edit', 'admin/pcs/edit.php', 'pcs_edit');
$router->post('/mes-pcs/[i:id]/update', 'admin/pcs/update.php', 'pcs_update');

// Supprimer un PC (DOIT ÊTRE AVANT /mes-pcs/[i:id])
$router->get('/mes-pcs/[i:id]/delete', 'admin/pcs/delete.php', 'pcs_delete');
$router->post('/mes-pcs/[i:id]/destroy', 'admin/pcs/destroy.php', 'pcs_destroy');

// Afficher un PC (DOIT ÊTRE EN DERNIER)
$router->get('/mes-pcs/[i:id]', 'admin/pcs/show.php', 'pcs_show');



// ========================================
// ROUTES ACTUALITÉS (CRUD COMPLET)
// ========================================
// CHEMINS CORRIGÉS: admin/actualites/ au lieu de actualites/

// Créer une nouvelle actualité (DOIT ÊTRE AVANT /actualites ET /actualites/[i:id])
$router->get('/actualites/create', 'admin/actualites/create.php', 'actualites_create');
$router->post('/actualites/store', 'admin/actualites/store.php', 'actualites_store');

// Modifier une actualité (DOIT ÊTRE AVANT /actualites/[i:id])
$router->get('/actualites/[i:id]/edit', 'admin/actualites/edit.php', 'actualites_edit');
$router->post('/actualites/[i:id]/update', 'admin/actualites/update.php', 'actualites_update');

// Supprimer une actualité (DOIT ÊTRE AVANT /actualites/[i:id])
$router->get('/actualites/[i:id]/delete', 'admin/actualites/delete.php', 'actualites_delete');

// Afficher une actualité (DOIT ÊTRE AVANT /actualites mais APRÈS les routes spécifiques)
$router->get('/actualites/[i:id]', 'admin/actualites/show.php', 'actualites_show');

// Lister toutes les actualités (DOIT ÊTRE EN DERNIER)
$router->get('/actualites', 'admin/actualites/actualites.php', 'actualites_list');