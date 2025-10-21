<?php
declare(strict_types=1);

namespace FwTest\Core;

use PDO;
use PDOException;

class Database
{
    private string $host;
    private string $user;
    private string $password;
    private int $port;
    private string $database;
    private string $charset;

    private PDO $object;

    public function __construct(?string $configPath = null)
    {
        $this->loadEnv($configPath);
        $this->connect();
    }

    /**
     * Charge la configuration depuis un fichier .env (si présent)
     * ou depuis les variables d'environnement (Docker ou système)
     */
    private function loadEnv(?string $configPath = null): void
    {
        $envPath = $configPath ?? realpath(__DIR__ . '/../../.env');

        // 🔹 Lecture facultative du fichier .env (utile en local)
        if ($envPath && file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                    continue;
                }
                [$key, $value] = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
                putenv(trim($key) . '=' . trim($value)); // 👈 utile si PHP lit via getenv()
            }
        }

        // 🔹 Lecture des valeurs d'environnement (Docker ou .env)
        $this->host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'localhost';
        $this->user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'root';
        $this->password = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: '';
        $this->port = (int)($_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: 3306);
        $this->database = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: 'test';
        $this->charset = $_ENV['DB_CHARSET'] ?? getenv('DB_CHARSET') ?: 'utf8mb4';

        // 🔹 Vérifie que les valeurs minimales sont présentes
        if (!$this->host || !$this->user || !$this->database) {
            throw new \RuntimeException('Missing required database environment variables');
        }
    }

    /**
     * Connexion PDO sécurisée
     */
    private function connect(): void
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $this->host,
            $this->port,
            $this->database,
            $this->charset
        );

        try {
            $this->object = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Exécute une requête préparée
     */
    public function query(string $sql, array $params = []): bool
    {
        $stmt = $this->object->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Récupère toutes les lignes
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->object->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Récupère la première colonne du premier résultat
     */
    public function fetchOne(string $sql, array $params = [])
    {
        $stmt = $this->object->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /**
     * Récupère une seule ligne
     */
    public function fetchRow(string $sql, array $params = []): ?array
    {
        $stmt = $this->object->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    /**
     * Retourne l’objet PDO natif
     */
    public function getPdo(): PDO
    {
        return $this->object;
    }
}
