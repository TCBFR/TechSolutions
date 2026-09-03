<?php

namespace Core;

use PDOException;

class Model
{
    protected $nomTable;

    protected $nomPK = 'id';

    /**
     * ACCESSEURS
     */

    /**
     * Get the value of nomTable
     */
    public function getNomTable()
    {
        return $this->nomTable;
    }

    /**
     * Set the value of nomTable
     */
    public function setNomTable($nomTable): self
    {
        $this->nomTable = $nomTable;

        return $this;
    }

    /**
     * Get the value of nomPK
     */
    public function getNomPK()
    {
        return $this->nomPK;
    }

    /**
     * Set the value of nomPK
     */
    public function setNomPK($nomPK): self
    {
        $this->nomPK = $nomPK;

        return $this;
    }

    /**
     * METHODES D'ACCES
     */
    public function findAll(): array
    {
        return db()
            ->query('select * from '.$this->nomTable)
            ->findAll();
    }

    public function get(int $id): array
    {
        return db()
            ->query(
                'select * from '.$this->nomTable.' where '.$this->nomPK.'=:id', ['id' => $id]
            )
            ->find();
    }

    public function delete($id): bool
    {
        try {
            return db()->execute(
                "DELETE FROM {$this->nomTable} WHERE {$this->nomPK}=:id",
                ['id' => $id]
            );
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                abort(403, 'Impossible de supprimer cette catégorie tant q\'une TODO lui est attachée !');
            }

            dd($e->getMessage());
        }

    }

    public function update(int $id, array $fields): bool
    {
        $f = [];
        foreach ($fields as $key => $value) {
            $f[] = "`$key`=:{$key}";
        }

        $fields['id'] = $id;

        $sql = "UPDATE {$this->nomTable} set ".implode(', ', $f)." WHERE {$this->nomPK} = :id";

        return db()->execute($sql, $fields);
    }

    public function store(array $fields): bool
    {

        $fieldNames = array_keys($fields);
        $sqlValues = array_map(fn ($nomChamp) => ":$nomChamp", $fieldNames);

        $sql = "INSERT INTO {$this->nomTable} (".implode(',', $fieldNames).')';
        $sql .= ' values ('.implode(', ', $sqlValues).')';

        return db()->execute($sql, $fields);
    }

    public function find(array|int $fields): array
    {
        if (! is_array($fields)) {
            return $this->get((int) $fields);
        }

        return $this->doFind($fields);
    }

    private function doFind(array $fields): array
    {
        $conditions = array_map(fn ($key) => "`$key`= :$key", array_keys($fields));
        $where = implode(' AND ', $conditions);

        $sql = "SELECT * FROM {$this->nomTable} WHERE ".$where;

        return db()->query($sql, $fields)->findAll();
    }

    public function findOrFail(array $fields): array
    {
        $result = $this->find($fields);

        if (! $result) {
            abort(404, 'ToDo introuvable');
        }

        return $result;
    }

    public function pluck(string $fieldName, string $order = 'ASC'): array
    {
        $sql = <<<EOQ
            SELECT {$this->getNomPK()}, $fieldName
            FROM {$this->getNomTable()}
            ORDER BY $fieldName $order
        EOQ;

        return db()
            ->query($sql)
            ->findAll();
    }
}
