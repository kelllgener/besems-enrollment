<!-- Main Container -->
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- 1. HERO SECTION: Guardian Welcome -->
        <div class="col-12">
            <div class="card border-0 bg-dark bg-gradient text-white shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-6 fw-bold mb-2">
                                <i class="bi bi-stars me-2"></i>Welcome back, <?= htmlspecialchars($name) ?>!
                            </h1>
                            <p class="lead mb-0 opacity-75">Manage your children's enrollment and track their academic journey with ease.</p>
                            <div class="mt-3 d-flex gap-2">
                                <span class="badge rounded-pill bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-3 py-2">
                                    <i class="bi bi-check-circle-fill me-1"></i>Account Active
                                </span>
                                <span class="badge rounded-pill bg-info bg-opacity-25 text-info border border-info border-opacity-25 px-3 py-2">
                                    <i class="bi bi-shield-check-fill me-1"></i>Guardian Portal
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end d-none d-md-block">
                            <i class="bi bi-mortarboard" style="font-size: 5rem; opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. STATS ROW: Using subtle backgrounds -->
        <div class="col-12">
            <div class="row g-3">
                <div class="col-6 col-xl-3">
                    <div class="card shadow-sm border-0 border-bottom border-primary border-4 h-100 rounded-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 small text-uppercase fw-semibold">Total Children</p>
                                    <h2 class="mb-0 fw-bold"><?= number_format($student_counts['total'] ?? 0) ?></h2>
                                </div>
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                                    <i class="bi bi-people fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card shadow-sm border-0 border-bottom border-success border-4 h-100 rounded-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 small text-uppercase fw-semibold">Active</p>
                                    <h2 class="mb-0 fw-bold text-success"><?= number_format($student_counts['active'] ?? 0) ?></h2>
                                </div>
                                <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                                    <i class="bi bi-person-check fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card shadow-sm border-0 border-bottom border-warning border-4 h-100 rounded-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 small text-uppercase fw-semibold">Pending</p>
                                    <h2 class="mb-0 fw-bold text-warning"><?= number_format($enrollment_counts['pending'] ?? 0) ?></h2>
                                </div>
                                <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                                    <i class="bi bi-hourglass-split fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card shadow-sm border-0 border-bottom border-info border-4 h-100 rounded-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 small text-uppercase fw-semibold">Approved</p>
                                    <h2 class="mb-0 fw-bold text-info"><?= number_format($enrollment_counts['approved'] ?? 0) ?></h2>
                                </div>
                                <div class="bg-info bg-opacity-10 text-info rounded-circle p-3">
                                    <i class="bi bi-patch-check fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. MAIN CONTENT: Left Column -->
        <div class="col-lg-8">
            <div class="row g-4">
                <!-- My Children Table (Recent Students) -->
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-person-workspace me-2 text-primary"></i>My Children
                            </h5>
                            <a href="add-student" class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="bi bi-plus-lg me-1"></i> Enroll New
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($students)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($students as $student): ?>
                                        <div class="list-group-item p-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-sm bg-light rounded-circle p-2 text-center" style="width: 45px; height: 45px;">
                                                    <i class="bi bi-person text-secondary fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold text-dark"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></div>
                                                    <div class="small text-muted">LRN: <?= htmlspecialchars($student['lrn']) ?> • <?= htmlspecialchars($student['gender']) ?> • <?= $student['age'] ?> yrs</div>
                                                    <div class="small text-muted">Grade: <?= htmlspecialchars($student['grade_name'] ?? 'N/A') ?> | Section: <?= htmlspecialchars($student['section_name'] ?? 'Pending Assignment') ?></div>
                                                </div>
                                                <div class="text-end">
                                                    <?php
                                                    $status = $student['enrollment_status'] ?? 'Not Enrolled';
                                                    $badge_class = match ($status) {
                                                        'Approved' => 'success',
                                                        'Pending', 'For Review' => 'warning',
                                                        'Declined' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                    ?>
                                                    <span class="badge rounded-pill bg-<?= $badge_class ?> bg-opacity-10 text-<?= $badge_class ?> px-3">
                                                        <?= htmlspecialchars($status) ?>
                                                    </span>
                                                </div>
                                                <div class="text-end">
                                                    <a href="view-student?id=<?= $student['student_id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">View</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox text-muted fs-1 opacity-25"></i>
                                    <p class="text-muted mt-2">No records found.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Announcements Section -->
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-megaphone me-2 text-danger"></i>Latest Announcements</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <?php if (!empty($announcements)): ?>
                                    <?php foreach ($announcements as $announcement): ?>
                                        <div class="list-group-item p-4">
                                            <div class="d-flex gap-3">
                                                <div class="flex-shrink-0">
                                                    <span class="badge bg-light text-primary p-3 rounded-3">
                                                        <i class="bi bi-info-circle fs-4"></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between">
                                                        <h6 class="fw-bold mb-1"><?= htmlspecialchars($announcement['title']) ?></h6>
                                                        <small class="text-muted"><?= date('M d', strtotime($announcement['created_at'])) ?></small>
                                                    </div>
                                                    <p class="text-muted small mb-0"><?= nl2br(htmlspecialchars($announcement['content'])) ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-4 text-center text-muted">No announcements yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. SIDEBAR: Right Column -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px; z-index: 1000;">
                <div class="row g-4">
                    <!-- Enrollment Status Chart -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart-fill me-2 text-info"></i>Status Breakdown</h6>
                            </div>
                            <div class="card-body">
                                <div style="height: 250px;">
                                    <canvas id="enrollmentChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions List -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                            <div class="card-header bg-dark text-white border-0 py-3">
                                <h6 class="mb-0 fw-bold">Quick Actions</h6>
                            </div>
                            <div class="list-group list-group-flush">
                                <a href="add-student" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                                    <i class="bi bi-person-plus me-3 text-primary"></i>Enroll a New Student
                                </a>
                                <a href="requirements" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                                    <i class="bi bi-cloud-arrow-up me-3 text-success"></i>Upload Missing Documents
                                </a>
                                <a href="profile" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                                    <i class="bi bi-shield-lock me-3 text-warning"></i>Update Security Settings
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Help & Contact Card -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4 bg-light">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3 text-primary">Need Assistance?</h6>
                                <p class="small text-muted">If you encounter issues with the enrollment process, please contact the registrar.</p>
                                <hr class="my-3 opacity-10">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-telephone text-primary me-3"></i>
                                    <span class="small fw-bold">(091) 234-5678</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-envelope-at text-primary me-3"></i>
                                    <span class="small fw-bold">support@school.edu</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Info -->
                    <div class="col-12 text-center">
                        <p class="small text-muted mb-0">System Version 2.4.0</p>
                        <p class="small text-muted">Last update: <?= date('M d, Y') ?></p>
                    </div>
                </div>
            </div> <!-- End Sticky -->
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