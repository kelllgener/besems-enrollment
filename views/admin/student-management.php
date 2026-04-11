<div class="row">
    <div class="col-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="bi bi-people-fill me-2"></i>Student Management
                </h3>
                <p class="text-muted mb-0">Manage all students and their information</p>
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

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-2">
                <div class="card shadow-sm border-0 border-start border-primary border-4">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                        </div>
                        <h4 class="mb-0"><?= number_format($stats['total']) ?></h4>
                        <small class="text-muted">Total Students</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <a href="?status=Active" class="text-decoration-none">
                    <div class="card shadow-sm border-0 <?= $status_filter === 'Active' ? 'border-success border-3' : '' ?>">
                        <div class="card-body text-center">
                            <div class="text-success mb-2">
                                <i class="bi bi-person-check-fill" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0"><?= number_format($stats['active']) ?></h4>
                            <small class="text-muted">Active</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="?status=Inactive" class="text-decoration-none">
                    <div class="card shadow-sm border-0 <?= $status_filter === 'Inactive' ? 'border-secondary border-3' : '' ?>">
                        <div class="card-body text-center">
                            <div class="text-secondary mb-2">
                                <i class="bi bi-person-dash-fill" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0"><?= number_format($stats['inactive']) ?></h4>
                            <small class="text-muted">Inactive</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="?status=Transferred" class="text-decoration-none">
                    <div class="card shadow-sm border-0 <?= $status_filter === 'Transferred' ? 'border-warning border-3' : '' ?>">
                        <div class="card-body text-center">
                            <div class="text-warning mb-2">
                                <i class="bi bi-arrow-left-right" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0"><?= number_format($stats['transferred']) ?></h4>
                            <small class="text-muted">Transferred</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="?status=Graduated" class="text-decoration-none">
                    <div class="card shadow-sm border-0 <?= $status_filter === 'Graduated' ? 'border-info border-3' : '' ?>">
                        <div class="card-body text-center">
                            <div class="text-info mb-2">
                                <i class="bi bi-mortarboard-fill" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0"><?= number_format($stats['graduated']) ?></h4>
                            <small class="text-muted">Graduated</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2">
                <a href="?status=Dropped" class="text-decoration-none">
                    <div class="card shadow-sm border-0 <?= $status_filter === 'Dropped' ? 'border-danger border-3' : '' ?>">
                        <div class="card-body text-center">
                            <div class="text-danger mb-2">
                                <i class="bi bi-person-x-fill" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0"><?= number_format($stats['dropped']) ?></h4>
                            <small class="text-muted">Dropped</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="student-management" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Search Students</label>
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

                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Section</label>
                            <select name="section" class="form-select">
                                <option value="">All Sections</option>
                                <?php 
                                $current_grade = '';
                                foreach ($sections as $section):
                                    if ($current_grade !== $section['grade_name']) {
                                        if ($current_grade !== '') echo '</optgroup>';
                                        echo '<optgroup label="' . htmlspecialchars($section['grade_name']) . '">';
                                        $current_grade = $section['grade_name'];
                                    }
                                ?>
                                    <option value="<?= $section['section_id'] ?>" 
                                            <?= $section_filter == $section['section_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($section['section_name']) ?>
                                    </option>
                                <?php 
                                endforeach;
                                if ($current_grade !== '') echo '</optgroup>';
                                ?>
                            </select>
                        </div>

                        <input type="hidden" name="status" value="<?= htmlspecialchars($status_filter) ?>">

                        <div class="col-md-1">
                            <label class="form-label small fw-semibold">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel"></i>
                            </button>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">&nbsp;</label>
                            <a href="student-management" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x-circle me-1"></i>Clear
                            </a>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            Showing <strong><?= count($students) ?></strong> of <strong><?= $total_records ?></strong> students
                        </span>
                        <a href="?export=csv&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&grade=<?= urlencode($grade_filter) ?>&section=<?= urlencode($section_filter) ?>" 
                           class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i>Export to CSV
                        </a>
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
                                    <th>Student Name</th>
                                    <th>Age/Gender</th>
                                    <th>Grade & Section</th>
                                    <th>Guardian</th>
                                    <th>Status</th>
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
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                    <i class="bi bi-person-fill text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">
                                                        <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        <?= htmlspecialchars($student['barangay'] . ', ' . $student['city_municipality']) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div><?= $student['age'] ?> years</div>
                                            <small class="text-muted">
                                                <i class="bi bi-gender-<?= strtolower($student['gender']) ?>"></i>
                                                <?= htmlspecialchars($student['gender']) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if ($student['grade_name'] && $student['section_name']): ?>
                                                <div class="fw-semibold"><?= htmlspecialchars($student['grade_name']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($student['section_name']) ?></small>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Not Assigned</span>
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
                                            $status_badge = match($student['student_status']) {
                                                'Active' => 'success',
                                                'Inactive' => 'secondary',
                                                'Transferred' => 'warning',
                                                'Graduated' => 'info',
                                                'Dropped' => 'danger',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-<?= $status_badge ?>">
                                                <?= htmlspecialchars($student['student_status']) ?>
                                            </span>
                                            <br>
                                            <small class="badge bg-<?= $student['enrollment_status'] === 'Approved' ? 'success' : 'warning' ?> mt-1">
                                                <?= htmlspecialchars($student['enrollment_status'] ?? 'Pending') ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="view-student?id=<?= $student['student_id'] ?>" 
                                                   class="btn btn-outline-primary" 
                                                   data-bs-toggle="tooltip" 
                                                   title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="edit-student?id=<?= $student['student_id'] ?>" 
                                                   class="btn btn-outline-secondary" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#assignModal<?= $student['student_id'] ?>"
                                                        title="Assign Section">
                                                    <i class="bi bi-grid-3x3-gap"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-outline-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#statusModal<?= $student['student_id'] ?>"
                                                        title="Change Status">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Assign Section Modal -->
                                    <div class="modal fade" id="assignModal<?= $student['student_id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Assign Section</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="assign-student-section">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
                                                        
                                                        <p><strong><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></strong></p>
                                                        <p class="text-muted small">Current: <?= $student['section_name'] ? htmlspecialchars($student['grade_name'] . ' - ' . $student['section_name']) : 'Not assigned' ?></p>
                                                        
                                                        <label class="form-label">Select Section</label>
                                                        <select name="section_id" class="form-select" required>
                                                            <option value="">Remove from section</option>
                                                            <?php 
                                                            $curr_grade = '';
                                                            foreach ($sections as $sec):
                                                                if ($curr_grade !== $sec['grade_name']) {
                                                                    if ($curr_grade !== '') echo '</optgroup>';
                                                                    echo '<optgroup label="' . htmlspecialchars($sec['grade_name']) . '">';
                                                                    $curr_grade = $sec['grade_name'];
                                                                }
                                                                $slots = $sec['max_students'] - $sec['current_students'];
                                                                $is_full = $slots <= 0 && $student['section_id'] != $sec['section_id'];
                                                            ?>
                                                                <option value="<?= $sec['section_id'] ?>" 
                                                                        <?= $is_full ? 'disabled' : '' ?>
                                                                        <?= $student['section_id'] == $sec['section_id'] ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($sec['section_name']) ?> 
                                                                    (<?= $slots ?> slots) <?= $is_full ? '- FULL' : '' ?>
                                                                </option>
                                                            <?php 
                                                            endforeach;
                                                            if ($curr_grade !== '') echo '</optgroup>';
                                                            ?>
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
                                    <div class="modal fade" id="statusModal<?= $student['student_id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Change Student Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="change-student-status">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
                                                        
                                                        <p><strong><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></strong></p>
                                                        <p class="text-muted small">Current Status: <span class="badge bg-<?= $status_badge ?>"><?= htmlspecialchars($student['student_status']) ?></span></p>
                                                        
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
                                            <a class="page-link" href="?page=<?= $current_page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&grade=<?= urlencode($grade_filter) ?>&section=<?= urlencode($section_filter) ?>">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>

                                        <?php
                                        $start = max(1, $current_page - 2);
                                        $end = min($total_pages, $current_page + 2);
                                        
                                        for ($i = $start; $i <= $end; $i++): ?>
                                            <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&grade=<?= urlencode($grade_filter) ?>&section=<?= urlencode($section_filter) ?>">
                                                    <?= $i ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?page=<?= $current_page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&grade=<?= urlencode($grade_filter) ?>&section=<?= urlencode($section_filter) ?>">
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
                        <h5 class="mt-3 text-muted">No students found</h5>
                        <?php if (!empty($search) || !empty($status_filter) || !empty($grade_filter) || !empty($section_filter)): ?>
                            <p class="text-muted">Try adjusting your filters</p>
                            <a href="student-management" class="btn btn-outline-primary">Clear Filters</a>
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