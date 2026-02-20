<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("location: login.php");
    exit;
}

include "config.php";

$userId = $_SESSION["id"];
$success = $error = "";

// Fetch user data including role and agent fields
$stmt = $conn->prepare("SELECT full_name, username, email, contact_number, cnic, address, role, expertise_area, experience_years, bio, website, linkedin FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

// Update profile if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name      = trim($_POST["full_name"]);
    $username       = trim($_POST["username"]);
    $email          = trim($_POST["email"]);
    $contact_number = trim($_POST["contact_number"]);
    $cnic           = trim($_POST["cnic"]);
    $address        = trim($_POST["address"]);

    // Agent-specific fields
    $expertise_area   = isset($_POST['expertise_area']) ? trim($_POST['expertise_area']) : null;
    $experience_years = isset($_POST['experience_years']) ? intval($_POST['experience_years']) : null;
    $bio              = isset($_POST['bio']) ? trim($_POST['bio']) : null;
    $website          = isset($_POST['website']) ? trim($_POST['website']) : null;
    $linkedin         = isset($_POST['linkedin']) ? trim($_POST['linkedin']) : null;

    if (!empty($full_name) && !empty($username) && !empty($email)) {
        if ($user['role'] === 'agent') {
            $stmt = $conn->prepare("UPDATE users SET full_name=?, username=?, email=?, contact_number=?, cnic=?, address=?, expertise_area=?, experience_years=?, bio=?, website=?, linkedin=? WHERE id=?");
            $stmt->bind_param("sssssssisssi", $full_name, $username, $email, $contact_number, $cnic, $address, $expertise_area, $experience_years, $bio, $website, $linkedin, $userId);
        } else {
            $stmt = $conn->prepare("UPDATE users SET full_name=?, username=?, email=?, contact_number=?, cnic=?, address=? WHERE id=?");
            $stmt->bind_param("ssssssi", $full_name, $username, $email, $contact_number, $cnic, $address, $userId);
        }

        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
            $_SESSION["full_name"] = $full_name;

            // Refresh local data for display
            $user["full_name"]      = $full_name;
            $user["username"]       = $username;
            $user["email"]          = $email;
            $user["contact_number"] = $contact_number;
            $user["cnic"]           = $cnic;
            $user["address"]        = $address;

            if ($user['role'] === 'agent') {
                $user['expertise_area']   = $expertise_area;
                $user['experience_years'] = $experience_years;
                $user['bio']              = $bio;
                $user['website']          = $website;
                $user['linkedin']         = $linkedin;
            }
        } else {
            $error = "Error updating profile: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error = "Name, Username, and Email are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Profile | </title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../CSS/style.css">
<style>
.profile-wrapper { background: #f8f9fa; min-height: 100vh; padding: 50px 0; }
.profile-card { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: none; overflow: hidden; }
.profile-header { background: var(--primary-color); color: white; padding: 30px; text-align: center; }
.profile-header h2 { font-family: 'Playfair Display', serif; margin: 0; }
.input-group-custom { position: relative; margin-bottom: 20px; }
.input-group-custom i { position: absolute; left: 15px; top: 42px; color: var(--primary-color); }
.input-group-custom .form-control { padding-left: 45px; border-radius: 10px; background: #fdfdfd; }
.btn-update { background: var(--primary-color); color: white; border-radius: 10px; padding: 12px; font-weight: 600; width: 100%; border: none; transition: 0.3s; }
.btn-update:hover { background: #bc8a5f; transform: translateY(-2px); }
</style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="profile-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card profile-card">
                    <div class="profile-header">
                        <h2><i class="fas fa-user-cog me-2"></i>Edit Profile</h2>
                    </div>
                    <div class="card-body p-4 p-md-5">

                        <?php if ($success): ?>
                        <div class="alert alert-success border-0 shadow-sm mb-4">
                            <i class="fas fa-check-circle me-2"></i> <?= $success ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="input-group-custom">
                                <label class="form-label fw-bold small text-muted">Full Name</label>
                                <i class="fas fa-id-badge"></i>
                                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group-custom">
                                        <label class="form-label fw-bold small text-muted">Username</label>
                                        <i class="fas fa-at"></i>
                                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group-custom">
                                        <label class="form-label fw-bold small text-muted">Contact Number</label>
                                        <i class="fas fa-phone"></i>
                                        <input type="text" name="contact_number" class="form-control" value="<?= htmlspecialchars($user['contact_number']) ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="input-group-custom">
                                <label class="form-label fw-bold small text-muted">Email Address</label>
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>

                            <div class="input-group-custom">
                                <label class="form-label fw-bold small text-muted">CNIC</label>
                                <i class="fas fa-id-card"></i>
                                <input type="text" name="cnic" class="form-control" value="<?= htmlspecialchars($user['cnic']) ?>">
                            </div>

                            <div class="input-group-custom">
                                <label class="form-label fw-bold small text-muted">Home Address</label>
                                <i class="fas fa-map-marker-alt"></i>
                                <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($user['address']) ?></textarea>
                            </div>

                            <?php if($user['role'] === 'agent'): ?>
                                <h5 class="mt-3">Agent Details</h5>
                                <div class="input-group-custom">
                                    <label class="form-label fw-bold small text-muted">Expertise Area</label>
                                    <i class="fas fa-briefcase"></i>
                                    <input type="text" name="expertise_area" class="form-control" value="<?= htmlspecialchars($user['expertise_area']) ?>">
                                </div>
                                <div class="input-group-custom">
                                    <label class="form-label fw-bold small text-muted">Years of Experience</label>
                                    <i class="fas fa-clock"></i>
                                    <input type="number" name="experience_years" class="form-control" value="<?= htmlspecialchars($user['experience_years']) ?>">
                                </div>
                                <div class="input-group-custom">
                                    <label class="form-label fw-bold small text-muted">Bio</label>
                                    <i class="fas fa-align-left"></i>
                                    <textarea name="bio" class="form-control" rows="2"><?= htmlspecialchars($user['bio']) ?></textarea>
                                </div>
                                <div class="input-group-custom">
                                    <label class="form-label fw-bold small text-muted">Website</label>
                                    <i class="fas fa-globe"></i>
                                    <input type="text" name="website" class="form-control" value="<?= htmlspecialchars($user['website']) ?>">
                                </div>
                                <div class="input-group-custom">
                                    <label class="form-label fw-bold small text-muted">LinkedIn</label>
                                    <i class="fab fa-linkedin"></i>
                                    <input type="text" name="linkedin" class="form-control" value="<?= htmlspecialchars($user['linkedin']) ?>">
                                </div>
                            <?php endif; ?>

                            <button type="submit" class="btn-update shadow-sm mt-3">Save Changes</button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="<?= $user['role'] === 'agent' ? 'agent/agent_dashboard.php' : 'dashboard.php' ?>" class="text-decoration-none text-muted small">
                                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>