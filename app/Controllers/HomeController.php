<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        $name = $_SESSION['name'];

        // Load the view from the views folder
        require_once __DIR__ . '/../../views/home.php';
    }
}