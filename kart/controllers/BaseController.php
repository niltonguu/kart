<?php
class BaseController {
    protected $db;
    protected $config;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->config = require __DIR__ . '/../config/env.php';
    }

    protected function view($template, $data = []) {
        extract($data);
        require __DIR__ . '/../view/' . $template . '.php';
    }

    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}