<?php
include('config.php');
session_start();

if (!isset($_SESSION['id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$agent_id = $_SESSION['id'];

// Fetch feedback for this agent
$result = mysqli_query($conn, "
    SELECT f.*, u.full_name AS user_name 
    FROM feedback f
    LEFT JOIN users u ON f.from_user_id = u.id
    WHERE f.to_agent_id = $agent_id
    ORDER BY f.submitted_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feedback Received</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../CSS/style.css">
<style>
.star-rating i { color: #ffc107; margin-right: 2px; }
</style>
</head>
<body class="bg-light">
<?php include('navbar.php'); ?>

<div class="container mt-5 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Feedback Received</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">#ID</th>
                        <th>From User</th>
                        <th>Rating</th>
                        <th>Comments</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="ps-4 text-muted">#<?= $row['feedback_id'] ?></td>
                        <td><?= htmlspecialchars($row['user_name'] ?? 'Admin') ?></td>
                        <td>
                            <div class="star-rating">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <i class="fas fa-star<?= $i <= $row['rating'] ? '' : '-half-alt' ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($row['comments']) ?></td>
                        <td><?= date('M d, Y H:i', strtotime($row['submitted_at'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($result) === 0): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">No feedback received yet.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>