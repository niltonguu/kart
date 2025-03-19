<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión para acceder a las variables de sesión
session_start();

// Configuración de cabeceras para AJAX
header('Content-Type: application/json');

try {
    // Definir la ruta base del proyecto
    $base_path = $_SERVER['DOCUMENT_ROOT'] . '/Kart';  // Ajusta 'Kart' según la carpeta de tu proyecto
    
    // Información sobre el archivo database.php
    $database_file = $base_path . '/config/database.php';
    if (!file_exists($database_file)) {
        throw new Exception("El archivo database.php no existe en la ruta: " . $database_file);
    }
    
    // Conexión a la base de datos
    require_once $database_file;
    
    // Verificar si $conn está definida
    if (!isset($conn)) {
        throw new Exception("La variable \$conn no está definida en database.php");
    }
    
    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión a la base de datos: " . $conn->connect_error);
    }
    
    // Obtener la acción solicitada
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    
    // Procesar según la acción
    if ($action == 'list') {
        // Construir consulta SQL
        $sql = "SELECT * FROM sesiones_karting ORDER BY session_datetime DESC";
        
        // Ejecutar consulta
        $result = $conn->query($sql);
        
        if (!$result) {
            throw new Exception("Error en la consulta: " . $conn->error);
        }
        
        // Preparar datos para DataTables
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        // Devolver respuesta en formato esperado por DataTables
        $response = [
            'data' => $data
        ];
    } else {
        $response = ['success' => false, 'message' => 'Acción no válida'];
    }
    
} catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
}

// Devolver respuesta como JSON
echo json_encode($response);
?>