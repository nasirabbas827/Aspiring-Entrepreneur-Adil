<?php
include('config.php');
session_start();
if (!isset($_SESSION['id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['id'];
$post_id = intval($_GET['post_id']);

// Add comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    mysqli_query($conn, "INSERT INTO forum_comments (post_id, user_id, comment) VALUES ('$post_id','$user_id','$comment')");
}

// Fetch post
$post_res = mysqli_query($conn,"SELECT f.*, u.full_name FROM forum_posts f JOIN users u ON f.user_id = u.id WHERE post_id = $post_id");
$post = mysqli_fetch_assoc($post_res);

// Fetch comments
$comments = mysqli_query($conn,"SELECT c.*, u.full_name FROM forum_comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = $post_id ORDER BY created_at ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($post['title']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div class="container mt-5">
    <h3><?= htmlspecialchars($post['title']) ?></h3>
    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
    <small>By <?= htmlspecialchars($post['full_name']) ?> on <?= date('M d, Y', strtotime($post['created_at'])) ?></small>

    <hr>
    <h5>Comments</h5>
    <?php while($c = mysqli_fetch_assoc($comments)): ?>
        <div class="mb-2">
            <strong><?= htmlspecialchars($c['full_name']) ?>:</strong>
            <?= nl2br(htmlspecialchars($c['comment'])) ?>
            <br><small class="text-muted"><?= date('M d, Y H:i', strtotime($c['created_at'])) ?></small>
        </div>
    <?php endwhile; ?>

    <form method="POST" class="mt-3">
        <textarea name="comment" class="form-control mb-2" placeholder="Write a comment" required></textarea>
        <button class="btn btn-primary">Add Comment</button>
    </form>
</div>
</body>
</html>