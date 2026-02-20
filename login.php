<?php
include('config.php');
session_start();

// Redirect if already logged in
if (isset($_SESSION["id"])) {
    if ($_SESSION["role"] === "agent") {
        header("Location: agent/agent_dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}

$email = $password = "";
$email_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email)) {
        $email_err = "Please enter your email.";
    }
    if (empty($password)) {
        $password_err = "Please enter your password.";
    }

    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT id, full_name, email, password, role, is_active FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $full_name, $email_db, $hashed_password, $role, $is_active);
                if (mysqli_stmt_fetch($stmt)) {

                    if (password_verify($password, $hashed_password)) {

                        // Check if account is active
                        if ($role === "agent" && $is_active == 0) {
                            $login_err = "Your agent account is pending approval by admin.";
                        } else {
                            // Successful login
                            $_SESSION["id"] = $id;
                            $_SESSION["full_name"] = $full_name;
                            $_SESSION["email"] = $email_db;
                            $_SESSION["role"] = $role;

                            // Redirect based on role
                            if ($role === "agent") {
                                header("Location: agent/agent_dashboard.php");
                            } else {
                                header("Location: dashboard.php");
                            }
                            exit();
                        }

                    } else {
                        $password_err = "Incorrect password.";
                    }
                }
            } else {
                $email_err = "No account found with that email.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="./CSS/style.css">
<style>
.login-wrapper {
    min-height: calc(100vh - 80px);
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%);
    padding: 20px;
}
.login-card {
    max-width: 450px;
    width: 100%;
    background: #ffffff;
    padding: 40px;
    border-radius: 25px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}
.login-header {
    text-align: center;
    margin-bottom: 30px;
}
.login-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem;
    color: #2d2d2d;
}
.input-group-custom {
    position: relative;
    margin-bottom: 20px;
}
.input-group-custom label {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 8px;
    display: block;
    color: #555;
}
.input-group-custom i {
    position: absolute;
    left: 15px;
    top: 42px;
    color: var(--primary-color);
    opacity: 0.8;
}
.input-group-custom .form-control {
    padding-left: 45px;
    height: 50px;
    border-radius: 12px;
    border: 1px solid #eee;
    background: #fcfcfc;
}
.error-msg {
    color: #dc3545;
    font-size: 0.8rem;
    margin-top: 5px;
    font-weight: 500;
}
.btn-login {
    background-color: var(--primary-color);
    color: white;
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    margin-top: 10px;
}
.btn-login:hover {
    background-color: #bc8a5f;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(212, 163, 115, 0.3);
    color: white;
}
.alert-login {
    border-radius: 12px;
    padding: 12px;
    background-color: #f8d7da;
    color: #842029;
    margin-bottom: 15px;
}
</style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-magic fa-2x mb-3 text-primary"></i>
            <h2>Welcome Back</h2>
            <p class="text-muted">Please Login to Access the Portal </p>
        </div>

        <?php if(!empty($login_err)) echo "<div class='alert-login'>$login_err</div>"; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-group-custom">
                <label>Email Address</label>
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter your email">
                <div class="error-msg"><?php echo $email_err; ?></div>
            </div>

            <div class="input-group-custom">
                <label>Password</label>
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" placeholder="Enter your password">
                <div class="error-msg"><?php echo $password_err; ?></div>
            </div>

            <button type="submit" class="btn btn-login shadow-sm">Login</button>

            <div class="text-center mt-4">
                <p class="text-muted small">Don't have an account? 
                    <a href="register.php" class="text-primary fw-bold text-decoration-none">Sign Up Now</a>
                </p>
                <p class="small">
                    <a href="adminlogin.php" class="text-muted text-decoration-none"><i class="fas fa-user-shield"></i> Admin Access</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>