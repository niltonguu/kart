<?php
session_start();
$_SESSION['circuito'] = $_POST['circuito'];
$_SESSION['trazado'] = $_POST['trazado'];
echo json_encode(["status" => "ok"]);
?>
