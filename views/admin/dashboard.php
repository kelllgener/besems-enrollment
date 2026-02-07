<div class="row g-4">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="text-primary mb-3">
                    <i class="bi bi-emoji-smile me-2"></i>Welcome back, <?= htmlspecialchars($name) ?>!
                </h2>
                <p class="text-muted mb-3">Here's an overview of your enrollment system today.</p>
                <div class="d-flex align-items-center">
                    <span class="badge bg-success me-2">
                        <i class="bi bi-check-circle me-1"></i>Database Connected
                    </span>
                    <span class="badge bg-info">
                        <i class="bi bi-shield-check me-1"></i>System Operational
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row 1 -->
    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm border-0 border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Students</h6>
                        <h3 class="mb-0"><?= number_format($stats['total_students']) ?></h3>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> Active enrollments
                        </small>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-people-fill" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm border-0 border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Pending Enrollments</h6>
                        <h3 class="mb-0"><?= number_format($stats['pending_enrollments']) ?></h3>
                        <small class="text-warning">
                            <i class="bi bi-clock"></i> Awaiting approval
                        </small>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-hourglass-split" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm border-0 border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Sections</h6>
                        <h3 class="mb-0"><?= number_format($stats['total_sections']) ?></h3>
                        <small class="text-success">
                            <i class="bi bi-door-open"></i> Active classes
                        </small>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-collection-fill" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm border-0 border-start border-info border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Subjects</h6>
                        <h3 class="mb-0"><?= number_format($stats['total_subjects']) ?></h3>
                        <small class="text-info">
                            <i class="bi bi-book"></i> Curriculum
                        </small>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-journal-text" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart-fill me-2"></i>Students Per Grade Level
                </h5>
            </div>
            <div class="card-body">
                <canvas id="studentsPerGradeChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Enrollment Status Breakdown -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="bi bi-pie-chart-fill me-2"></i>Enrollment Status
                </h5>
            </div>
            <div class="card-body">
                <canvas id="enrollmentStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Enrollments -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Recent Enrollments
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_students)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_students as $student): ?>
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-person-fill text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">
                                            <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                                        </div>
                                        <small class="text-muted">
                                            <i class="bi bi-award me-1"></i>LRN: <?= htmlspecialchars($student['lrn']) ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-<?= $student['enrollment_status'] === 'Approved' ? 'success' : ($student['enrollment_status'] === 'Pending' ? 'warning' : 'danger') ?>">
                                        <?= htmlspecialchars($student['enrollment_status']) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p class="mt-2">No recent enrollments</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="bi bi-lightning-fill me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="students" class="btn btn-outline-primary text-start">
                            <i class="bi bi-person-plus me-2"></i>Manage Students
                        </a>
                        <a href="sections" class="btn btn-outline-success text-start">
                            <i class="bi bi-grid-3x3-gap me-2"></i>Manage Sections
                        </a>
                        <a href="subjects" class="btn btn-outline-info text-start">
                            <i class="bi bi-book me-2"></i>Manage Subjects
                        </a>
                        <a href="schedules" class="btn btn-outline-warning text-start">
                            <i class="bi bi-calendar-event me-2"></i>Manage Schedules
                        </a>
                        <a href="announcements" class="btn btn-outline-secondary text-start">
                            <i class="bi bi-megaphone me-2"></i>Post Announcement
                        </a>
                    <?php else: ?>
                        <a href="my-students" class="btn btn-outline-primary text-start">
                            <i class="bi bi-people me-2"></i>View My Students
                        </a>
                        <a href="enroll-student" class="btn btn-outline-success text-start">
                            <i class="bi bi-person-plus me-2"></i>Enroll New Student
                        </a>
                        <a href="announcements" class="btn btn-outline-info text-start">
                            <i class="bi bi-megaphone me-2"></i>View Announcements
                        </a>
                    <?php endif; ?>
                </div>
            </div>
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