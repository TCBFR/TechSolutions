<?php require_once base_path('view/partials/head.php'); ?>

<style>
    .form-container {
        max-width: 1400px;
        margin: 3rem auto;
        padding: 2rem;
    }

    .form-card {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .form-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-header h1 {
        font-size: 2rem;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .form-sections {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 2rem;
    }

    .form-section h2 {
        font-size: 1.5rem;
        color: #2d3748;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }

    .error {
        color: #fc8181;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Composants section */
    .composants-section {
        grid-column: 1 / -1;
        background: #f7fafc;
        padding: 2rem;
        border-radius: 15px;
        margin-top: 2rem;
    }

    .composants-section h2 {
        font-size: 1.5rem;
        color: #2d3748;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .category-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        border-left: 4px solid lightgray;
    }

    .category-card h3 {
        color: #667eea;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        text-transform: uppercase;
        font-weight: 700;
    }

    .composant-checkbox {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-radius: 8px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .composant-checkbox:hover {
        background: #f7fafc;
    }

    .composant-checkbox.selected {
        background: #ebf8ff;
        border: 1px solid lightgray;
    }

    .composant-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-right: 0.75rem;
        cursor: pointer;
    }

    .composant-label {
        flex: 1;
        cursor: pointer;
    }

    .composant-name {
        font-weight: 600;
        color: #2d3748;
        display: block;
        margin-bottom: 0.25rem;
    }

    .composant-specs {
        font-size: 0.875rem;
        color: #718096;
    }

    .quantity-input {
        width: 60px;
        padding: 0.25rem 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        text-align: center;
        margin-left: 0.5rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
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

    .btn-primary {
        background: lightgray;
        color: white;
        flex: 1;
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

    @media (max-width: 768px) {
        .form-sections {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h1>Modifier le PC</h1>
            <p>Modifiez la configuration #<?= $id ?></p>
        </div>

        <form method="POST" action="/mes-pcs/<?= $id ?>/update">
            <input type="hidden" name="id" value="<?= $id ?>">
            
            <div class="form-sections">
                
                <!-- Section Informations principales -->
                <div class="form-section">
                    <h2>Informations principales</h2>
                    
                    <div class="form-group">
                        <label for="nom">Nom du PC *</label>
                        <input 
                            type="text" 
                            id="nom" 
                            name="nom" 
                            value="<?= htmlspecialchars($PC['nom'] ?? '') ?>"
                            required
                        >
                        <?php if (isset($errors['nom'])): ?>
                            <div class="error"><?= $errors['nom'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="service">Service *</label>
                        <input 
                            type="text" 
                            id="service" 
                            name="service" 
                            value="<?= htmlspecialchars($PC['service'] ?? '') ?>"
                            required
                        >
                        <?php if (isset($errors['service'])): ?>
                            <div class="error"><?= $errors['service'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="effectif">Effectif *</label>
                        <input 
                            type="number" 
                            id="effectif" 
                            name="effectif" 
                            value="<?= htmlspecialchars($PC['effectif'] ?? '1') ?>"
                            min="0"
                            required
                        >
                        <?php if (isset($errors['effectif'])): ?>
                            <div class="error"><?= $errors['effectif'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="image">URL de l'image</label>
                        <input 
                            type="url" 
                            id="image" 
                            name="image" 
                            value="<?= htmlspecialchars($PC['image'] ?? '') ?>"
                        >
                        <?php if (isset($errors['image'])): ?>
                            <div class="error"><?= $errors['image'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Section Description -->
                <div class="form-section">
                    <h2>Description</h2>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea 
                            id="description" 
                            name="description"
                            style="min-height: 280px;"
                        ><?= htmlspecialchars($PC['description'] ?? '') ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                            <div class="error"><?= $errors['description'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Section Composants -->
            <div class="composants-section">
                <h2>Gérer les composants</h2>
                
                <div class="categories-grid">
                    <?php 
                    // Créer un tableau des IDs de composants déjà associés avec leur quantité
                    $composantsActuels = [];
                    foreach ($composantsPC as $comp) {
                        $composantsActuels[$comp['id_composant']] = $comp['quantite'];
                    }
                    
                    // Grouper les composants par catégorie
                    $composantsParCategorie = [];
                    foreach ($tousComposants as $composant) {
                        $categorie = $composant['categorie_nom'] ?? 'Non catégorisé';
                        if (!isset($composantsParCategorie[$categorie])) {
                            $composantsParCategorie[$categorie] = [];
                        }
                        $composantsParCategorie[$categorie][] = $composant;
                    }
                    ?>
                    
                    <?php foreach ($composantsParCategorie as $categorie => $comps): ?>
                        <div class="category-card">
                            <h3>📦 <?= htmlspecialchars($categorie) ?></h3>
                            
                            <?php foreach ($comps as $comp): ?>
                                <?php 
                                $isSelected = isset($composantsActuels[$comp['id']]);
                                $quantite = $isSelected ? $composantsActuels[$comp['id']] : 1;
                                ?>
                                <div class="composant-checkbox <?= $isSelected ? 'selected' : '' ?>">
                                    <input 
                                        type="checkbox" 
                                        name="composants[]" 
                                        value="<?= $comp['id'] ?>"
                                        id="comp_<?= $comp['id'] ?>"
                                        <?= $isSelected ? 'checked' : '' ?>
                                        onchange="toggleQuantity(<?= $comp['id'] ?>)"
                                    >
                                    <label for="comp_<?= $comp['id'] ?>" class="composant-label">
                                        <span class="composant-name"><?= htmlspecialchars($comp['nom']) ?></span>
                                        <?php if (!empty($comp['specifications'])): ?>
                                            <span class="composant-specs"><?= htmlspecialchars($comp['specifications']) ?></span>
                                        <?php endif; ?>
                                    </label>
                                    <input 
                                        type="number" 
                                        name="quantites[<?= $comp['id'] ?>]" 
                                        value="<?= $quantite ?>" 
                                        min="1" 
                                        class="quantity-input" 
                                        id="qty_<?= $comp['id'] ?>"
                                        <?= $isSelected ? '' : 'disabled' ?>
                                    >
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-actions">
                <a href="/mes-pcs/<?= $id ?>" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleQuantity(id) {
    const checkbox = document.getElementById('comp_' + id);
    const quantity = document.getElementById('qty_' + id);
    const container = checkbox.closest('.composant-checkbox');
    
    quantity.disabled = !checkbox.checked;
    
    if (checkbox.checked) {
        container.classList.add('selected');
        if (quantity.value < 1) quantity.value = 1;
    } else {
        container.classList.remove('selected');
        quantity.value = 1;
    }
}
</script>

<?php require_once base_path('view/partials/foot.php'); ?>