<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit();
    }

    try {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT id_usuario, nombre, nickname, email, password, rol FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario["password"])) {
            $_SESSION["id_usuario"] = $usuario["id_usuario"];
            $_SESSION["nombre"] = $usuario["nombre"];
            $_SESSION["nickname"] = $usuario["nickname"];
            $_SESSION["email"] = $usuario["email"];
            $_SESSION["rol"] = $usuario["rol"];

            // Redirección según el rol
            switch ($usuario["rol"]) {
                case "admin":
                    echo json_encode(["status" => "ok", "redirect" => "dashboard.php"]);
                    break;
                case "preparador":
                    echo json_encode(["status" => "ok", "redirect" => "preparador.php"]);
                    break;
                case "piloto":
                    echo json_encode(["status" => "ok", "redirect" => "perfil.php"]);
                    break;
                case "mecanico":
                    echo json_encode(["status" => "ok", "redirect" => "mecanico.php"]);
                    break;
                default:
                    echo json_encode(["status" => "error", "message" => "Rol no reconocido."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Correo o contraseña incorrectos."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error de conexión: " . $e->getMessage()]);
    }
}
?>
