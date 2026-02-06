<?php
namespace App\Controllers;

use BaseController;

class HomeController extends BaseController {
    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: login");
            exit();
        }

        $this->render('home', [
            "pageTitle" => "Dashboard - BESEMS",
            'name' => $_SESSION['name'],
        ]);
    }
}