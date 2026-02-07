<?php
namespace App\Controllers;

class BaseController
{
    protected function render($view, $data = [])
    {
        extract($data);
        $header = __DIR__ . "/../../views/partials/dashboard_header.php";
        $viewPath = __DIR__ . "/../../views/{$view}.php";
        $footer = __DIR__ . "/../../views/partials/dashboard_footer.php";

        if (file_exists($viewPath)) {
            require_once $header;
            require_once $viewPath;
            require_once $footer;
        } else {
            die("View not found: {$viewPath}");
        }
    }

    /**
     * Renders a clean view (like Login/Register) without sidebars
     */
    protected function renderAuth($view, $data = [])
    {
        extract($data);

        $header = __DIR__ . '/../../views/partials/auth_header.php';
        $viewPath =  __DIR__ . "/../../views/{$view}.php";
        $footer = __DIR__ . '/../../views/partials/auth_footer.php';

        if (file_exists($viewPath)) {
            require_once $header;
            require_once $viewPath;
            require_once $footer;
        } else {
            die("View not found: {$viewPath}");
        }
    }
}
