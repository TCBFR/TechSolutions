<?php

session_start();

// Charger les classes nécessaires
require_once base_path('Core/Database.php');

// Charger la configuration si nécessaire
if (file_exists(base_path('config/config.php'))) {
    require_once base_path('config/config.php');
}

require_once base_path('view/partials/head.php');

// Connexion à la base de données
$pdo = \Core\Database::getInstance()->getConnection();

$showModal = false;
$pc = null;

// Si un ID est passé en paramètre, récupérer le PC avec ses détails
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // CORRECTION: pc au lieu de pcs
    $stmt = $pdo->prepare("SELECT * FROM pc WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $pc = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($pc) {
        $showModal = true;
        
        // Récupérer les composants du PC
        // CORRECTION: pc_composant, composant, categorie + id_pc, id_composant, id_categorie
        $stmt = $pdo->prepare("
            SELECT c.*, pcc.quantite, cat.nom as categorie_nom
            FROM pc_composant pcc
            JOIN composant c ON pcc.id_composant = c.id
            LEFT JOIN categorie cat ON c.id_categorie = cat.id
            WHERE pcc.id_pc = ?
            ORDER BY cat.nom, c.nom
        ");
        $stmt->execute([$_GET['id']]);
        $pc['composants'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Récupérer tous les PC
// CORRECTION: pc au lieu de pcs
$stmt = $pdo->prepare("SELECT * FROM pc ORDER BY id DESC");
$stmt->execute();
$pcs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<style>
    .composants-section {
        padding: 2rem 0;
    }
    
    .composants-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .composants-header h1 {
        font-size: 2.5rem;
        color: #2c3e50;
        margin-bottom: 1rem;
    }
    
    .composants-header p {
        font-size: 1.2rem;
        color: #7f8c8d;
    }
    
    .pc-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 35px;
        margin-top: 40px;
    }
    
    .pc-card {
        background: white;
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.4s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }
    
    .pc-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        border-color: #667eea;
    }
    
    .pc-card-image {
        width: 100%;
        height: 280px;
        overflow: hidden;
        position: relative;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .pc-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .pc-card-image .placeholder {
        color: white;
        font-size: 5rem;
    }
    
    .pc-card:hover .pc-card-image img {
        transform: scale(1.15);
    }
    
    .pc-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.95);
        color: #667eea;
        padding: 10px 20px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .pc-effectif-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.95);
        color: #27ae60;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .pc-card-content {
        padding: 30px;
    }
    
    .pc-card-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
        line-height: 1.3;
    }
    
    .pc-card-service {
        color: #667eea;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .pc-card-description {
        color: #7f8c8d;
        font-size: 15px;
        line-height: 1.7;
        margin-bottom: 25px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .pc-view-btn {
        background: #667eea;
        color: white;
        border: none;
        padding: 14px 30px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .pc-view-btn:hover {
        background: #5568d3;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    
    .no-pc-message {
        text-align: center;
        padding: 80px 40px;
        background: white;
        border-radius: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .no-pc-message h2 {
        color: #7f8c8d;
        margin-bottom: 15px;
        font-size: 2rem;
    }
    
    .no-pc-message p {
        color: #95a5a6;
        font-size: 1.1rem;
    }
    
    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.75);
        align-items: center;
        justify-content: center;
        padding: 20px;
        overflow-y: auto;
    }
    
    .modal-content {
        background: white;
        border-radius: 25px;
        max-width: 1300px;
        width: 100%;
        max-height: 95vh;
        overflow-y: auto;
        position: relative;
        animation: modalSlideIn 0.4s ease;
        margin: 20px auto;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-60px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .modal-close {
        position: sticky;
        top: 25px;
        right: 25px;
        float: right;
        font-size: 35px;
        font-weight: bold;
        color: #95a5a6;
        cursor: pointer;
        z-index: 1001;
        background: white;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        transition: all 0.3s;
    }
    
    .modal-close:hover {
        color: #e74c3c;
        transform: rotate(90deg);
        box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3);
    }
    
    .modal-header {
        padding: 40px 50px 30px;
        clear: both;
        border-bottom: 3px solid #f0f0f0;
    }
    
    .modal-pc-title {
        font-size: 38px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
    }
    
    .modal-info-badges {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }
    
    .modal-pc-service {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 25px;
        border-radius: 25px;
        font-size: 15px;
        font-weight: 700;
    }
    
    .modal-pc-effectif {
        display: inline-block;
        background: #e8f5e9;
        color: #2e7d32;
        padding: 10px 25px;
        border-radius: 25px;
        font-size: 15px;
        font-weight: 700;
    }
    
    .modal-body {
        padding: 40px 50px 50px;
    }
    
    .modal-section {
        margin-bottom: 45px;
    }
    
    .modal-section-title {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 15px;
        border-bottom: 3px solid #667eea;
    }
    
    .modal-description {
        color: #5a6c7d;
        font-size: 17px;
        line-height: 1.8;
        background: #f8f9fa;
        padding: 25px;
        border-radius: 15px;
        border-left: 5px solid #667eea;
    }
    
    .components-grid-modal {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }
    
    .component-card-modal {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 15px;
        border-left: 5px solid #667eea;
        transition: all 0.3s;
        position: relative;
    }
    
    .component-card-modal:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .component-category-badge {
        display: inline-block;
        background: #667eea;
        color: white;
        padding: 6px 14px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .component-card-title {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
        line-height: 1.4;
    }
    
    .component-specs {
        font-size: 14px;
        color: #7f8c8d;
        line-height: 1.6;
        margin-bottom: 12px;
    }
    
    .component-qty-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }
    
    .no-components-message {
        text-align: center;
        padding: 40px;
        background: #fff3cd;
        border-radius: 15px;
        color: #856404;
        font-size: 16px;
    }
</style>

<main class="container">
    <section class="composants-section">
        <div class="composants-header">
            <h1>Nos PC Configurés</h1>
            <p>Découvrez nos configurations professionnelles adaptées à chaque service</p>
        </div>
        
        <?php if (empty($pcs)): ?>
            <div class="no-pc-message">
                <h2>🖥️ Aucun PC disponible</h2>
                <p>Nos configurations seront bientôt disponibles</p>
            </div>
        <?php else: ?>
            <div class="pc-grid">
                <?php foreach ($pcs as $pcItem): ?>
                <div class="pc-card" onclick="openModal(<?= $pcItem['id'] ?>)">
                    <div class="pc-card-image">
                        <?php if (!empty($pcItem['image'])): ?>
                            <img src="<?= htmlspecialchars($pcItem['image']) ?>" alt="<?= htmlspecialchars($pcItem['nom']) ?>">
                        <?php else: ?>
                            <span class="placeholder">🖥️</span>
                        <?php endif; ?>
                        <div class="pc-badge"><?= htmlspecialchars($pcItem['service']) ?></div>
                        <div class="pc-effectif-badge">👥 <?= $pcItem['effectif'] ?> postes</div>
                    </div>
                    <div class="pc-card-content">
                        <h3 class="pc-card-title"><?= htmlspecialchars($pcItem['nom']) ?></h3>
                        <p class="pc-card-service"><?= htmlspecialchars($pcItem['service']) ?></p>
                        <p class="pc-card-description"><?= htmlspecialchars($pcItem['description']) ?></p>
                       
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<!-- Modal de détail PC -->
<div id="productModal" class="modal" style="<?= $showModal ? 'display:flex;' : 'display:none;' ?>">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        
        <?php if ($showModal && $pc): ?>
        <div class="modal-header">
            <h2 class="modal-pc-title"><?= htmlspecialchars($pc['nom']) ?></h2>
            <div class="modal-info-badges">
                <span class="modal-pc-service">📂 <?= htmlspecialchars($pc['service']) ?></span>
                <span class="modal-pc-effectif">👥 <?= $pc['effectif'] ?> postes</span>
            </div>
        </div>
        
        <div class="modal-body">
            <!-- Description -->
            <div class="modal-section">
                <div class="modal-section-title">Description</div>
                <div class="modal-description"><?= nl2br(htmlspecialchars($pc['description'])) ?></div>
            </div>
            
            <!-- Composants -->
            <div class="modal-section">
                <div class="modal-section-title">
                    Configuration matérielle complète
                </div>
                <?php if (!empty($pc['composants'])): ?>
                    <div class="components-grid-modal">
                        <?php foreach ($pc['composants'] as $comp): ?>
                        <div class="component-card-modal">
                            <span class="component-category-badge">
                                <?= htmlspecialchars($comp['categorie_nom'] ?? 'Composant') ?>
                            </span>
                            <div class="component-card-title"><?= htmlspecialchars($comp['nom']) ?></div>
                            <?php if (!empty($comp['specifications'])): ?>
                                <div class="component-specs">
                                    🔌 <?= nl2br(htmlspecialchars($comp['specifications'])) ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($comp['description'])): ?>
                                <div class="component-specs">
                                    💡 <?= htmlspecialchars($comp['description']) ?>
                                </div>
                            <?php endif; ?>
                            <span class="component-qty-badge">Quantité: <?= $comp['quantite'] ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-components-message">
                        ⚠️ Aucun composant associé à cette configuration
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function openModal(pcId) {
    window.location.href = '/pcs?id=' + pcId;
}

function closeModal() {
    window.location.href = '/pcs';
}

// Fermer le modal si on clique en dehors
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target == modal) {
        closeModal();
    }
}

// Fermer avec la touche Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('productModal');
        if (modal.style.display === 'flex') {
            closeModal();
        }
    }
});
</script>

<?php require_once base_path('view/partials/foot.php'); ?>