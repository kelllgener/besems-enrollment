<div class="row">
    <div class="col-lg-10 mx-auto">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="bi bi-file-earmark-check-fill me-2"></i>Student Requirements
                </h3>
                <p class="text-muted mb-0">Upload and manage enrollment documents</p>
            </div>
            <a href="guardian-dashboard" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>

        <!-- Success/Error Messages -->
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($all_students)): ?>
            <!-- No Students State -->
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-person-plus" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="mt-3 text-muted">No students enrolled yet</h5>
                    <p class="text-muted mb-3">Please add a student first before uploading requirements</p>
                    <a href="add-student" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Student
                    </a>
                </div>
            </div>
        <?php else: ?>

            <!-- Student Selection -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold mb-2">Select Student</label>
                            <select class="form-select form-select-lg" onchange="window.location.href='requirements?student_id=' + this.value">
                                <?php foreach ($all_students as $s): ?>
                                    <option value="<?= $s['student_id'] ?>" <?= $s['student_id'] == $selected_student_id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name']) ?> 
                                        (LRN: <?= htmlspecialchars($s['lrn']) ?>) - 
                                        <?= htmlspecialchars($s['enrollment_status'] ?? 'Not Enrolled') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="add-student" class="btn btn-outline-primary mt-4">
                                <i class="bi bi-plus-circle me-1"></i>Add New Student
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($student): ?>
                <!-- Student Info Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-vcard me-2"></i>Student Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Full Name:</strong><br>
                                <?= htmlspecialchars($student['first_name'] . ' ' . ($student['middle_name'] ?? '') . ' ' . $student['last_name']) ?>
                            </div>
                            <div class="col-md-2">
                                <strong>LRN:</strong><br>
                                <span class="badge bg-secondary"><?= htmlspecialchars($student['lrn']) ?></span>
                            </div>
                            <div class="col-md-2">
                                <strong>Age:</strong><br>
                                <?= $student['age'] ?> years old
                            </div>
                            <div class="col-md-2">
                                <strong>Gender:</strong><br>
                                <?= htmlspecialchars($student['gender']) ?>
                            </div>
                            <div class="col-md-2">
                                <strong>Status:</strong><br>
                                <?php
                                $status = $student['enrollment_status'] ?? 'Pending';
                                $badge_class = match($status) {
                                    'Approved' => 'success',
                                    'For Review' => 'info',
                                    'Pending' => 'warning',
                                    'Declined' => 'danger',
                                    'Incomplete' => 'secondary',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $badge_class ?>"><?= htmlspecialchars($status) ?></span>
                            </div>
                        </div>
                        <?php if ($student['grade_name']): ?>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <strong>Assigned:</strong> 
                                    <?= htmlspecialchars($student['grade_name']) ?> - <?= htmlspecialchars($student['section_name']) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Requirements Checklist -->
                <form method="POST" action="requirements?student_id=<?= $student['student_id'] ?>">
                    <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-clipboard-check me-2"></i>Required Documents (DepEd Standard)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Important:</strong> All required documents marked with <span class="text-danger">*</span> must be submitted for enrollment approval.
                            </div>

                            <!-- Birth Certificate -->
                            <div class="form-check form-switch mb-3 p-3 bg-light rounded">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="birth_certificate" 
                                       name="birth_certificate"
                                       <?= $student['birth_certificate'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="birth_certificate">
                                    <strong>PSA Birth Certificate <span class="text-danger">*</span></strong>
                                    <p class="text-muted small mb-0">Official birth certificate from PSA (Philippine Statistics Authority)</p>
                                </label>
                            </div>

                            <!-- Report Card / Form 137 -->
                            <div class="form-check form-switch mb-3 p-3 bg-light rounded">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="report_card_form137" 
                                       name="report_card_form137"
                                       <?= $student['report_card_form137'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="report_card_form137">
                                    <strong>Report Card (Form 137) <span class="text-danger">*</span></strong>
                                    <p class="text-muted small mb-0">Permanent record or report card from previous school</p>
                                </label>
                            </div>

                            <!-- Good Moral Certificate -->
                            <div class="form-check form-switch mb-3 p-3 bg-light rounded">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="good_moral_certificate" 
                                       name="good_moral_certificate"
                                       <?= $student['good_moral_certificate'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="good_moral_certificate">
                                    <strong>Certificate of Good Moral Character <span class="text-danger">*</span></strong>
                                    <p class="text-muted small mb-0">Issued by previous school (for transferees)</p>
                                </label>
                            </div>

                            <!-- Certificate of Completion -->
                            <div class="form-check form-switch mb-3 p-3 bg-light rounded">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="certificate_of_completion" 
                                       name="certificate_of_completion"
                                       <?= $student['certificate_of_completion'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="certificate_of_completion">
                                    <strong>Certificate of Completion <span class="text-danger">*</span></strong>
                                    <p class="text-muted small mb-0">For previous grade level completed</p>
                                </label>
                            </div>

                            <!-- ID Picture -->
                            <div class="form-check form-switch mb-3 p-3 bg-light rounded">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="id_picture_2x2" 
                                       name="id_picture_2x2"
                                       <?= $student['id_picture_2x2'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="id_picture_2x2">
                                    <strong>2x2 ID Picture <span class="text-danger">*</span></strong>
                                    <p class="text-muted small mb-0">Recent 2x2 ID photo (2 copies)</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Documents -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-file-earmark-plus me-2"></i>Additional Documents (Optional)
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Transfer Credential -->
                            <div class="form-check form-switch mb-3 p-3 bg-light rounded">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="transfer_credential" 
                                       name="transfer_credential"
                                       <?= $student['transfer_credential'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="transfer_credential">
                                    <strong>Transfer Credential (Form 137-A)</strong>
                                    <p class="text-muted small mb-0">Required only for transferees from other schools</p>
                                </label>
                            </div>

                            <!-- Medical Certificate -->
                            <div class="form-check form-switch mb-3 p-3 bg-light rounded">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="medical_certificate" 
                                       name="medical_certificate"
                                       <?= $student['medical_certificate'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="medical_certificate">
                                    <strong>Medical Certificate</strong>
                                    <p class="text-muted small mb-0">General health check-up from licensed physician</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Remarks (if any) -->
                    <?php if ($student['remarks']): ?>
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">
                                <i class="bi bi-exclamation-triangle me-2"></i>Admin Remarks
                            </h6>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($student['remarks'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Admin Notes (if any) -->
                    <?php if ($student['admin_notes']): ?>
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bi bi-info-circle me-2"></i>Additional Notes
                            </h6>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($student['admin_notes'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Submission Tracking -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="row">
                                <?php if ($student['submitted_at']): ?>
                                    <div class="col-md-4">
                                        <i class="bi bi-clock-history text-primary me-2"></i>
                                        <strong>Submitted:</strong><br>
                                        <small class="text-muted"><?= date('M d, Y g:i A', strtotime($student['submitted_at'])) ?></small>
                                    </div>
                                <?php endif; ?>

                                <?php if ($student['reviewed_at']): ?>
                                    <div class="col-md-4">
                                        <i class="bi bi-eye text-success me-2"></i>
                                        <strong>Reviewed:</strong><br>
                                        <small class="text-muted"><?= date('M d, Y g:i A', strtotime($student['reviewed_at'])) ?></small>
                                    </div>
                                <?php endif; ?>

                                <?php if ($student['approved_at']): ?>
                                    <div class="col-md-4">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <strong>Approved:</strong><br>
                                        <small class="text-muted"><?= date('M d, Y g:i A', strtotime($student['approved_at'])) ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
                        <a href="my-students" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Back to My Children
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Save Requirements
                        </button>
                    </div>
                </form>

                <!-- Instructions -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-question-circle me-2"></i>How to Submit Documents
                        </h6>
                    </div>
                    <div class="card-body">
                        <ol class="mb-0">
                            <li class="mb-2">Check the boxes above to indicate which documents you have prepared</li>
                            <li class="mb-2">Click "Save Requirements" to update your submission status</li>
                            <li class="mb-2">Submit physical copies of all checked documents to the school registrar</li>
                            <li class="mb-2">Wait for admin to review and approve your enrollment</li>
                            <li class="mb-0">You will be notified once your enrollment is approved</li>
                        </ol>
                        
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Note:</strong> This checklist is for tracking purposes only. You must submit the actual physical documents to the school office for verification.
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>