<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'BESEMS Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .wrapper { display: flex; width: 100%; align-items: stretch; }
        #sidebar { min-width: 250px; max-width: 250px; min-height: 100vh; transition: all 0.3s; }
        .content { width: 100%; padding: 20px; }
    </style>
</head>
<body>

<div class="wrapper">
    <nav id="sidebar" class="bg-dark text-white p-3">
        <h3>BESEMS</h3>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="home" class="nav-link text-white">Dashboard</a>
            </li>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a href="subjects" class="nav-link text-white">Subjects</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a href="logout" class="nav-link text-danger mt-5">Logout</a>
            </li>
        </ul>
    </nav>

    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 shadow-sm">
            <div class="container-fluid">
                <span class="navbar-text">Logged in as: <strong><?= $_SESSION['name'] ?></strong></span>
            </div>
        </nav>