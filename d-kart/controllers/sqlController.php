<?php
session_start();
require_once "../config/database.php";

// Verificar que solo los administradores puedan ejecutar scripts SQL
if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol"] !== "admin") {
    echo json_encode([
        "status" => "error",
        "message" => "❌ Acceso denegado. Solo los administradores pueden ejecutar scripts SQL."
    ]);
    exit();
}

$accion = $_POST["accion"] ?? "";

if ($accion !== "ejecutar") {
    echo json_encode([
        "status" => "error",
        "message" => "⚠️ Acción no válida."
    ]);
    exit();
}

if (!isset($_POST["script"]) || trim($_POST["script"]) === "") {
    echo json_encode([
        "status" => "error",
        "message" => "⚠️ No se proporcionó ningún script SQL."
    ]);
    exit();
}

try {
    $conn = getConnection();
    $script = trim($_POST["script"]);
    
    // Dividir el script en múltiples consultas si contiene punto y coma
    $queries = array_filter(array_map('trim', explode(';', $script)));
    
    $results = [];
    $affectedRows = 0;
    
    foreach ($queries as $query) {
        if (empty($query)) continue;
        
        // Ejecutar la consulta
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        // Si es un SELECT, obtener los resultados
        if (stripos($query, 'SELECT') === 0) {
            $results[] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        $affectedRows += $stmt->rowCount();
    }
    
    // Preparar el mensaje de respuesta
    $message = "Script ejecutado correctamente.";
    if ($affectedRows > 0) {
        $message .= " Filas afectadas: " . $affectedRows;
    }
    
    // Preparar los detalles si hay resultados de SELECT
    $details = "";
    if (!empty($results)) {
        ob_start();
        foreach ($results as $result) {
            if (!empty($result)) {
                echo "Resultados:\n";
                print_r($result);
                echo "\n";
            }
        }
        $details = ob_get_clean();
    }
    
    echo json_encode([
        "status" => "ok",
        "message" => "✅ " . $message,
        "details" => $details
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "❌ Error al ejecutar el script SQL",
        "details" => $e->getMessage()
    ]);
}
?>