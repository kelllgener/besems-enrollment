<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        // Logic happens here
        $name = "Student"; 
        
        // Pass data to the view
        require_once __DIR__ . '/../../views/home.php';
    }
}