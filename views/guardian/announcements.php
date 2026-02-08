<div class="row">
    <div class="col-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="bi bi-megaphone-fill me-2"></i>School Announcements
                </h3>
                <p class="text-muted mb-0">Stay updated with the latest school news and events</p>
            </div>
            <a href="guardian-dashboard" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="announcements" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <!-- Search -->
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Search Announcements</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Search by title or content" 
                                       value="<?= htmlspecialchars($search) ?>">
                            </div>
                        </div>

                        <!-- Type Filter -->
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Filter by Type</label>
                            <select name="type" class="form-select" onchange="document.getElementById('filterForm').submit()">
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
                        <div class="col-md-3">
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
                                Showing <strong><?= count($announcements) ?></strong> of <strong><?= $total_records ?></strong> announcements
                            </span>
                        </div>
                        <?php if (!empty($search) || !empty($type_filter)): ?>
                            <a href="announcements" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-x-circle me-1"></i>Clear Filters
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Announcements Grid -->
        <?php if (!empty($announcements)): ?>
            <div class="row g-4 mb-4">
                <?php foreach ($announcements as $announcement): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 h-100">
                            <!-- Type Badge -->
                            <div class="position-absolute top-0 end-0 m-3">
                                <?php
                                $type_badge = match($announcement['announcement_type']) {
                                    'Emergency' => 'danger',
                                    'Enrollment' => 'primary',
                                    'Event' => 'info',
                                    'Holiday' => 'success',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $type_badge ?>">
                                    <?= htmlspecialchars($announcement['announcement_type']) ?>
                                </span>
                            </div>

                            <!-- Icon -->
                            <div class="card-body">
                                <div class="mb-3">
                                    <?php
                                    $icon_class = match($announcement['announcement_type']) {
                                        'Enrollment' => 'bi-clipboard-check text-primary',
                                        'Event' => 'bi-calendar-event text-info',
                                        'Holiday' => 'bi-gift text-success',
                                        'Emergency' => 'bi-exclamation-triangle text-danger',
                                        default => 'bi-info-circle text-secondary'
                                    };
                                    ?>
                                    <i class="bi <?= $icon_class ?>" style="font-size: 2.5rem;"></i>
                                </div>

                                <!-- Title -->
                                <h5 class="card-title mb-2"><?= htmlspecialchars($announcement['title']) ?></h5>

                                <!-- Content Preview -->
                                <p class="card-text text-muted small">
                                    <?php
                                    $content = strip_tags($announcement['content']);
                                    echo htmlspecialchars(strlen($content) > 120 ? substr($content, 0, 120) . '...' : $content);
                                    ?>
                                </p>

                                <!-- Meta Info -->
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        <?= date('M d, Y', strtotime($announcement['created_at'])) ?>
                                    </small>
                                    <?php if ($announcement['target_audience'] === 'Specific Grade' && $announcement['grade_name']): ?>
                                        <small class="badge bg-info">
                                            <?= htmlspecialchars($announcement['grade_name']) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>

                                <!-- Read More Button -->
                                <div class="d-grid mt-3">
                                    <a href="view-announcement?id=<?= $announcement['announcement_id'] ?>" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Page <?= $current_page ?> of <?= $total_pages ?>
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <!-- Previous Button -->
                                    <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" 
                                           href="?page=<?= $current_page - 1 ?>&search=<?= urlencode($search) ?>&type=<?= urlencode($type_filter) ?>">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>

                                    <!-- Page Numbers -->
                                    <?php
                                    $start_page = max(1, $current_page - 2);
                                    $end_page = min($total_pages, $current_page + 2);
                                    
                                    if ($start_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=1&search=<?= urlencode($search) ?>&type=<?= urlencode($type_filter) ?>">1</a>
                                        </li>
                                        <?php if ($start_page > 2): ?>
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                        <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                                            <a class="page-link" 
                                               href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&type=<?= urlencode($type_filter) ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($end_page < $total_pages): ?>
                                        <?php if ($end_page < $total_pages - 1): ?>
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        <?php endif; ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $total_pages ?>&search=<?= urlencode($search) ?>&type=<?= urlencode($type_filter) ?>">
                                                <?= $total_pages ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Next Button -->
                                    <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                                        <a class="page-link" 
                                           href="?page=<?= $current_page + 1 ?>&search=<?= urlencode($search) ?>&type=<?= urlencode($type_filter) ?>">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Empty State -->
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-megaphone" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="mt-3 text-muted">No announcements found</h5>
                    <?php if (!empty($search) || !empty($type_filter)): ?>
                        <p class="text-muted">Try adjusting your filters</p>
                        <a href="announcements" class="btn btn-outline-primary">Clear Filters</a>
                    <?php else: ?>
                        <p class="text-muted mb-0">There are no announcements at this time</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>  