<?php
session_start();
include "config.php";

// Get Resource ID from URL
$resource_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($resource_id <= 0) {
    header("Location: index.php");
    exit();
}

// Fetch Detailed Resource Info + Agent Details
$query = "SELECT r.*, sc.name AS category_name, 
                 u.full_name AS agent_name, u.expertise_area, u.bio AS agent_bio, u.experience_years, u.linkedin
          FROM business_resources r
          LEFT JOIN service_categories sc ON r.category_id = sc.id
          LEFT JOIN users u ON r.agent_id = u.id
          WHERE r.resource_id = $resource_id";

$result = mysqli_query($conn, $query);
$res = mysqli_fetch_assoc($result);

if (!$res) {
    echo "Resource not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($res['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="./CSS/style.css">

    <style>
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; }
        .resource-header { background: #1e293b; color: white; padding: 60px 0; }
        .content-card { background: white; border-radius: 20px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .mentor-card { border-radius: 20px; border: 1px solid #e2e8f0; }
        .type-badge { background: #3b82f6; color: white; padding: 5px 15px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; }
        .download-box { background: #f1f5f9; border-radius: 15px; border: 2px dashed #cbd5e1; transition: 0.3s; }
        .download-box:hover { border-color: #3b82f6; background: #eff6ff; }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="resource-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-info text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white opacity-50" aria-current="page"><?= htmlspecialchars($res['category_name']) ?></li>
            </ol>
        </nav>
        <span class="type-badge mb-3 d-inline-block"><?= strtoupper(str_replace('_', ' ', $res['type'])) ?></span>
        <h1 class="display-5 fw-bold"><?= htmlspecialchars($res['title']) ?></h1>
        <p class="lead opacity-75">Posted on <?= date('M d, Y', strtotime($res['created_at'])) ?></p>
    </div>
</div>

<div class="container mt-n4" style="margin-top: -40px;">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card content-card p-4 p-md-5 mb-4">
                <h4 class="fw-bold mb-4">Executive Summary</h4>
                <p class="fs-5 text-secondary mb-5" style="line-height: 1.8;">
                    <?= nl2br(htmlspecialchars($res['summary'])) ?>
                </p>

                <h4 class="fw-bold mb-3">Full Details</h4>
                <div class="resource-content text-muted">
                    <?= nl2br(htmlspecialchars($res['content'])) ?>
                </div>

                <?php if ($res['file_path']): ?>
                <div class="download-box p-4 mt-5 text-center">
                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                    <h5>Attached Resource File</h5>
                    <p class="small text-muted">Download the full documentation or template provided by the mentor.</p>
                    <a href="<?= htmlspecialchars($res['file_path']) ?>" class="btn btn-primary px-5 rounded-pill shadow" download>
                        <i class="fas fa-download me-2"></i> Download Resource
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mentor-card bg-white p-4 sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4">About the Mentor</h5>
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <?= strtoupper(substr($res['agent_name'], 0, 1)) ?>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold"><?= htmlspecialchars($res['agent_name']) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($res['expertise_area']) ?></small>
                    </div>
                </div>
                
                <p class="small text-secondary mb-3">
                    <?= htmlspecialchars($res['agent_bio'] ?: 'A professional mentor with years of experience in business growth and strategy.') ?>
                </p>

                <ul class="list-unstyled small mb-4">
                    <li class="mb-2"><i class="fas fa-award text-warning me-2"></i> <strong>Experience:</strong> <?= (int)$res['experience_years'] ?> Years</li>
                    <?php if($res['linkedin']): ?>
                    <li><i class="fab fa-linkedin text-primary me-2"></i> <a href="<?= htmlspecialchars($res['linkedin']) ?>" target="_blank" class="text-decoration-none">LinkedIn Profile</a></li>
                    <?php endif; ?>
                </ul>

                <hr>

                <div class="d-grid gap-2">
                    <a href="messages.php?to_id=<?= $res['agent_id'] ?>" class="btn btn-dark rounded-pill">
                        <i class="fas fa-paper-plane me-2"></i> Send Inquiry
                    </a>
                    <a href="feedback_agent.php?agent_id=<?= $res['agent_id'] ?>" class="btn btn-outline-primary rounded-pill">
                        <i class="fas fa-star me-2"></i> Rate Mentor
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>