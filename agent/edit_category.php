<?php
include('config.php');
session_start();

if (!isset($_SESSION['id'])) { 
    header("Location: login.php"); 
    exit(); 
}
$id = intval($_GET['id']);
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "UPDATE service_categories SET name='$name', description='$description' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: view_categories.php?msg=updated");
    } else {
        $message = "<div class='alert alert-danger'>Update failed: " . mysqli_error($conn) . "</div>";
    }
}

$res = mysqli_query($conn, "SELECT * FROM service_categories WHERE id=$id");
$cat = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/style.css">

</head>
<body class="bg-light" style="font-family: 'Inter', sans-serif;">
    <?php include('navbar.php'); ?>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-5">
                        <h3 class="fw-bold mb-4">
                            <i class="fas fa-edit me-2 text-warning"></i>Edit Category
                        </h3>
                        
                        <?php echo $message; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted small uppercase">Category Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($cat['name']); ?>" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted small uppercase">Description</label>
                                <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($cat['description']); ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-dark w-100 py-2 fw-bold">
                                <i class="fas fa-save me-2"></i>Update Category
                            </button>
                            
                            <a href="view_categories.php" class="btn btn-link w-100 mt-2 text-muted text-decoration-none small">
                                <i class="fas fa-arrow-left me-1"></i> Cancel and Go Back
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>