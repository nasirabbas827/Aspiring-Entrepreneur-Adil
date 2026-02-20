<?php
include('config.php');
session_start();

if (!isset($_SESSION['id'])) { 
    header("Location: login.php"); 
    exit(); 
}
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "INSERT INTO service_categories (name, description) VALUES ('$name', '$description')";
    if (mysqli_query($conn, $sql)) {
        $message = "<div class='alert alert-success'>Category added successfully! <a href='view_categories.php'>View List</a></div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/style.css">

</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-5">
                        <h3 class="fw-bold mb-4"><i class="fas fa-folder-plus me-2 text-warning"></i>Add Category</h3>
                        <?php echo $message; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Category Name</label>
                                <input type="text" name="name" class="form-control"  required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="4" ></textarea>
                            </div>
                            <button type="submit" class="btn btn-dark w-100 py-2">Save Category</button>
                            <a href="view_categories.php" class="btn btn-link w-100 mt-2 text-muted text-decoration-none">Cancel and Go Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>