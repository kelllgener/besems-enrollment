<?php
namespace App\Controllers;

session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\HomeController;


$page = $_GET['page'] ?? 'login';
// Clean the path (remove trailing slashes)
$page = rtrim($page, '/');

switch ($page) {
    case 'home':
        (new HomeController())->index();
        break;
    case 'login':
        (new AuthController())->login();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;

    default:
        http_response_code(404);
        echo "404 - Page not found";
        break;
}