<?php
require_once "../config/database.php";

$conn = getConnection();
$accion = $_POST["accion"] ?? "";

switch ($accion) {
    case "listar":
        listarTrazados($conn);
        break;
    case "listar_circuitos":
        listarCircuitos($conn);
        break;
    case "agregar":
        agregarTrazado($conn);
        break;
    case "editar":
        editarTrazado($conn);
        break;
    case "eliminar":
        eliminarTrazado($conn);
        break;
    default:
        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
        break;
}

function listarTrazados($conn) {
    try {
        $stmt = $conn->query("SELECT t.*, c.nombre as nombre_circuito 
                             FROM trazados t 
                             JOIN circuitos c ON t.id_circuito = c.id_circuito
                             ORDER BY t.nombre");
        $trazados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<table class='table table-striped'>";
        echo "<thead><tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Circuito</th>
                <th>Longitud</th>
                <th>Tipo</th>
                <th>Sentido</th>
                <th>Acciones</th>
              </tr></thead><tbody>";
        
        foreach ($trazados as $t) {
            $nombreJS = addslashes($t['nombre']);
            echo "<tr>
                    <td>{$t['id_trazado']}</td>
                    <td>{$t['nombre']}</td>
                    <td>{$t['nombre_circuito']}</td>
                    <td>{$t['longitud']} m</td>
                    <td>{$t['tipo']}</td>
                    <td>{$t['sentido']}</td>
                    <td>
                        <button class='btn btn-warning btn-sm' 
                                onclick='editarTrazado({$t['id_trazado']}, 
                                                     \"{$nombreJS}\", 
                                                     {$t['id_circuito']}, 
                                                     \"{$t['longitud']}\", 
                                                     \"{$t['tipo']}\", 
                                                     \"{$t['sentido']}\")'>
                            Editar
                        </button>
                        <button class='btn btn-danger btn-sm' 
                                onclick='eliminarTrazado({$t['id_trazado']})'>
                            Eliminar
                        </button>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error al cargar los trazados: " . $e->getMessage() . "</div>";
    }
}

function listarCircuitos($conn) {
    try {
        $stmt = $conn->query("SELECT id_circuito, nombre FROM circuitos ORDER BY nombre");
        $circuitos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($circuitos as $c) {
            echo "<option value='{$c['id_circuito']}'>{$c['nombre']}</option>";
        }
    } catch (PDOException $e) {
        echo "<option value=''>Error al cargar circuitos</option>";
    }
}

function agregarTrazado($conn) {
    try {
        $stmt = $conn->prepare("INSERT INTO trazados (nombre, id_circuito, longitud, tipo, sentido) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST["nombre"],
            $_POST["id_circuito"],
            $_POST["longitud"],
            $_POST["tipo"],
            $_POST["sentido"]
        ]);
        
        echo json_encode(["status" => "ok", "message" => "Trazado agregado correctamente"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
}

function editarTrazado($conn) {
    try {
        $stmt = $conn->prepare("UPDATE trazados 
                               SET nombre = ?, id_circuito = ?, longitud = ?, 
                                   tipo = ?, sentido = ? 
                               WHERE id_trazado = ?");
        $stmt->execute([
            $_POST["nombre"],
            $_POST["id_circuito"],
            $_POST["longitud"],
            $_POST["tipo"],
            $_POST["sentido"],
            $_POST["id_trazado"]
        ]);
        
        echo json_encode(["status" => "ok", "message" => "Trazado actualizado correctamente"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
}

function eliminarTrazado($conn) {
    try {
        $stmt = $conn->prepare("DELETE FROM trazados WHERE id_trazado = ?");
        $stmt->execute([$_POST["id_trazado"]]);
        
        echo json_encode(["status" => "ok", "message" => "Trazado eliminado correctamente"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
}