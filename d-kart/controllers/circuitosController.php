<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol"] !== "admin") {
    echo json_encode(["status" => "error", "message" => "❌ Acceso denegado."]);
    exit();
}

$conn = getConnection();
$accion = $_POST["accion"] ?? "";

if (!$accion) {
    echo json_encode(["status" => "error", "message" => "⚠️ No se recibió la acción."]);
    exit();
}

switch ($accion) {
    case "listar_circuitos":
        cir_listarSelectCircuitos($conn);
        break;
    case "listar":
        cir_listarCircuitos($conn);
        break;
    case "agregar":
        cir_agregarCircuito($conn);
        break;
    case "editar":
        cir_editarCircuito($conn);
        break;
    case "eliminar":
        cir_eliminarCircuito($conn);
        break;
    default:
        echo json_encode(["status" => "error", "message" => "⚠️ Acción no válida: " . $accion]);
        exit();
}

function cir_listarCircuitos($conn) {
    try {
        $stmt = $conn->query("SELECT id_circuito, nombre, ubicacion, longitud FROM circuitos ORDER BY id_circuito DESC");
        $circuitos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<table class='table table-striped'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Ubicación</th><th>Longitud (km)</th><th>Acciones</th></tr>";
        foreach ($circuitos as $c) {
            echo "<tr>
                    <td>{$c['id_circuito']}</td>
                    <td>{$c['nombre']}</td>
                    <td>{$c['ubicacion']}</td>
                    <td>{$c['longitud']}</td>
                    <td>
                        <button class='btn btn-warning btn-sm' onclick=\"cir_editarCircuito({$c['id_circuito']}, '{$c['nombre']}', '{$c['ubicacion']}', '{$c['longitud']}')\">✏️</button>
                        <button class='btn btn-danger btn-sm' onclick=\"cir_eliminarCircuito({$c['id_circuito']})\">🗑</button>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "<p class='text-danger'>Error al cargar los circuitos: " . $e->getMessage() . "</p>";
    }
}

function cir_agregarCircuito($conn) {
    if (!isset($_POST["nombre"], $_POST["ubicacion"], $_POST["longitud"])) {
        echo json_encode(["status" => "error", "message" => "⚠️ Todos los campos son obligatorios."]);
        exit();
    }

    try {
        $stmt = $conn->prepare("INSERT INTO circuitos (nombre, ubicacion, longitud) VALUES (?, ?, ?)");
        $stmt->execute([$_POST["nombre"], $_POST["ubicacion"], $_POST["longitud"]]);

        echo json_encode(["status" => "ok", "message" => "✅ Circuito agregado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "❌ Error: " . $e->getMessage()]);
    }
    exit();
}

function cir_editarCircuito($conn) {
    if (!isset($_POST["id_circuito"], $_POST["nombre"], $_POST["ubicacion"], $_POST["longitud"])) {
        echo json_encode(["status" => "error", "message" => "⚠️ Todos los campos son obligatorios."]);
        exit();
    }

    try {
        $stmt = $conn->prepare("UPDATE circuitos SET nombre = ?, ubicacion = ?, longitud = ? WHERE id_circuito = ?");
        $stmt->execute([$_POST["nombre"], $_POST["ubicacion"], $_POST["longitud"], $_POST["id_circuito"]]);

        echo json_encode(["status" => "ok", "message" => "✅ Circuito actualizado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "❌ Error: " . $e->getMessage()]);
    }
    exit();
}

function cir_eliminarCircuito($conn) {
    if (!isset($_POST["id_circuito"])) {
        echo json_encode(["status" => "error", "message" => "⚠️ ID de circuito no proporcionado."]);
        exit();
    }

    try {
        $stmt = $conn->prepare("DELETE FROM circuitos WHERE id_circuito = ?");
        $stmt->execute([$_POST["id_circuito"]]);

        echo json_encode(["status" => "ok", "message" => "✅ Circuito eliminado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "❌ Error: " . $e->getMessage()]);
    }
    exit();
}

function cir_listarSelectCircuitos($conn) {
    try {
        $stmt = $conn->query("SELECT id_circuito, nombre FROM circuitos ORDER BY nombre");
        $circuitos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($circuitos as $circuito) {
            echo "<option value='{$circuito['id_circuito']}'>{$circuito['nombre']}</option>";
        }
    } catch (PDOException $e) {
        echo "Error al listar circuitos: " . $e->getMessage();
    }
}
?>