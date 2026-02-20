<?php
session_start();
include "config.php";

// Optional: get search query
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$type_filter = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : '';

// Build query
$query = "SELECT * FROM contents WHERE 1=1";

if($search != '') {
    $query .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";
}

if($type_filter != '') {
    $query .= " AND resource_type='$type_filter'";
}

$query .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All Contents</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5 mb-5">
    <h2 class="fw-bold mb-4">Explore Contents</h2>

    <!-- Search & Filter -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search contents..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-4">
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="tutorial" <?= $type_filter=='tutorial'?'selected':'' ?>>Tutorial</option>
                <option value="event" <?= $type_filter=='event'?'selected':'' ?>>Event</option>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-primary"><i class="fas fa-search me-1"></i> Search</button>
        </div>
    </form>

    <!-- Contents List -->
    <div class="row">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars(substr($row['description'],0,150)) ?><?= strlen($row['description'])>150?'...':'' ?></p>
                        <span class="badge bg-info text-dark"><?= ucfirst($row['resource_type']) ?></span>
                        <?php if($row['file_path']): ?>
                            <a href="uploads/<?= htmlspecialchars($row['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                <i class="fas fa-file"></i> View File
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer text-muted small">
                        Created at: <?= date('M d, Y', strtotime($row['created_at'])) ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning">No contents found matching your search criteria.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>