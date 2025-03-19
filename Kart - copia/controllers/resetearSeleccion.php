<?php
session_start();
unset($_SESSION['circuito']);
unset($_SESSION['trazado']);
echo json_encode(["status" => "reset"]);
?>
