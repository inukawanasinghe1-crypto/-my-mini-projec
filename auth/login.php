<?php
/**
 * User Login Page (login.php)
 * EduHub - University Study Note & Resource Hub
 * Rajarata University of Sri Lanka | ICT 2209 Web Technologies
 */

$base_path = '../';
$page_title = 'Student Sign In — EduHub RUSL';
$active_page = 'login';

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already authenticated
if (is_logged_in()) {
    header("Location: ../dashboard.php");
    exit;
}

$errors = [];
$identifier = '';

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($identifier)) {
        $errors[] = 'Please enter your username or registered email.';
    }
    if (empty($password)) {
        $errors[] = 'Please enter your account password.';
    }

    if (empty($errors)) {
        try {
            // Find user by username OR email using prepared statement
            $stmt = $pdo->prepare("SELECT id, username, email, password FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->execute([$identifier, $identifier]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Password matches - regenerate session ID for security
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                set_flash('success', "Welcome back, <strong>" . htmlspecialchars($user['username']) . "</strong>! Successfully logged in.");
                header("Location: ../dashboard.php");
                exit;
            } else {
                $errors[] = 'Invalid username/email or password combination.';
            }
        } catch (PDOException $e) {
            $errors[] = 'Database service error. Please try again.';
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
                <i class="bi bi-box-arrow-in-right"></i>
            </div>
            <h3 class="fw-bold mb-1">Student Sign In</h3>
            <p class="text-white-50 small mb-0">Access your academic notes and shared resources</p>
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

            <!-- Examiner / Quick Demo Tip Box -->
            <div class="alert alert-primary-subtle border border-primary-subtle small rounded-3 p-3 mb-4">
                <div class="fw-bold text-primary mb-1"><i class="bi bi-info-circle-fill me-1"></i> Demo Test Account:</div>
                <div class="text-muted">Username: <code>kamal_ict</code> | Password: <code>password</code></div>
                <div class="text-muted">Or you can <a href="register.php" class="fw-semibold text-primary">sign up</a> for a new account.</div>
            </div>

            <form action="login.php" method="POST" id="loginForm" novalidate>
                <!-- Username or Email -->
                <div class="mb-3">
                    <label for="identifier" class="form-label">Username or Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person-badge"></i></span>
                        <input type="text" name="identifier" id="identifier" class="form-control border-start-0 ps-2" 
                               placeholder="e.g. kamal_ict or student@rusl.ac.lk" value="<?php echo htmlspecialchars($identifier); ?>" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control border-start-0 ps-2" 
                               placeholder="Your password" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary-gradient w-100 py-2 fw-semibold rounded-3 mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to EduHub
                </button>

                <!-- Switch to Register -->
                <div class="text-center small text-muted">
                    Don't have an account yet? 
                    <a href="register.php" class="text-primary fw-semibold text-decoration-none">Create One Now</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
