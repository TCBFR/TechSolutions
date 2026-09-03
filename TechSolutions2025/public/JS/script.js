
function openModal(id) {
    window.location.href = 'composants.php?id=' + id;
}

function closeModal() {
    window.location.href = 'composants.php';
}

window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target == modal) {
        closeModal();
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// Fonction pour afficher le formulaire d'ajout
function showAddForm() {
    document.getElementById('addForm').style.display = 'block';
    document.getElementById('formTitle').textContent = 'Ajouter un composant';
    document.getElementById('composant_id').value = '';
    document.querySelector('form').reset();
    
    // Scroll vers le formulaire
    document.getElementById('addForm').scrollIntoView({ behavior: 'smooth' });
}

// Fonction pour masquer le formulaire
function hideAddForm() {
    document.getElementById('addForm').style.display = 'none';
    document.querySelector('form').reset();
    document.getElementById('composant_id').value = '';
}

// Fonction pour éditer un composant
function editComposant(composant) {
    // Afficher le formulaire
    document.getElementById('addForm').style.display = 'block';
    document.getElementById('formTitle').textContent = 'Modifier le composant';
    
    // Remplir les champs du formulaire
    document.getElementById('composant_id').value = composant.id;
    document.getElementById('nom').value = composant.nom;
    document.getElementById('description').value = composant.description;
    document.getElementById('prix').value = composant.prix;
    document.getElementById('image').value = composant.image;
    document.getElementById('categorie').value = composant.categorie;
    document.getElementById('specifications').value = composant.specifications;
    
    // Scroll vers le formulaire
    document.getElementById('addForm').scrollIntoView({ behavior: 'smooth' });
}

// Fonction pour supprimer un composant
function deleteComposant(id, nom) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer "${nom}" ?`)) {
        window.location.href = `admin_dashboard.php?delete=${id}`;
    }
}

// Fermer automatiquement les alertes après 5 secondes
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });
});
/*======================================Login=====================================*/
function showAdminCard() {
    const userCard = document.getElementById('user-card');
    const adminCard = document.getElementById('admin-card');
    
    userCard.style.animation = 'slideOut 0.4s ease forwards';
    setTimeout(() => {
        userCard.style.display = 'none';
        adminCard.style.display = 'block';
        adminCard.style.animation = 'slideIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        document.getElementById('username-admin').focus();
    }, 400);
}

function showUserCard() {
    const userCard = document.getElementById('user-card');
    const adminCard = document.getElementById('admin-card');
    
    adminCard.style.animation = 'slideOut 0.4s ease forwards';
    setTimeout(() => {
        adminCard.style.display = 'none';
        userCard.style.display = 'block';
        userCard.style.animation = 'slideIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        document.getElementById('username-user').focus();
    }, 400);
}
document.addEventListener('DOMContentLoaded', function() {
    showAdminCard();
});