<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: adminlogin.php"); 
    exit(); 
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $resource_type = $_POST['resource_type'];

    $file_path = NULL;
    if(isset($_FILES['file']) && $_FILES['file']['error']==0) {
        $filename = time().'_'.basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], "../uploads/".$filename);
        $file_path = $filename;
    }

    mysqli_query($conn, "INSERT INTO contents (title, description, resource_type, file_path) VALUES ('$title','$description','$resource_type','$file_path')");
    header("Location: view_contents.php?msg=added");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Content | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div class="container mt-5 mb-5">
        <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
    <h2 class="fw-bold mb-4">Add New Content</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Resource Type</label>
            <select name="resource_type" class="form-select" required>
                <option value="">Select</option>
                <option value="tutorial">Tutorial</option>
                <option value="event">Event</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload File (optional)</label>
            <input type="file" name="file" class="form-control">
        </div>
        <button class="btn btn-primary fw-bold">Add Content</button>
    </form>
</div>
</div>
</div>
</body>
</html>