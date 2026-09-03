<?php require_once base_path('view/partials/head.php'); ?>

<style>
    .main-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .back-button {
        margin-bottom: 1.5rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
        cursor: pointer;
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #2d3748;
    }

    .btn-secondary:hover {
        background: #cbd5e0;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .form-container {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .form-header {
        margin-bottom: 2rem;
    }

    .form-header h1 {
        font-size: 2rem;
        color: #2d3748;
        margin: 0 0 0.5rem 0;
    }

    .form-header p {
        color: #718096;
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .form-input,
    .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s;
        font-family: inherit;
    }

    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-textarea {
        min-height: 200px;
        resize: vertical;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .alert-error {
        background: #fed7d7;
        color: #742a2a;
        border-left: 4px solid #fc8181;
    }

    .error-list {
        margin: 0.5rem 0 0 0;
        padding-left: 1.5rem;
    }
</style>

<div class="main-container">
    <div class="back-button">
        <a href="/actualites" class="btn btn-secondary">← Retour aux actualités</a>
    </div>

    <div class="form-container">
        <div class="form-header">
            <h1>✏️ Modifier l'actualité</h1>
            <p>Mettez à jour les informations de votre actualité</p>
        </div>

        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                ❌ Erreurs de validation :
                <ul class="error-list">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <form method="POST" action="/actualites/<?= $actualite['id'] ?>/update">
            <div class="form-group">
                <label for="titre" class="form-label">Titre *</label>
                <input 
                    type="text" 
                    id="titre" 
                    name="titre" 
                    class="form-input" 
                    placeholder="Ex: Nouvelle version de notre application"
                    value="<?= htmlspecialchars($_SESSION['old']['titre'] ?? $actualite['titre']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="auteur" class="form-label">Auteur</label>
                <input 
                    type="text" 
                    id="auteur" 
                    name="auteur" 
                    class="form-input" 
                    placeholder="Nom de l'auteur"
                    value="<?= htmlspecialchars($_SESSION['old']['auteur'] ?? $actualite['auteur']) ?>"
                >
            </div>

            <div class="form-group">
                <label for="image" class="form-label">URL de l'image (optionnel)</label>
                <input 
                    type="url" 
                    id="image" 
                    name="image" 
                    class="form-input" 
                    placeholder="https://exemple.com/image.jpg"
                    value="<?= htmlspecialchars($_SESSION['old']['image'] ?? $actualite['image']) ?>"
                >
            </div>

            <div class="form-group">
                <label for="contenu" class="form-label">Contenu *</label>
                <textarea 
                    id="contenu" 
                    name="contenu" 
                    class="form-textarea" 
                    placeholder="Rédigez votre actualité ici..."
                    required
                ><?= htmlspecialchars($_SESSION['old']['contenu'] ?? $actualite['contenu']) ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    ✅ Mettre à jour
                </button>
                <a href="/actualites" class="btn btn-secondary">
                    ❌ Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<?php 
unset($_SESSION['old']); 
require_once base_path('view/partials/foot.php'); 
?>