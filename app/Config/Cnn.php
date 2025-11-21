<?php
namespace App\Config;

use PDO;
use PDOException;

require_once __DIR__ . '/config.php';

class Cnn {
    
    private ?PDO $pdo = null;

    public function __construct() {
        $dsn = $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch(PDOException $e) {
            throw new PDOException("Error de conexión: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    // Método para obtener el objeto PDO
    public function getConnection(): PDO {
        return $this->pdo;
    }

    // Ejecuta INSERT, UPDATE, DELETE
    public function executeQuery(string $sql, array $params = []): int {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    // Ejecuta SELECT y devuelve arreglo completo
    public function fetchQuery(string $sql, array $params = []): array {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Obtenemos UN solo registro
    public function fetchOne(string $sql, array $params = []): ?array {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
