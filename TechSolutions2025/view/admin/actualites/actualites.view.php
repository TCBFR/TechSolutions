<?php require_once base_path('view/partials/head.php'); ?>

<style>
    .main-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .page-header {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header h1 {
        font-size: 2rem;
        color: #2d3748;
        margin: 0;
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
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #2d3748;
    }

    .btn-secondary:hover {
        background: #cbd5e0;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .alert-success {
        background: #c6f6d5;
        color: #22543d;
        border-left: 4px solid #38a169;
    }

    .alert-error {
        background: #fed7d7;
        color: #742a2a;
        border-left: 4px solid #fc8181;
    }

    .actualites-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .actualite-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }

    .actualite-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .actualite-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background-image: url('/img/actualites.jpg');
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: white;
    }

    .actualite-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .actualite-content {
        padding: 1.5rem;
    }

    .actualite-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .actualite-meta {
        display: flex;
        gap: 1rem;
        color: #718096;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .actualite-excerpt {
        color: #4a5568;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .actualite-actions {
        display: flex;
        gap: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    .btn-small {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .btn-edit {
        background: #667eea;
        color: white;
    }

    .btn-delete {
        background: #fc8181;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
    }

    .empty-state-icon {
        font-size: 5rem;
        margin-bottom: 1rem;
    }

    .back-to-dashboard {
        margin-bottom: 1rem;
    }
</style>

<div class="main-container">
    
    <!-- Bouton retour au dashboard -->
    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
    <div class="back-to-dashboard">
        <a href="/admin" class="btn btn-secondary">← Retour</a>
    </div>
    <?php endif; ?>

    <!-- En-tête de page -->
    <div class="page-header">
        <div>
            <h1> Nos Actualités</h1>
            <p style="color: #718096; margin: 0.5rem 0 0 0;">
                <?= count($actualites) ?> actualité(s) publiée(s)
            </p>
        </div>
        <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
        <a href="/actualites/create" class="btn-primary">
            ➕ Nouvelle actualité
        </a>
        <?php endif; ?>
    </div>

    <!-- Messages flash -->
    <?php if (isset($_GET['success']) && $_GET['success'] === 'created'): ?>
        <div class="alert alert-success">
            ✅ Actualité créée avec succès !
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
        <div class="alert alert-success">
            ✅ Actualité modifiée avec succès !
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
        <div class="alert alert-success">
            ✅ Actualité supprimée avec succès !
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            ❌ Une erreur est survenue
        </div>
    <?php endif; ?>

    <!-- Grille des actualités -->
    <?php if (empty($actualites)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h3>Aucune actualité publiée</h3>
            <p>Les actualités apparaîtront ici une fois publiées</p>
            <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
            <a href="/actualites/create" class="btn btn-primary">
                ➕ Créer ma première actualité
            </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="actualites-grid">
            <?php foreach ($actualites as $actu): ?>
                <div class="actualite-card">
                    <div class="actualite-image">
                        <?php if (!empty($actu['image'])): ?>
                            <img src="<?= htmlspecialchars($actu['image']) ?>" 
                                 alt="<?= htmlspecialchars($actu['titre']) ?>">
                        <?php else: ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="actualite-content">
                        <h3 class="actualite-title">
                            <?= htmlspecialchars($actu['titre']) ?>
                        </h3>
                        
                        <div class="actualite-meta">
                            <span>👤 <?= htmlspecialchars($actu['auteur']) ?></span>
                            <span>📅 <?= date('d/m/Y', strtotime($actu['date_publication'])) ?></span>
                        </div>
                        
                        <div class="actualite-excerpt">
                            <?= htmlspecialchars(substr($actu['contenu'], 0, 150)) ?>...
                        </div>
                        
                        <div class="actualite-actions">
                            <a href="/actualites/<?= $actu['id'] ?>" 
                               class="btn btn-secondary btn-small">
                                Lire
                            </a>
                            
                            <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                            <a href="/actualites/<?= $actu['id'] ?>/edit" 
                               class="btn btn-edit btn-small">
                                Modifier
                            </a>
                            <a href="/actualites/<?= $actu['id'] ?>/delete" 
                               class="btn btn-delete btn-small"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')">
                                Supprimer
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once base_path('view/partials/foot.php'); ?>