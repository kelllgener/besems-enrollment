<!-- Main Container -->
<div class="container-fluid py-4">
    
    <!-- 1. HEADER SECTION -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md">
            <h1 class="h3 fw-bold mb-1 text-dark">
                <i class="bi bi-people-fill me-2 text-primary"></i>My Children
            </h1>
            <p class="text-muted mb-0">Manage and view your children's academic records and enrollment status.</p>
        </div>
        <div class="col-md-auto">
            <a href="add-student" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Add New Child
            </a>
        </div>
    </div>

    <!-- 2. FILTERS AND SEARCH CARD -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="my-students" id="filterForm">
                <div class="row g-3">
                    <!-- Search -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label small text-uppercase fw-bold text-muted">Search Records</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-0 bg-light" 
                                   placeholder="Student Name or LRN..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                    </div>

                    <!-- Student Status Filter -->
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small text-uppercase fw-bold text-muted">Student Status</label>
                        <select name="status" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <?php foreach(['Active', 'Inactive', 'Transferred', 'Graduated', 'Dropped'] as $opt): ?>
                                <option value="<?= $opt ?>" <?= $status_filter === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Enrollment Status Filter -->
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small text-uppercase fw-bold text-muted">Enrollment</label>
                        <select name="enrollment" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">All Enrollments</option>
                            <?php foreach(['Pending', 'For Review', 'Approved', 'Declined', 'Incomplete'] as $opt): ?>
                                <option value="<?= $opt ?>" <?= $enrollment_filter === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="col-lg-2 col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-dark w-100 rounded-pill">
                            <i class="bi bi-funnel me-2"></i>Filter
                        </button>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top gap-3">
                    <div class="text-muted small">
                        Showing <span class="fw-bold text-dark"><?= count($students) ?></span> of <span class="fw-bold text-dark"><?= $total_records ?></span> children
                    </div>
                    <div class="d-flex gap-2">
                        <?php if (!empty($search) || !empty($status_filter) || !empty($enrollment_filter)): ?>
                            <a href="my-students" class="btn btn-light btn-sm rounded-pill px-3">
                                <i class="bi bi-x-lg me-1"></i> Clear Filters
                            </a>
                        <?php endif; ?>
                        <a href="my-students?export=csv&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&enrollment=<?= urlencode($enrollment_filter) ?>" 
                           class="btn btn-success bg-opacity-10 text-white border-success border-opacity-25 btn-sm rounded-pill px-3">
                            <i class="bi bi-download me-1"></i> Export CSV
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. STUDENTS TABLE CARD -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <?php if (!empty($students)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0 small text-uppercase fw-bold text-muted py-3">Student</th>
                                <th class="border-0 small text-uppercase fw-bold text-muted py-3">LRN</th>
                                <th class="border-0 small text-uppercase fw-bold text-muted py-3">Age/Gender</th>
                                <th class="border-0 small text-uppercase fw-bold text-muted py-3">Grade/Section</th>
                                <th class="border-0 small text-uppercase fw-bold text-muted py-3">Enrollment</th>
                                <th class="border-0 small text-uppercase fw-bold text-muted py-3">Status</th>
                                <th class="pe-4 border-0 small text-uppercase fw-bold text-muted py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($student['city_municipality']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fw-normal px-2 py-1">
                                            <?= htmlspecialchars($student['lrn']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small text-dark fw-semibold"><?= $student['age'] ?> yrs</div>
                                        <div class="small text-muted"><i class="bi bi-gender-<?= strtolower($student['gender']) ?> me-1"></i><?= htmlspecialchars($student['gender']) ?></div>
                                    </td>
                                    <td>
                                        <?php if ($student['grade_name']): ?>
                                            <div class="small fw-bold text-dark"><?= htmlspecialchars($student['grade_name']) ?></div>
                                            <div class="small text-muted"><?= htmlspecialchars($student['section_name'] ?? 'Section Pending') ?></div>
                                        <?php else: ?>
                                            <span class="text-muted small fst-italic">Not assigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $en_status = $student['enrollment_status'] ?? 'Not Enrolled';
                                        $en_color = match($en_status) {
                                            'Approved' => 'success',
                                            'Pending', 'For Review' => 'warning',
                                            'Declined' => 'danger',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge rounded-pill bg-<?= $en_color ?> bg-opacity-10 text-<?= $en_color ?> px-3">
                                            <?= htmlspecialchars($en_status) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $st_status = $student['student_status'];
                                        $st_color = match($st_status) {
                                            'Active' => 'success',
                                            'Graduated' => 'primary',
                                            'Transferred' => 'warning',
                                            'Dropped' => 'danger',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <div class="d-flex align-items-center small text-<?= $st_color ?> fw-bold">
                                            <i class="bi bi-circle-fill me-2" style="font-size: 0.5rem;"></i>
                                            <?= htmlspecialchars($st_status) ?>
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group shadow-sm rounded-pill bg-white border">
                                            <a href="view-student?id=<?= $student['student_id'] ?>" class="btn btn-sm text-primary border-0" title="View"><i class="bi bi-eye"></i></a>
                                            <a href="edit-student?id=<?= $student['student_id'] ?>" class="btn btn-sm text-secondary border-0" title="Edit"><i class="bi bi-pencil"></i></a>
                                            <a href="requirements?student_id=<?= $student['student_id'] ?>" class="btn btn-sm text-info border-0" title="Files"><i class="bi bi-file-earmark-check"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- 4. PAGINATION -->
                <?php if ($total_pages > 1): ?>
                    <div class="p-4 bg-light border-top d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="small text-muted">Page <strong><?= $current_page ?></strong> of <?= $total_pages ?></div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link rounded-circle me-1 border-0" href="?page=<?= $current_page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&enrollment=<?= urlencode($enrollment_filter) ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                                <!-- Simplified for design context: render current, prev, and next -->
                                <li class="page-item active"><span class="page-link rounded-circle me-1 border-0"><?= $current_page ?></span></li>
                                <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                                    <a class="page-link rounded-circle border-0" href="?page=<?= $current_page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>&enrollment=<?= urlencode($enrollment_filter) ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- EMPTY STATE -->
                <div class="text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-person-plus text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold">No students found</h5>
                    <p class="text-muted mb-4">Start by adding your first child or adjusting your filters.</p>
                    <a href="add-student" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-plus-lg me-2"></i>Enroll a Child
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (el) {
            return new bootstrap.Tooltip(el);
        });
    });
</script>