<?php
return [
    'app_name' => 'Kart Management System',
    'debug' => true,
    'database' => [
        'host' => 'localhost',
        'name' => 'nil_kart',
        'user' => 'niltongu',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'session' => [
        'lifetime' => 3600, // 1 hora
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true
    ]
];