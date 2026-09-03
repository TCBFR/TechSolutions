<?php

// SUPPRIMEZ cette ligne qui cause le problème :
// require '../Core/Database.php';

require_once base_path('view/partials/head.php');
?>

<style>
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }
    
    .service-card {
        background: white;
        border-radius: 16px;
        padding: 35px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-top: 6px solid lightgray;
    }
    
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.15);
    }
    
    .service-icon {
        font-size: 56px;
        margin-bottom: 20px;
        display: inline-block;
    }
    
    .service-title {
        font-size: 24px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 15px;
    }
    
    .service-effectif {
        display: inline-block;
        background: #667eea;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .service-description {
        color: #7f8c8d;
        line-height: 1.7;
        margin-bottom: 15px;
        font-size: 15px;
    }
    
    .services-section-header {
        text-align: center;
        margin-bottom: 50px;
    }
    
    .services-section-header h2 {
        font-size: 36px;
        color: #2c3e50;
        margin-bottom: 15px;
    }
    
    .services-section-header p {
        color: #7f8c8d;
        font-size: 18px;
        max-width: 700px;
        margin: 0 auto;
    }
    
    .advantages-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }
    
    .advantage-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .advantage-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    
    .advantage-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }
    
    .advantage-card h3 {
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 20px;
    }
    
    .advantage-card p {
        color: #7f8c8d;
        line-height: 1.6;
    }
</style>

<body>
    <main class="container">
        
        <!-- Section Hero -->
        <section class="hero">
            <div class="hero-content">
                <h1>Bienvenue chez TechSolutions</h1>
                <p class="hero-subtitle">Votre partenaire technologique de confiance depuis 2020</p>
            </div>
        </section>
        
        <!-- Section À propos -->
        <section class="content-section">
            <h2>À propos de nous</h2>
            <div class="content-text">
                <p>
                    TechSolutions est une entreprise informatique innovante spécialisée 
                    dans le développement de solutions numériques sur mesure pour les 
                    professionnels et les particuliers. Fondée par une équipe de passionnés 
                    de technologie, elle propose des services variés tels que la création de 
                    sites web, le développement d'applications mobiles, la gestion d'infrastructures 
                    réseaux et la cybersécurité.
                </p>
                
                <p>
                    Grâce à son expertise et à son approche centrée sur le client, TechSolutions
                    accompagne les entreprises dans leur transformation digitale, en alliant 
                    performance, fiabilité et sécurité. Sa mission est d'offrir des outils 
                    technologiques modernes permettant à chaque client d'optimiser ses activités 
                    et de rester compétitif dans un environnement numérique en constante évolution.
                </p>
            </div>
        </section>
        
        <!-- Section Nos Services -->
        <section class="latest-products">
            <div class="services-section-header">
                <h2>Nos Services</h2>
                <p>
                    Des solutions technologiques complètes pour accompagner votre transformation digitale 
                    et optimiser vos processus métiers
                </p>
            </div>
            
            <div class="services-grid">
                
                <!-- Service 1: Développement logiciel -->
                <div class="service-card">
                    <h3 class="service-title">Développement d'application</h3>
                    <p class="service-description">
                    Responsable de la création et de la maintenance des logiciels sur mesure pour les clients.
                    </p>
                </div>
                
                <!-- Service 2: Gestion des infrastructures -->
                <div class="service-card">
                    <h3 class="service-title">Gestion des infrastructures systèmes & réseau</h3>
                    <p class="service-description">
                    Chargé de la mise en place et de l'entretien des infrastructures informatiques, incluant les réseaux
                    et les serveurs.
                    </p>
                </div>
                
                <!-- Service 3: Site web -->
                <div class="service-card">
                    <h3 class="service-title">Site web</h3>
                    <p class="service-description">
                    Création de site web, avec une bonne interface et des fonctionnalités attrayantes.
                    </p>
                </div>
                
                <!-- Service 4: Cyber -->
                <div class="service-card">
                    <h3 class="service-title">Cybersécurité</h3>
                    <p class="service-description">
                    Contrôle des risques d'incidents, de sécurité dans les infrastructure
                    </p>
                </div>
            </div>
        </section>
        
        <!-- Section Avantages -->
        <section class="advantages-section">
            <h2>Pourquoi choisir TechSolutions ?</h2>
            
            <div class="advantages-grid">
                <div class="advantage-card">
                    <div class="advantage-icon">🚀</div>
                    <h3>Réactivité</h3>
                    <p>
                        Déploiement rapide de vos projets avec méthodologie agile et équipe dédiée.
                    </p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">✔</div>
                    <h3>Expertise</h3>
                    <p>
                        Plus de 15 ans d'expérience et certifications reconnues dans tous nos domaines.
                    </p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">💬</div>
                    <h3>Support 24/7</h3>
                    <p>
                        Notre équipe d'experts est disponible jour et nuit pour vous assister.
                    </p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">💰</div>
                    <h3>Prix Transparents</h3>
                    <p>
                        Devis détaillés et tarifs compétitifs sans coûts cachés.
                    </p>
                </div>
            </div>
        </section>
        
    </main>
    
    <?php require_once base_path('view/partials/foot.php'); ?>
    
</body>
</html>