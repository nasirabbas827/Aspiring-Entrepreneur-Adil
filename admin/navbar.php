<?php
// Get current page filename to set active class
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="admin_dashboard.php">
            <i class="fas fa-user-shield me-2 text-warning"></i>
            EntrepreneurHub <span class="ms-1 fw-light text-muted small">| Admin</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav ms-auto align-items-center text-uppercase small fw-bold">
                
                <li class="nav-item px-1">
                    <a class="nav-link <?= ($current_page == 'admin_dashboard.php') ? 'active text-warning' : '' ?>" href="admin_dashboard.php">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item px-1">
                    <a class="nav-link <?= ($current_page == 'view_categories.php') ? 'active text-warning' : '' ?>" href="view_categories.php">
                        Categories
                    </a>
                </li>
                
                <li class="nav-item px-1">
                    <a class="nav-link <?= ($current_page == 'view_users.php') ? 'active text-warning' : '' ?>" href="view_users.php">
                        Users
                    </a>
                </li>
                <li class="nav-item px-1">
                    <a class="nav-link <?= ($current_page == 'view_contents.php') ? 'active text-warning' : '' ?>" href="view_contents.php">
                        Tutorial - Events
                    </a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a class="btn btn-outline-danger btn-sm px-4 rounded-pill fw-bold" href="logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .nav-link {
        letter-spacing: 0.5px;
        transition: color 0.2s ease;
    }
    .nav-link:hover {
        color: #d4a373 !important; /* Accent Gold */
    }
    .navbar-dark .navbar-nav .nav-link.active {
        border-bottom: 2px solid #d4a373;
    }
</style>