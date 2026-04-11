<div class="row">
    <div class="col-lg-10 mx-auto">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="bi bi-clipboard-check me-2"></i>Review Enrollment
                </h3>
                <p class="text-muted mb-0">Review student information and requirements</p>
            </div>
            <a href="enrollments" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to List
            </a>
        </div>

        <div class="row g-4">
            <!-- Left Column - Student Information -->
            <div class="col-lg-8">
                <!-- Student Profile Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-person-vcard me-2"></i>Student Information
                            </h5>
                            <?php
                            $status = $student['enrollment_status'] ?? 'Pending';
                            $badge = match($status) {
                                'Approved' => 'success',
                                'For Review' => 'info',
                                'Pending' => 'warning',
                                'Declined' => 'danger',
                                'Incomplete' => 'secondary',
                                default => 'secondary'
                            };
                            ?>
                            <span class="badge bg-<?= $badge ?> fs-6">
                                <?= htmlspecialchars($status) ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Full Name</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($student['first_name'] . ' ' . 
                                        ($student['middle_name'] ? $student['middle_name'] . ' ' : '') . 
                                        $student['last_name'] . ' ' . 
                                        ($student['name_extension'] ?? '')) ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small">LRN</label>
                                <div class="fw-semibold">
                                    <span class="badge bg-secondary font-monospace">
                                        <?= htmlspecialchars($student['lrn']) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small">Gender</label>
                                <div class="fw-semibold">
                                    <i class="bi bi-gender-<?= strtolower($student['gender']) ?> me-1"></i>
                                    <?= htmlspecialchars($student['gender']) ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Date of Birth</label>
                                <div class="fw-semibold">
                                    <?= date('F d, Y', strtotime($student['date_of_birth'])) ?>
                                    (<?= $student['age'] ?> years old)
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Place of Birth</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($student['place_of_birth'] ?? 'N/A') ?>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                            <div class="col-md-6">
                                <label class="text-muted small">House Number & Street</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars(trim(($student['house_number'] ?? '') . ' ' . ($student['street_name'] ?? '')) ?: 'N/A') ?>
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
                            <div class="col-md-6">
                                <label class="text-muted small">Zip Code</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($student['zip_code'] ?? 'N/A') ?>
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
                                <h6 class="text-primary border-bottom pb-2">Father's Information</h6>
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
                                    <label class="text-muted small">Contact</label>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($student['father_contact'] ?? 'N/A') ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Mother -->
                            <div class="col-md-6">
                                <h6 class="text-primary border-bottom pb-2">Mother's Information</h6>
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
                                    <label class="text-muted small">Contact</label>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($student['mother_contact'] ?? 'N/A') ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Guardian/Primary Contact -->
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">Guardian/Primary Contact</h6>
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
                                <h6 class="text-primary border-bottom pb-2">Account Guardian</h6>
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
                                            <?= htmlspecialchars($student['guardian_email']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Contact Number</label>
                                        <div class="fw-semibold">
                                            <?= htmlspecialchars($student['guardian_contact']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- School Information -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="bi bi-building me-2"></i>School Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="text-muted small">Enrollment Type</label>
                                <div class="fw-semibold">
                                    <span class="badge bg-primary">
                                        <?= htmlspecialchars($student['enrollment_type']) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Previous School</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($student['previous_school'] ?? 'N/A') ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Previous Grade Level</label>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($student['previous_grade_level'] ?? 'N/A') ?>
                                </div>
                            </div>
                            <?php if ($student['assigned_section_id']): ?>
                                <div class="col-md-6">
                                    <label class="text-muted small">Currently Assigned To</label>
                                    <div class="fw-semibold">
                                        <span class="badge bg-success">
                                            <?= htmlspecialchars($student['grade_name']) ?> - <?= htmlspecialchars($student['section_name']) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Room Number</label>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($student['room_number'] ?? 'N/A') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Requirements & Actions -->
            <div class="col-lg-4">
                <!-- Requirements Checklist -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clipboard-check me-2"></i>Requirements Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $requirements = [
                            'birth_certificate' => 'PSA Birth Certificate',
                            'report_card_form137' => 'Report Card (Form 137)',
                            'good_moral_certificate' => 'Good Moral Certificate',
                            'certificate_of_completion' => 'Certificate of Completion',
                            'id_picture_2x2' => '2x2 ID Picture',
                            'transfer_credential' => 'Transfer Credential',
                            'medical_certificate' => 'Medical Certificate'
                        ];

                        $completed = 0;
                        $total = count($requirements);
                        ?>

                        <ul class="list-group list-group-flush">
                            <?php foreach ($requirements as $key => $label): ?>
                                <?php 
                                $isSubmitted = !empty($student[$key]);
                                if ($isSubmitted) $completed++;
                                ?>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span class="<?= $isSubmitted ? 'text-decoration-line-through text-muted' : '' ?>">
                                        <?= $label ?>
                                    </span>
                                    <?php if ($isSubmitted): ?>
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    <?php else: ?>
                                        <i class="bi bi-x-circle text-danger"></i>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small fw-semibold">Progress</span>
                                <span class="small fw-semibold"><?= $completed ?>/<?= $total ?></span>
                            </div>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar <?= $completed === $total ? 'bg-success' : 'bg-warning' ?>" 
                                     style="width: <?= ($completed / $total) * 100 ?>%">
                                    <?= round(($completed / $total) * 100) ?>%
                                </div>
                            </div>
                        </div>

                        <?php if ($student['submitted_at']): ?>
                            <div class="alert alert-info mt-3 mb-0">
                                <small>
                                    <i class="bi bi-clock-history me-1"></i>
                                    Submitted: <?= date('M d, Y g:i A', strtotime($student['submitted_at'])) ?>
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Review History -->
                <?php if ($student['reviewed_at'] || $student['remarks']): ?>
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-clock-history me-2"></i>Review History
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if ($student['reviewed_at']): ?>
                                <div class="mb-2">
                                    <small class="text-muted">Last Reviewed</small>
                                    <div class="fw-semibold">
                                        <?= date('M d, Y g:i A', strtotime($student['reviewed_at'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($student['approved_at']): ?>
                                <div class="mb-2">
                                    <small class="text-muted">Approved On</small>
                                    <div class="fw-semibold text-success">
                                        <?= date('M d, Y g:i A', strtotime($student['approved_at'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($student['remarks']): ?>
                                <div>
                                    <small class="text-muted">Previous Remarks</small>
                                    <div class="alert alert-warning mt-1 mb-0">
                                        <?= nl2br(htmlspecialchars($student['remarks'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Action Form -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i>Review Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="review-enrollment?id=<?= $student['student_id'] ?>" id="reviewForm">
                            <!-- Section Assignment -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Assign to Section <span class="text-danger">*</span>
                                </label>
                                <select name="section_id" id="section_id" class="form-select" required>
                                    <option value="">Select Section</option>
                                    <?php 
                                    $current_grade = '';
                                    foreach ($sections as $section): 
                                        if ($current_grade !== $section['grade_name']) {
                                            if ($current_grade !== '') echo '</optgroup>';
                                            echo '<optgroup label="' . htmlspecialchars($section['grade_name']) . '">';
                                            $current_grade = $section['grade_name'];
                                        }
                                        $slots_left = $section['max_students'] - $section['current_students'];
                                        $is_full = $slots_left <= 0;
                                    ?>
                                        <option value="<?= $section['section_id'] ?>" 
                                                <?= $is_full ? 'disabled' : '' ?>
                                                <?= $student['section_id'] == $section['section_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($section['section_name']) ?> 
                                            (<?= $section['current_students'] ?>/<?= $section['max_students'] ?>)
                                            <?= $is_full ? '- FULL' : '' ?>
                                        </option>
                                    <?php 
                                    endforeach; 
                                    if ($current_grade !== '') echo '</optgroup>';
                                    ?>
                                </select>
                                <small class="text-muted">Required for approval</small>
                            </div>

                            <!-- Remarks -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Remarks / Notes</label>
                                <textarea name="remarks" class="form-control" rows="4" 
                                          placeholder="Add any comments or instructions..."><?= htmlspecialchars($student['remarks'] ?? '') ?></textarea>
                                <small class="text-muted">Will be visible to the guardian</small>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                <button type="submit" name="action" value="approve" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Approve Enrollment
                                </button>
                                <button type="submit" name="action" value="return_incomplete" class="btn btn-warning">
                                    <i class="bi bi-arrow-return-left me-2"></i>Return as Incomplete
                                </button>
                                <button type="submit" name="action" value="decline" class="btn btn-danger" 
                                        onclick="return confirm('Are you sure you want to decline this enrollment?')">
                                    <i class="bi bi-x-circle me-2"></i>Decline Enrollment
                                </button>
                                <a href="enrollments" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Back to List
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    const action = e.submitter.value;
    const sectionId = document.getElementById('section_id').value;
    const remarks = document.querySelector('textarea[name="remarks"]').value.trim();

    if (action === 'approve' && !sectionId) {
        e.preventDefault();
        alert('Please select a section before approving');
        return false;
    }

    if (action === 'decline' && !remarks) {
        e.preventDefault();
        alert('Please provide a reason for declining');
        return false;
    }

    if (action === 'return_incomplete' && !remarks) {
        e.preventDefault();
        alert('Please specify what requirements are missing');
        return false;
    }
});
</script>