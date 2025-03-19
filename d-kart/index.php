<?php
session_start();

// Si no hay sesión, redirigir al login
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

// Si hay sesión, redirigir según el rol
if (isset($_SESSION["rol"])) {
    switch ($_SESSION["rol"]) {
        case "admin":
            header("Location: dashboard.php");
            break;
        case "preparador":
            header("Location: preparador.php");
            break;
        case "piloto":
            header("Location: perfil.php");
            break;
        case "mecanico":
            header("Location: mecanico.php");
            break;
        default:
            // Si el rol no es reconocido, cerrar sesión y redirigir al login
            session_destroy();
            header("Location: login.php?error=rol_invalido");
    }
    exit();
} else {
    // Si hay ID de usuario pero no hay rol (situación anómala), cerrar sesión
    session_destroy();
    header("Location: login.php?error=sesion_invalida");
    exit();
}
?>