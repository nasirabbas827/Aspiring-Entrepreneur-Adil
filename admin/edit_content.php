<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: adminlogin.php"); 
    exit(); 
}

if(!isset($_GET['id'])) header("Location: view_contents.php");

$id = intval($_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM contents WHERE content_id=$id");
$content = mysqli_fetch_assoc($res);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $resource_type = $_POST['resource_type'];

    $file_path = $content['file_path'];
    if(isset($_FILES['file']) && $_FILES['file']['error']==0) {
        // Remove old file
        if($file_path && file_exists("../uploads/".$file_path)) unlink("../uploads/".$file_path);
        $filename = time().'_'.basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], "../uploads/".$filename);
        $file_path = $filename;
    }

    mysqli_query($conn, "UPDATE contents SET title='$title', description='$description', resource_type='$resource_type', file_path='$file_path' WHERE content_id=$id");
    header("Location: view_contents.php?msg=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Content | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div class="container mt-5 mb-5">
        <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
    <h2 class="fw-bold mb-4">Edit Content</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($content['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($content['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Resource Type</label>
            <select name="resource_type" class="form-select" required>
                <option value="tutorial" <?= $content['resource_type']=='tutorial'?'selected':'' ?>>Tutorial</option>
                <option value="event" <?= $content['resource_type']=='event'?'selected':'' ?>>Event</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">File (optional)</label>
            <?php if($content['file_path']): ?>
                <div class="mb-2">
                    <a href="../uploads/<?= htmlspecialchars($content['file_path']) ?>" target="_blank"><?= htmlspecialchars($content['file_path']) ?></a>
                </div>
            <?php endif; ?>
            <input type="file" name="file" class="form-control">
        </div>
        <button class="btn btn-primary fw-bold">Update Content</button>
    </form>
</div>
</div>
</div>
</body>
</html>