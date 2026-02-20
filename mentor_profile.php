<?php
include('config.php');
session_start();

// Redirect if not logged in
if (!isset($_SESSION['id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$agent_id = isset($_GET['agent_id']) ? intval($_GET['agent_id']) : 0;
$user_id = $_SESSION['id'];

// Fetch agent profile with error handling
$agent_query = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ? AND role = 'agent'");
mysqli_stmt_bind_param($agent_query, "i", $agent_id);
mysqli_stmt_execute($agent_query);
$agent = mysqli_stmt_get_result($agent_query)->fetch_assoc();

if (!$agent) {
    die("<div class='container mt-5 alert alert-danger'>Mentor not found or profile is not active.</div>");
}

// Fetch all resources uploaded by this agent
$resources = mysqli_query($conn, "
    SELECT br.*, sc.name AS category_name 
    FROM business_resources br 
    JOIN service_categories sc ON br.category_id = sc.id
    WHERE br.agent_id = $agent_id 
    ORDER BY br.created_at DESC
");

// Fetch Average Rating for the Mentor
$rating_res = mysqli_query($conn, "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM feedback WHERE to_agent_id = $agent_id");
$rating_data = mysqli_fetch_assoc($rating_res);
$avg_rating = number_format($rating_data['avg_rating'] ?? 0, 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($agent['full_name']) ?> | Expert Mentor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="./CSS/style.css">

    <style>
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; }
        .profile-header { 
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); 
            color: white; 
            padding: 80px 0; 
            border-radius: 0 0 40px 40px; 
        }
        .mentor-avatar {
            width: 120px;
            height: 120px;
            background: #3b82f6;
            color: white;
            font-size: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 20px;
            border: 4px solid rgba(255,255,255,0.2);
            font-weight: 800;
        }
        .stat-pill {
            background: rgba(255,255,255,0.1);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            backdrop-filter: blur(5px);
        }
        .resource-card {
            border: none;
            border-radius: 20px;
            transition: 0.3s;
            background: white;
            height: 100%;
        }
        .resource-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
        }
        .social-link {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f1f5f9;
            color: #1e293b;
            transition: 0.3s;
            margin: 0 5px;
        }
        .social-link:hover { background: #3b82f6; color: white; }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<header class="profile-header mb-5 shadow">
    <div class="container text-center">
        <div class="mentor-avatar">
            <?= strtoupper(substr($agent['full_name'], 0, 1)) ?>
        </div>
        <h2 class="fw-bold mb-2"><?= htmlspecialchars($agent['full_name']) ?></h2>
        <p class="text-info fw-semibold mb-3"><?= htmlspecialchars($agent['expertise_area'] ?? 'Business Consultant') ?></p>
        
        <div class="d-flex justify-content-center gap-3 mb-4">
            <span class="stat-pill"><i class="fas fa-star text-warning me-2"></i><?= $avg_rating ?> Rating</span>
            <span class="stat-pill"><i class="fas fa-briefcase text-info me-2"></i><?= intval($agent['experience_years']) ?> Years Exp.</span>
        </div>

        <p class="mx-auto opacity-75 mb-4" style="max-width: 700px;">
            <?= nl2br(htmlspecialchars($agent['bio'] ?? 'Dedicated mentor helping entrepreneurs turn ideas into reality.')) ?>
        </p>

        <div class="d-flex justify-content-center">
            <?php if($agent['website']): ?>
                <a href="<?= htmlspecialchars($agent['website']) ?>" target="_blank" class="social-link" title="Website"><i class="fas fa-globe"></i></a>
            <?php endif; ?>
            <?php if($agent['linkedin']): ?>
                <a href="<?= htmlspecialchars($agent['linkedin']) ?>" target="_blank" class="social-link" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            <?php endif; ?>
        </div>
        
        <div class="mt-4">
            <a href="messages.php?to_id=<?= $agent_id ?>" class="btn btn-primary px-4 rounded-pill me-2">
                <i class="fas fa-envelope me-2"></i>Contact Mentor
            </a>
            <a href="feedback_agent.php?agent_id=<?= $agent_id ?>" class="btn btn-outline-light px-4 rounded-pill">
                <i class="fas fa-star me-2"></i>Leave Review
            </a>
        </div>
    </div>
</header>

<div class="container mb-5">
    <div class="d-flex align-items-center mb-4">
        <div class="bg-primary rounded-3 p-2 text-white me-3">
            <i class="fas fa-book-open"></i>
        </div>
        <h4 class="fw-bold mb-0">Published Resources</h4>
    </div>

    <?php if(mysqli_num_rows($resources) > 0): ?>
    <div class="row g-4">
        <?php while($res = mysqli_fetch_assoc($resources)): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card resource-card p-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="badge bg-light text-primary border border-primary px-3 py-2 rounded-pill small">
                        <?= htmlspecialchars($res['category_name']) ?>
                    </span>
                </div>
                <h5 class="fw-bold text-dark mb-2"><?= htmlspecialchars($res['title']) ?></h5>
                <p class="text-muted small mb-4"><?= htmlspecialchars(substr($res['summary'] ?: $res['description'], 0, 120)) ?>...</p>
                
                <div class="mt-auto d-grid">
                    <a href="resource_detail.php?id=<?= $res['resource_id'] ?>" class="btn btn-outline-dark rounded-pill btn-sm">
                        View Full Details
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm p-5 text-center rounded-4">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No business resources available yet.</h5>
        </div>
    <?php endif; ?>
</div>

 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>