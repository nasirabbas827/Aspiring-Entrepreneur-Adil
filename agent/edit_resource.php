<?php
include('config.php');
session_start();

if (!isset($_SESSION['id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$id = intval($_GET['id']);
$message = "";

$res = mysqli_query($conn, "SELECT * FROM business_resources WHERE resource_id=$id");
$resource = mysqli_fetch_assoc($res);

// Fetch categories for dropdown
$categories = mysqli_query($conn, "SELECT * FROM service_categories ORDER BY name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = intval($_POST['category_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $summary = mysqli_real_escape_string($conn, $_POST['summary']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $type = $_POST['type'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // File upload handling
    $file_path = $resource['file_path'];
    if (!empty($_FILES['file']['name'])) {
        $target_dir = "uploads/";
        $filename = time() . "_" . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_path = $target_file;
        }
    }

    $sql = "UPDATE business_resources SET 
        category_id='$category_id', title='$title', summary='$summary', content='$content', 
        type='$type', description='$description', file_path='$file_path' 
        WHERE resource_id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: view_resources.php?msg=updated");
    } else {
        $message = "<div class='alert alert-danger'>Update failed: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Resource</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../CSS/style.css">
</head>
<body class="bg-light">
<?php include('navbar.php'); ?>
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4"><i class="fas fa-edit me-2 text-warning"></i>Edit Resource</h3>
                    <?= $message ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category_id" class="form-control" required>
                                <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $cat['id']==$resource['category_id']?'selected':'' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($resource['title']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Summary</label>
                            <textarea name="summary" class="form-control" rows="3"><?= htmlspecialchars($resource['summary']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Content</label>
                            <textarea name="content" class="form-control" rows="4"><?= htmlspecialchars($resource['content']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Type</label>
                            <select name="type" class="form-control" required>
                                <?php 
                                $types = ['business_idea','template','framework','market_research','startup_plan','funding_guide','legal_document','opportunity','case_study','tutorial','event'];
                                foreach($types as $t) {
                                    $sel = $resource['type']==$t?'selected':'';
                                    echo "<option value='$t' $sel>$t</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($resource['description']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload File</label>
                            <input type="file" name="file" class="form-control">
                            <?php if($resource['file_path']): ?>
                                <small class="text-muted">Current file: <a href="<?= $resource['file_path'] ?>" target="_blank">View</a></small>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-2">Update Resource</button>
                        <a href="view_resources.php" class="btn btn-link w-100 mt-2 text-muted text-decoration-none">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>