<!-- Main Container -->
<div class="container-fluid py-4">
    
    <div class="row g-4">
        <!-- 1. HERO SECTION: Admin Welcome -->
        <div class="col-12">
            <div class="card border-0 bg-dark bg-gradient text-white shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-6 fw-bold mb-2">
                                <i class="bi bi-speedometer2 me-2"></i>Welcome back, <?= htmlspecialchars($name) ?>!
                            </h1>
                            <p class="lead mb-0 opacity-75">Here's an overview of your enrollment system today.</p>
                            <div class="mt-3 d-flex gap-2">
                                <span class="badge rounded-pill bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-3 py-2">
                                    <i class="bi bi-check-circle-fill me-1"></i>Database Connected
                                </span>
                                <span class="badge rounded-pill bg-info bg-opacity-25 text-info border border-info border-opacity-25 px-3 py-2">
                                    <i class="bi bi-shield-check-fill me-1"></i>System Operational
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end d-none d-md-block">
                            <i class="bi bi-pc-display-horizontal" style="font-size: 5rem; opacity: 0.2;"></i>
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
                                    <p class="text-muted mb-1 small text-uppercase fw-semibold">Total Students</p>
                                    <h2 class="mb-0 fw-bold"><?= number_format($stats['total_students']) ?></h2>
                                </div>
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                                    <i class="bi bi-people fs-3"></i>
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
                                    <h2 class="mb-0 fw-bold text-warning"><?= number_format($stats['pending_enrollments']) ?></h2>
                                </div>
                                <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                                    <i class="bi bi-hourglass-split fs-3"></i>
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
                                    <p class="text-muted mb-1 small text-uppercase fw-semibold">Sections</p>
                                    <h2 class="mb-0 fw-bold text-success"><?= number_format($stats['total_sections']) ?></h2>
                                </div>
                                <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                                    <i class="bi bi-collection fs-3"></i>
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
                                    <p class="text-muted mb-1 small text-uppercase fw-semibold">Subjects</p>
                                    <h2 class="mb-0 fw-bold text-info"><?= number_format($stats['total_subjects']) ?></h2>
                                </div>
                                <div class="bg-info bg-opacity-10 text-info rounded-circle p-3">
                                    <i class="bi bi-journal-text fs-3"></i>
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
                <!-- Grade Level Chart -->
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Students Per Grade Level
                            </h5>
                        </div>
                        <div class="card-body">
                            <div style="min-height: 300px;">
                                <canvas id="studentsPerGradeChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Enrollments -->
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-clock-history me-2 text-primary"></i>Recent Enrollments
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($recent_students)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($recent_students as $student): ?>
                                        <div class="list-group-item p-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-sm bg-light rounded-circle p-2 text-center" style="width: 45px; height: 45px;">
                                                    <i class="bi bi-person text-secondary fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold text-dark"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></div>
                                                    <div class="small text-muted">LRN: <?= htmlspecialchars($student['lrn']) ?></div>
                                                </div>
                                                <div class="text-end">
                                                    <?php 
                                                        $status = $student['enrollment_status'];
                                                        $badge = match($status) {
                                                            'Approved' => 'success',
                                                            'Pending' => 'warning',
                                                            default => 'danger'
                                                        };
                                                    ?>
                                                    <span class="badge rounded-pill bg-<?= $badge ?> bg-opacity-10 text-<?= $badge ?> px-3">
                                                        <?= htmlspecialchars($status) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox text-muted fs-1 opacity-25"></i>
                                    <p class="text-muted mt-2">No recent enrollments found</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-white border-top-0 text-center py-3">
                            <a href="students" class="btn btn-sm btn-outline-primary rounded-pill px-4">View All Students</a>
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
                                    <canvas id="enrollmentStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                            <div class="card-header bg-primary text-white border-0 py-3">
                                <h6 class="mb-0 fw-bold">Admin Controls</h6>
                            </div>
                            <div class="list-group list-group-flush">
                                <a href="students" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                                    <i class="bi bi-person-plus me-3 fs-5 text-primary"></i>Manage Students
                                </a>
                                <a href="sections" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                                    <i class="bi bi-grid-3x3-gap me-3 fs-5 text-success"></i>Manage Sections
                                </a>
                                <a href="subjects" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                                    <i class="bi bi-book me-3 fs-5 text-info"></i>Manage Subjects
                                </a>
                                <a href="announcements" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                                    <i class="bi bi-megaphone me-3 fs-5 text-warning"></i>Post Announcement
                                </a>
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
    // Pass PHP data to JS variables
    const studentsPerGradeLabels = <?= json_encode(array_column($students_per_grade, 'grade_name')) ?>;
    const studentsPerGradeData = <?= json_encode(array_column($students_per_grade, 'student_count')) ?>;
    const enrollmentStatusData = {
        approved: <?= $enrollment_status['approved'] ?>,
        pending: <?= $enrollment_status['pending'] ?>,
        declined: <?= $enrollment_status['declined'] ?>
    };
</script>
<script src="assets/js/app.js"></script>