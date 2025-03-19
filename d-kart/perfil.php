<?php
session_start();
if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol"] !== "piloto") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Perfil - Piloto</title>
</head>
<body>
    <div class="container mt-4">
        <h2>🏁 Mi Perfil</h2>
        <p>Bienvenido, <?= $_SESSION["nombre"] ?>. Aquí puedes ver tu rendimiento y estadísticas.</p>

        <a href="historial_sesiones.php" class="btn btn-success">📜 Ver Historial</a>
        <a href="estadisticas_personales.php" class="btn btn-primary">📈 Ver Estadísticas</a>
    </div>
</body>
</html>
