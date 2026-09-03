<?php

session_start();

// Charger les classes nécessaires
require_once base_path('Core/Database.php');

// Charger la configuration si nécessaire
if (file_exists(base_path('config/config.php'))) {
    require_once base_path('config/config.php');
}

require_once base_path('view/partials/head.php');

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $sujet = htmlspecialchars(trim($_POST['sujet']));
    $messageContent = htmlspecialchars(trim($_POST['message']));
    
    if (!empty($nom) && !empty($email) && !empty($sujet) && !empty($messageContent)) {
        // Validation de l'email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Ici vous pouvez ajouter l'envoi d'email ou l'enregistrement en base
            $message = 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.';
            $messageType = 'success';
        } else {
            $message = 'Veuillez entrer une adresse email valide.';
            $messageType = 'error';
        }
    } else {
        $message = 'Veuillez remplir tous les champs du formulaire.';
        $messageType = 'error';
    }
}
?>
<body>
    <main class="container">
        <section class="contact-section">
            <div class="contact-header">
                <h1>Contactez-nous</h1>
                <p class="contact-intro">Nous sommes à votre écoute. N'hésitez pas à nous contacter pour toute question ou demande d'information.</p>
            </div>
            
            <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $messageType ?>">
                <?= $message ?>
            </div>
            <?php endif; ?>
            
            <div class="contact-container">
                <div class="contact-form-wrapper">
                    <h2>Envoyez-nous un message</h2>
                    <form class="contact-form" method="POST" action="contact.php">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom">Nom *</label>
                                <input type="text" id="nom" name="nom" placeholder="Nom"required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" placeholder="adc@gmail.com" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="sujet">Sujet *</label>
                            <input type="text" id="sujet" name="sujet" placeholder="Objet" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" rows="8" placeholder="Votre message..." required></textarea>
                        </div>
                        
                        <button type="submit" class="btn-submit">
                            <span>Envoyer le message</span>
                        </button>
                    </form>
                </div>
                
                <div class="contact-info-wrapper">
                    <h2>Informations de contact</h2>
                    <div class="contact-info">
                        <div class="info-item">
                            <div class="info-icon">📍</div>
                            <div class="info-content">
                                <strong>Adresse</strong>
                                <p>12 rue des Innovateurs<br>19100 Brive - La - Gaillarde, France</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">📧</div>
                            <div class="info-content">
                                <strong>Email</strong>
                                <p><a href="mailto:contact@techsolutions.fr">contact@techsolutions.fr</a></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">📞</div>
                            <div class="info-content">
                                <strong>Téléphone</strong>
                                <p><a href="tel:+33123456789">+33 5 63 85 97 28</a></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">🕒</div>
                            <div class="info-content">
                                <strong>Horaires d'ouverture</strong>
                                <p>Lundi - Vendredi: 9h00 - 18h00<br>
                                Samedi: 10h00 - 16h00<br>
                                Dimanche: Fermé</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php require_once '../view/partials/foot.php'; ?>

    <style>
.contact-section {
    padding: 3rem 0;
}

.contact-header {
    text-align: center;
    margin-bottom: 3rem;
}

.contact-header h1 {
    font-size: 2.5rem;
    color: #17181c;
    margin-bottom: 1rem;
}

.contact-intro {
    font-size: 1.1rem;
    color: #17181c;
    max-width: 700px;
    margin: 0 auto;
}

.contact-container {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 3rem;
    margin-top: 2rem;
}

.contact-form-wrapper,
.contact-info-wrapper {
    background: white;
    padding: 2.5rem;
    border-radius: 12px;
    box-shadow: var(--shadow-md);
}

.contact-form-wrapper h2,
.contact-info-wrapper h2 {
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    color: var(--text-color);
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}
.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 0.9rem;
    border: 1px solid lightgray;
    border-radius: 8px;
    font-size: 1rem;
    transition: var(--transition);
    font-family: inherit;
}

.form-group textarea{
    margin-top:10px;
}
.btn-submit{
    width: 100%;
    padding: 1rem;
    background: lightgray;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    transition: var(--transition);
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    margin-left:45px;
    margin-top:20px;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.contact-info {
    margin-bottom: 2rem;
}

.info-item {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.info-item:last-child {
    border-bottom: none;
}

.info-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
}

.info-content strong {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-color);
    font-size: 1.1rem;
}

.info-content p {
    color: var(--text-light);
    line-height: 1.6;
}

.info-content a {
    color: var(--primary-color);
    transition: var(--transition);
}

.info-content a:hover {
    text-decoration: underline;
}
</style>

