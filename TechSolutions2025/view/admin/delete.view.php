<?php require_once base_path('view/partials/head.php'); ?>

<style>
    .delete-container {
        max-width: 600px;
        margin: 4rem auto;
        padding: 2rem;
    }

    .delete-card {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        text-align: center;
    }

    .warning-icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
    }

    .delete-card h1 {
        font-size: 2rem;
        color: #2d3748;
        margin-bottom: 1rem;
    }

    .delete-card p {
        color: #718096;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .pc-info {
        background: #fff5f5;
        padding: 1.5rem;
        border-radius: 15px;
        border-left: 4px solid #fc8181;
        margin-bottom: 2rem;
        text-align: left;
    }

    .pc-info strong {
        color: #2d3748;
        display: block;
        margin-bottom: 0.5rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .btn {
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 1rem;
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #2d3748;
    }

    .btn-delete {
        background: #fc8181;
        color: white;
    }

    .warning-text {
        background: #fef5e7;
        padding: 1rem;
        border-radius: 10px;
        border-left: 4px solid #f39c12;
        color: #7d6608;
        font-size: 0.875rem;
        margin-bottom: 2rem;
    }
</style>

<div class="delete-container">
    <div class="delete-card">
        <div class="warning-icon">⚠️</div>
        
        <h1>Confirmer la suppression</h1>
        <p>Êtes-vous sûr de vouloir supprimer ce PC ?</p>

        <div class="pc-info">
            <strong>PC à supprimer :</strong>
            <span>📌 <?= htmlspecialchars($PC['nom']) ?></span><br>
            <strong>Service :</strong>
            <span><?= htmlspecialchars($PC['service']) ?></span><br>
            <strong>ID :</strong>
            <span>#<?= $id ?></span>
        </div>

        <div class="warning-text">
            ⚠️ <strong>Attention :</strong> Tous les composants associés seront supprimés.
        </div>

        <form method="POST" action="/mes-pcs/<?= $id ?>/destroy">
            <input type="hidden" name="id" value="<?= $id ?>">
            
            <div class="form-actions">
                <a href="/mes-pcs/<?= $id ?>" class="btn btn-cancel">← Annuler</a>
                <button type="submit" class="btn btn-delete">Supprimer</button>
            </div>
        </form>
    </div>
</div>

<?php require_once base_path('view/partials/foot.php'); ?>