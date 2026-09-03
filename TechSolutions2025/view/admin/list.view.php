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

    .btn-primary {
        background: white;
        color: darkgray;
        font-size: 25px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #2d3748;
    }

    .btn-secondary:hover {
        background: #cbd5e0;
    }

    .btn-edit {
        background: #667eea;
        color: white;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .btn-delete {
        background: #fc8181;
        color: white;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
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

    .pcs-table {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f7fafc;
    }

    th {
        padding: 1rem;
        text-align: left;
        font-weight: 700;
        color: #2d3748;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 1rem;
        border-top: 1px solid #e2e8f0;
        color: #4a5568;
    }

    tbody tr {
        transition: all 0.2s;
    }

    tbody tr:hover {
        background: #f7fafc;
    }

    .pc-name {
        font-weight: 700;
        color: #2d3748;
        font-size: 1.1rem;
    }

    .pc-service {
        color: #718096;
        font-size: 0.875rem;
    }

    .pc-effectif {
        display: inline-block;
        background: #667eea;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .actions {
        display: flex;
        gap: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        font-size: 5rem;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #718096;
        margin-bottom: 2rem;
    }

    .pc-image-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 10px;
    }

    .back-to-dashboard {
        margin-bottom: 1rem;
    }
</style>

<div class="main-container">
    
    <!-- Bouton retour au dashboard -->
    <div class="back-to-dashboard">
        <a href="/admin" class="btn btn-secondary">← Retour</a>
    </div>

    <!-- En-tête de page -->
    <div class="page-header">
        <div>
            <h1>Gestion des PC</h1>
            <p style="color: #718096; margin: 0.5rem 0 0 0;">
                <?= count($pcs) ?> configuration(s) enregistrée(s)
            </p>
        </div>
        <a href="/mes-pcs/create" class="btn btn-primary">
            ➕ Nouveau PC
        </a>
    </div>

    <!-- Messages flash -->
    <?php if (isset($_GET['success']) && $_GET['success'] === 'created'): ?>
        <div class="alert alert-success">
            ✅ PC créé avec succès !
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
        <div class="alert alert-success">
            ✅ PC modifié avec succès !
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
        <div class="alert alert-success">
            ✅ PC supprimé avec succès !
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            ❌ Une erreur est survenue
        </div>
    <?php endif; ?>

    <!-- Tableau des PC -->
    <div class="pcs-table">
        <?php if (empty($pcs)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <h3>Aucun PC configuré</h3>
                <p>Commencez par créer votre première configuration de PC</p>
                <a href="/mes-pcs/create" class="btn btn-primary">
                    ➕ Créer mon premier PC
                </a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Service</th>
                        <th>Effectif</th>
                        <th>Date d'ajout</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pcs as $pc): ?>
                        <tr>
                            <td><strong>#<?= $pc['id'] ?></strong></td>
                            
                            <td>
                                <?php if (!empty($pc['image'])): ?>
                                    <img src="<?= htmlspecialchars($pc['image']) ?>" 
                                         alt="<?= htmlspecialchars($pc['nom']) ?>"
                                         class="pc-image-thumb">
                                <?php else: ?>
                                    <div style="width: 60px; height: 60px; background: #e2e8f0; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                        💻
                                    </div>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <div class="pc-name"><?= htmlspecialchars($pc['nom']) ?></div>
                                <?php if (!empty($pc['description'])): ?>
                                    <div class="pc-service">
                                        <?= htmlspecialchars(substr($pc['description'], 0, 50)) ?>...
                                    </div>
                                <?php endif; ?>
                            </td>
                            
                            <td><?= htmlspecialchars($pc['service']) ?></td>
                            
                            <td>
                                <span class="pc-effectif">
                                    <?= $pc['effectif'] ?> 👥
                                </span>
                            </td>
                            
                            <td><?= date('d/m/Y', strtotime($pc['date_ajout'])) ?></td>
                            
                            <td>
                                <div class="actions">
                                    <a href="/mes-pcs/<?= $pc['id'] ?>" 
                                       class="btn btn-secondary"
                                       style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                        Voir
                                    </a>
                                    <a href="/mes-pcs/<?= $pc['id'] ?>/edit" 
                                     class="btn btn-edit">
                                     Modifier
                                    </a>
                                    <a href="/mes-pcs/<?= $pc['id'] ?>/delete" 
                                       class="btn btn-delete"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce PC ?')">
                                        Supprimer
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once base_path('view/partials/foot.php'); ?>