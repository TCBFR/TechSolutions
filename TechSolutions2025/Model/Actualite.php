<?php

namespace Model;

use Core\Database;

class Actualite
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Récupérer toutes les actualités
     */
    public function getAll(): array
    {
        return $this->db->query("
            SELECT * FROM actualite 
            ORDER BY date_publication DESC
        ")->findAll();
    }

    /**
     * Récupérer les dernières actualités (limitées)
     */
    public function getRecent(int $limit = 5): array
    {
        return $this->db->query("
            SELECT * FROM actualite 
            ORDER BY date_publication DESC 
            LIMIT ?
        ", [$limit])->findAll();
    }

    /**
     * Récupérer une actualité par son ID
     */
    public function get(int $id): array|false
    {
        try {
            return $this->db->get($id, 'actualite');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Créer une nouvelle actualité
     */
    public function store(array $data): string
    {
        $this->db->execute("
            INSERT INTO actualite (titre, contenu, auteur, image, date_publication)
            VALUES (?, ?, ?, ?, NOW())
        ", [
            $data['titre'],
            $data['contenu'],
            $data['auteur'] ?? 'Admin',
            $data['image'] ?? ''
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Mettre à jour une actualité
     */
    public function update(int $id, array $data): bool
    {
        return $this->db->execute("
            UPDATE actualite 
            SET titre = ?, 
                contenu = ?, 
                auteur = ?, 
                image = ?
            WHERE id = ?
        ", [
            $data['titre'],
            $data['contenu'],
            $data['auteur'],
            $data['image'] ?? '',
            $id
        ]);
    }

    /**
     * Supprimer une actualité
     */
    public function delete(int $id): bool
    {
        try {
            return $this->db->execute("DELETE FROM actualite WHERE id = ?", [$id]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Vérifier si une actualité existe
     */
    public function exists(int $id): bool
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM actualite WHERE id = ?", [$id])->find();
        return $result && $result['count'] > 0;
    }

    /**
     * Compter le nombre total d'actualités
     */
    public function count(): int
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM actualite")->find();
        return $result ? (int) $result['count'] : 0;
    }

    /**
     * Rechercher des actualités
     */
    public function search(string $term): array
    {
        return $this->db->query("
            SELECT * FROM actualite 
            WHERE titre LIKE ? OR contenu LIKE ?
            ORDER BY date_publication DESC
        ", ["%$term%", "%$term%"])->findAll();
    }
}