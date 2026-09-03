<?php
session_start();

// ============================================
// CHARGEMENT DES DÉPENDANCES
// ============================================
require_once base_path('Core/Database.php');
require_once base_path('Model/Admin.php');
require_once base_path('view/partials/head.php');

// ============================================
// VÉRIFICATION DE L'AUTHENTIFICATION ADMIN
// ============================================
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /login');
    exit;
}

// ============================================
// RÉCUPÉRATION DES DONNÉES
// ============================================
$adminModel = new \Model\Admin();
$pcs = $adminModel->getAllPCs();
?>

<style>
    /* ========================================
       CONTAINER PRINCIPAL
       ======================================== */
    .admin-main-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    /* ========================================
       EN-TÊTE ADMIN
       ======================================== */
    .admin-header {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 3rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .admin-header h1 {
        font-size: 2.5rem;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .admin-header p {
        color: #718096;
        font-size: 1.1rem;
    }

    /* Bouton de déconnexion */
    .btn-logout {
        background: #fc8181;
        color: white;
        padding: 1rem 2rem;
        border-radius: 15px;
        text-decoration: none;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        transition: all 0.3s;
        font-size: 1rem;
        border: none;
        cursor: pointer;
    }

    .btn-logout:hover {
        background: #f56565;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(252, 129, 129, 0.4);
    }

    /* ========================================
       TITRE DES MODULES
       ======================================== */
    .modules-title {
        text-align: center;
        color: #2d3748;
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 2.5rem;
    }

    /* ========================================
       GRILLE DES MODULES (PC & ACTUALITÉS)
       ======================================== */
    .modules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2.5rem;
        margin-bottom: 3rem;
    }

    /* Carte de module */
    .module-card {
        background: white;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        transition: all 0.4s;
        border: 4px solid transparent;
    }

    .module-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 25px 60px rgba(0,0,0,0.25);
        border-color: lightgray;
    }

    /* ========================================
       EN-TÊTE DES MODULES (avec images)
       ======================================== */
    .module-PC, 
    .module-Actu {
        padding: 3rem 2.5rem 2rem;
        text-align: center;
        color: black;
        /* Configuration des images de fond */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        /* Overlay pour améliorer la lisibilité du texte */
        position: relative;
    }

    /* Overlay semi-transparent pour améliorer la lisibilité */
    .module-PC::before,
    .module-Actu::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
    }

    /* S'assurer que le texte est au-dessus de l'overlay */
    .module-title,
    .module-description {
        position: relative;
        z-index: 2;
        color:white;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
    }

    /* Image de fond pour le module PC */
    .module-PC {
        background-image: url('/img/PC.webp');
    }

    /* Image de fond pour le module Actualités */
    .module-Actu {
        background-image: url('/img/actualites.jpg');
    }

    .module-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
    }

    .module-description {
        font-size: 1.1rem;
        opacity: 0.95;
        line-height: 1.6;
    }

    /* ========================================
       CORPS DU MODULE (fonctionnalités)
       ======================================== */
    .module-body {
        padding: 2.5rem;
    }

    /* Liste des fonctionnalités */
    .module-features {
        list-style: none;
        margin-bottom: 2rem;
        padding: 0;
    }

    .module-features li {
        padding: 1rem;
        margin-bottom: 0.8rem;
        background: #f7fafc;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 1rem;
        font-weight: 600;
        color: #2d3748;
        border-left: 3px solid lightgray;
    }

    /* Icône de validation avant chaque fonctionnalité */
    .module-features li::before {
        content: "✓";
        background: #667eea;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
    }

    /* ========================================
       BOUTON D'ACCÈS AU MODULE
       ======================================== */
    .btn-module {
        display: block;
        padding: 0.7rem;
        background: lightgray;
        color: white;
        text-decoration: none;
        border-radius: 15px;
        font-weight: 700;
        font-size: 1.1rem;
        text-align: center;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-module:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    }
</style>

<main class="admin-main-container">
    
    <!-- ========================================
         EN-TÊTE DE L'ADMINISTRATION
         ======================================== -->
    <div class="admin-header">
        <div>
            <h1>🏢 Administration TechSolutions</h1>
            <p>Bienvenue, <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></p>
        </div>
        <a href="/logout" class="btn-logout">
            🚪 Déconnexion
        </a>
    </div>

    <!-- ========================================
         TITRE DE LA SECTION MODULES
         ======================================== -->
    <h2 class="modules-title">Modules de gestion</h2>
    
    <!-- ========================================
         GRILLE DES MODULES
         ======================================== -->
    <div class="modules-grid">
        
        <!-- ====================================
             MODULE : GESTION DES PC
             ==================================== -->
        <div class="module-card">
            <!-- En-tête avec image de fond -->
            <div class="module-PC">
                <h3 class="module-title">Gestion des PC</h3>
                <p class="module-description">
                    Configurez et gérez toutes vos configurations de PC par service
                </p>
            </div>
            
            <!-- Corps du module avec fonctionnalités -->
            <div class="module-body">
                <ul class="module-features">
                    <li>Créer de nouvelles configurations</li>
                    <li>Afficher les PC existants</li>
                    <li>Modifier les configurations</li>
                    <li>Supprimer des PC</li>
                </ul>
                
                <!-- Bouton d'accès au module -->
                <a href="/mes-pcs" class="btn-module">
                    Accéder aux PC
                </a>
            </div>
        </div>
        
        <!-- ====================================
             MODULE : GESTION DES ACTUALITÉS
             ==================================== -->
        <div class="module-card">
            <!-- En-tête avec image de fond -->
            <div class="module-Actu">
                <h3 class="module-title">Gestion des Actualités</h3>
                <p class="module-description">
                    Publiez et gérez toutes les actualités de l'entreprise
                </p>
            </div>
            
            <!-- Corps du module avec fonctionnalités -->
            <div class="module-body">
                <ul class="module-features">
                    <li>Créer de nouvelles actualités</li>
                    <li>Afficher les publications</li>
                    <li>Modifier les articles</li>
                    <li>Supprimer des actualités</li>
                </ul>
                
                <!-- Bouton d'accès au module -->
                <a href="/actualites" class="btn-module">
                    Accéder aux actualités
                </a>
            </div>
        </div>
        
    </div>
       
</main>

<?php require_once base_path('view/partials/foot.php'); ?>