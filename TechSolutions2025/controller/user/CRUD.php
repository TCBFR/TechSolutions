<?php

session_start();

require_once base_path('Core/Database.php');
require_once base_path('view/partials/head.php');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: /login');
    exit;
}

$success = '';
$error = '';

$pdo = \Core\Database::getInstance()->getConnection();

// Récupérer les informations actuelles
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $country = trim($_POST['country'] ?? 'France');
    
    // Validation de l'email
    if (empty($email)) {
        $error = 'L\'email est requis';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format d\'email invalide';
    } else {
        
        // Vérifier que l'email n'est pas déjà utilisé
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $_SESSION['user_id']]);
        
        if ($stmt->fetch()) {
            $error = 'Cet email est déjà utilisé par un autre compte';
        } else {
            
            try {
                $update_stmt = $pdo->prepare("
                    UPDATE users 
                    SET first_name = ?, last_name = ?, email = ?, phone = ?, 
                        address = ?, city = ?, postal_code = ?, country = ?
                    WHERE id = ?
                ");
                
                $result = $update_stmt->execute([
                    $first_name, $last_name, $email, $phone, 
                    $address, $city, $postal_code, $country, 
                    $_SESSION['user_id']
                ]);
                
                if ($result) {
                    // Mettre à jour la session
                    $_SESSION['first_name'] = $first_name;
                    $_SESSION['last_name'] = $last_name;
                    $_SESSION['email'] = $email;
                    
                    // Recharger les données
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    $user = $stmt->fetch();
                    
                    $success = 'Vos informations ont été mises à jour avec succès !';
                } else {
                    $error = 'Erreur lors de la mise à jour. Aucune ligne affectée.';
                }
                
            } catch (PDOException $e) {
                $error = 'Erreur lors de la mise à jour : ' . $e->getMessage();
            }
        }
    }
}
?>

<style>
    .profile-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 2rem;
    }
    
    .profile-header {
        text-align: center;
        margin-bottom: 2rem;
        padding: 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .profile-header h1 {
        font-size: 2.5rem;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .profile-header p {
        color: #718096;
        font-size: 1.1rem;
    }
    
    .profile-nav {
        margin-bottom: 2rem;
    }
    
    .btn-back {
        background: #e2e8f0;
        color: #2d3748;
        padding: 0.8rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
    }
    
    .btn-back:hover {
        background: #cbd5e0;
        transform: translateX(-5px);
    }
    
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        font-weight: 600;
    }
    
    .alert-success {
        background: #c6f6d5;
        color: #22543d;
        border: 2px solid #9ae6b4;
    }
    
    .alert-error {
        background: #fed7d7;
        color: #742a2a;
        border: 2px solid #fc8181;
    }
    
    .profile-card {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
    }
    
    .form-section {
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .form-section:last-of-type {
        border-bottom: none;
    }
    
    .form-section h2 {
        font-size: 1.5rem;
        color: #2d3748;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }
    
    .form-group input,
    .form-group select {
        padding: 0.8rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 3rem;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 3rem;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    }
    
    .btn-cancel {
        background: #e2e8f0;
        color: #2d3748;
        padding: 1rem 2rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-cancel:hover {
        background: #cbd5e0;
    }
</style>

<main class="profile-container">
    
    <header class="profile-header">
        <h1>Mon Compte</h1>
        <p>Gérez vos informations personnelles</p>
    </header>
    
    <div class="profile-nav">
        <a href="/user" class="btn-back">← Retour au dashboard</a>
    </div>
    
    <?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <span>✅</span>
        <?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
    <div class="alert alert-error">
        <span>⚠️</span>
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <div class="profile-card">
        
        <!-- CORRECTION: action="/user/CRUD" au lieu de "/user/profile" -->
        <form method="POST" action="/user/CRUD">
            
            <!-- Informations de base -->
            <div class="form-section">
                <h2>Informations personnelles</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">Prénom</label>
                        <input 
                            type="text" 
                            id="first_name" 
                            name="first_name" 
                            value="<?= htmlspecialchars($user['first_name'] ?? '') ?>"
                            placeholder="Votre prénom"
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">Nom</label>
                        <input 
                            type="text" 
                            id="last_name" 
                            name="last_name" 
                            value="<?= htmlspecialchars($user['last_name'] ?? '') ?>"
                            placeholder="Votre nom"
                        >
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                            placeholder="votre.email@exemple.com"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                            placeholder="06 12 34 56 78"
                        >
                    </div>
                </div>
            </div>
            
            <!-- Adresse -->
            <div class="form-section">
                <h2>📍 Adresse</h2>
                
                <div class="form-group">
                    <label for="address">Adresse</label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address" 
                        value="<?= htmlspecialchars($user['address'] ?? '') ?>"
                        placeholder="12 rue de la République"
                    >
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="city">Ville</label>
                        <input 
                            type="text" 
                            id="city" 
                            name="city" 
                            value="<?= htmlspecialchars($user['city'] ?? '') ?>"
                            placeholder="Paris"
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="postal_code">Code postal</label>
                        <input 
                            type="text" 
                            id="postal_code" 
                            name="postal_code" 
                            value="<?= htmlspecialchars($user['postal_code'] ?? '') ?>"
                            placeholder="75001"
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="country">Pays</label>
                        <input 
                            type="text" 
                            id="country" 
                            name="country" 
                            value="<?= htmlspecialchars($user['country'] ?? 'France') ?>"
                            placeholder="France"
                        >
                    </div>
                </div>
            </div>
            
            <!-- Boutons -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    Enregistrer les modifications
                </button>
                <a href="/user" class="btn-cancel">Annuler</a>
            </div>
            
        </form>
    </div>
</main>

<?php require_once base_path('view/partials/foot.php'); ?>