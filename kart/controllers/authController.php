<?php
class AuthController extends BaseController {
    public function __construct() {
        parent::__construct();
        session_start();
    }

    public function login($username, $password) {
        try {
            $sql = "SELECT * FROM usuarios WHERE username = ?";
            $user = $this->db->fetchOne($sql, [$username]);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ];
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }

    public function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    public function getCurrentUser() {
        return $_SESSION['user'] ?? null;
    }
}