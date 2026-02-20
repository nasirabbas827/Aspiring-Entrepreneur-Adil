<?php
session_start();
include "config.php";

// Admin protection
if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: adminlogin.php"); 
    exit(); 
}

// Delete content
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Delete file if exists
    $res = mysqli_query($conn, "SELECT file_path FROM contents WHERE content_id=$id");
    $row = mysqli_fetch_assoc($res);
    if($row['file_path'] && file_exists("../uploads/".$row['file_path'])) unlink("../uploads/".$row['file_path']);
    
    mysqli_query($conn, "DELETE FROM contents WHERE content_id=$id");
    header("Location: view_contents.php?msg=deleted");
    exit();
}

// Fetch contents
$result = mysqli_query($conn, "SELECT * FROM contents ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Contents | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <h2 class="fw-bold">Contents</h2>
        <a href="add_content.php" class="btn btn-warning fw-bold px-4 rounded-pill"><i class="fas fa-plus me-2"></i>New Content</a>
    </div>

    <?php if(isset($_GET['msg'])) echo "<div class='alert alert-success py-2'>Action performed successfully.</div>"; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>File</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row=mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="ps-4">#<?= $row['content_id'] ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= $row['resource_type'] ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td>
                                <?php if($row['file_path']): ?>
                                    <a href="../uploads/<?= htmlspecialchars($row['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-file"></i></a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                            <td class="text-center">
                                <a href="edit_content.php?id=<?= $row['content_id'] ?>" class="btn btn-sm btn-outline-primary me-2"><i class="fas fa-edit"></i></a>
                                <a href="view_contents.php?delete=<?= $row['content_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this content?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($result) == 0): ?>
                        <tr><td colspan="7" class="text-center text-muted">No contents found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>