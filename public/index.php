<?php

namespace App\Controllers;

session_start();
require_once __DIR__ . '/../vendor/autoload.php';

$page = $_GET['page'] ?? 'login';
// Clean the path (remove trailing slashes)
$page = rtrim($page, '/');

switch ($page) {
    case 'dashboard':
        $_SESSION['role'] === 'admin' ? (new AdminController())->dashboard() : (new GuardianController())->dashboard();
        break;
    case 'login':
        (new AuthController())->login();
        break;
    case 'register':
        (new AuthController())->register();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;

    default:
        http_response_code(404);
        echo "404 - Page not found";
        break;
}
