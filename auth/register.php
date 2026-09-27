<?php
/**
 * User Registration Page (register.php)
 * EduHub - University Study Note & Resource Hub
 * Rajarata University of Sri Lanka | ICT 2209 Web Technologies
 */

$base_path = '../';
$page_title = 'Register Account — EduHub RUSL';
$active_page = 'register';

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already authenticated
if (is_logged_in()) {
    header("Location: ../dashboard.php");
    exit;
}

$errors = [];
$username = '';
$email = '';

// Process registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Backend validation
    if (empty($username)) {
        $errors[] = 'Username is required.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        $errors[] = 'Username must be 3-30 characters and only contain letters, numbers, or underscores.';
    }

    if (empty($email)) {
        $errors[] = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Password confirmation does not match.';
    }

    // Check database if username or email already registered
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->execute([$username, $email]);
            $existingUser = $stmt->fetch();

            if ($existingUser) {
                $errors[] = 'That username or email is already registered. Please log in instead.';
            } else {
                // Securely hash password using BCRYPT algorithm
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Insert new user into database
                $insertStmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
                $insertStmt->execute([$username, $email, $hashed_password]);

                $newUserId = $pdo->lastInsertId();

                // Establish authenticated session
                $_SESSION['user_id'] = $newUserId;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;

                set_flash('success', "Welcome to EduHub, <strong>" . htmlspecialchars($username) . "</strong>! Your account has been created.");
                header("Location: ../dashboard.php");
                exit;
            }
        } catch (PDOException $e) {
            $errors[] = 'Database error occurred during registration. Please try again later.';
        }
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-page-wrapper">
    <div class="auth-card">
        <!-- Card Header -->
        <div class="auth-header">
            <div class="brand-icon-box mx-auto mb-3 shadow">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h3 class="fw-bold mb-1">Create Student Account</h3>
            <p class="text-white-50 small mb-0">Join the RUSL ICT Peer Resource Sharing Network</p>
        </div>

        <!-- Card Body -->
        <div class="auth-body">
            <?php display_flash(); ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <ul class="mb-0 ps-3 small">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" id="registerForm" novalidate>
                <!-- Username -->
                <div class="mb-3">
                    <label for="reg_username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" id="reg_username" class="form-control border-start-0 ps-2" 
                               placeholder="e.g. kamal_ict" value="<?php echo htmlspecialchars($username); ?>" required>
                        <div class="invalid-feedback">Username must be 3-30 letters, numbers, or underscores.</div>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="reg_email" class="form-label">University / Personal Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="reg_email" class="form-control border-start-0 ps-2" 
                               placeholder="student@rusl.ac.lk" value="<?php echo htmlspecialchars($email); ?>" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="reg_password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="reg_password" class="form-control border-start-0 ps-2" 
                               placeholder="Minimum 6 characters" required>
                        <div class="invalid-feedback">Password must be at least 6 characters.</div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="reg_confirm_password" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-shield-lock"></i></span>
                        <input type="password" name="confirm_password" id="reg_confirm_password" class="form-control border-start-0 ps-2" 
                               placeholder="Re-enter password" required>
                        <div class="invalid-feedback">Passwords do not match.</div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary-gradient w-100 py-2 fw-semibold rounded-3 mb-3">
                    <i class="bi bi-check-circle me-1"></i> Complete Registration
                </button>

                <!-- Switch to Login -->
                <div class="text-center small text-muted">
                    Already have an account? 
                    <a href="login.php" class="text-primary fw-semibold text-decoration-none">Sign In Here</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
