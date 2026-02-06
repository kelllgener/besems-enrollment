<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userModel = new User();
            $user = $userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['first_name'];

                header("Location: home");
                exit();
            } else {
                $error = "Invalid username or password";
                require_once __DIR__ . '/../../views/auth/login.php';
            }
        } else {
            require_once __DIR__ . '/../../views/auth/login.php';
        }
    }

    public function logout() {
        session_destroy();
        header("Location: login");
    }
}