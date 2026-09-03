<?php

session_start();

// Charger les classes nécessaires
require_once base_path('Core/Database.php');

// Charger la configuration si nécessaire
if (file_exists(base_path('config/config.php'))) {
    require_once base_path('config/config.php');
}

require_once base_path('view/partials/head.php');

// Redirection si déjà connecté
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: /user');
    exit;
}

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: /admin');
    exit;
}

$error = '';

// TRAITEMENT DU FORMULAIRE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $login_type = $_POST['login_type'] ?? 'user';
    
    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        
        if ($login_type === 'admin') {
            // CONNEXION ADMIN
            $admin_username = defined('ADMIN_USERNAME') ? ADMIN_USERNAME : 'admin';
            $admin_password = defined('ADMIN_PASSWORD') ? ADMIN_PASSWORD : 'admin123';
            
            if ($username === $admin_username && $password === $admin_password) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                $_SESSION['login_time'] = time();
                header('Location: /admin');
                exit;
            } else {
                $error = 'Identifiant ou mot de passe administrateur incorrect';
            }
            
        } else {
            // CONNEXION USER
            try {
                $pdo = \Core\Database::getInstance()->getConnection();
                
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
                $stmt->execute([':username' => $username]);
                $user = $stmt->fetch();
                
                if (!$user) {
                    $error = "Identifiant ou mot de passe incorrect";
                } elseif (!password_verify($password, $user['password'])) {
                    $error = 'Identifiant ou mot de passe incorrect';
                } else {
                    // Connexion réussie
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['first_name'] = $user['first_name'] ?? '';
                    $_SESSION['last_name'] = $user['last_name'] ?? '';
                    $_SESSION['login_time'] = time();
                    
                    header('Location: /user');
                    exit;
                }
                
            } catch (PDOException $e) {
                $error = 'Erreur de connexion à la base de données';
            }
        }
    }
}
?>

<style>
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .login-card {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        max-width: 450px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    
    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .login-header h1 {
        font-size: 2rem;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    
    .login-header p {
        color: #7f8c8d;
    }
    
    .switch-buttons {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        background: #f7fafc;
        padding: 0.5rem;
        border-radius: 10px;
    }
    
    .switch-btn {
        flex: 1;
        padding: 0.8rem;
        border: none;
        background: transparent;
        color: #718096;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .switch-btn.active {
        background: white;
        color: #667eea;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    
    .alert-error {
        background: #fee;
        color: #c33;
        border: 1px solid #fcc;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #2c3e50;
        font-weight: 600;
    }
    
    .form-group input {
        width: 100%;
        padding: 0.8rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    
    .form-group input:focus {
        outline: none;
        border-color: #667eea;
    }
    
    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-submit:hover {
        background: #5568d3;
        transform: translateY(-2px);
    }
    
    .login-info {
        margin-top: 2rem;
        padding: 1rem;
        background: #f7fafc;
        border-radius: 8px;
    }
    
    .info-box p {
        margin: 0.5rem 0;
        color: #4a5568;
    }
    
    .info-box code {
        background: #e2e8f0;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        color: #2d3748;
        font-family: monospace;
    }
</style>

<main class="login-container">
    <div class="login-card">
        
        <div class="login-header">
            <h1 id="login-title">Connexion Utilisateur</h1>
            <p id="login-subtitle">Accédez à votre espace personnel</p>
        </div>
        
        <!-- Boutons de basculement -->
        <div class="switch-buttons">
            <button class="switch-btn active" id="btn-user" onclick="switchToUser()">
                👤 Utilisateur
            </button>
            <button class="switch-btn" id="btn-admin" onclick="switchToAdmin()">
                🔐 Admin
            </button>
        </div>
        
        <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <span>⚠️</span>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>
        
        <form class="login-form" method="POST" action="/login">
            <input type="hidden" name="login_type" value="user" id="login_type">
            
            <div class="form-group">
                <label for="username">
                    <span>👤</span> Identifiant
                </label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Entrez votre identifiant" 
                    required 
                    autofocus
                    value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="password">
                    <span>🔒</span> Mot de passe
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Entrez votre mot de passe" 
                    required
                >
            </div>
            
            <button type="submit" class="btn-submit">
                Se connecter
            </button>
        </form>
        
        <div class="login-info" id="login-info">
            <div class="info-box">
                <p><strong>Identifiants de test :</strong></p>
                <p>Identifiant : <code id="test-username">user</code></p>
                <p>Mot de passe : <code id="test-password">password</code></p>
            </div>
        </div>
        
    </div>
</main>

<script>
function switchToUser() {
    document.getElementById('login_type').value = 'user';
    document.getElementById('login-title').textContent = 'Connexion Utilisateur';
    document.getElementById('login-subtitle').textContent = 'Accédez à votre espace personnel';
    document.getElementById('test-username').textContent = 'user';
    document.getElementById('test-password').textContent = 'password';
    document.getElementById('btn-user').classList.add('active');
    document.getElementById('btn-admin').classList.remove('active');
}

function switchToAdmin() {
    document.getElementById('login_type').value = 'admin';
    document.getElementById('login-title').textContent = 'Connexion Admin';
    document.getElementById('login-subtitle').textContent = 'Accédez au tableau de bord de gestion';
    document.getElementById('test-username').textContent = 'admin';
    document.getElementById('test-password').textContent = 'admin123';
    document.getElementById('btn-admin').classList.add('active');
    document.getElementById('btn-user').classList.remove('active');
}

// Si erreur admin, afficher l'onglet admin
<?php if (!empty($error) && isset($_POST['login_type']) && $_POST['login_type'] === 'admin'): ?>
switchToAdmin();
<?php endif; ?>
</script>

<?php require_once base_path('view/partials/foot.php'); ?>