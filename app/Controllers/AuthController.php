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
                // Check if account is active
                if ($user['is_active'] != 1) {
                    $error = "Your account has been deactivated. Please contact the administrator.";
                    $this->renderAuth('auth/login', ['error' => $error]);
                    return;
                }

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['username']; // Using username since we don't have first_name anymore
                $_SESSION['email'] = $user['email'];

                header("Location: dashboard");
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
            $errors = [];

            // Validate required fields
            $required_fields = ['username', 'email', 'contact_number', 'password', 'confirm_password'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required";
                }
            }

            // Validate password match
            if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
                if ($_POST['password'] !== $_POST['confirm_password']) {
                    $errors[] = "Passwords do not match";
                }
                if (strlen($_POST['password']) < 6) {
                    $errors[] = "Password must be at least 6 characters long";
                }
            }

            // Validate email format
            if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            }

            // Validate contact number (Philippine format)
            if (!empty($_POST['contact_number'])) {
                $contact = preg_replace('/[^0-9]/', '', $_POST['contact_number']);
                if (!preg_match('/^09[0-9]{9}$/', $contact)) {
                    $errors[] = "Contact number must be in format: 09XXXXXXXXX";
                }
            }

            // Validate username (alphanumeric and underscore only)
            if (!empty($_POST['username'])) {
                if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $_POST['username'])) {
                    $errors[] = "Username must be 4-20 characters (letters, numbers, and underscore only)";
                }
            }

            // If no validation errors, proceed with registration
            if (empty($errors)) {
                $userModel = new User();

                // Check if username already exists
                if ($userModel->findByUsername($_POST['username'])) {
                    $errors[] = "Username already exists";
                }

                // Check if email already exists
                if ($userModel->findByEmail($_POST['email'])) {
                    $errors[] = "Email already exists";
                }

                // If still no errors, create the user
                if (empty($errors)) {
                    $data = [
                        'username' => trim($_POST['username']),
                        'password' => $_POST['password'],
                        'email' => trim($_POST['email']),
                        'contact_number' => trim($_POST['contact_number'])
                    ];

                    if ($userModel->create($data)) {
                        // Registration successful
                        $_SESSION['registration_success'] = "Registration successful! Please login with your credentials.";
                        header("Location: login");
                        exit();
                    } else {
                        $errors[] = "Registration failed. Please try again.";
                    }
                }
            }

            // If there are errors, show the form again with errors
            $this->renderAuth('auth/register', [
                'errors' => $errors,
                'old' => $_POST // Keep old input values
            ]);
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