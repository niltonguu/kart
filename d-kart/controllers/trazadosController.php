<?php
//Archivo: /controllers/trazadosController.php


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
    case "listar":
        tra_listarTrazados($conn);
        break;
    case "agregar":
        tra_agregarTrazado($conn);
        break;
    case "editar":
        tra_editarTrazado($conn);
        break;
    case "eliminar":
        tra_eliminarTrazado($conn);
        break;
    default:
        echo json_encode(["status" => "error", "message" => "⚠️ Acción no válida: " . $accion]);
        exit();
}

function tra_listarTrazados($conn) {
    try {
        $stmt = $conn->query("
            SELECT t.*, c.nombre as nombre_circuito 
            FROM trazados t 
            JOIN circuitos c ON t.id_circuito = c.id_circuito
            ORDER BY t.id DESC
        ");
        $trazados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<table class='table table-striped'>";
        echo "<tr>
                <th>ID</th>
                <th>Circuito</th>
                <th>Nombre</th>
                <th>Longitud (m)</th>
                <th>Tipo</th>
                <th>Sentido</th>
                <th>Acciones</th>
              </tr>";
        foreach ($trazados as $t) {
            echo "<tr>
                    <td>{$t['id']}</td>
                    <td>{$t['nombre_circuito']}</td>
                    <td>{$t['nombre']}</td>
                    <td>{$t['longitud']}</td>
                    <td>{$t['tipo']}</td>
                    <td>{$t['sentido']}</td>
                    <td>
                        <button class='btn btn-warning btn-sm' onclick=\"tra_editarTrazado(
                            {$t['id']}, 
                            {$t['id_circuito']}, 
                            '{$t['nombre']}', 
                            {$t['longitud']}, 
                            '{$t['tipo']}', 
                            '{$t['sentido']}'
                        )\">✏️</button>
                        <button class='btn btn-danger btn-sm' onclick=\"tra_eliminarTrazado({$t['id']})\">🗑</button>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "<p class='text-danger'>Error al cargar los trazados: " . $e->getMessage() . "</p>";
    }
}

function tra_agregarTrazado($conn) {
    if (!isset($_POST["id_circuito"], $_POST["nombre"], $_POST["longitud"], $_POST["tipo"], $_POST["sentido"])) {
        echo json_encode(["status" => "error", "message" => "⚠️ Todos los campos son obligatorios."]);
        exit();
    }

    try {
        $stmt = $conn->prepare("
            INSERT INTO trazados (id_circuito, nombre, longitud, tipo, sentido) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_POST["id_circuito"],
            $_POST["nombre"],
            $_POST["longitud"],
            $_POST["tipo"],
            $_POST["sentido"]
        ]);

        echo json_encode(["status" => "ok", "message" => "✅ Trazado agregado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "❌ Error: " . $e->getMessage()]);
    }
    exit();
}

function tra_editarTrazado($conn) {
    if (!isset($_POST["id"], $_POST["id_circuito"], $_POST["nombre"], $_POST["longitud"], $_POST["tipo"], $_POST["sentido"])) {
        echo json_encode(["status" => "error", "message" => "⚠️ Todos los campos son obligatorios."]);
        exit();
    }

    try {
        $stmt = $conn->prepare("
            UPDATE trazados 
            SET id_circuito = ?, nombre = ?, longitud = ?, tipo = ?, sentido = ? 
            WHERE id = ?
        ");
        $stmt->execute([
            $_POST["id_circuito"],
            $_POST["nombre"],
            $_POST["longitud"],
            $_POST["tipo"],
            $_POST["sentido"],
            $_POST["id"]
        ]);

        echo json_encode(["status" => "ok", "message" => "✅ Trazado actualizado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "❌ Error: " . $e->getMessage()]);
    }
    exit();
}

function tra_eliminarTrazado($conn) {
    if (!isset($_POST["id"])) {
        echo json_encode(["status" => "error", "message" => "⚠️ ID de trazado no proporcionado."]);
        exit();
    }

    try {
        $stmt = $conn->prepare("DELETE FROM trazados WHERE id = ?");
        $stmt->execute([$_POST["id"]]);

        echo json_encode(["status" => "ok", "message" => "✅ Trazado eliminado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "❌ Error: " . $e->getMessage()]);
    }
    exit();
}

?>