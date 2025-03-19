<?php
// Configuración de la Base de Datos
define("DB_HOST", "localhost");
define("DB_NAME", "nil_kart");
define("DB_USER", "niltongu"); // Cambia esto si tienes otro usuario
define("DB_PASS", "#HaNnANiLtOn24#"); // Cambia esto si tienes contraseña

function getConnection() {
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}
?>
