
<div class="bg-light min-vh-90 d-flex align-items-center justify-content-center">
  <div class="w-100" style="max-width: 480px;">
    <!-- Large screens: scrollable box -->
    <div class="d-none d-lg-block" style="height:90vh; overflow-y:auto;">
      <div class="brand-logo">
          <h2>BESEMS</h2>
          <p>Guardian Registration</p>
          <small class="text-muted">Create your account to manage student enrollments</small>
      </div>
      <?php if (isset($errors) && !empty($errors)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong><i class="bi bi-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
              <ul class="mb-0 mt-2">
                  <?php foreach ($errors as $error): ?>
                      <li><?= htmlspecialchars($error) ?></li>
                  <?php endforeach; ?>
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      <?php endif; ?>
      <div class="alert alert-info alert-dismissible fade show" role="alert">
          <i class="bi bi-info-circle me-2"></i>
          <strong>Note:</strong> After registration, you can add your children's complete information from your dashboard.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <form action="register" method="POST">
          <div class="mb-3">
              <label class="form-label fw-semibold">
                  Username <span class="text-danger">*</span>
              </label>
              <input type="text" name="username" class="form-control" 
                     placeholder="Choose a username" 
                     value="<?= htmlspecialchars($old['username'] ?? '') ?>" 
                     pattern="[a-zA-Z0-9_]{4,20}"
                     required>
              <small class="text-muted">4-20 characters (letters, numbers, and underscore only)</small>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">
                  Email Address <span class="text-danger">*</span>
              </label>
              <input type="email" name="email" class="form-control" 
                     placeholder="your.email@example.com" 
                     value="<?= htmlspecialchars($old['email'] ?? '') ?>" 
                     required>
              <small class="text-muted">We'll use this for important notifications</small>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">
                  Contact Number <span class="text-danger">*</span>
              </label>
              <input type="tel" name="contact_number" class="form-control" 
                     placeholder="09123456789" 
                     pattern="09[0-9]{9}" 
                     value="<?= htmlspecialchars($old['contact_number'] ?? '') ?>" 
                     maxlength="11"
                     required>
              <small class="text-muted">Format: 09XXXXXXXXX</small>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">
                  Password <span class="text-danger">*</span>
              </label>
              <input type="password" name="password" class="form-control" 
                     placeholder="••••••••" 
                     minlength="6" 
                     required>
              <small class="text-muted">At least 6 characters</small>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">
                  Confirm Password <span class="text-danger">*</span>
              </label>
              <input type="password" name="confirm_password" class="form-control" 
                     placeholder="••••••••" 
                     minlength="6" 
                     required>
          </div>
          <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" id="terms" required>
              <label class="form-check-label small" for="terms">
                  I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
              </label>
          </div>
          <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary btn-lg">
                  <i class="bi bi-person-plus-fill me-2"></i>Create Guardian Account
              </button>
              <a href="login" class="btn btn-link text-decoration-none">
                  <i class="bi bi-arrow-left me-1"></i>Already have an account? Sign In
              </a>
          </div>
      </form>
      <div class="mt-4 p-3 bg-light rounded">
          <h6 class="fw-bold text-primary mb-2">
              <i class="bi bi-question-circle me-2"></i>What's Next?
          </h6>
          <ol class="mb-0 small">
              <li>Create your guardian account</li>
              <li>Log in to your dashboard</li>
              <li>Add your children's complete information</li>
              <li>Submit enrollment requirements</li>
              <li>Wait for admin approval</li>
          </ol>
      </div>
    </div>
    <!-- Small screens: normal scroll -->
    <div class="d-block d-lg-none">
      <div class="brand-logo">
          <h2>BESEMS</h2>
          <p>Guardian Registration</p>
          <small class="text-muted">Create your account to manage student enrollments</small>
      </div>
      <?php if (isset($errors) && !empty($errors)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong><i class="bi bi-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
              <ul class="mb-0 mt-2">
                  <?php foreach ($errors as $error): ?>
                      <li><?= htmlspecialchars($error) ?></li>
                  <?php endforeach; ?>
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      <?php endif; ?>
      <div class="alert alert-info alert-dismissible fade show" role="alert">
          <i class="bi bi-info-circle me-2"></i>
          <strong>Note:</strong> After registration, you can add your children's complete information from your dashboard.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <form action="register" method="POST">
          <div class="mb-3">
              <label class="form-label fw-semibold">
                  Username <span class="text-danger">*</span>
              </label>
              <input type="text" name="username" class="form-control" 
                     placeholder="Choose a username" 
                     value="<?= htmlspecialchars($old['username'] ?? '') ?>" 
                     pattern="[a-zA-Z0-9_]{4,20}"
                     required>
              <small class="text-muted">4-20 characters (letters, numbers, and underscore only)</small>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">
                  Email Address <span class="text-danger">*</span>
              </label>
              <input type="email" name="email" class="form-control" 
                     placeholder="your.email@example.com" 
                     value="<?= htmlspecialchars($old['email'] ?? '') ?>" 
                     required>
              <small class="text-muted">We'll use this for important notifications</small>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">
                  Contact Number <span class="text-danger">*</span>
              </label>
              <input type="tel" name="contact_number" class="form-control" 
                     placeholder="09123456789" 
                     pattern="09[0-9]{9}" 
                     value="<?= htmlspecialchars($old['contact_number'] ?? '') ?>" 
                     maxlength="11"
                     required>
              <small class="text-muted">Format: 09XXXXXXXXX</small>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">
                  Password <span class="text-danger">*</span>
              </label>
              <input type="password" name="password" class="form-control" 
                     placeholder="••••••••" 
                     minlength="6" 
                     required>
              <small class="text-muted">At least 6 characters</small>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">
                  Confirm Password <span class="text-danger">*</span>
              </label>
              <input type="password" name="confirm_password" class="form-control" 
                     placeholder="••••••••" 
                     minlength="6" 
                     required>
          </div>
          <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" id="terms" required>
              <label class="form-check-label small" for="terms">
                  I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
              </label>
          </div>
          <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary btn-lg">
                  <i class="bi bi-person-plus-fill me-2"></i>Create Guardian Account
              </button>
              <a href="login" class="btn btn-link text-decoration-none">
                  <i class="bi bi-arrow-left me-1"></i>Already have an account? Sign In
              </a>
          </div>
      </form>
      <div class="mt-4 p-3 bg-light rounded">
          <h6 class="fw-bold text-primary mb-2">
              <i class="bi bi-question-circle me-2"></i>What's Next?
          </h6>
          <ol class="mb-0 small">
              <li>Create your guardian account</li>
              <li>Log in to your dashboard</li>
              <li>Add your children's complete information</li>
              <li>Submit enrollment requirements</li>
              <li>Wait for admin approval</li>
          </ol>
      </div>
    </div>
  </div>
</div>
