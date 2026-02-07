<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="assets/images/logo.jpg" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'BESEMS Dashboard' ?></title>
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">

    <?php include __DIR__ . '/sidebar.php'; ?>

    <div id="content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary sidebar-toggle d-md-none me-2" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>

                <span class="navbar-brand mb-0 h1"><?= $pageTitle ?? 'Dashboard' ?></span>

                <div class="ms-auto d-flex align-items-center">
                    <span class="navbar-text me-3">
                        <span id="realtime-clock"></span>
                    </span>

                </div>
            </div>
        </nav>

        <script>
            function updateClock() {
                const now = new Date();
                const dateOptions = {
                    year: 'numeric',
                    month: 'short',
                    day: '2-digit'
                };
                const timeOptions = {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };
                const dateStr = now.toLocaleDateString([], dateOptions);
                const timeStr = now.toLocaleTimeString([], timeOptions);
                document.getElementById('realtime-clock').textContent = dateStr + ' ' + timeStr;
            }
            setInterval(updateClock, 1000);
            updateClock();
        </script>

        <!-- Main Content Area -->
        <div class="container-fluid p-4">