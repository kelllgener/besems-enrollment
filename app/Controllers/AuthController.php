<?php

namespace App\Controllers;

use App\Models\User;

class AuthController extends BaseController
{

    public function login()
    {
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
                $this->renderAuth('auth/login', ['error' => $error]);
            }
        } else {
            $this->renderAuth('auth/login');
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle registration logic here
            // Validate input, create user, etc.
        } else {
            $this->renderAuth('auth/register');
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: login");
        exit();
    }
}
