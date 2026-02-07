<div class="row g-4">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="text-primary mb-3">
                    <i class="bi bi-emoji-smile me-2"></i>Welcome back, <?= htmlspecialchars($name) ?>!
                </h2>
                <p class="mb-0 opacity-75">Manage your children's enrollment and track their academic journey.</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm border-0 border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">Total Children</h6>
                        <h3 class="mb-0 fw-bold"><?= $student_counts['total'] ?? 0 ?></h3>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-people-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm border-0 border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">Active Students</h6>
                        <h3 class="mb-0 fw-bold text-success"><?= $student_counts['active'] ?? 0 ?></h3>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-person-check-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm border-0 border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">Pending Approval</h6>
                        <h3 class="mb-0 fw-bold text-warning"><?= $enrollment_counts['pending'] ?? 0 ?></h3>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-hourglass-split" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm border-0 border-start border-info border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">Approved</h6>
                        <h3 class="mb-0 fw-bold text-info"><?= $enrollment_counts['approved'] ?? 0 ?></h3>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-check-circle-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Children Section -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-person-lines-fill me-2"></i>My Children
                </h5>
                <a href="add-student" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Add Child
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($students)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Student Name</th>
                                    <th>LRN</th>
                                    <th>Age</th>
                                    <th>Grade & Section</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                    <i class="bi bi-person-fill text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">
                                                        <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="bi bi-gender-<?= strtolower($student['gender']) ?>"></i>
                                                        <?= htmlspecialchars($student['gender']) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?= htmlspecialchars($student['lrn']) ?></span>
                                        </td>
                                        <td><?= $student['age'] ?> yrs</td>
                                        <td>
                                            <?php if ($student['grade_name'] && $student['section_name']): ?>
                                                <div><?= htmlspecialchars($student['grade_name']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($student['section_name']) ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">Not assigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $status = $student['enrollment_status'] ?? 'Not Enrolled';
                                            $badge_class = match ($status) {
                                                'Approved' => 'success',
                                                'Pending', 'For Review' => 'warning',
                                                'Declined' => 'danger',
                                                'Incomplete' => 'secondary',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-<?= $badge_class ?>">
                                                <?= htmlspecialchars($status) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="view-student?id=<?= $student['student_id'] ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-person-plus" style="font-size: 4rem; opacity: 0.3;"></i>
                        <h5 class="mt-3 text-muted">No children enrolled yet</h5>
                        <p class="text-muted mb-3">Start by adding your child's information</p>
                        <a href="add-student" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add Your First Child
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Enrollment Status Chart -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="bi bi-pie-chart-fill me-2"></i>Enrollment Status
                </h5>
            </div>
            <div class="card-body">
                <canvas id="enrollmentChart"></canvas>

                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small">
                            <i class="bi bi-circle-fill text-success me-1"></i>Approved
                        </span>
                        <strong><?= $enrollment_counts['approved'] ?? 0 ?></strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small">
                            <i class="bi bi-circle-fill text-warning me-1"></i>Pending
                        </span>
                        <strong><?= $enrollment_counts['pending'] ?? 0 ?></strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small">
                            <i class="bi bi-circle-fill text-info me-1"></i>For Review
                        </span>
                        <strong><?= $enrollment_counts['for_review'] ?? 0 ?></strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small">
                            <i class="bi bi-circle-fill text-danger me-1"></i>Declined
                        </span>
                        <strong><?= $enrollment_counts['declined'] ?? 0 ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="bi bi-megaphone-fill me-2"></i>Announcements
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($announcements)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($announcements as $announcement): ?>
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <?php
                                        $icon_class = match ($announcement['announcement_type']) {
                                            'Enrollment' => 'bi-clipboard-check text-primary',
                                            'Event' => 'bi-calendar-event text-info',
                                            'Holiday' => 'bi-gift text-success',
                                            'Emergency' => 'bi-exclamation-triangle text-danger',
                                            default => 'bi-info-circle text-secondary'
                                        };
                                        ?>
                                        <i class="bi <?= $icon_class ?>" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="mb-0"><?= htmlspecialchars($announcement['title']) ?></h6>
                                            <span class="badge bg-<?= $announcement['announcement_type'] === 'Emergency' ? 'danger' : 'secondary' ?> ms-2">
                                                <?= htmlspecialchars($announcement['announcement_type']) ?>
                                            </span>
                                        </div>
                                        <p class="text-muted mb-2 small"><?= nl2br(htmlspecialchars($announcement['content'])) ?></p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            <?= date('F d, Y g:i A', strtotime($announcement['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center mt-3">
                        <a href="announcements" class="btn btn-outline-primary btn-sm">
                            View All Announcements
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-megaphone" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-2 mb-0">No announcements at this time</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="bi bi-lightning-fill me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="add-student" class="btn btn-outline-primary text-start">
                        <i class="bi bi-person-plus me-2"></i>Enroll New Child
                    </a>
                    <a href="my-students" class="btn btn-outline-success text-start">
                        <i class="bi bi-people me-2"></i>View All Children
                    </a>
                    <a href="requirements" class="btn btn-outline-info text-start">
                        <i class="bi bi-file-earmark-check me-2"></i>Upload Requirements
                    </a>
                    <a href="profile" class="btn btn-outline-secondary text-start">
                        <i class="bi bi-person-gear me-2"></i>Update Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="bi bi-telephone-fill me-2"></i>Need Help?
                </h5>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">Contact the school for assistance:</p>
                <div class="mb-2">
                    <i class="bi bi-envelope me-2 text-primary"></i>
                    <a href="mailto:school@besems.com" class="text-decoration-none">school@besems.com</a>
                </div>
                <div class="mb-2">
                    <i class="bi bi-telephone me-2 text-primary"></i>
                    <a href="tel:0912345678" class="text-decoration-none">(091) 234-5678</a>
                </div>
                <div>
                    <i class="bi bi-clock me-2 text-primary"></i>
                    <span class="small">Mon-Fri, 8:00 AM - 5:00 PM</span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Pass PHP data to JS variables for chart
    const guardianEnrollmentStatusData = {
        approved: <?= $enrollment_counts['approved'] ?? 0 ?>,
        pending: <?= $enrollment_counts['pending'] ?? 0 ?>,
        for_review: <?= $enrollment_counts['for_review'] ?? 0 ?>,
        declined: <?= $enrollment_counts['declined'] ?? 0 ?>
    };
</script>
<script src="assets/js/app.js"></script>