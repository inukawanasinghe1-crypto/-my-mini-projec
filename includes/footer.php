<?php
/**
 * Global Footer Component (footer.php)
 * EduHub - University Study Note & Resource Hub
 * Rajarata University of Sri Lanka | ICT 2209 Web Technologies
 */

if (!isset($base_path)) {
    $base_path = '';
}
?>
    <!-- Global Footer -->
    <footer class="site-footer mt-auto text-white">
        <div class="footer-top py-5">
            <div class="container">
                <div class="row g-4 justify-content-between">
                    <!-- Column 1: About EduHub -->
                    <div class="col-lg-4 col-md-6">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="brand-icon-box shadow-sm">
                                <i class="bi bi-mortarboard-fill"></i>
                            </span>
                            <span class="brand-title text-white">Edu<strong>Hub</strong></span>
                        </div>
                        <p class="text-white-50 small mb-3">
                            An interactive web portal engineered for university students to share, discover, and organize study notes, lab sheets, and past papers dynamically.
                        </p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-secondary-subtle text-light border border-secondary">PHP & MySQL</span>
                            <span class="badge bg-secondary-subtle text-light border border-secondary">Bootstrap 5</span>
                            <span class="badge bg-secondary-subtle text-light border border-secondary">Vanilla JS</span>
                        </div>
                    </div>

                    <!-- Column 2: Quick Links -->
                    <div class="col-lg-2 col-md-6">
                        <h6 class="text-uppercase fw-bold mb-3 tracking-wider text-white">Navigation</h6>
                        <ul class="list-unstyled footer-links small">
                            <li class="mb-2"><a href="<?php echo $base_path; ?>index.php" class="text-white-50 text-decoration-none hover-light">Home</a></li>
                            <li class="mb-2"><a href="<?php echo $base_path; ?>dashboard.php" class="text-white-50 text-decoration-none hover-light">Resource Hub</a></li>
                            <li class="mb-2"><a href="<?php echo $base_path; ?>contact.php" class="text-white-50 text-decoration-none hover-light">Contact Queries</a></li>
                            <?php if (!is_logged_in()): ?>
                                <li class="mb-2"><a href="<?php echo $base_path; ?>auth/login.php" class="text-white-50 text-decoration-none hover-light">Student Login</a></li>
                                <li class="mb-2"><a href="<?php echo $base_path; ?>auth/register.php" class="text-white-50 text-decoration-none hover-light">Create Account</a></li>
                            <?php else: ?>
                                <li class="mb-2"><a href="<?php echo $base_path; ?>auth/logout.php" class="text-danger-emphasis text-decoration-none hover-light">Sign Out</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Column 3: Academic Details -->
                    <div class="col-lg-3 col-md-6">
                        <h6 class="text-uppercase fw-bold mb-3 tracking-wider text-white">Academic Details</h6>
                        <ul class="list-unstyled small text-white-50">
                            <li class="mb-2"><i class="bi bi-bank me-2 text-info"></i> Rajarata University of Sri Lanka</li>
                            <li class="mb-2"><i class="bi bi-cpu me-2 text-info"></i> Faculty of Technology</li>
                            <li class="mb-2"><i class="bi bi-code-slash me-2 text-info"></i> Department of ICT</li>
                            <li class="mb-2"><i class="bi bi-file-earmark-code me-2 text-info"></i> ICT 2209 Web Technologies</li>
                        </ul>
                    </div>

                    <!-- Column 4: Contact & Support -->
                    <div class="col-lg-3 col-md-6">
                        <h6 class="text-uppercase fw-bold mb-3 tracking-wider text-white">Support & Queries</h6>
                        <p class="small text-white-50 mb-3">
                            Have questions or want to request study materials for specific course modules? Reach out via our query system.
                        </p>
                        <a href="<?php echo $base_path; ?>contact.php" class="btn btn-outline-info btn-sm rounded-pill px-3">
                            <i class="bi bi-send me-1"></i> Send a Message
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="footer-bottom py-3 border-top border-secondary border-opacity-25 small text-center text-white-50">
            <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div>
                    &copy; <?php echo date('Y'); ?> EduHub. Built for ICT 2209 Mini Project submission.
                </div>
                <div class="d-flex gap-3">
                    <span class="text-white-50"><i class="bi bi-shield-check text-success me-1"></i> Input Validated</span>
                    <span class="text-white-50"><i class="bi bi-lock text-warning me-1"></i> BCRYPT Hashed</span>
                    <span class="text-white-50"><i class="bi bi-database text-info me-1"></i> PDO MySQL</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.2 Bundle JS (Includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Custom Main JS -->
    <script src="<?php echo $base_path; ?>js/main.js"></script>
</body>
</html>
