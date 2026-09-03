<?php

namespace Core;

use PDO;
use PDOStatement;
use PDOException;

class Database
{
    private PDO $connector;

    private static ?self $instance = null;

    private PDOStatement|bool $statement;

    private function __construct($config, $user = 'root', $password = '')
    {
        try {
            // Utiliser le socket Unix si disponible (MAMP sur macOS)
            if (isset($config['unix_socket']) && file_exists($config['unix_socket'])) {
                $dsn = sprintf(
                    'mysql:unix_socket=%s;dbname=%s;charset=%s',
                    $config['unix_socket'],
                    $config['dbname'],
                    $config['charset']
                );
            } else {
                // Sinon utiliser host/port
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                    $config['host'] ?? 'localhost',
                    $config['port'] ?? 3306,
                    $config['dbname'],
                    $config['charset']
                );
            }

            $this->connector = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            $config = require base_path('config/app.php');
            $user = $config['db_user'] ?? 'root';
            $password = $config['db_password'] ?? 'root';
            self::$instance = new self($config['database'], $user, $password);
        }

        return self::$instance;
    }

    public function query($query, $param = []): self
    {
        $this->statement = $this->connector->prepare($query);
        $this->statement->execute($param);

        return $this;
    }

    public function find(): array|false
    {
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll(): array
    {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get(int $id, string $tableName, string $pkName = 'id'): array
    {
        $sql = "SELECT * FROM $tableName WHERE $pkName = :id";

        return $this->query($sql, ['id' => $id])
            ->findOrFail();
    }

    public function findOrFail(): array
    {
        $result = $this->statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            abort(404, 'Enregistrement inexistant');
        }

        return $result;
    }

    public function execute($query, $param = []): bool
    {
        $statement = $this->connector->prepare($query);

        return $statement->execute($param);
    }

    public function lastInsertId(): string
    {
        return $this->connector->lastInsertId();
    }

    public function rowCount(): int
    {
        return $this->statement->rowCount();
    }

    public function getConnection(): PDO
    {
        return $this->connector;
    }
}