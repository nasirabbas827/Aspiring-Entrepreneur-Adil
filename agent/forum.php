<?php
include('config.php');
session_start();
if (!isset($_SESSION['id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['id'];

// Handle new post
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['content'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $sql = "INSERT INTO forum_posts (user_id, title, content) VALUES ('$user_id','$title','$content')";
    if(mysqli_query($conn,$sql)) { $message = "<div class='alert alert-success'>Post added!</div>"; }
}

$posts = mysqli_query($conn,"SELECT f.*, u.full_name FROM forum_posts f JOIN users u ON f.user_id = u.id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Discussion Forum</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div class="container mt-5">
    <h3>Discussion Forum</h3>
    <?= $message ?>
    <form method="POST" class="mb-4">
        <input type="text" name="title" class="form-control mb-2" placeholder="Post Title" required>
        <textarea name="content" class="form-control mb-2" placeholder="Write your post" required></textarea>
        <button class="btn btn-primary">Add Post</button>
    </form>

    <?php while($p = mysqli_fetch_assoc($posts)): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5><?= htmlspecialchars($p['title']) ?></h5>
                <p><?= nl2br(htmlspecialchars($p['content'])) ?></p>
                <small>By <?= htmlspecialchars($p['full_name']) ?> on <?= date('M d, Y', strtotime($p['created_at'])) ?></small>
                <a href="post_details.php?post_id=<?= $p['post_id'] ?>" class="btn btn-sm btn-outline-primary float-end">View</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>