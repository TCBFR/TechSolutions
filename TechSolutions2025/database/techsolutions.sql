DROP DATABASE IF EXISTS techsolutions;

CREATE DATABASE techsolutions CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techsolutions;

-- Table catégorie (singulier)
CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table composant (singulier)
CREATE TABLE composant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(500),
    id_categorie INT,
    specifications TEXT,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pc (singulier)
CREATE TABLE pc (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    service VARCHAR(255) NOT NULL,
    effectif INT NOT NULL DEFAULT 0,
    description TEXT,
    image VARCHAR(500),
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pc_composant (singulier)
CREATE TABLE pc_composant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pc INT NOT NULL,
    id_composant INT NOT NULL,
    quantite INT DEFAULT 1,
    FOREIGN KEY (id_pc) REFERENCES pc(id) ON DELETE CASCADE,
    FOREIGN KEY (id_composant) REFERENCES composant(id) ON DELETE CASCADE,
    UNIQUE KEY unique_pc_composant (id_pc, id_composant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    phone VARCHAR(20),
    address VARCHAR(255),
    city VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100) DEFAULT 'France',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Créer la table actualite
CREATE TABLE IF NOT EXISTS actualite (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `titre` VARCHAR(255) NOT NULL,
  `contenu` TEXT NOT NULL,
  `auteur` VARCHAR(100) DEFAULT 'Admin',
  `image` VARCHAR(500) DEFAULT '',
  `date_publication` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insérer quelques données de test (optionnel)
INSERT INTO `actualite` (`titre`, `contenu`, `auteur`, `image`, `date_publication`) VALUES
('Bienvenue sur TechSolutions', 'Nous sommes ravis de vous présenter notre nouvelle plateforme de gestion. Découvrez toutes les fonctionnalités qui faciliteront votre travail au quotidien.', 'Admin', '', NOW()),
('Nouvelle mise à jour disponible', 'Une mise à jour importante est maintenant disponible. Elle apporte de nombreuses améliorations de performance et de nouvelles fonctionnalités passionnantes.', 'Admin', '', NOW()),
('Guide de démarrage rapide', 'Consultez notre guide complet pour bien démarrer avec TechSolutions. Toutes les étapes sont détaillées pour une prise en main rapide et efficace.', 'Admin', '', NOW());