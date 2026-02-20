<?php
include('config.php');
session_start();

if (!isset($_SESSION['id'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Delete Logic
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM service_categories WHERE id = $id");
    header("Location: view_categories.php?msg=deleted");
}

$result = mysqli_query($conn, "SELECT * FROM service_categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/style.css">

</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-5 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Service Categories</h2>
            <a href="add_category.php" class="btn btn-warning fw-bold px-4 rounded-pill">
                <i class="fas fa-plus me-2"></i>New Category
            </a>
        </div>

        <?php if(isset($_GET['msg'])) echo "<div class='alert alert-info py-2'>Action performed successfully.</div>"; ?>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Created At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="ps-4 text-muted">#<?php echo $row['id']; ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="text-muted small"><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td class="text-center">
                                <a href="edit_category.php?id=<?php echo $row['id']; ?>" class="mb-2 btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="view_categories.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>