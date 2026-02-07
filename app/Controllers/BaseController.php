<?php

namespace App\Controllers;

class BaseController
{
    /**
     * Renders an admin page with admin header/footer
     */
    protected function renderAdmin($view, $data = [])
    {
        extract($data);
        $header = __DIR__ . '/../../views/partials/dashboard_header.php';
        $viewPath =  __DIR__ . "/../../views/admin/{$view}.php";
        $footer = __DIR__ . '/../../views/partials/dashboard_footer.php';

        if (file_exists($viewPath)) {
            require_once $header;
            require_once $viewPath;
            require_once $footer;
        } else {
            die("View not found: {$viewPath}");
        }
    }

    /**
     * Renders a guardian page with guardian header/footer
     */
    protected function renderGuardian($view, $data = [])
    {
        extract($data);
        $header = __DIR__ . '/../../views/partials/dashboard_header.php';
        $viewPath =  __DIR__ . "/../../views/guardian/{$view}.php";
        $footer = __DIR__ . '/../../views/partials/dashboard_footer.php';

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
