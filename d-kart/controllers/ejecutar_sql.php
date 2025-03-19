<?php/*
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol"] !== "admin") {
    echo json_encode(["status" => "error", "message" => "Acceso denegado."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sqlQuery"])) {
    try {
        $conn = getConnection();
        $query = $_POST["sqlQuery"];

        // Evitar consultas peligrosas
        $blacklist = ["DROP", "DELETE", "TRUNCATE", "ALTER"];
        foreach ($blacklist as $dangerous) {
            if (stripos($query, $dangerous) !== false) {
                echo json_encode(["status" => "error", "message" => "❌ Consulta bloqueada por seguridad."]);
                exit();
            }
        }

        $stmt = $conn->prepare($query);
        $stmt->execute();

        echo json_encode(["status" => "ok", "message" => "SQL ejecutado con éxito."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error SQL: " . $e->getMessage()]);
    }
}*/
?>
