// autoload.php
spl_autoload_register(function ($class) {
    // Convertir el namespace o nombre de clase en una ruta de archivo
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        die("No se pudo cargar la clase: $class");
    }
});
