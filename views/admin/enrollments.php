<div class="row">
    <div class="col-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="bi bi-clipboard-check-fill me-2"></i>Enrollment Management
                </h3>
                <p class="text-muted mb-0">Review and manage student enrollment requests</p>
            </div>
            <a href="dashboard" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>

        <!-- Success/Error Messages -->
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Status Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-2">
                <a href="?enrollment=For Review" class="text-decoration-none">
                    <div class="card shadow-sm border-0 <?= $enrollment_filter === 'For Review' ? 'border-info border-3' : '' ?>">
                        <div class="card-body text-center">
                            <div class="text-info mb-2">
                                <i class="bi bi-hourglass-split" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0"><?= $counts['for_review'] ?? 0 ?></h4>
                            <small class="text-muted">For Review</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="?enrollment=Pending" class="text-decoration-none">
                    <div class="card shadow-sm border-0 <?= $enrollment_filter === 'Pending' ? 'border-warning border-3' : '' ?>">
                        <div class="card-body text-center">
                            <div class="text-warning mb-2">
                                <i class="bi bi-clock-history" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0"><?= $counts['pending'] ?? 0 ?></h4>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="?enrollment=Incomplete" class="text-decoration-none">
                    <div class="card shadow-sm border-0 <?= $enrollment_filter === 'Incomplete' ? 'border-secondary border-3' : '' ?>">
                        <div class="card-body text-center">
                            <div class="text-secondary mb-2">
                                <i class="bi bi-file-earmark-x" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0"><?= $counts['incomplete'] ?? 0 ?></h4>
                            <small class="text-muted">Incomplete</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="?enrollment=Approved" class="text-decoration-none">
                    <div class="card shadow-sm border-0 <?= $enrollment_filter === 'Approved' ? 'border-success border-3' : '' ?>">
                        <div class="card-body text-center">
                            <div class="text-success mb-2">
                                <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0"><?= $counts['approved'] ?? 0 ?></h4>
                            <small class="text-muted">Approved</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="?enrollment=Declined" class="text-decoration-none">
                    <div class="card shadow-sm border-0 <?= $enrollment_filter === 'Declined' ? 'border-danger border-3' : '' ?>">
                        <div class="card-body text-center">
                            <div class="text-danger mb-2">
                                <i class="bi bi-x-circle-fill" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0"><?= $counts['declined'] ?? 0 ?></h4>
                            <small class="text-muted">Declined</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="?" class="text-decoration-none">
                    <div class="card shadow-sm border-0 <?= empty($enrollment_filter) ? 'border-primary border-3' : '' ?>">
                        <div class="card-body text-center">
                            <div class="text-primary mb-2">
                                <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0"><?= array_sum($counts) ?></h4>
                            <small class="text-muted">All Students</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="enrollments" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Search</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Name or LRN" 
                                   value="<?= htmlspecialchars($search) ?>">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Grade Level</label>
                            <select name="grade" class="form-select">
                                <option value="">All Grades</option>
                                <?php foreach ($grade_levels as $grade): ?>
                                    <option value="<?= $grade['grade_id'] ?>" 
                                            <?= $grade_filter == $grade['grade_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($grade['grade_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="Active" <?= $status_filter === 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="Inactive" <?= $status_filter === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>

                        <input type="hidden" name="enrollment" value="<?= htmlspecialchars($enrollment_filter) ?>">

                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel me-1"></i>Filter
                            </button>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">&nbsp;</label>
                            <a href="enrollments" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x-circle me-1"></i>Clear
                            </a>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-top">
                        <span class="text-muted small">
                            Showing <strong><?= count($students) ?></strong> of <strong><?= $total_records ?></strong> records
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <?php if (!empty($students)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3">LRN</th>
                                    <th>Student Info</th>
                                    <th>Guardian</th>
                                    <th>Requirements</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td class="px-3">
                                            <span class="badge bg-secondary font-monospace">
                                                <?= htmlspecialchars($student['lrn']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">
                                                <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                                            </div>
                                            <small class="text-muted">
                                                <?= $student['age'] ?> years old • <?= htmlspecialchars($student['gender']) ?>
                                            </small>
                                            <?php if ($student['grade_name']): ?>
                                                <br><small class="badge bg-info">
                                                    <?= htmlspecialchars($student['grade_name']) ?> - <?= htmlspecialchars($student['section_name']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <strong><?= htmlspecialchars($student['guardian_username']) ?></strong>
                                            </div>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($student['guardian_contact']) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php
                                            $total_req = 5; // Required documents
                                            $completed_req = 
                                                ($student['birth_certificate'] ?? 0) +
                                                ($student['report_card_form137'] ?? 0) +
                                                ($student['good_moral_certificate'] ?? 0) +
                                                ($student['certificate_of_completion'] ?? 0) +
                                                ($student['id_picture_2x2'] ?? 0);
                                            $percentage = ($completed_req / $total_req) * 100;
                                            ?>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar <?= $percentage == 100 ? 'bg-success' : 'bg-warning' ?>" 
                                                     style="width: <?= $percentage ?>%">
                                                    <?= $completed_req ?>/<?= $total_req ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
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
                                            <span class="badge bg-<?= $badge ?>">
                                                <?= htmlspecialchars($status) ?>
                                            </span>
                                            <?php if ($student['remarks']): ?>
                                                <i class="bi bi-info-circle text-muted ms-1" 
                                                   data-bs-toggle="tooltip" 
                                                   title="<?= htmlspecialchars($student['remarks']) ?>"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= $student['submitted_at'] ? date('M d, Y', strtotime($student['submitted_at'])) : 'Not submitted' ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <a href="review-enrollment?id=<?= $student['student_id'] ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye me-1"></i>Review
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Page <?= $current_page ?> of <?= $total_pages ?>
                                </div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?page=<?= $current_page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&enrollment=<?= urlencode($enrollment_filter) ?>&grade=<?= urlencode($grade_filter) ?>">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>

                                        <?php
                                        $start = max(1, $current_page - 2);
                                        $end = min($total_pages, $current_page + 2);
                                        
                                        for ($i = $start; $i <= $end; $i++): ?>
                                            <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&enrollment=<?= urlencode($enrollment_filter) ?>&grade=<?= urlencode($grade_filter) ?>">
                                                    <?= $i ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?page=<?= $current_page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&enrollment=<?= urlencode($enrollment_filter) ?>&grade=<?= urlencode($grade_filter) ?>">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
                        <h5 class="mt-3 text-muted">No enrollment requests found</h5>
                        <?php if (!empty($search) || !empty($status_filter) || !empty($grade_filter)): ?>
                            <p class="text-muted">Try adjusting your filters</p>
                            <a href="enrollments?enrollment=<?= urlencode($enrollment_filter) ?>" class="btn btn-outline-primary">
                                Clear Filters
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Enable tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>