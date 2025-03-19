<?php
// Punto de entrada principal
require_once 'autoload.php';

// Cargar configuración
$config = require 'config/env.php';

// Configurar reporte de errores
if ($config['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Iniciar sesión
session_start();

// Cargar rutas
require_once 'routes/web.php';