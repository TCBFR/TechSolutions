<?php

namespace Model;

use Core\Database;

class Admin
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Récupérer tous les PCs
     */
    public function getAllPCs(): array
    {
        return $this->db->query("
            SELECT * FROM pc 
            ORDER BY date_ajout DESC
        ")->findAll();
    }

    /**
     * Récupérer un PC par son ID
     */
    public function get(int $id): array|false
    {
        try {
            return $this->db->get($id, 'pc');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Récupérer les composants d'un PC avec leurs informations complètes
     */
    public function getPCComposants(int $pcId): array
    {
        return $this->db->query("
            SELECT 
                c.*,
                pc.quantite,
                cat.nom as categorie_nom
            FROM pc_composant pc
            INNER JOIN composant c ON pc.id_composant = c.id
            LEFT JOIN categorie cat ON c.id_categorie = cat.id
            WHERE pc.id_pc = ?
            ORDER BY cat.nom, c.nom
        ", [$pcId])->findAll();
    }

    /**
     * Récupérer les associations brutes PC-Composants (pour l'édition)
     */
    public function getPCComposantsRaw(int $pcId): array
    {
        return $this->db->query("
            SELECT id_composant, quantite
            FROM pc_composant
            WHERE id_pc = ?
        ", [$pcId])->findAll();
    }

    /**
     * Créer un nouveau PC
     */
    public function store(array $data): string
    {
        $this->db->execute("
            INSERT INTO pc (nom, service, effectif, description, image, date_ajout)
            VALUES (?, ?, ?, ?, ?, NOW())
        ", [
            $data['nom'],
            $data['service'],
            $data['effectif'],
            $data['description'] ?? '',
            $data['image'] ?? ''
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Mettre à jour un PC
     */
    public function update(int $id, array $data): bool
    {
        return $this->db->execute("
            UPDATE pc 
            SET nom = ?, 
                service = ?, 
                effectif = ?, 
                description = ?, 
                image = ?
            WHERE id = ?
        ", [
            $data['nom'],
            $data['service'],
            $data['effectif'],
            $data['description'] ?? '',
            $data['image'] ?? '',
            $id
        ]);
    }

    /**
     * Supprimer un PC (et ses composants)
     */
    public function delete(int $id): bool
    {
        try {
            // Supprimer d'abord les associations PC-Composants
            $this->db->execute("DELETE FROM pc_composant WHERE id_pc = ?", [$id]);
            
            // Supprimer le PC
            return $this->db->execute("DELETE FROM pc WHERE id = ?", [$id]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Ajouter un composant à un PC
     */
    public function addComposantToPC(int $pcId, int $composantId, int $quantite = 1): bool
    {
        return $this->db->execute("
            INSERT INTO pc_composant (id_pc, id_composant, quantite)
            VALUES (?, ?, ?)
        ", [$pcId, $composantId, $quantite]);
    }

    /**
     * Supprimer tous les composants d'un PC
     */
    public function removeAllComposantsFromPC(int $pcId): bool
    {
        return $this->db->execute("
            DELETE FROM pc_composant WHERE id_pc = ?
        ", [$pcId]);
    }

    /**
     * Mettre à jour la quantité d'un composant dans un PC
     */
    public function updateComposantQuantity(int $pcId, int $composantId, int $quantite): bool
    {
        return $this->db->execute("
            UPDATE pc_composant 
            SET quantite = ?
            WHERE id_pc = ? AND id_composant = ?
        ", [$quantite, $pcId, $composantId]);
    }

    /**
     * Vérifier si un PC existe
     */
    public function exists(int $id): bool
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM pc WHERE id = ?", [$id])->find();
        return $result && $result['count'] > 0;
    }

    /**
     * Compter le nombre total de PCs
     */
    public function count(): int
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM pc")->find();
        return $result ? (int) $result['count'] : 0;
    }

    /**
     * Rechercher des PCs par nom ou service
     */
    public function search(string $term): array
    {
        return $this->db->query("
            SELECT * FROM pc 
            WHERE nom LIKE ? OR service LIKE ?
            ORDER BY date_ajout DESC
        ", ["%$term%", "%$term%"])->findAll();
    }

    /**
     * Récupérer les statistiques du dashboard
     */
    public function getStats(): array
    {
        $stats = [];
        
        // Nombre total de PCs
        $result = $this->db->query("SELECT COUNT(*) as count FROM pc")->find();
        $stats['total_pcs'] = $result ? (int) $result['count'] : 0;
        
        // Nombre total de composants
        $result = $this->db->query("SELECT COUNT(*) as count FROM composant")->find();
        $stats['total_composants'] = $result ? (int) $result['count'] : 0;
        
        // Nombre total d'utilisateurs
        $result = $this->db->query("SELECT COUNT(*) as count FROM user")->find();
        $stats['total_users'] = $result ? (int) $result['count'] : 0;
        
        // Nombre total d'actualités
        $result = $this->db->query("SELECT COUNT(*) as count FROM actualite")->find();
        $stats['total_actualites'] = $result ? (int) $result['count'] : 0;
        
        return $stats;
    }

    /**
     * Récupérer tous les composants avec leurs catégories
     */
    public function getAllComposants(): array
    {
        return $this->db->query("
            SELECT 
                c.*,
                cat.nom as categorie_nom,
                cat.id as categorie_id
            FROM composant c
            LEFT JOIN categorie cat ON c.id_categorie = cat.id
            ORDER BY cat.nom, c.nom
        ")->findAll();
    }

    /**
     * Récupérer toutes les catégories
     */
    public function getAllCategories(): array
    {
        return $this->db->query("
            SELECT * FROM categorie 
            ORDER BY nom
        ")->findAll();
    }
}