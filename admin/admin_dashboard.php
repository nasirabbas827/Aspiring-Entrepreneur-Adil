<?php
session_start();
include "config.php";

// ───────────────────────────────────────────────
// Security: Only allow logged-in admin
// ───────────────────────────────────────────────
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

// ───────────────────────────────────────────────
// Dashboard Statistics Queries
// ───────────────────────────────────────────────

// 1. Total Regular Users (role = 'user')
$total_users_query = "SELECT COUNT(*) AS count FROM users WHERE role = 'user'";
$total_users_result = mysqli_query($conn, $total_users_query);
$total_users = mysqli_fetch_assoc($total_users_result)['count'];

// 2. Total Agents / Mentors (role = 'agent')
$total_agents_query = "SELECT COUNT(*) AS count FROM users WHERE role = 'agent'";
$total_agents_result = mysqli_query($conn, $total_agents_query);
$total_agents = mysqli_fetch_assoc($total_agents_result)['count'];

// 3. Pending Agents (for approval)
$pending_agents_query = "SELECT COUNT(*) AS count FROM users WHERE role = 'agent' AND is_active = 0";
$pending_agents_result = mysqli_query($conn, $pending_agents_query);
$pending_agents = mysqli_fetch_assoc($pending_agents_result)['count'];

// 4. Total Business Resources
$total_resources_query = "SELECT COUNT(*) AS count FROM business_resources";
$total_resources_result = mysqli_query($conn, $total_resources_query);
$total_resources = mysqli_fetch_assoc($total_resources_result)['count'];

// 5. Total Feedback Received
$total_feedback_query = "SELECT COUNT(*) AS count FROM feedback";
$total_feedback_result = mysqli_query($conn, $total_feedback_query);
$total_feedback = mysqli_fetch_assoc($total_feedback_result)['count'];

// 6. Total Forum Posts
$total_posts_query = "SELECT COUNT(*) AS count FROM forum_posts";
$total_posts_result = mysqli_query($conn, $total_posts_query);
$total_posts = mysqli_fetch_assoc($total_posts_result)['count'];

// ───────────────────────────────────────────────
// Recent Registrations (last 10 users + agents)
// ───────────────────────────────────────────────
$recent_query = "
    SELECT id, full_name, username, email, role, contact_number, created_at, is_active 
    FROM users 
    ORDER BY created_at DESC 
    LIMIT 10
";
$recent_result = mysqli_query($conn, $recent_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | EntrepreneurHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/style.css">

    <style>
        :root {
            --primary-teal: #0d9488;
            --dark-navy: #0f172a;
            --light-bg: #f8fafc;
            --card-shadow: 0 6px 20px rgba(0,0,0,0.07);
        }
        body {
            background: var(--light-bg);
            font-family: 'Inter', sans-serif;
            color: #1e293b;
        }
        .main-wrapper {
            padding: 2.5rem 1.5rem;
        }
        .stat-card {
            border: none;
            border-radius: 16px;
            background: white;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(13,148,136,0.15);
        }
        .icon-shape {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.7rem;
        }
        .table-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }
        .status-pill, .role-badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        h2, h5 { font-weight: 700; }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="main-wrapper container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Admin Dashboard</h2>
            <p class="text-muted mb-0">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Administrator'); ?></p>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row g-4 mb-5">
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card p-4">
                <div class="d-flex align-items-center">
                    <div class="icon-shape me-3 bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small fw-bold text-uppercase">Total Users</h6>
                        <h3 class="mb-0"><?php echo number_format($total_users); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card stat-card p-4">
                <div class="d-flex align-items-center">
                    <div class="icon-shape me-3 bg-success bg-opacity-10 text-success">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small fw-bold text-uppercase">Total Mentors</h6>
                        <h3 class="mb-0"><?php echo number_format($total_agents); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card stat-card p-4">
                <div class="d-flex align-items-center">
                    <div class="icon-shape me-3 bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small fw-bold text-uppercase">Pending Approvals</h6>
                        <h3 class="mb-0"><?php echo number_format($pending_agents); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card stat-card p-4">
                <div class="d-flex align-items-center">
                    <div class="icon-shape me-3 bg-info bg-opacity-10 text-info">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small fw-bold text-uppercase">Resources</h6>
                        <h3 class="mb-0"><?php echo number_format($total_resources); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Registrations Table -->
        <div class="col-lg-8">
            <div class="card table-card">
                <div class="card-header bg-white border-0 py-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Registrations</h5>
                    <a href="view_users.php" class="btn btn-link fw-bold text-decoration-none">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4">Name</th>
                                    <th>Role</th>
                                    <th>Contact / Email</th>
                                    <th>Joined</th>
                                    <th class="text-end pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($recent_result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($recent_result)): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold"><?php echo htmlspecialchars($row['full_name']); ?></div>
                                                <div class="text-muted small">@<?php echo htmlspecialchars($row['username']); ?></div>
                                            </td>
                                            <td>
                                                <?php if ($row['role'] === 'agent'): ?>
                                                    <span class="role-badge bg-purple bg-opacity-10 text-purple">Mentor</span>
                                                <?php else: ?>
                                                    <span class="role-badge bg-secondary bg-opacity-10 text-secondary">User</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="small"><?php echo htmlspecialchars($row['contact_number'] ?: '—'); ?></div>
                                                <div class="text-muted small"><?php echo htmlspecialchars($row['email']); ?></div>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                            <td class="text-end pe-4">
                                                <?php if ($row['is_active']): ?>
                                                    <span class="status-pill bg-success bg-opacity-10 text-success">Active</span>
                                                <?php else: ?>
                                                    <span class="status-pill bg-warning bg-opacity-10 text-warning">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center py-5 text-muted">No recent registrations yet.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Sidebar -->
        <div class="col-lg-4">
            <div class="card table-card p-4">
                <h5 class="fw-bold mb-4">Quick Actions</h5>
                <div class="d-grid gap-3">
                    <a href="view_users.php" class="btn btn-outline-dark text-start p-3 border-dashed">
                        <i class="fas fa-user-check me-2 text-success"></i> Approve / Reject Mentors
                    </a>
                    <a href="view_categories.php" class="btn btn-outline-dark text-start p-3 border-dashed">
                        <i class="fas fa-tags me-2 text-info"></i> Manage Categories
                    </a>
                    <a href="view_contents.php" class="btn btn-outline-dark text-start p-3 border-dashed">
                        <i class="fas fa-book-open me-2 text-warning"></i> Tutorials & Events
                    </a>
                 </div>

                <div class="mt-5 p-4 bg-dark text-white rounded-4 shadow-sm">
                    <h6 class="fw-bold mb-3"><i class="fas fa-server me-2 text-success"></i> System Status</h6>
                    <hr class="opacity-25 mb-3">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="opacity-75">Database</span>
                        <span class="badge bg-success">Online</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="opacity-75">Current Time</span>
                        <span><?php echo date('H:i • d M Y'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>