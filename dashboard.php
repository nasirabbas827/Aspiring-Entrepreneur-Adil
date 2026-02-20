<?php
date_default_timezone_set('Asia/Karachi');
include('config.php');
session_start();

// Security: Ensure only 'user' role can access this (or redirect agents to their own)
if (!isset($_SESSION['id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['id'];
$full_name = $_SESSION['full_name'] ?? 'User';

// --- USER STATISTICS ---
// Count user's forum posts
$forum_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM forum_posts WHERE user_id = $user_id"))['total'];
// Count unread messages for user
$unread_msg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM private_messages WHERE receiver_id = $user_id AND is_read = 0"))['total'];
// Count feedbacks given by user
$feedback_given = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM feedback WHERE from_user_id = $user_id"))['total'];

// Fetch categories for search dropdown
$categories = mysqli_query($conn, "SELECT * FROM service_categories ORDER BY name ASC");

// Handle search filters
$search_category = $_GET['category'] ?? '';
$search_agent = mysqli_real_escape_string($conn, $_GET['agent'] ?? '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="./CSS/style.css">

    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .dashboard-header { background: #2d2d2d; color: white; padding: 40px 0; border-radius: 0 0 30px 30px; }
        .stat-card { border: none; border-radius: 15px; transition: 0.3s; background: #fff; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .resource-card { border: none; border-radius: 20px; transition: 0.3s; overflow: hidden; height: 100%; }
        .resource-card:hover { box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .category-badge { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<header class="dashboard-header mb-4 shadow">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2>Welcome, <?= htmlspecialchars($full_name) ?></h2>
                <p class="opacity-75 mb-0">Member since: <?= date('M Y') ?></p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="forum.php" class="btn btn-primary rounded-pill px-4">Ask a Question</a>
            </div>
        </div>
    </div>
</header>

<div class="container mt-4 mb-4">
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-info p-3 rounded-3 text-white">
                        <i class="fas fa-comments fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0 fw-bold"><?= $forum_count ?></h5>
                        <small class="text-muted">Forum Posts</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success p-3 rounded-3 text-white">
                        <i class="fas fa-envelope-open-text fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0 fw-bold"><?= $unread_msg ?></h5>
                        <small class="text-muted">New Messages</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning p-3 rounded-3 text-white">
                        <i class="fas fa-star fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0 fw-bold"><?= $feedback_given ?></h5>
                        <small class="text-muted">Feedbacks Given</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-4 p-4">
        <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-search me-2"></i>Find Business Mentors</h5>
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <select name="category" class="form-select border-0 bg-light">
                    <option value="">All Service Categories</option>
                    <?php 
                    mysqli_data_seek($categories, 0);
                    while($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($search_category == $cat['id'] ? 'selected':'') ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="agent" class="form-control border-0 bg-light" placeholder="Search by name, expertise or keyword..." value="<?= htmlspecialchars($search_agent) ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 shadow-sm"><i class="fas fa-filter me-2"></i>Filter Results</button>
            </div>
        </form>
    </div>

    <?php
    $agent_condition = $search_agent ? " AND (u.full_name LIKE '%$search_agent%' OR u.expertise_area LIKE '%$search_agent%' OR br.title LIKE '%$search_agent%')" : '';
    $category_condition = $search_category ? " AND br.category_id = $search_category" : '';

    $resources_query = "
        SELECT br.*, u.full_name AS agent_name, u.expertise_area, u.experience_years, sc.name AS category_name
        FROM business_resources br
        JOIN users u ON br.agent_id = u.id
        JOIN service_categories sc ON br.category_id = sc.id
        WHERE u.role = 'agent' $agent_condition $category_condition
        ORDER BY br.created_at DESC
    ";
    $resources = mysqli_query($conn, $resources_query);

    if(mysqli_num_rows($resources) > 0):
    ?>
        <div class="row g-4">
        <?php while($res = mysqli_fetch_assoc($resources)): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm resource-card">
                    <div class="card-header bg-white border-0 pt-4">
                        <span class="badge bg-soft-primary text-primary border border-primary category-badge px-3 py-2 rounded-pill mb-2">
                            <?= htmlspecialchars($res['category_name']) ?>
                        </span>
                        <h5 class="fw-bold text-dark"><?= htmlspecialchars($res['title']) ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3"><?= htmlspecialchars(substr($res['summary'] ?: $res['description'], 0, 100)) ?>...</p>
                        
                        <div class="d-flex align-items-center p-2 bg-light rounded-3 mb-3">
                            <div class="ms-2">
                                <p class="mb-0 small fw-bold text-dark"><?= htmlspecialchars($res['agent_name']) ?></p>
                                <p class="mb-0 x-small text-muted" style="font-size: 0.75rem;">
                                    <?= htmlspecialchars($res['expertise_area']) ?> • <?= $res['experience_years'] ?> yrs exp.
                                </p>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="mentor_profile.php?agent_id=<?= $res['agent_id'] ?>" class="btn btn-outline-dark btn-sm rounded-pill">
                                <i class="fas fa-user me-1"></i> View Profile
                            </a>
                            <?php if($res['file_path']): ?>
                                <a href="agent/<?= $res['file_path'] ?>" class="btn btn-warning btn-sm rounded-pill" target="_blank">
                                    <i class="fas fa-download me-1"></i> Get Resource
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No resources or mentors matching your search.</h4>
            <a href="dashboard.php" class="btn btn-link">Clear all filters</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>