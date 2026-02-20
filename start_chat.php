<?php
include('config.php');
session_start();
if (!isset($_SESSION['id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['id'];

// Fetch all active agents for the user to choose
$agents = mysqli_query($conn, "
    SELECT id, full_name, expertise_area, experience_years, bio, email, contact_number 
    FROM users 
    WHERE role='agent' AND is_active=1 
    ORDER BY full_name ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Start Conversation</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="./CSS/style.css">

<style>
    .agent-card { border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 15px; transition: 0.3s; }
    .agent-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
    .avatar { width: 60px; height: 60px; border-radius: 50%; background: #eee; display:flex; align-items:center; justify-content:center; font-size:24px; color:#888; }
    .btn-start-chat { width: 100%; }
</style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h3 class="mb-4">Select an Agent to Start Chat</h3>

    <?php if(mysqli_num_rows($agents) === 0): ?>
        <div class="alert alert-info">No active agents available at the moment.</div>
    <?php else: ?>
        <div class="row">
            <?php while($a = mysqli_fetch_assoc($agents)): ?>
            <div class="col-md-6">
                <div class="card agent-card p-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-3"><i class="fas fa-user-tie"></i></div>
                        <div>
                            <h5 class="mb-0"><?= htmlspecialchars($a['full_name']) ?></h5>
                            <small class="text-muted"><?= htmlspecialchars($a['expertise_area'] ?? 'Expert') ?> | <?= intval($a['experience_years'] ?? 0) ?> yrs experience</small>
                        </div>
                    </div>
                    <?php if(!empty($a['bio'])): ?>
                        <p class="small text-muted mb-2"><?= htmlspecialchars(substr($a['bio'],0,120)) ?><?= strlen($a['bio'])>120?'...':'' ?></p>
                    <?php endif; ?>
                    <ul class="list-inline small text-muted mb-3">
                        <?php if($a['email']): ?><li class="list-inline-item"><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($a['email']) ?></li><?php endif; ?>
                        <?php if($a['contact_number']): ?><li class="list-inline-item"><i class="fas fa-phone me-1"></i><?= htmlspecialchars($a['contact_number']) ?></li><?php endif; ?>
                    </ul>
                    <a href="chat.php?user_id=<?= $a['id'] ?>" class="btn btn-warning btn-start-chat">
                        <i class="fas fa-comment me-1"></i> Start Chat
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>