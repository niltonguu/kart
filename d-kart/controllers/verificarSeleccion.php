<?php
session_start();
$circuito = isset($_SESSION['circuito']) ? $_SESSION['circuito'] : null;
$trazado = isset($_SESSION['trazado']) ? $_SESSION['trazado'] : null;
echo json_encode(["circuito" => $circuito, "trazado" => $trazado]);
?>
