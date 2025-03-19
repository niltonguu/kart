<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/database.php';

if (isset($conn) && !$conn->connect_error) {
    echo "Conexión exitosa a la base de datos";
} else {
    echo "Error de conexión: " . ($conn->connect_error ?? "Variable \$conn no definida");
}
?>