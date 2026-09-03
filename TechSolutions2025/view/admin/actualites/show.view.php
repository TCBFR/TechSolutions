<?php require_once base_path('view/partials/head.php'); ?>

<style>
    .main-container {
        max-width: 1000px;
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

    .btn-edit {
        background: #667eea;
        color: white;
    }

    .btn-delete {
        background: #fc8181;
        color: white;
    }

    .article-container {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .article-image {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8rem;
        color: white;
    }

    .article-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .article-content {
        padding: 3rem;
    }

    .article-header {
        margin-bottom: 2rem;
    }

    .article-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0 0 1rem 0;
        line-height: 1.2;
    }

    .article-meta {
        display: flex;
        gap: 2rem;
        color: #718096;
        font-size: 1rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .article-body {
        font-size: 1.125rem;
        line-height: 1.8;
        color: #4a5568;
        margin: 2rem 0;
        white-space: pre-wrap;
    }

    .article-actions {
        display: flex;
        gap: 1rem;
        padding-top: 2rem;
        border-top: 2px solid #e2e8f0;
    }
</style>

<div class="main-container">
    <div class="back-button">
        <a href="/actualites" class="btn btn-secondary">← Retour aux actualités</a>
    </div>

    <article class="article-container">
        <div class="article-image">
            <?php if (!empty($actualite['image'])): ?>
                <img src="<?= htmlspecialchars($actualite['image']) ?>" 
                     alt="<?= htmlspecialchars($actualite['titre']) ?>">
            <?php else: ?>
                📰
            <?php endif; ?>
        </div>

        <div class="article-content">
            <div class="article-header">
                <h1 class="article-title">
                    <?= htmlspecialchars($actualite['titre']) ?>
                </h1>

                <div class="article-meta">
                    <span>👤 Par <?= htmlspecialchars($actualite['auteur']) ?></span>
                    <span>📅 Publié le <?= date('d/m/Y à H:i', strtotime($actualite['date_publication'])) ?></span>
                </div>
            </div>

            <div class="article-body">
                <?= nl2br(htmlspecialchars($actualite['contenu'])) ?>
            </div>

            <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
            <div class="article-actions">
                <a href="/actualites/<?= $actualite['id'] ?>/edit" class="btn btn-edit">
                    ✏️ Modifier
                </a>
                <a href="/actualites/<?= $actualite['id'] ?>/delete" 
                   class="btn btn-delete"
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')">
                    🗑️ Supprimer
                </a>
            </div>
            <?php endif; ?>
        </div>
    </article>
</div>

<?php require_once base_path('view/partials/foot.php'); ?>