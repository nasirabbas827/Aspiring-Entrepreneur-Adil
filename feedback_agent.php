<?php
include('config.php');
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$agent_id = intval($_GET['agent_id']);
$message = "";

// Fetch agent profile
$agent_res = mysqli_query($conn, "SELECT full_name FROM users WHERE id = $agent_id AND role = 'agent'");
$agent = mysqli_fetch_assoc($agent_res);
if (!$agent) die("Agent not found.");

// Handle feedback submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = intval($_POST['rating']);
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);

    if ($rating >= 1 && $rating <= 5) {
        $sql = "INSERT INTO feedback (from_user_id, to_agent_id, rating, comments) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $user_id, $agent_id, $rating, $comments);
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Feedback submitted successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert alert-warning'>Rating must be between 1 and 5.</div>";
    }
}

// Fetch existing feedbacks for this agent
$feedbacks = mysqli_query($conn, "
    SELECT f.*, u.full_name AS from_user_name
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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Feedback for <?= htmlspecialchars($agent['full_name']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body { background: #f8f9fa; font-family: 'Inter', sans-serif; }
    .feedback-card { border-radius: 15px; margin-bottom: 20px; }
    .rating-star { color: #ffc107; }
</style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5 mb-5">
    <h3 class="fw-bold mb-4">Feedback for <?= htmlspecialchars($agent['full_name']) ?></h3>

    <?php if($message) echo $message; ?>

    <!-- Feedback Form -->
    <div class="card shadow-sm feedback-card p-4">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Rating (1-5)</label>
                <select name="rating" class="form-select" required>
                    <option value="">Select Rating</option>
                    <?php for($i=1;$i<=5;$i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Comments</label>
                <textarea name="comments" class="form-control" rows="3" placeholder="Write your feedback" required></textarea>
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane me-1"></i> Submit Feedback</button>
        </form>
    </div>

    <!-- Existing Feedbacks -->
    <h5 class="mt-5 mb-3 fw-bold">Previous Feedbacks</h5>
    <?php if(mysqli_num_rows($feedbacks) > 0): ?>
        <?php while($f = mysqli_fetch_assoc($feedbacks)): ?>
            <div class="card shadow-sm feedback-card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong><?= $f['from_user_name'] ?? 'Admin' ?></strong>
                    <small class="text-muted"><?= date('M d, Y H:i', strtotime($f['submitted_at'])) ?></small>
                </div>
                <div>
                    <?php for($i=1; $i<=5; $i++): ?>
                        <i class="fas fa-star rating-star <?= $i <= $f['rating'] ? '' : 'text-secondary' ?>"></i>
                    <?php endfor; ?>
                </div>
                <p class="mt-2"><?= htmlspecialchars($f['comments']) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info">No feedbacks submitted yet for this agent.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>