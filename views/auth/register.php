<div class="text-center mb-4">
    <h2 class="fw-bold text-primary mb-2">BESEMS</h2>
    <p class="text-muted">Create your account</p>
</div>

<form action="register" method="POST">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">First Name</label>
            <input type="text" name="first_name" class="form-control" placeholder="John" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Last Name</label>
            <input type="text" name="last_name" class="form-control" placeholder="Doe" required>
        </div>

        <div class="col-12">
            <label class="form-label fw-semibold">Username</label>
            <input type="text" name="username" class="form-control" placeholder="johndoe" required>
        </div>

        <div class="col-12">
            <label class="form-label fw-semibold">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
        </div>

        <div class="col-12">
            <label class="form-label fw-semibold">Contact Number</label>
            <input type="text" name="contact_number" class="form-control" placeholder="09xxxxxxxxx" pattern="09[0-9]{9}">
        </div>

        <div class="col-12">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div class="col-12">
            <label class="form-label fw-semibold">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
        </div>
    </div>

    <div class="d-grid gap-2 mt-4">
        <button type="submit" class="btn btn-primary btn-lg">Create Account</button>
        <a href="login" class="btn btn-link text-decoration-none">Already have an account? Sign In</a>
    </div>
</form>