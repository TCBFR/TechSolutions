<footer class="main-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>TechSolutions</h3>
                <p>Votre partenaire de confiance en composants informatiques. Innovation, qualité et performance depuis 2020.</p>
            </div>
            <div class="footer-section">
                <h3>Liens rapides</h3>
                <ul>
                    <li><a href="/index.php">Accueil</a></li>
                    <li><a href="/composants.php">Composants</a></li>
                    <li><a href="actualites.php">Actualités</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p><strong>Email:</strong> contact@techsolutions.fr</p>
                <p><strong>Téléphone:</strong> +33 5 23 45 77 18</p>
                <p><strong>Adresse:</strong> 12 rue des Innovateurs, 19100 Brive - La - Gaillarde</p>
            </div>
            <div class="footer-section">
                <h3>Horaires</h3>
                <p>Lundi - Vendredi: 9h00 - 18h00</p>
                <p>Samedi: 10h00 - 16h00</p>
                <p>Dimanche: Fermé</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 TechSolutions. Tous droits réservés.</p>
        </div>
    </div>
</footer>
</body>
</html>

<style>
    .main-footer {
    background: #1f2937;
    color: white;
    padding: 3rem 0 1.5rem;
    margin-top: 5rem;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 3rem;
    margin-bottom: 2rem;
}

.footer-section h3 {
    margin-bottom: 1rem;
    color: var(--accent-color);
    font-size: 1.2rem;
}

.footer-section ul {
    list-style: none;
}

.footer-section ul li {
    margin-bottom: 0.6rem;
}

.footer-section a {
    color: #d1d5db;
    transition: var(--transition);
}

.footer-section a:hover {
    color: var(--accent-color);
    transform: translateX(3px);
    display: inline-block;
}

.footer-section p {
    color: #d1d5db;
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

.footer-bottom {
    text-align: center;
    padding-top: 2rem;
    border-top: 1px solid #374151;
    color: #9ca3af;
}
</style>