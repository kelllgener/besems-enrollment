<div class="row">
    <div class="col-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="bi bi-people-fill me-2"></i>My Children
                </h3>
                <p class="text-muted mb-0">Manage and view all your enrolled children</p>
            </div>
            <a href="add-student" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add New Child
            </a>
        </div>

        <!-- Filters and Search Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="my-students" id="filterForm">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Name or LRN" 
                                       value="<?= htmlspecialchars($search) ?>">
                            </div>
                        </div>

                        <!-- Student Status Filter -->
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Student Status</label>
                            <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">All Status</option>
                                <option value="Active" <?= $status_filter === 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="Inactive" <?= $status_filter === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="Transferred" <?= $status_filter === 'Transferred' ? 'selected' : '' ?>>Transferred</option>
                                <option value="Graduated" <?= $status_filter === 'Graduated' ? 'selected' : '' ?>>Graduated</option>
                                <option value="Dropped" <?= $status_filter === 'Dropped' ? 'selected' : '' ?>>Dropped</option>
                            </select>
                        </div>

                        <!-- Enrollment Status Filter -->
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Enrollment Status</label>
                            <select name="enrollment" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">All Enrollment</option>
                                <option value="Pending" <?= $enrollment_filter === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="For Review" <?= $enrollment_filter === 'For Review' ? 'selected' : '' ?>>For Review</option>
                                <option value="Approved" <?= $enrollment_filter === 'Approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="Declined" <?= $enrollment_filter === 'Declined' ? 'selected' : '' ?>>Declined</option>
                                <option value="Incomplete" <?= $enrollment_filter === 'Incomplete' ? 'selected' : '' ?>>Incomplete</option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel me-1"></i>Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <div>
                            <span class="text-muted small">
                                Showing <strong><?= count($students) ?></strong> of <strong><?= $total_records ?></strong> students
                            </span>
                        </div>
                        <div class="btn-group">
                            <?php if (!empty($search) || !empty($status_filter) || !empty($enrollment_filter)): ?>
                                <a href="my-students" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-x-circle me-1"></i>Clear Filters
                                </a>
                            <?php endif; ?>
                            <a href="my-students?export=csv&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&enrollment=<?= urlencode($enrollment_filter) ?>" 
                               class="btn btn-success btn-sm">
                                <i class="bi bi-file-earmark-excel me-1"></i>Export to CSV
                            </a>
                        </div>
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
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Grade & Section</th>
                                    <th>Enrollment Status</th>
                                    <th>Student Status</th>
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
                                            <i class="bi bi-gender-<?= strtolower($student['gender']) ?> me-1"></i>
                                            <?= htmlspecialchars($student['gender']) ?>
                                        </td>
                                        <td><?= $student['age'] ?> yrs</td>
                                        <td>
                                            <?php if ($student['grade_name'] && $student['section_name']): ?>
                                                <div>
                                                    <strong><?= htmlspecialchars($student['grade_name']) ?></strong>
                                                </div>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($student['section_name']) ?>
                                                    <?php if ($student['room_number']): ?>
                                                        (Room <?= htmlspecialchars($student['room_number']) ?>)
                                                    <?php endif; ?>
                                                </small>
                                            <?php else: ?>
                                                <span class="text-muted small">Not assigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $status = $student['enrollment_status'] ?? 'Not Enrolled';
                                            $badge_class = match($status) {
                                                'Approved' => 'success',
                                                'Pending' => 'warning',
                                                'For Review' => 'info',
                                                'Declined' => 'danger',
                                                'Incomplete' => 'secondary',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-<?= $badge_class ?>">
                                                <?= htmlspecialchars($status) ?>
                                            </span>
                                            <?php if ($student['remarks']): ?>
                                                <i class="bi bi-info-circle text-muted ms-1" 
                                                   data-bs-toggle="tooltip" 
                                                   title="<?= htmlspecialchars($student['remarks']) ?>"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $student_status_badge = match($student['student_status']) {
                                                'Active' => 'success',
                                                'Inactive' => 'secondary',
                                                'Transferred' => 'warning',
                                                'Graduated' => 'primary',
                                                'Dropped' => 'danger',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-<?= $student_status_badge ?>">
                                                <?= htmlspecialchars($student['student_status']) ?>
                                            </span>
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
                                                <a href="requirements?student_id=<?= $student['student_id'] ?>" 
                                                   class="btn btn-outline-info" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Requirements">
                                                    <i class="bi bi-file-earmark-check"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Page <?= $current_page ?> of <?= $total_pages ?>
                                </div>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <!-- Previous Button -->
                                        <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                            <a class="page-link" 
                                               href="?page=<?= $current_page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&enrollment=<?= urlencode($enrollment_filter) ?>">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>

                                        <!-- Page Numbers -->
                                        <?php
                                        $start_page = max(1, $current_page - 2);
                                        $end_page = min($total_pages, $current_page + 2);
                                        
                                        if ($start_page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=1&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&enrollment=<?= urlencode($enrollment_filter) ?>">1</a>
                                            </li>
                                            <?php if ($start_page > 2): ?>
                                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                            <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                                                <a class="page-link" 
                                                   href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&enrollment=<?= urlencode($enrollment_filter) ?>">
                                                    <?= $i ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($end_page < $total_pages): ?>
                                            <?php if ($end_page < $total_pages - 1): ?>
                                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                            <?php endif; ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= $total_pages ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&enrollment=<?= urlencode($enrollment_filter) ?>">
                                                    <?= $total_pages ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <!-- Next Button -->
                                        <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                                            <a class="page-link" 
                                               href="?page=<?= $current_page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&enrollment=<?= urlencode($enrollment_filter) ?>">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
                        <h5 class="mt-3 text-muted">No students found</h5>
                        <?php if (!empty($search) || !empty($status_filter) || !empty($enrollment_filter)): ?>
                            <p class="text-muted">Try adjusting your filters</p>
                            <a href="my-students" class="btn btn-outline-primary">Clear Filters</a>
                        <?php else: ?>
                            <p class="text-muted mb-3">You haven't enrolled any children yet</p>
                            <a href="add-student" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Add Your First Child
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Enable Bootstrap Tooltips -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>