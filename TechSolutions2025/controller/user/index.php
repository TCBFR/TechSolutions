<?php
session_start();

require_once base_path('Core/Database.php');
require_once base_path('view/partials/head.php');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: /login');
    exit;
}

// Récupérer les informations de l'utilisateur
$pdo = \Core\Database::getInstance()->getConnection();
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Calculer la durée de la session
$session_duration = time() - $_SESSION['login_time'];
$hours = floor($session_duration / 3600);
$minutes = floor(($session_duration % 3600) / 60);
?>

<style>
    .dashboard-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 2rem;
    }
    
    .top-bar {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 2rem;
    }
    
    .btn-logout {
        background: #fc8181;
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
        border: 2px solid transparent;
    }
    
    .btn-logout:hover {
        background: #f56565;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(252, 129, 129, 0.4);
    }
    
    .profile-card {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }
    
    .profile-header {
        text-align: center;
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .user-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background-image: url('/img/profil.jpg');
    background-size: cover;  /* Ajoutez ceci */
    background-position: center;  /* Et ceci */
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: bold;
    color: white;
    margin: 0 auto 1.5rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    overflow: hidden;  /* Ajoutez ceci aussi */
}
    
    .profile-header h1 {
        font-size: 2rem;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .profile-header p {
        color: #718096;
        font-size: 1rem;
    }
    
    .stats-row {
        display: flex;
        justify-content: center;
        gap: 3rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #718096;
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
    }
    
    .stat-value {
        font-size: 1.2rem;
        font-weight: bold;
        color: #667eea;
    }
    
    .section-title {
        font-size: 1.5rem;
        color: #2d3748;
        margin-bottom: 2rem;
        text-align: center;
        font-weight: 600;
    }
    
    .info-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .info-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.2rem;
        background: #f7fafc;
        border-radius: 12px;
        transition: all 0.3s;
    }
    
    .info-row:hover {
        background: #edf2f7;
        transform: translateX(5px);
    }
    
    .info-content {
        flex: 1;
    }
    
    .info-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        color: #718096;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .info-value {
        font-size: 1.1rem;
        color: #2d3748;
        font-weight: 500;
    }
    
    .info-value.empty {
        color: #cbd5e0;
        font-style: italic;
    }
    
    .btn-edit {
        background: #667eea;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        white-space: nowrap;
    }
    
    .btn-edit:hover {
        background: #5568d3;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }
</style>

<div class="dashboard-container">
    
    <!-- Bouton de déconnexion en haut à droite -->
    <div class="top-bar">
        <a href="/logout" class="btn-logout">
            <span>🚪</span> Se déconnecter
        </a>
    </div>
    
    <!-- Carte de profil -->
    <div class="profile-card">
        
        <!-- En-tête du profil -->
        <div class="profile-header">
            <div class="user-avatar">
                
            </div>
            <h1><?= htmlspecialchars($user['first_name'] ?: $user['username']) ?></h1>
            <p>Bienvenue sur votre espace personnel</p>

        <!-- Informations personnelles -->
        <h2 class="section-title">Mes informations personnelles</h2>
        
        <div class="info-list">
            <div class="info-row">
                <div class="info-content">
                    <div class="info-label">Nom complet</div>
                    <div class="info-value <?= empty($user['first_name']) && empty($user['last_name']) ? 'empty' : '' ?>">
                        <?= htmlspecialchars(trim($user['first_name'] . ' ' . $user['last_name'])) ?: 'Non renseigné' ?>
                    </div>
                </div>
                <a href="/user/CRUD" class="btn-edit">
                Modifier
                </a>
            </div>
            
            <div class="info-row">
                <div class="info-content">
                    <div class="info-label">Nom d'utilisateur</div>
                    <div class="info-value">
                        <?= htmlspecialchars($user['username']) ?>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-content">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        <?= htmlspecialchars($user['email']) ?>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-content">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value <?= empty($user['phone']) ? 'empty' : '' ?>">
                        <?= htmlspecialchars($user['phone'] ?? '') ?: 'Non renseigné' ?>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-content">
                    <div class="info-label">Adresse</div>
                    <div class="info-value <?= empty($user['address']) ? 'empty' : '' ?>">
                        <?= htmlspecialchars($user['address'] ?? '') ?: 'Non renseignée' ?>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-content">
                    <div class="info-label">Ville</div>
                    <div class="info-value <?= empty($user['city']) ? 'empty' : '' ?>">
                        <?= htmlspecialchars($user['city'] ?? '') ?: 'Non renseignée' ?>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-content">
                    <div class="info-label">Code postal</div>
                    <div class="info-value <?= empty($user['postal_code']) ? 'empty' : '' ?>">
                        <?= htmlspecialchars($user['postal_code'] ?? '') ?: 'Non renseigné' ?>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-content">
                    <div class="info-label">Pays</div>
                    <div class="info-value">
                        <?= htmlspecialchars($user['country'] ?? 'France') ?>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div> 
</div>

<?php require_once base_path('view/partials/foot.php'); ?>