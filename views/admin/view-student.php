<div class="row">
    <div class="col-lg-10 mx-auto">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="bi bi-person-vcard me-2"></i>Student Profile
                </h3>
                <p class="text-muted mb-0">Complete student information</p>
            </div>
            <div class="btn-group">
                <a href="edit-student?id=<?= $student['student_id'] ?>" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i>Edit Student
                </a>
                <a href="students" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column - Main Information -->
            <div class="col-lg-8">
                <!-- Student Profile Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-person-vcard me-2"></i>Personal Information
                            </h5>
                            <div>
                                <?php
                                $status_badge = match($student['student_status']) {
                                    'Active' => 'success',
                                    'Inactive' => 'secondary',
                                    'Transferred' => 'warning',
                                    'Graduated' => 'info',
                                    'Dropped' => 'danger',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $status_badge ?> fs-6">
                                    <?= htmlspecialchars($student['student_status']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="text-muted small">Full Name</label>
                                <div class="fw-semibold fs-5">
                                    <?= htmlspecialchars($student['first_name'] . ' ' . 
                                        ($student['middle_name'] ? $student['middle_name'] . ' ' : '') . 
                                        $student['last_name'] . ' ' . 
                                        ($student['name_extension'] ?? '')) ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">LRN</label>
                                <div class="fw-semibold">
                                    <span class="badge bg-secondary font-monospace fs-6">
                                        <?= htmlspecialchars($student['lrn']) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Date of Birth</label>
                                <div class="fw-semibold">
                                    <?= date('F d, Y', strtotime($student['date_of_birth'])) ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Age</label>
                                <div class="fw-semibold">
                                    <?= $student['age'] ?> years old
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Gender</label>
                                <div class="fw-semibold">
                                    <i class="bi bi-gender-<?= strtolower($student['gender']) ?> me-1"></i>
                                    <?= htmlspecialchars($student['gender']) ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Place of Birth</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($student['place_of_birth'] ?? 'N/A') ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Mother Tongue</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($student['mother_tongue'] ?? 'N/A') ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Religion</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($student['religion'] ?? 'N/A') ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Indigenous People</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($student['indigenous_people'] ?? 'N/A') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-house-door me-2"></i>Address Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="text-muted small">Complete Address</label>
                                <div class="fw-semibold">
                                    <?php
                                    $address_parts = array_filter([
                                        $student['house_number'],
                                        $student['street_name'],
                                        $student['barangay'],
                                        $student['city_municipality'],
                                        $student['province'],
                                        $student['region'],
                                        $student['zip_code']
                                    ]);
                                    echo htmlspecialchars(implode(', ', $address_parts));
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Barangay</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($student['barangay']) ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">City/Municipality</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($student['city_municipality']) ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Province</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($student['province']) ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Region</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($student['region'] ?? 'N/A') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parent/Guardian Information -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-people me-2"></i>Parent/Guardian Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Father -->
                            <div class="col-md-6">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="bi bi-person me-1"></i>Father's Information
                                </h6>
                                <div class="mb-2">
                                    <label class="text-muted small">Name</label>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($student['father_name'] ?? 'N/A') ?>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="text-muted small">Occupation</label>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($student['father_occupation'] ?? 'N/A') ?>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-muted small">Contact Number</label>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($student['father_contact'] ?? 'N/A') ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Mother -->
                            <div class="col-md-6">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="bi bi-person me-1"></i>Mother's Information
                                </h6>
                                <div class="mb-2">
                                    <label class="text-muted small">Name</label>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($student['mother_name'] ?? 'N/A') ?>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="text-muted small">Occupation</label>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($student['mother_occupation'] ?? 'N/A') ?>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-muted small">Contact Number</label>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($student['mother_contact'] ?? 'N/A') ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Primary Guardian -->
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="bi bi-person-badge me-1"></i>Primary Guardian/Contact
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="text-muted small">Guardian Name</label>
                                        <div class="fw-semibold">
                                            <?= htmlspecialchars($student['guardian_name'] ?? 'N/A') ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Relationship</label>
                                        <div class="fw-semibold">
                                            <?= htmlspecialchars($student['guardian_relationship']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Occupation</label>
                                        <div class="fw-semibold">
                                            <?= htmlspecialchars($student['guardian_occupation'] ?? 'N/A') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Guardian -->
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="bi bi-person-circle me-1"></i>Account Guardian (System User)
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="text-muted small">Username</label>
                                        <div class="fw-semibold">
                                            <?= htmlspecialchars($student['guardian_username']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Email</label>
                                        <div class="fw-semibold">
                                            <a href="mailto:<?= htmlspecialchars($student['guardian_email']) ?>">
                                                <?= htmlspecialchars($student['guardian_email']) ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Contact Number</label>
                                        <div class="fw-semibold">
                                            <a href="tel:<?= htmlspecialchars($student['guardian_contact']) ?>">
                                                <?= htmlspecialchars($student['guardian_contact']) ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- School Information -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="bi bi-building me-2"></i>School Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Enrollment Type</label>
                                <div class="fw-semibold">
                                    <span class="badge bg-primary">
                                        <?= htmlspecialchars($student['enrollment_type']) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Student Status</label>
                                <div class="fw-semibold">
                                    <span class="badge bg-<?= $status_badge ?>">
                                        <?= htmlspecialchars($student['student_status']) ?>
                                    </span>
                                </div>
                            </div>
                            <?php if ($student['previous_school']): ?>
                                <div class="col-md-6">
                                    <label class="text-muted small">Previous School</label>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($student['previous_school']) ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Previous Grade Level</label>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($student['previous_grade_level'] ?? 'N/A') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Enrollment & Status -->
            <div class="col-lg-4">
                <!-- Current Assignment -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-grid-3x3-gap me-2"></i>Current Assignment
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($student['assigned_section_id']): ?>
                            <div class="text-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 80px; height: 80px;">
                                    <i class="bi bi-mortarboard-fill text-success" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="mb-1"><?= htmlspecialchars($student['grade_name']) ?></h4>
                                <p class="text-muted mb-0">
                                    <strong><?= htmlspecialchars($student['section_name']) ?></strong>
                                </p>
                                <?php if ($student['room_number']): ?>
                                    <p class="text-muted small">
                                        <i class="bi bi-door-open me-1"></i>Room <?= htmlspecialchars($student['room_number']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reassignModal">
                                    <i class="bi bi-arrow-repeat me-1"></i>Reassign Section
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="bi bi-x-circle text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2 mb-3">Not assigned to any section</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reassignModal">
                                    <i class="bi bi-plus-circle me-1"></i>Assign Section
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Enrollment Status -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clipboard-check me-2"></i>Enrollment Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <?php
                            $enrollment_status = $student['enrollment_status'] ?? 'Pending';
                            $enrollment_badge = match($enrollment_status) {
                                'Approved' => 'success',
                                'For Review' => 'info',
                                'Pending' => 'warning',
                                'Declined' => 'danger',
                                'Incomplete' => 'secondary',
                                default => 'secondary'
                            };
                            ?>
                            <span class="badge bg-<?= $enrollment_badge ?> fs-5 px-4 py-2">
                                <?= htmlspecialchars($enrollment_status) ?>
                            </span>
                        </div>

                        <?php if ($student['remarks']): ?>
                            <div class="alert alert-info">
                                <strong>Remarks:</strong><br>
                                <?= nl2br(htmlspecialchars($student['remarks'])) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($enrollment_status !== 'Approved'): ?>
                            <div class="d-grid">
                                <a href="review-enrollment?id=<?= $student['student_id'] ?>" class="btn btn-primary">
                                    <i class="bi bi-eye me-1"></i>Review Enrollment
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-lightning-fill me-2"></i>Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="edit-student?id=<?= $student['student_id'] ?>" class="btn btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i>Edit Information
                            </a>
                            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#statusModal">
                                <i class="bi bi-arrow-repeat me-1"></i>Change Status
                            </button>
                            <a href="student-management" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reassign Section Modal -->
<div class="modal fade" id="reassignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign/Reassign Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="assign-student-section">
                <div class="modal-body">
                    <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
                    
                    <p class="mb-3">
                        <strong><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></strong>
                    </p>
                    
                    <label class="form-label">Select Section</label>
                    <select name="section_id" class="form-select" required>
                        <option value="">Remove from section</option>
                        <!-- Sections will be loaded from the controller -->
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Student Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="change-student-status">
                <div class="modal-body">
                    <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
                    
                    <p class="mb-3">
                        <strong><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></strong><br>
                        <small class="text-muted">Current Status: <span class="badge bg-<?= $status_badge ?>"><?= htmlspecialchars($student['student_status']) ?></span></small>
                    </p>
                    
                    <label class="form-label">New Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Active" <?= $student['student_status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="Inactive" <?= $student['student_status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                        <option value="Transferred" <?= $student['student_status'] === 'Transferred' ? 'selected' : '' ?>>Transferred</option>
                        <option value="Graduated" <?= $student['student_status'] === 'Graduated' ? 'selected' : '' ?>>Graduated</option>
                        <option value="Dropped" <?= $student['student_status'] === 'Dropped' ? 'selected' : '' ?>>Dropped</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Change Status</button>
                </div>
            </form>
        </div>
    </div>
</div>