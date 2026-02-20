<?php
include('config.php');
session_start();

if (!isset($_SESSION['id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$agent_id = $_SESSION['id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = intval($_POST['category_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $summary = mysqli_real_escape_string($conn, $_POST['summary']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $type = $_POST['type'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Handle file upload
    $file_path = null;
    if (!empty($_FILES['file']['name'])) {
        $target_dir = "uploads/";
        $filename = time() . "_" . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_path = $target_file;
        }
    }

    $sql = "INSERT INTO business_resources 
        (agent_id, category_id, title, summary, content, type, description, file_path) 
        VALUES ('$agent_id', '$category_id', '$title', '$summary', '$content', '$type', '$description', '$file_path')";

    if (mysqli_query($conn, $sql)) {
        $message = "<div class='alert alert-success'>Resource added successfully! <a href='view_resources.php'>View List</a></div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch categories for dropdown
$categories = mysqli_query($conn, "SELECT * FROM service_categories ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Resource</title>
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
                    <h3 class="fw-bold mb-4"><i class="fas fa-folder-plus me-2 text-warning"></i>Add Resource</h3>
                    <?php echo $message; ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Summary</label>
                            <textarea name="summary" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Content</label>
                            <textarea name="content" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Type</label>
                            <select name="type" class="form-control" required>
                                <option value="">Select Type</option>
                                <?php 
                                $types = ['business_idea','template','framework','market_research','startup_plan','funding_guide','legal_document','opportunity','case_study','tutorial','event'];
                                foreach($types as $t) echo "<option value='$t'>$t</option>";
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload File (optional)</label>
                            <input type="file" name="file" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-2">Save Resource</button>
                        <a href="view_resources.php" class="btn btn-link w-100 mt-2 text-muted text-decoration-none">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>