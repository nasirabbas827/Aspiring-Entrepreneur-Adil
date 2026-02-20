<?php
include('config.php');
session_start();

if (!isset($_SESSION['id'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Delete logic
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM business_resources WHERE resource_id = $id");
    header("Location: view_resources.php?msg=deleted");
}

// Fetch resources
$result = mysqli_query($conn, "
    SELECT br.*, sc.name AS category_name, u.full_name AS agent_name 
    FROM business_resources br
    JOIN service_categories sc ON br.category_id = sc.id
    JOIN users u ON br.agent_id = u.id
    ORDER BY br.resource_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Resources</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../CSS/style.css">
</head>
<body class="bg-light">
<?php include('navbar.php'); ?>
<div class="container mt-5 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Business Resources</h2>
        <a href="add_resource.php" class="btn btn-warning fw-bold px-4 rounded-pill"><i class="fas fa-plus me-2"></i>New Resource</a>
    </div>

    <?php if(isset($_GET['msg'])) echo "<div class='alert alert-info py-2'>Action performed successfully.</div>"; ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Agent</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="ps-4 text-muted">#<?= $row['resource_id'] ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['category_name']) ?></td>
                        <td><?= $row['type'] ?></td>
                        <td><?= htmlspecialchars($row['agent_name']) ?></td>
                        <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                        <td class="text-center">
                            <a href="edit_resource.php?id=<?= $row['resource_id'] ?>" class="mb-2 btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="view_resources.php?delete=<?= $row['resource_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this resource?')">
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