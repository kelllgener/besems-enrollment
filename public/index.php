<?php

namespace App\Controllers;

session_start();
require_once __DIR__ . '/../vendor/autoload.php';

$page = $_GET['page'] ?? 'login';
// Clean the path (remove trailing slashes)
$page = rtrim($page, '/');

switch ($page) {

    // Authentication routes
    case 'login':
        (new AuthController())->login();
        break;
    case 'register':
        (new AuthController())->register();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;


    // Dashboard route
    case 'dashboard':
        $dashboardController = new DashboardController();
        $_SESSION['role'] === 'admin' ? $dashboardController->adminDashboard() : $dashboardController->guardianDashboard();
        break;


    // Guardian routes
    case 'my-students':
        (new GuardianController())->myStudents();
        break;
    case 'add-student':
        (new GuardianController())->addStudent();
        break;
    case 'requirements':
        (new GuardianController())->requirements();
        break;
    case 'announcements':
        (new GuardianController())->announcements();
        break;
    case 'settings':
        (new GuardianController())->settings();
        break;

    default:
        http_response_code(404);
        echo "404 - Page not found";
        break;


    // Admin routes

}
