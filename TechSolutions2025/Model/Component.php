<?php

namespace Model;

use Core\Database;

class Component
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
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

    /**
     * Récupérer tous les composants
     */
    public function getAllComposants(): array
    {
        return $this->db->query("
            SELECT * FROM composant 
            ORDER BY nom
        ")->findAll();
    }

    /**
     * Récupérer tous les composants avec leur catégorie
     */
    public function getAllComposantsWithCategory(): array
    {
        return $this->db->query("
            SELECT 
                c.*,
                cat.nom as categorie_nom
            FROM composant c
            LEFT JOIN categorie cat ON c.id_categorie = cat.id
            ORDER BY cat.nom, c.nom
        ")->findAll();
    }

    /**
     * Récupérer un composant par son ID
     */
    public function get(int $id): array|false
    {
        try {
            return $this->db->get($id, 'composant');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Créer un nouveau composant
     */
    public function store(array $data): string
    {
        $this->db->execute("
            INSERT INTO composant (nom, id_categorie, specifications)
            VALUES (?, ?, ?)
        ", [
            $data['nom'],
            $data['id_categorie'] ?? null,
            $data['specifications'] ?? ''
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Mettre à jour un composant
     */
    public function update(int $id, array $data): bool
    {
        return $this->db->execute("
            UPDATE composant 
            SET nom = ?, 
                id_categorie = ?, 
                specifications = ?
            WHERE id = ?
        ", [
            $data['nom'],
            $data['id_categorie'] ?? null,
            $data['specifications'] ?? '',
            $id
        ]);
    }

    /**
     * Supprimer un composant
     */
    public function delete(int $id): bool
    {
        try {
            // Supprimer d'abord les associations PC-Composants
            $this->db->execute("DELETE FROM pc_composant WHERE id_composant = ?", [$id]);
            
            // Supprimer le composant
            return $this->db->execute("DELETE FROM composant WHERE id = ?", [$id]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Récupérer les composants par catégorie
     */
    public function getByCategory(int $categoryId): array
    {
        return $this->db->query("
            SELECT * FROM composant 
            WHERE id_categorie = ?
            ORDER BY nom
        ", [$categoryId])->findAll();
    }

    /**
     * Compter le nombre de composants
     */
    public function count(): int
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM composant")->find();
        return $result ? (int) $result['count'] : 0;
    }
}