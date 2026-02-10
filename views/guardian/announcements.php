<!-- Main Container -->
<div class="container-fluid py-4">

    <!-- 1. HEADER SECTION -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md">
            <h1 class="h3 fw-bold mb-1 text-dark">
                <i class="bi bi-megaphone-fill me-2 text-primary"></i>School Announcements
            </h1>
            <p class="text-muted mb-0">Stay updated with the latest school news and events.</p>
        </div>
        <div class="col-md-auto">
            <a href="dashboard" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- 2. FILTERS AND SEARCH CARD -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="announcements" id="filterForm">
                <div class="row g-3">
                    <!-- Search -->
                    <div class="col-lg-6 col-md-6">
                        <label class="form-label small text-uppercase fw-bold text-muted">Search Announcements</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-0 bg-light"
                                placeholder="Search by title or content..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                    </div>

                    <!-- Type Filter -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label small text-uppercase fw-bold text-muted">Filter by Type</label>
                        <select name="type" class="form-select border-0 bg-light" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <option value="General" <?= $type_filter === 'General' ? 'selected' : '' ?>>
                                General (<?= $counts_by_type['General'] ?? 0 ?>)
                            </option>
                            <option value="Enrollment" <?= $type_filter === 'Enrollment' ? 'selected' : '' ?>>
                                Enrollment (<?= $counts_by_type['Enrollment'] ?? 0 ?>)
                            </option>
                            <option value="Event" <?= $type_filter === 'Event' ? 'selected' : '' ?>>
                                Event (<?= $counts_by_type['Event'] ?? 0 ?>)
                            </option>
                            <option value="Holiday" <?= $type_filter === 'Holiday' ? 'selected' : '' ?>>
                                Holiday (<?= $counts_by_type['Holiday'] ?? 0 ?>)
                            </option>
                            <option value="Emergency" <?= $type_filter === 'Emergency' ? 'selected' : '' ?>>
                                Emergency (<?= $counts_by_type['Emergency'] ?? 0 ?>)
                            </option>
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
                        Showing <span class="fw-bold text-dark"><?= count($announcements) ?></span> of <span class="fw-bold text-dark"><?= $total_records ?></span> announcements
                    </div>
                    <div class="d-flex gap-2">
                        <?php if (!empty($search) || !empty($type_filter)): ?>
                            <a href="announcements" class="btn btn-light btn-sm rounded-pill px-3">
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

    <!-- 3. ANNOUNCEMENTS GRID CARD -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <?php if (!empty($announcements)): ?>
                <div class="p-4">
                    <div class="row g-4">
                        <?php foreach ($announcements as $announcement): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card shadow-sm border-0 rounded-4 h-100 position-relative">
                                    <!-- Type Badge -->
                                    <div class="position-absolute top-0 end-0 m-3" style="z-index: 1;">
                                        <?php
                                        $type_badge = match ($announcement['announcement_type']) {
                                            'Emergency' => 'danger',
                                            'Enrollment' => 'primary',
                                            'Event' => 'info',
                                            'Holiday' => 'success',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge rounded-pill bg-<?= $type_badge ?> bg-opacity-10 text-<?= $type_badge ?> px-3">
                                            <?= htmlspecialchars($announcement['announcement_type']) ?>
                                        </span>
                                    </div>

                                    <div class="card-body p-4">
                                        <!-- Icon -->
                                        <div class="mb-3">
                                            <?php
                                            $icon_class = match ($announcement['announcement_type']) {
                                                'Enrollment' => 'bi-clipboard-check text-primary',
                                                'Event' => 'bi-calendar-event text-info',
                                                'Holiday' => 'bi-gift text-success',
                                                'Emergency' => 'bi-exclamation-triangle text-danger',
                                                default => 'bi-info-circle text-secondary'
                                            };
                                            ?>
                                            <div class="avatar-sm bg-<?= str_replace('text-', '', $icon_class) ?> bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="bi <?= $icon_class ?>" style="font-size: 1.5rem;"></i>
                                            </div>
                                        </div>

                                        <!-- Title -->
                                        <h5 class="fw-bold text-dark mb-2"><?= htmlspecialchars($announcement['title']) ?></h5>

                                        <!-- Content Preview -->
                                        <p class="text-muted small mb-3">
                                            <?php
                                            $content = strip_tags($announcement['content']);
                                            echo htmlspecialchars(strlen($content) > 120 ? substr($content, 0, 120) . '...' : $content);
                                            ?>
                                        </p>

                                        <!-- Meta Info -->
                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3 border-top">
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                <?= date('M d, Y', strtotime($announcement['created_at'])) ?>
                                            </small>
                                            <?php if ($announcement['target_audience'] === 'Specific Grade' && $announcement['grade_name']): ?>
                                                <small class="badge rounded-pill bg-info bg-opacity-10 text-info px-2">
                                                    <?= htmlspecialchars($announcement['grade_name']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Read More Button -->
                                        <div class="d-grid mt-3">
                                            <a href="view-announcement?id=<?= $announcement['announcement_id'] ?>"
                                                class="btn btn-primary rounded-pill btn-sm shadow-sm">
                                                <i class="bi bi-eye me-1"></i>Read More
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 4. PAGINATION -->
                <?php if ($total_pages > 1): ?>
                    <div class="p-4 bg-light border-top d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="small text-muted">Page <strong><?= $current_page ?></strong> of <?= $total_pages ?></div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link rounded-circle me-1 border-0" href="?page=<?= $current_page - 1 ?>&search=<?= urlencode($search) ?>&type=<?= urlencode($type_filter) ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>

                                <?php
                                $start_page = max(1, $current_page - 2);
                                $end_page = min($total_pages, $current_page + 2);

                                if ($start_page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link rounded-circle me-1 border-0" href="?page=1&search=<?= urlencode($search) ?>&type=<?= urlencode($type_filter) ?>">1</a>
                                    </li>
                                    <?php if ($start_page > 2): ?>
                                        <li class="page-item disabled"><span class="page-link rounded-circle me-1 border-0">...</span></li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                    <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                                        <a class="page-link rounded-circle me-1 border-0" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&type=<?= urlencode($type_filter) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($end_page < $total_pages): ?>
                                    <?php if ($end_page < $total_pages - 1): ?>
                                        <li class="page-item disabled"><span class="page-link rounded-circle me-1 border-0">...</span></li>
                                    <?php endif; ?>
                                    <li class="page-item">
                                        <a class="page-link rounded-circle me-1 border-0" href="?page=<?= $total_pages ?>&search=<?= urlencode($search) ?>&type=<?= urlencode($type_filter) ?>">
                                            <?= $total_pages ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                                    <a class="page-link rounded-circle border-0" href="?page=<?= $current_page + 1 ?>&search=<?= urlencode($search) ?>&type=<?= urlencode($type_filter) ?>">
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
                        <i class="bi bi-megaphone text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold">No announcements found</h5>
                    <?php if (!empty($search) || !empty($type_filter)): ?>
                        <p class="text-muted mb-4">Try adjusting your filters to see more results.</p>
                        <a href="announcements" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-x-lg me-2"></i>Clear Filters
                        </a>
                    <?php else: ?>
                        <p class="text-muted mb-0">There are no announcements at this time.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>