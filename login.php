<!DOCTYPE html>
<?php
require_once 'php/includes/config.php';

// Check if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] === 'admin') {
        header('Location: dashboard-admin.php');
    } elseif ($_SESSION['user_type'] === 'employer') {
        header('Location: dashboard-employer.php');
    } else {
        header('Location: dashboard-candidate.php');
    }
    exit();
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JobPortal</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body style="background: var(--gray-100); min-height: 100vh; display: flex; flex-direction: column;">
    <!-- Header -->
    <header class="header" style="position: relative;">
        <div class="container">
            <nav class="navbar">
                <a href="index.html" class="logo">Job<span>Portal</span></a>
                <div class="nav-actions">
                    <a href="login.php" class="btn btn-outline btn-sm">Login</a>
                    <a href="register.html" class="btn btn-primary btn-sm">Register</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Login Form -->
    <main style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
        <div class="card" style="width: 100%; max-width: 450px;">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2>Welcome Back!</h2>
                <p style="color: var(--gray-500);">Login to your account</p>
            </div>

            <div id="loginAlert" class="alert hidden"></div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($_SESSION['success']);
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" action="php/handlers/login-handler.php" method="POST">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter your password" required>
                        <button type="button" onclick="togglePassword()"
                            style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gray-500);">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="forgot-password.html" style="font-size: 14px;">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div style="text-align: center; margin-top: 30px;">
                <p style="color: var(--gray-500);">Don't have an account? <a href="register.html">Register Now</a></p>
            </div>

            <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid var(--gray-200);">
                <p style="text-align: center; color: var(--gray-500); margin-bottom: 15px;">Demo Credentials:</p>
                <div
                    style="background: var(--gray-100); padding: 15px; border-radius: var(--border-radius); font-size: 14px;">
                    <p style="margin-bottom: 10px;"><strong>Candidate:</strong> candidate@test.com / password</p>
                    <p style="margin-bottom: 10px;"><strong>Employer:</strong> employer@test.com / password</p>
                    <p style="margin: 0;"><strong>Admin:</strong> admin@jobportal.com / password</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
            submitBtn.disabled = true;
        });
    </script>
</body>

</html>