<?php
include('config.php');
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'agent') { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['id'];
$full_name = $_SESSION['full_name'] ?? 'Agent';

// --- Fetch Statistics from Database ---

// 1. Total Resources Posted
$res_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM business_resources WHERE agent_id = '$user_id'");
$res_data = mysqli_fetch_assoc($res_query);
$total_resources = $res_data['total'];

// 2. Average Feedback Rating
$rating_query = mysqli_query($conn, "SELECT AVG(rating) as avg_r FROM feedback WHERE to_agent_id = '$user_id'");
$rating_data = mysqli_fetch_assoc($rating_query);
$avg_rating = number_format($rating_data['avg_r'] ?? 0, 1);

// 3. Unread Private Messages
$msg_query = mysqli_query($conn, "SELECT COUNT(*) as unread FROM private_messages WHERE receiver_id = '$user_id' AND is_read = 0");
$msg_data = mysqli_fetch_assoc($msg_query);
$unread_msg = $msg_data['unread'];

// 4. Fetch 5 Recent Feedbacks
$feedbacks = mysqli_query($conn, "SELECT f.*, u.full_name FROM feedback f 
                                  LEFT JOIN users u ON f.from_user_id = u.id 
                                  WHERE to_agent_id = '$user_id' 
                                  ORDER BY submitted_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Dashboard | </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../CSS/style.css">

    <style>
        body { background-color: #f4f7f6; }
        .dashboard-header { background: #2d2d2d; color: white; padding: 40px 0; border-radius: 0 0 30px 30px; }
        .stat-card { border: none; border-radius: 15px; transition: 0.3s; background: white; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .icon-box { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<header class="dashboard-header mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h2>Welcome back, <?= htmlspecialchars($full_name) ?></h2>
            <p class="opacity-75 mb-0">Role: Business Agent | <?= date('D, d M Y') ?></p>
        </div>
        <div>
            <a href="add_resource.php" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-plus me-2"></i> Add Resource
            </a>
        </div>
    </div>
</header>

<div class="container mb-4">
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card p-4 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-primary text-white me-3"><i class="fas fa-file-invoice"></i></div>
                    <div>
                        <h3 class="mb-0 fw-bold"><?= $total_resources ?></h3>
                        <p class="text-muted mb-0">Total Resources</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-warning text-white me-3"><i class="fas fa-star"></i></div>
                    <div>
                        <h3 class="mb-0 fw-bold"><?= $avg_rating ?> / 5</h3>
                        <p class="text-muted mb-0">Agent Rating</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-success text-white me-3"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h3 class="mb-0 fw-bold"><?= $unread_msg ?></h3>
                        <p class="text-muted mb-0">New Messages</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-header bg-white py-3">
                    
                    <h5 class="mb-0 fw-bold text-dark">Recent Feedback - 
<a href="feedback_agent.php">View All Feedbacks</a>
                     
                    </h5>

                </div>
                
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($f = mysqli_fetch_assoc($feedbacks)): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($f['full_name'] ?? 'Admin') ?></strong></td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <?= $f['rating'] ?> <i class="fas fa-star ms-1"></i>
                                        </span>
                                    </td>
                                    <td class="text-muted small"><?= htmlspecialchars($f['comments']) ?></td>
                                    <td class="small"><?= date('M d, Y', strtotime($f['submitted_at'])) ?></td>
                                </tr>
                                <?php endwhile; if(mysqli_num_rows($feedbacks) == 0) echo "<tr><td colspan='4' class='text-center py-3'>No feedback received yet.</td></tr>"; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-3">Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="forum.php" class="btn btn-outline-dark text-start border-0 shadow-sm p-3">
                        <i class="fas fa-comments me-2 text-primary"></i> Join Forum Discussion
                    </a>
                    <a href="update_profile.php" class="btn btn-outline-dark text-start border-0 shadow-sm p-3">
                        <i class="fas fa-user-edit me-2 text-primary"></i> Edit Professional Bio
                    </a>
                    <a href="messages.php" class="btn btn-outline-dark text-start border-0 shadow-sm p-3">
                        <i class="fas fa-paper-plane me-2 text-primary"></i> View Private Messages
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>