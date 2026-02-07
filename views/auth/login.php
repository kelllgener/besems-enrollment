<div class="text-center mb-4">
    <h2 class="fw-bold text-primary mb-2">BESEMS</h2>
    <p class="text-muted">Barangay Elementary School Enrollment Management System</p>
</div>

<?php if (isset($_SESSION['registration_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['registration_success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['registration_success']); ?>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form method="POST" action="login">
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
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
        </button>
    </div>
</form>

<hr class="my-4">

<div class="text-center">
    <p class="text-muted mb-2">Don't have an account?</p>
    <a href="register" class="btn btn-outline-primary">
        <i class="bi bi-person-plus me-2"></i>Create Guardian Account
    </a>
</div>

<div class="mt-4 p-3 bg-light rounded text-center">
    <small class="text-muted">
        <i class="bi bi-shield-check me-1"></i>
        For admin access, please contact the school administrator
    </small>
</div>