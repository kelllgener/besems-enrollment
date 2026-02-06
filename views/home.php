<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>BESEMS</title>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="text-primary">Welcome, <?= $name ?>!</h1>
                <p>Status: <span class="badge bg-success">Connected to Database</span></p>
                <a href="logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
</body>

</html>