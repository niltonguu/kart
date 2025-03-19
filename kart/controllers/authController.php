<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/authHelper.php';

class AuthController {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login.php');
        }

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            setError('Todos los campos son requeridos');
            redirect('/login.php');
        }

        try {
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Iniciar sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['username'] = $user['username'];

                // Registrar el login
                $this->logLogin($user['id']);

                // Redirigir según el rol
                $this->redirectByRole($user['role']);
            } else {
                setError('Usuario o contraseña incorrectos');
                redirect('/login.php');
            }
        } catch (PDOException $e) {
            setError('Error en el sistema');
            redirect('/login.php');
        }
    }

    private function logLogin($userId) {
        $stmt = $this->db->prepare("INSERT INTO login_logs (user_id, login_time, ip_address) VALUES (?, NOW(), ?)");
        $stmt->execute([$userId, $_SERVER['REMOTE_ADDR']]);
    }

    private function redirectByRole($role) {
        switch ($role) {
            case 'preparador':
                redirect('/view/layouts/preparador.php');
                break;
            case 'piloto':
                redirect('/view/layouts/piloto.php');
                break;
            case 'mecanico':
                redirect('/view/layouts/mecanico.php');
                break;
            default:
                redirect('/view/layouts/dashboard.php');
        }
    }
}

// Manejo de la solicitud
if (isset($_POST['action'])) {
    $auth = new AuthController();
    switch ($_POST['action']) {
        case 'login':
            $auth->login();
            break;
        // Otros casos según necesidad
    }
}