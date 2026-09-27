<?php
/**
 * Contact & Inquiry Page (contact.php)
 * EduHub - University Study Note & Resource Hub
 * Rajarata University of Sri Lanka | ICT 2209 Web Technologies
 */

$base_path = '';
$page_title = 'Contact & Support — EduHub RUSL';
$active_page = 'contact';

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$errors = [];
$name = '';
$email = '';
$subject = '';
$message = '';

// Pre-fill email and name if user is logged in
if (is_logged_in()) {
    $name = current_username();
    $email = $_SESSION['email'] ?? '';
}

// Process contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Backend validation
    if (empty($name)) {
        $errors[] = 'Please provide your full name.';
    }
    if (empty($email)) {
        $errors[] = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (empty($subject)) {
        $errors[] = 'Subject is required.';
    }
    if (empty($message)) {
        $errors[] = 'Please type your query or message.';
    } elseif (strlen($message) < 10) {
        $errors[] = 'Your message must be at least 10 characters long.';
    }

    if (empty($errors)) {
        try {
            // Save inquiry into messages table
            $stmt = $pdo->prepare("
                INSERT INTO messages (name, email, subject, message, created_at) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$name, $email, $subject, $message]);

            set_flash('success', "Thank you, <strong>" . htmlspecialchars($name) . "</strong>! Your message has been sent successfully. The EduHub academic support team will respond shortly.");
            
            // Clear message fields on success
            $subject = '';
            $message = '';
            if (!is_logged_in()) {
                $name = '';
                $email = '';
            }

            header("Location: contact.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Failed to submit inquiry due to a database error. Please try again.';
        }
    }
}

include_once __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<div class="dashboard-header-banner">
    <div class="container text-center">
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill fw-semibold mb-2">
            <i class="bi bi-chat-dots me-1"></i> Academic Support & Inquiries
        </span>
        <h2 class="fw-bold mb-2 text-white">Get in Touch with EduHub</h2>
        <p class="text-white-50 max-w-600 mx-auto small mb-0">
            Have questions regarding ICT 2209 course materials, want to request specific past exam discussions, or report an issue? Send us a message below.
        </p>
    </div>
</div>

<!-- Main Contact Section -->
<div class="container py-5">
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

    <div class="row g-4 align-items-stretch">
        <!-- Contact Information Column -->
        <div class="col-lg-5">
            <div class="contact-info-card shadow">
                <span class="badge bg-info text-dark fw-bold px-3 py-1 rounded-pill mb-3">Academic Institution</span>
                <h3 class="fw-bold mb-3 text-white">Department of ICT</h3>
                <p class="text-white-50 small mb-4">
                    Faculty of Technology, Rajarata University of Sri Lanka. EduHub is built as a peer-learning initiative for Web Technologies and ICT undergraduates.
                </p>

                <!-- Info Item 1 -->
                <div class="contact-info-item">
                    <div class="contact-icon-bubble">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <div class="fw-bold small text-light">Campus Location</div>
                        <div class="text-white-50 small">Faculty of Technology, Rajarata University of Sri Lanka, Mihintale, Sri Lanka.</div>
                    </div>
                </div>

                <!-- Info Item 2 -->
                <div class="contact-info-item">
                    <div class="contact-icon-bubble">
                        <i class="bi bi-envelope-at-fill"></i>
                    </div>
                    <div>
                        <div class="fw-bold small text-light">Email Address</div>
                        <div class="text-white-50 small">ict2209@tech.rjt.ac.lk / eduhub@rusl.ac.lk</div>
                    </div>
                </div>

                <!-- Info Item 3 -->
                <div class="contact-info-item">
                    <div class="contact-icon-bubble">
                        <i class="bi bi-book-half"></i>
                    </div>
                    <div>
                        <div class="fw-bold small text-light">Course Module</div>
                        <div class="text-white-50 small">ICT 2209 – Web Technologies Mini Project</div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-top border-secondary border-opacity-25">
                    <div class="small text-white-50 mb-2">Office Hours:</div>
                    <div class="badge bg-secondary-subtle text-light border border-secondary">Monday – Friday: 8:30 AM – 4:30 PM</div>
                </div>
            </div>
        </div>

        <!-- Contact Form Column -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100">
                <h4 class="fw-bold mb-2">Send an Inquiry or Feedback</h4>
                <p class="text-muted small mb-4">Fill out the form below. Your message will be logged securely to the university database.</p>

                <form action="contact.php" method="POST" id="contactForm" novalidate>
                    <div class="row g-3">
                        <!-- Name Field -->
                        <div class="col-md-6">
                            <label for="contact_name" class="form-label">Full Name *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" id="contact_name" class="form-control border-start-0 ps-2" 
                                       placeholder="e.g. Kasun Perera" value="<?php echo htmlspecialchars($name); ?>" required>
                                <div class="invalid-feedback">Please provide your full name.</div>
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="col-md-6">
                            <label for="contact_email" class="form-label">Email Address *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="contact_email" class="form-control border-start-0 ps-2" 
                                       placeholder="e.g. student@rusl.ac.lk" value="<?php echo htmlspecialchars($email); ?>" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                        </div>

                        <!-- Subject Field -->
                        <div class="col-12">
                            <label for="contact_subject" class="form-label">Subject *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-tag"></i></span>
                                <input type="text" name="subject" id="contact_subject" class="form-control border-start-0 ps-2" 
                                       placeholder="e.g. Request for ICT 2209 Lab Sheet 4 Discussion" value="<?php echo htmlspecialchars($subject); ?>" required>
                                <div class="invalid-feedback">Please enter the subject of your query.</div>
                            </div>
                        </div>

                        <!-- Message Field -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="contact_message" class="form-label mb-0">Your Message *</label>
                                <span id="contactCharCount" class="small text-muted">0 / 1000 characters</span>
                            </div>
                            <textarea name="message" id="contact_message" rows="5" maxlength="1000" class="form-control" 
                                      placeholder="Describe your question, request, or suggestions in detail..." required><?php echo htmlspecialchars($message); ?></textarea>
                            <div class="invalid-feedback">Please enter a message of at least 10 characters.</div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-primary-gradient px-4 py-2 rounded-pill fw-semibold shadow-sm">
                                <i class="bi bi-send-fill me-2"></i> Submit Inquiry
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
