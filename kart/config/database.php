<?php
class Database {
    private const HOST = "localhost";
    private const NAME = "nil_kart";
    private const USER = "niltongu";
    private const PASS = "";
    private const CHARSET = "utf8mb4";
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . self::HOST . 
                   ";dbname=" . self::NAME . 
                   ";charset=" . self::CHARSET;

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->connection = new PDO($dsn, self::USER, self::PASS, $options);
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Método para ejecutar consultas
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            throw new Exception("Error al ejecutar la consulta");
        }
    }

    // Método para obtener un solo registro
    public function fetchOne($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    // Método para obtener todos los registros
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }
}