<?php require_once base_path('view/partials/head.php'); ?>

<style>
    .pc-container {
        max-width: 1200px;
        margin: 3rem auto;
        padding: 2rem;
    }

    .pc-card {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .pc-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .pc-info h1 {
        font-size: 2.5rem;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .pc-info .badge {
        display: inline-block;
        background: #667eea;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .pc-actions {
        display: flex;
        gap: 1rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-edit {
        background: #667eea;
        color: white;
    }

    .btn-edit:hover {
        background: #5568d3;
        transform: translateY(-2px);
    }

    .btn-delete {
        background: #fc8181;
        color: white;
    }

    .btn-delete:hover {
        background: #f56565;
        transform: translateY(-2px);
    }

    .btn-back {
        background: #e2e8f0;
        color: #2d3748;
    }

    .btn-back:hover {
        background: #cbd5e0;
    }

    .pc-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .detail-item {
        background: #f7fafc;
        padding: 1.5rem;
        border-radius: 15px;
        border-left: 4px solid lightgray;
    }

    .detail-label {
        font-size: 0.875rem;
        color: #718096;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .detail-value {
        font-size: 1.25rem;
        color: #2d3748;
        font-weight: 700;
    }

    .pc-description {
        background: #f7fafc;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
    }

    .pc-description h3 {
        color: #2d3748;
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .pc-description p {
        color: #4a5568;
        line-height: 1.8;
    }

    .pc-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 15px;
        margin-bottom: 2rem;
    }

    .composants-section {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .composants-section h2 {
        font-size: 2rem;
        color: #2d3748;
        margin-bottom: 2rem;
        text-align: center;
    }

    .composants-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .composant-card {
        background: #f7fafc;
        padding: 1.5rem;
        border-radius: 15px;
        border-left: 4px solid lightgray;
    }

    .composant-category {
        font-size: 0.75rem;
        color: #667eea;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .composant-name {
        font-size: 1.1rem;
        color: #2d3748;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .composant-quantity {
        font-size: 0.875rem;
        color: #718096;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #718096;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
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
</style>

<div class="pc-container">

    <?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
        <div class="alert alert-success">
            ✅ PC modifié avec succès !
        </div>
    <?php endif; ?>

    <div class="pc-card">
        <div class="pc-header">
            <div class="pc-info">
                <h1> <?= htmlspecialchars($PC['nom']) ?></h1>
                <span class="badge">ID: #<?= $id ?></span>
            </div>
            
            <div class="pc-actions">
                <a href="/mes-pcs" class="btn btn-back">← Retour</a>
                <a href="/mes-pcs/<?= $id ?>/edit" class="btn btn-edit"> Modifier</a>
                <a href="/mes-pcs/<?= $id ?>/delete" 
                   class="btn btn-delete" 
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce PC ?')">
                    Supprimer
                </a>
            </div>
        </div>

        <?php if (!empty($PC['image'])): ?>
            <img src="<?= htmlspecialchars($PC['image']) ?>" 
                 alt="<?= htmlspecialchars($PC['nom']) ?>" 
                 class="pc-image">
        <?php endif; ?>

        <div class="pc-details">
            <div class="detail-item">
                <div class="detail-label">Service</div>
                <div class="detail-value"><?= htmlspecialchars($PC['service']) ?></div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Effectif</div>
                <div class="detail-value"><?= htmlspecialchars($PC['effectif']) ?> personnes</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Date d'ajout</div>
                <div class="detail-value">
                    <?= date('d/m/Y', strtotime($PC['date_ajout'])) ?>
                </div>
            </div>
        </div>

        <?php if (!empty($PC['description'])): ?>
            <div class="pc-description">
                <h3>Description</h3>
                <p><?= nl2br(htmlspecialchars($PC['description'])) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <div class="composants-section">
        <h2>Composants du PC</h2>
        
        <?php if (!empty($composants)): ?>
            <div class="composants-grid">
                <?php foreach ($composants as $composant): ?>
                    <div class="composant-card">
                        <div class="composant-category">
                            <?= htmlspecialchars($composant['categorie_nom'] ?? 'Non catégorisé') ?>
                        </div>
                        <div class="composant-name">
                            <?= htmlspecialchars($composant['nom']) ?>
                        </div>
                        <div class="composant-quantity">
                            Quantité: <?= htmlspecialchars($composant['quantite']) ?>
                        </div>
                        <?php if (!empty($composant['specifications'])): ?>
                            <div style="font-size: 0.875rem; color: #718096; margin-top: 0.5rem;">
                                <?= htmlspecialchars($composant['specifications']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <p>Aucun composant n'a encore été ajouté à ce PC</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once base_path('view/partials/foot.php'); ?>