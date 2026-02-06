<?php
session_start();

// Load the Composer Autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;

// Basic Routing logic
$app = new HomeController();
$app->index();