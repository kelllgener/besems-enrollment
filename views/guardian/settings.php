<!-- Main Container -->
<div class="container-fluid py-4">
    
    <!-- 1. HEADER SECTION -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md">
            <h1 class="h3 fw-bold mb-1 text-dark">
                <i class="bi bi-gear-fill me-2 text-primary"></i>Account Settings
            </h1>
            <p class="text-muted mb-0">Manage your account information and preferences.</p>
        </div>
        <div class="col-md-auto">
            <a href="dashboard" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= $success_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= nl2br($error_message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Account Information Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body text-center p-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 100px; height: 100px;">
                        <i class="bi bi-person-fill text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($user['username']) ?></h5>
                    <p class="text-muted small mb-3">Guardian Account</p>
                    
                    <div class="d-grid gap-2 mb-4">
                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>Active Account
                        </span>
                    </div>

                    <hr class="my-4">

                    <div class="text-start">
                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-envelope-fill text-primary me-2 mt-1"></i>
                                <div class="flex-grow-1">
                                    <small class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.7rem;">Email</small>
                                    <span class="text-dark small"><?= htmlspecialchars($user['email']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-phone-fill text-primary me-2 mt-1"></i>
                                <div class="flex-grow-1">
                                    <small class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.7rem;">Contact</small>
                                    <span class="text-dark small"><?= htmlspecialchars($user['contact_number']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-calendar-fill text-primary me-2 mt-1"></i>
                                <div class="flex-grow-1">
                                    <small class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.7rem;">Member Since</small>
                                    <span class="text-dark small"><?= date('F d, Y', strtotime($user['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-link-45deg me-2 text-primary"></i>Quick Links
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="my-students" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="bi bi-people me-2 text-primary"></i>My Children
                        </a>
                        <a href="add-student" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="bi bi-person-plus me-2 text-primary"></i>Add New Child
                        </a>
                        <a href="requirements" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="bi bi-file-earmark-check me-2 text-primary"></i>Requirements
                        </a>
                        <a href="announcements" class="list-group-item list-group-item-action border-0 py-3">
                            <i class="bi bi-megaphone me-2 text-primary"></i>Announcements
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Forms -->
        <div class="col-lg-8">
            <!-- Profile Information -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-primary bg-opacity-10 border-0 py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-person-vcard me-2"></i>Profile Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="settings">
                        <input type="hidden" name="action" value="update_profile">

                        <div class="mb-4">
                            <label class="form-label small text-uppercase fw-bold text-muted">Username</label>
                            <input type="text" class="form-control bg-light border-0" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                            <small class="text-muted">Username cannot be changed</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-uppercase fw-bold text-muted">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" class="form-control border-0 bg-light" 
                                   value="<?= htmlspecialchars($user['email']) ?>" 
                                   required>
                            <small class="text-muted">Used for important notifications</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-uppercase fw-bold text-muted">
                                Contact Number <span class="text-danger">*</span>
                            </label>
                            <input type="tel" name="contact_number" class="form-control border-0 bg-light" 
                                   value="<?= htmlspecialchars($user['contact_number']) ?>" 
                                   pattern="09[0-9]{9}"
                                   maxlength="11"
                                   placeholder="09XXXXXXXXX"
                                   required>
                            <small class="text-muted">Format: 09XXXXXXXXX</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary rounded-pill shadow-sm">
                                <i class="bi bi-check-circle me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-warning bg-opacity-10 border-0 py-3">
                    <h5 class="mb-0 fw-bold text-warning">
                        <i class="bi bi-shield-lock me-2"></i>Change Password
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="settings" id="changePasswordForm">
                        <input type="hidden" name="action" value="change_password">

                        <div class="mb-4">
                            <label class="form-label small text-uppercase fw-bold text-muted">
                                Current Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" name="current_password" class="form-control border-0 bg-light" 
                                   placeholder="Enter current password" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-uppercase fw-bold text-muted">
                                New Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" name="new_password" class="form-control border-0 bg-light" 
                                   placeholder="Enter new password" 
                                   minlength="6" required>
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-uppercase fw-bold text-muted">
                                Confirm New Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" name="confirm_password" class="form-control border-0 bg-light" 
                                   placeholder="Re-enter new password" 
                                   minlength="6" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning rounded-pill shadow-sm">
                                <i class="bi bi-key me-2"></i>Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security & Privacy -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-info bg-opacity-10 border-0 py-3">
                    <h5 class="mb-0 fw-bold text-info">
                        <i class="bi bi-shield-check me-2"></i>Security & Privacy
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-4 h-100">
                                <h6 class="mb-2 fw-bold">
                                    <i class="bi bi-lock-fill text-success me-2"></i>Account Status
                                </h6>
                                <p class="text-muted small mb-0">
                                    Your account is currently active and secure
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-4 h-100">
                                <h6 class="mb-2 fw-bold">
                                    <i class="bi bi-clock-history text-primary me-2"></i>Last Updated
                                </h6>
                                <p class="text-muted small mb-0">
                                    <?= date('F d, Y g:i A', strtotime($user['updated_at'])) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning rounded-4 border-0 mb-0">
                        <h6 class="alert-heading fw-bold">
                            <i class="bi bi-exclamation-triangle me-2"></i>Data Privacy Notice
                        </h6>
                        <p class="small mb-0">
                            Your personal information is protected and will only be used for enrollment purposes. 
                            We do not share your information with third parties without your consent.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card shadow-sm border-danger rounded-4 mb-4">
                <div class="card-header bg-danger bg-opacity-10 border-danger py-3">
                    <h5 class="mb-0 fw-bold text-danger">
                        <i class="bi bi-exclamation-octagon me-2"></i>Danger Zone
                    </h5>
                </div>
                <div class="card-body p-4">
                    <h6 class="text-danger fw-bold mb-2">Deactivate Account</h6>
                    <p class="text-muted small mb-3">
                        Deactivating your account will prevent you from logging in and accessing your children's information. 
                        You can contact the school administrator to reactivate your account.
                    </p>
                    <button type="button" class="btn btn-outline-danger rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#deactivateModal">
                        <i class="bi bi-x-circle me-2"></i>Deactivate Account
                    </button>
                </div>
            </div>

            <!-- Help & Support -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-question-circle me-2 text-primary"></i>Help & Support
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">Need assistance? Contact the school office:</p>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-envelope-fill text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.7rem;">Email</small>
                                    <a href="mailto:school@besems.com" class="text-decoration-none text-dark fw-semibold small">school@besems.com</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-telephone-fill text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.7rem;">Phone</small>
                                    <a href="tel:09123456789" class="text-decoration-none text-dark fw-semibold small">(091) 234-5678</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-clock-fill text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.7rem;">Office Hours</small>
                                    <span class="text-dark fw-semibold small">Monday - Friday, 8:00 AM - 5:00 PM</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate Account Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1" aria-labelledby="deactivateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-danger bg-opacity-10 border-0">
                <h5 class="modal-title fw-bold text-danger" id="deactivateModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirm Account Deactivation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-danger rounded-4 border-0">
                    <h6 class="alert-heading fw-bold">Warning!</h6>
                    <p class="mb-0">
                        Are you sure you want to deactivate your account? This action will:
                    </p>
                    <ul class="mt-2 mb-0">
                        <li>Prevent you from logging in</li>
                        <li>Disable access to your children's information</li>
                        <li>Require admin approval to reactivate</li>
                    </ul>
                </div>
                <p class="text-muted mb-0">
                    If you're experiencing issues, please contact the school office instead.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="deactivate-account" class="d-inline">
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">
                        <i class="bi bi-x-circle me-2"></i>Yes, Deactivate My Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Password Match Validation -->
<script>
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    const newPassword = document.querySelector('input[name="new_password"]').value;
    const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('New passwords do not match!');
        return false;
    }
});
</script>