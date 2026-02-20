<?php
session_start();
include "config.php";

// Admin Protection
if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: adminlogin.php"); 
    exit(); 
}

// Get agent ID
if (!isset($_GET['agent_id'])) {
    header("Location: manage_agents.php");
    exit();
}

$agent_id = intval($_GET['agent_id']);

// Delete feedback
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM feedback WHERE feedback_id = $delete_id");
    header("Location: agent_feedback_admin.php?agent_id=$agent_id");
    exit();
}

// Add new feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If admin is adding feedback, use NULL
    $from_user = 'NULL'; 
    if(isset($_POST['from_user_id']) && !empty($_POST['from_user_id'])) {
        $from_user = intval($_POST['from_user_id']);
    }

    $rating = intval($_POST['rating']);
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);

    $insert_query = "INSERT INTO feedback (from_user_id, to_agent_id, rating, comments) 
                     VALUES ($from_user, $agent_id, $rating, '$comments')";
    mysqli_query($conn, $insert_query);
    header("Location: agent_feedback_admin.php?agent_id=$agent_id");
    exit();
}

// Fetch agent info
$agent_res = mysqli_query($conn, "SELECT * FROM users WHERE id=$agent_id AND role='agent'");
$agent = mysqli_fetch_assoc($agent_res);

// Fetch feedbacks
$feedback_res = mysqli_query($conn, "
    SELECT f.*, u.full_name AS from_name
    FROM feedback f
    LEFT JOIN users u ON f.from_user_id = u.id
    WHERE f.to_agent_id = $agent_id
    ORDER BY f.submitted_at DESC
");

// Fetch users for dropdown
$users_res = mysqli_query($conn, "SELECT id, full_name FROM users ORDER BY full_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Agent Feedbacks | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/style.css">

<style>
    body { background-color: #f4f7f6; }
    .card { border-radius: 12px; }
    .table thead { background-color: #212529; color: white; }
    .badge { font-size: 0.85rem; }
</style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-star me-2 text-warning"></i>Feedbacks for <?= htmlspecialchars($agent['full_name']); ?></h2>
        <a href="manage_agents.php" class="btn btn-outline-secondary fw-bold"><i class="fas fa-arrow-left me-1"></i> Back to Agents</a>
    </div>

    <!-- Add Feedback Form -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Add New Feedback</h5>
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="from_user_id" class="form-label">From User (optional, leave blank for Admin)</label>
                        <select name="from_user_id" id="from_user_id" class="form-select">
                            <option value="">Admin</option>
                            <?php while($u = mysqli_fetch_assoc($users_res)): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['full_name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="rating" class="form-label">Rating</label>
                        <select name="rating" id="rating" class="form-select" required>
                            <option value="">Select</option>
                            <?php for($i=1;$i<=5;$i++): ?>
                                <option value="<?= $i ?>"><?= $i ?> Star<?= $i>1?'s':'' ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="comments" class="form-label">Comments</label>
                        <input type="text" name="comments" id="comments" class="form-control" required>
                    </div>
                </div>
                <button class="btn btn-primary fw-bold"><i class="fas fa-plus me-1"></i> Add Feedback</button>
            </form>
        </div>
    </div>

    <!-- Feedback List -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">From</th>
                        <th>Rating</th>
                        <th>Comments</th>
                        <th>Submitted At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($f = mysqli_fetch_assoc($feedback_res)): ?>
                        <tr>
                            <td><?= htmlspecialchars($f['from_name'] ?? 'Admin') ?></td>
                            <td>
                                <?php for($i=1;$i<=5;$i++): ?>
                                    <i class="fas fa-star <?= $i <= $f['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                <?php endfor; ?>
                            </td>
                            <td><?= htmlspecialchars($f['comments']); ?></td>
                            <td><?= date('M d, Y H:i', strtotime($f['submitted_at'])); ?></td>
                            <td class="text-center">
                                <a href="agent_feedback_admin.php?agent_id=<?= $agent_id ?>&delete_id=<?= $f['feedback_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this feedback?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($feedback_res) == 0): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No feedback available for this agent.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>