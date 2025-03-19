<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('/login.php');
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION['user_role'] !== $role) {
        setError('No tienes permiso para acceder a esta sección');
        redirect('/dashboard.php');
    }
}

function setError($message) {
    $_SESSION['error'] = $message;
}

function redirect($path) {
    header("Location: $path");
    exit();
}

function logout() {
    session_destroy();
    redirect('/login.php');
}