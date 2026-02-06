<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

// Get the requested page, default to login
$page = $_GET['page'] ?? 'login';

if ($page === 'home') {
    // This calls the HomeController which will then LOAD the view
    $controller = new \App\Controllers\HomeController();
    $controller->index();
} elseif ($page === 'login') {
    $controller = new \App\Controllers\AuthController();
    $controller->login();
}