<?php
// Define menu items based on role
$menuItems = [
    [
        'label' => 'Dashboard',
        'icon' => 'bi-speedometer2',
        'url' => 'home',
        'roles' => ['admin', 'user', 'student', 'teacher'] // Available to all
    ],
    [
        'label' => 'Subjects',
        'icon' => 'bi-book',
        'url' => 'subjects',
        'roles' => ['admin', 'teacher']
    ],
    [
        'label' => 'Students',
        'icon' => 'bi-people',
        'url' => 'students',
        'roles' => ['admin', 'teacher']
    ],
    [
        'label' => 'Teachers',
        'icon' => 'bi-person-badge',
        'url' => 'teachers',
        'roles' => ['admin']
    ],
    [
        'label' => 'Reports',
        'icon' => 'bi-graph-up',
        'url' => 'reports',
        'roles' => ['admin', 'teacher']
    ],
    [
        'label' => 'Settings',
        'icon' => 'bi-gear',
        'url' => 'settings',
        'roles' => ['admin', 'user', 'student', 'teacher']
    ]
];

// Get current page for active state
$currentPage = basename($_SERVER['REQUEST_URI']);
$userRole = $_SESSION['role'] ?? 'user';
?>

<nav id="sidebar" class="bg-dark vh-100 position-fixed">
    <div class="d-flex flex-column h-100">
        <!-- Brand -->
        <div class="p-3 border-bottom border-secondary">
            <h4 class="text-white mb-0 fw-bold">
                <i class="bi bi-building me-2"></i>BESEMS
            </h4>
            <small class="text-white-50">Management System</small>
        </div>

        <!-- User Info -->
        <div class="p-3 border-bottom border-secondary">
            <div class="d-flex align-items-center">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                    <i class="bi bi-person-fill text-white fs-5"></i>
                </div>
                <div class="flex-grow-1 text-truncate">
                    <div class="text-white fw-semibold small text-truncate"><?= htmlspecialchars($_SESSION['name'] ?? 'User') ?></div>
                    <div class="text-white-50 small text-capitalize">
                        <i class="bi bi-circle-fill text-success" style="font-size: 0.5rem;"></i>
                        <?= htmlspecialchars($userRole) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-grow-1 overflow-auto py-3">
            <ul class="nav flex-column px-2">
                <?php foreach ($menuItems as $item): ?>
                    <?php if (in_array($userRole, $item['roles'])): ?>
                        <?php
                        $isActive = (strpos($currentPage, $item['url']) !== false);
                        $activeClass = $isActive ? 'bg-primary' : '';
                        ?>
                        <li class="nav-item mb-1">
                            <a href="<?= htmlspecialchars($item['url']) ?>" 
                               class="nav-link text-white d-flex align-items-center rounded <?= $activeClass ?>">
                                <i class="bi <?= $item['icon'] ?> me-2"></i>
                                <span><?= htmlspecialchars($item['label']) ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Logout -->
        <div class="p-3 border-top border-secondary">
            <a href="logout" class="nav-link text-danger d-flex align-items-center">
                <i class="bi bi-box-arrow-right me-2"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</nav>