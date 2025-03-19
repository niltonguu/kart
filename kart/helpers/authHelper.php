<?php
class AuthHelper {
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public static function generateToken() {
        return bin2hex(random_bytes(32));
    }

    public static function checkPermission($requiredRole) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $requiredRole) {
            header('Location: /login');
            exit;
        }
    }
}