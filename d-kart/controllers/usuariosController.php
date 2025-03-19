<?php
//  /controllers/usuariosController.php
session_start();
require_once "../config/database.php";

// 🔍 Verificar si el usuario es admin
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

error_log("Acción recibida en usuariosController.php: " . $accion);

switch ($accion) {
    case "listar":
        user_listarUsuarios($conn);
        break;
    case "agregar":
        user_agregarUsuario($conn);
        break;
    case "editar":
        user_editarUsuario($conn);
        break;
    case "eliminar":
        user_eliminarUsuario($conn);
        break;
    default:
        echo json_encode(["status" => "error", "message" => "⚠️ Acción no válida: " . $accion]);
        exit();
}

function user_listarUsuarios($conn) {
    try {
        $stmt = $conn->query("SELECT id_usuario, nombre, nickname, email, rol FROM usuarios");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<table class='table table-striped'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Nickname</th><th>Email</th><th>Rol</th><th>Acciones</th></tr>";
        foreach ($usuarios as $u) {
            echo "<tr>
                    <td>{$u['id_usuario']}</td>
                    <td>{$u['nombre']}</td>
                    <td>{$u['nickname']}</td>
                    <td>{$u['email']}</td>
                    <td>{$u['rol']}</td>
                    <td>
                        <button class='btn btn-warning btn-sm' onclick=\"user_editarUsuario({$u['id_usuario']}, '{$u['nombre']}', '{$u['nickname']}', '{$u['email']}', '{$u['rol']}')\">✏️</button>
                        <button class='btn btn-danger btn-sm' onclick=\"user_eliminarUsuario({$u['id_usuario']})\">🗑</button>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "<p class='text-danger'>Error al cargar los usuarios: " . $e->getMessage() . "</p>";
    }
}

function user_agregarUsuario($conn) {
    if (!isset($_POST["nombre"], $_POST["nickname"], $_POST["email"], $_POST["password"], $_POST["rol"])) {
        echo json_encode(["status" => "error", "message" => "⚠️ Todos los campos son obligatorios."]);
        exit();
    }

    if (empty($_POST["password"])) {
        echo json_encode(["status" => "error", "message" => "⚠️ La contraseña es obligatoria."]);
        exit();
    }

    try {
        $hashedPassword = password_hash($_POST["password"], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, nickname, email, password, rol) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST["nombre"], $_POST["nickname"], $_POST["email"], $hashedPassword, $_POST["rol"]]);

        echo json_encode(["status" => "ok", "message" => "✅ Usuario agregado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "❌ Error: " . $e->getMessage()]);
    }
    exit();
}

function user_editarUsuario($conn) {
    if (!isset($_POST["id_usuario"], $_POST["nombre"], $_POST["nickname"], $_POST["email"], $_POST["rol"])) {
        echo json_encode(["status" => "error", "message" => "⚠️ Todos los campos son obligatorios."]);
        exit();
    }

    try {
        if (!empty($_POST["password"])) {
            $hashedPassword = password_hash($_POST["password"], PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, nickname = ?, email = ?, password = ?, rol = ? WHERE id_usuario = ?");
            $stmt->execute([$_POST["nombre"], $_POST["nickname"], $_POST["email"], $hashedPassword, $_POST["rol"], $_POST["id_usuario"]]);
        } else {
            $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, nickname = ?, email = ?, rol = ? WHERE id_usuario = ?");
            $stmt->execute([$_POST["nombre"], $_POST["nickname"], $_POST["email"], $_POST["rol"], $_POST["id_usuario"]]);
        }

        echo json_encode(["status" => "ok", "message" => "✅ Usuario actualizado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "❌ Error: " . $e->getMessage()]);
    }
    exit();
}

function user_eliminarUsuario($conn) {
    if (!isset($_POST["id_usuario"])) {
        echo json_encode(["status" => "error", "message" => "⚠️ ID de usuario no proporcionado."]);
        exit();
    }

    try {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$_POST["id_usuario"]]);

        echo json_encode(["status" => "ok", "message" => "✅ Usuario eliminado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "❌ Error: " . $e->getMessage()]);
    }
    exit();
}
?>