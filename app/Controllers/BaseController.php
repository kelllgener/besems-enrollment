<?php

namespace App\Controllers;

class BaseController
{
    // Render dashboard views
    protected function render($view, $data = [])
    {
        extract($data);

        $viewPath = $this->getCurrentUserRole() === 'admin'
            ? __DIR__ . "/../../views/admin/{$view}.php"
            : __DIR__ . "/../../views/guardian/{$view}.php";

        $header = __DIR__ . '/../../views/partials/dashboard_header.php';
        $footer = __DIR__ . '/../../views/partials/dashboard_footer.php';

        if (file_exists($viewPath)) {
            require_once $header;
            require_once $viewPath;
            require_once $footer;
        } else {
            die("View not found: {$viewPath}");
        }
    }

    // Render auth views (login, register)
    protected function renderAuth($view, $data = [])
    {
        extract($data);

        $header = __DIR__ . '/../../views/partials/auth_header.php';
        $viewPath = __DIR__ . "/../../views/{$view}.php";
        $footer = __DIR__ . '/../../views/partials/auth_footer.php';

        if (file_exists($viewPath)) {
            require_once $header;
            require_once $viewPath;
            require_once $footer;
        } else {
            die("View not found: {$viewPath}");
        }
    }

    // Check if user is logged in
    protected function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login');
            exit;
        }
    }

    // Check if user has specific role
    protected function requireRole($role)
    {
        $this->requireAuth();

        if ($_SESSION['role'] !== $role) {
            header('Location: login');
            exit;
        }
    }

    // Check if user is guardian
    protected function requireGuardian()
    {
        $this->requireRole('guardian');
    }

    // Check if user is admin
    protected function requireAdmin()
    {
        $this->requireRole('admin');
    }

    // Get current user ID
    protected function getCurrentUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    // Get current user role
    protected function getCurrentUserRole()
    {
        return $_SESSION['role'] ?? null;
    }

    // Validate required fields
    protected function validateRequired($data, $fields)
    {
        $errors = [];

        foreach ($fields as $field) {
            if (empty($data[$field])) {
                $label = ucfirst(str_replace('_', ' ', $field));
                $errors[] = "{$label} is required";
            }
        }

        return $errors;
    }

    // Validate email
    protected function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }
        return null;
    }

    // Validate Philippine contact number
    protected function validateContactNumber($contact)
    {
        if (!preg_match('/^09[0-9]{9}$/', $contact)) {
            return "Contact number must be in format: 09XXXXXXXXX";
        }
        return null;
    }

    // Validate password strength
    protected function validatePassword($password, $min_length = 6)
    {
        if (strlen($password) < $min_length) {
            return "Password must be at least {$min_length} characters long";
        }
        return null;
    }

    // Set success message in session
    protected function setSuccessMessage($message)
    {
        $_SESSION['success_message'] = $message;
    }

    // Set error message in session
    protected function setErrorMessage($message)
    {
        $_SESSION['error_message'] = $message;
    }

    // Get and clear success message
    protected function getSuccessMessage()
    {
        $message = $_SESSION['success_message'] ?? null;
        unset($_SESSION['success_message']);
        return $message;
    }

    // Get and clear error message
    protected function getErrorMessage()
    {
        $message = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        return $message;
    }

    // Redirect with message
    protected function redirectWithSuccess($url, $message)
    {
        $this->setSuccessMessage($message);
        header("Location: {$url}");
        exit;
    }

    protected function redirectWithError($url, $message)
    {
        $this->setErrorMessage($message);
        header("Location: {$url}");
        exit;
    }

    // Sanitize input data
    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }

        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}
