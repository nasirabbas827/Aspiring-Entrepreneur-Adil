<?php
session_start();
include "config.php";

// Basic search & filter logic
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Optimized query for resources with Agent details
$query = "SELECT r.*, sc.name AS category_name, u.full_name AS agent_name, u.expertise_area 
          FROM business_resources r
          LEFT JOIN service_categories sc ON r.category_id = sc.id
          LEFT JOIN users u ON r.agent_id = u.id
          WHERE 1=1"; 

if ($search !== '') {
    $search = mysqli_real_escape_string($conn, $search);
    $query .= " AND (r.title LIKE '%$search%' OR r.summary LIKE '%$search%')";
}
if ($category_filter > 0) {
    $query .= " AND r.category_id = $category_filter";
}

$query .= " ORDER BY r.created_at DESC LIMIT 9";
$resources = mysqli_query($conn, $query);

// Get categories for dropdown
$categories = mysqli_query($conn, "SELECT id, name FROM service_categories ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Empowering Entrepreneurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #0d6efd;
            --dark-surface: #1e293b;
            --glass-bg: rgba(255, 255, 255, 0.95);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fcfcfd;
            color: #1e293b;
        }
        /* Hero Section with refined gradient */
        .hero-section {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.8)), 
                        url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            padding: 120px 0 160px;
            color: white;
            text-align: center;
            clip-path: ellipse(150% 100% at 50% 0%);
        }
        .hero-title { font-weight: 800; font-size: 3.5rem; letter-spacing: -1px; }
        
        /* Floating Search Bar */
        .search-wrapper {
            margin-top: -60px;
            position: relative;
            z-index: 100;
        }
        .search-glass {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        /* Resource Cards */
        .res-card {
            border: none;
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            background: white;
            border: 1px solid #f1f5f9;
        }
        .res-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.1);
        }
        .type-tag {
            background: #eef2ff;
            color: #4f46e5;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 50px;
            text-transform: uppercase;
        }
        .agent-info { font-size: 0.85rem; color: #64748b; }
        
        /* Section Divider */
        .section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: var(--primary-blue);
            margin: 15px auto;
            border-radius: 2px;
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<section class="hero-section">
    <div class="container">
        <h1 class="hero-title mb-3">Launch Your Vision.</h1>
        <p class="lead opacity-75 mb-5 fs-4 fw-light">Access professional business frameworks, startup templates, and mentors.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="#resources" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow">Browse Ideas</a>
            <a href="register.php" class="btn btn-outline-light btn-lg rounded-pill px-5">Join as Mentor</a>
        </div>
    </div>
</section>

<div class="container search-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form class="search-glass" method="GET">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="small fw-bold text-muted mb-2 ms-2">WHAT ARE YOU LOOKING FOR?</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control border-0 shadow-none bg-light rounded-3" 
                                   placeholder="Startup plans, Legal docs..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold text-muted mb-2 ms-2">INDUSTRY CATEGORY</label>
                        <select name="category" class="form-select border-0 shadow-none bg-light rounded-3">
                            <option value="">All Industries</option>
                            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?= $cat['id'] ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-grid align-items-end">
                        <button type="submit" class="btn btn-dark rounded-3 py-3 fw-bold">Filter Assets</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<section class="py-5 mt-5" id="resources">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title fw-bold">Latest Business Assets</h2>
            <p class="text-muted">High-quality resources contributed by our top-rated agents</p>
        </div>

        <div class="row g-4">
            <?php if (mysqli_num_rows($resources) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($resources)): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card res-card h-100 p-3">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="type-tag"><?= str_replace('_', ' ', $row['type']) ?></span>
                                    <small class="text-muted small"><?= date('M j', strtotime($row['created_at'])) ?></small>
                                </div>
                                
                                <h5 class="fw-bold mb-2"><?= htmlspecialchars($row['title']) ?></h5>
                                <p class="text-muted small flex-grow-1">
                                    <?= htmlspecialchars($row['summary'] ?: 'Access this professional resource to scale your business.') ?>
                                </p>

                                <div class="agent-info d-flex align-items-center mt-3 pt-3 border-top">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px; font-size: 10px;">
                                        <?= strtoupper(substr($row['agent_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark"><?= htmlspecialchars($row['agent_name']) ?></span>
                                        <span class="x-small"><?= htmlspecialchars($row['expertise_area']) ?></span>
                                    </div>
                                    <a href="resource_detail.php?id=<?= $row['resource_id'] ?>" class="ms-auto btn btn-link text-primary p-0">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50">
                    <p class="text-muted">No resources found matching your criteria.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="bg-dark text-white py-5 mt-5">
    <div class="container text-center">
        <div class="row g-4">
            <div class="col-md-4">
                <h2 class="fw-bold">500+</h2>
                <p class="text-muted mb-0">Verified Business Ideas</p>
            </div>
            <div class="col-md-4">
                <h2 class="fw-bold">150+</h2>
                <p class="text-muted mb-0">Professional Mentors</p>
            </div>
            <div class="col-md-4">
                <h2 class="fw-bold">2.5k</h2>
                <p class="text-muted mb-0">Active Entrepreneurs</p>
            </div>
        </div>
    </div>
</section>

<footer class="bg-white border-top py-5 mt-5">
    <div class="container text-center">
        <p class="fw-bold mb-1 text-primary">EntrepreneurHub</p>
        <p class="text-muted small">Bridging the gap between ideas and execution for the Pakistani youth.</p>
        <div class="mt-4">
            <small class="text-muted">© <?= date('Y') ?> EntrepreneurHub. All Rights Reserved.</small>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>