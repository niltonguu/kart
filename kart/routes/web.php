<?php
require_once __DIR__ . '/../autoload.php';

// Crear instancia del controlador de autenticación
$authController = new AuthController();

// Definir rutas
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Rutas públicas
$publicRoutes = ['/login', '/register'];

// Verificar si la ruta actual requiere autenticación
if (!in_array($uri, $publicRoutes) && !$authController->isLoggedIn()) {
    header('Location: /login');
    exit;
}

// Enrutamiento
switch ($uri) {
    case '/login':
        if ($method === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if ($authController->login($username, $password)) {
                header('Location: /dashboard');
                exit;
            } else {
                // Redirigir con mensaje de error
                header('Location: /login?error=1');
                exit;
            }
        } else {
            // Mostrar formulario de login
            require __DIR__ . '/../view/auth/login.php';
        }
        break;

    case '/logout':
        $authController->logout();
        break;

    case '/dashboard':
        require __DIR__ . '/../view/dashboard/index.php';
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        require __DIR__ . '/../view/errors/404.php';
        break;
}