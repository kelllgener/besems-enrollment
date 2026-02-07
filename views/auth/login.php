<div class="text-center mb-4">
    <h2 class="fw-bold text-primary mb-2">BESEMS</h2>
    <p class="text-muted">Sign in to your account</p>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form method="POST" action="index.php?action=login">
    <div class="mb-3">
        <label for="username" class="form-label fw-semibold">Username</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required autofocus>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label fw-semibold">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
            <label class="form-check-label" for="remember_me">
                Remember me
            </label>
        </div>
        <a href="forgot-password" class="text-decoration-none small">Forgot password?</a>
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary btn-lg">Sign In</button>
    </div>
</form>

<hr class="my-4">

<div class="text-center">
    <p class="text-muted mb-0">Don't have an account? <a href="register" class="text-decoration-none fw-semibold">Create account</a></p>
</div>