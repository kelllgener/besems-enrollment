<?php
namespace App\Controllers;

class DashboardController extends BaseController {
    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: login");
            exit();
        }

        $this->render('dashboard', [
            "pageTitle" => "Dashboard - BESEMS",
            'name' => $_SESSION['name'],
        ]);
    }
}