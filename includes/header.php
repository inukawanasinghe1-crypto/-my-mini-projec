<?php
/**
 * Global Header Component (header.php)
 * EduHub - University Study Note & Resource Hub
 * Rajarata University of Sri Lanka | ICT 2209 Web Technologies
 */

if (!isset($base_path)) {
    $base_path = '';
}
if (!isset($page_title)) {
    $page_title = 'EduHub — University Study Note & Resource Hub';
}
if (!isset($active_page)) {
    $active_page = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EduHub is an interactive university study note, past paper, and academic resource platform built for RUSL ICT students.">
    <meta name="author" content="Department of ICT, Faculty of Technology, RUSL">
    <title><?php echo htmlspecialchars($page_title); ?></title>

    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Platform Theme CSS -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/style.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-canvas">

    <!-- Top Announcement Bar -->
    <div class="top-announcement py-1 text-center text-white small fw-medium">
        <span>🎓 <strong>Rajarata University of Sri Lanka</strong> &bull; Faculty of Technology &bull; ICT 2209 Web Technologies</span>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top glass-navbar py-3">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo $base_path; ?>index.php">
                <span class="brand-icon-box shadow-sm">
                    <i class="bi bi-mortarboard-fill"></i>
                </span>
                <div class="brand-text-group">
                    <span class="brand-title">Edu<strong>Hub</strong></span>
                    <span class="brand-subtitle">RUSL ICT NOTES</span>
                </div>
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarEduHub" aria-controls="navbarEduHub" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav Links & Auth Buttons -->
            <div class="collapse navbar-collapse" id="navbarEduHub">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link custom-nav-link <?php echo ($active_page === 'home') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>index.php">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link custom-nav-link <?php echo ($active_page === 'dashboard') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>dashboard.php">
                            <i class="bi bi-journal-bookmark me-1"></i> Resource Hub
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link custom-nav-link <?php echo ($active_page === 'contact') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>contact.php">
                            <i class="bi bi-envelope me-1"></i> Contact Us
                        </a>
                    </li>
                </ul>

                <!-- Auth Navigation Actions -->
                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    <?php if (is_logged_in()): ?>
                        <div class="dropdown">
                            <button class="btn btn-user-badge dropdown-toggle d-flex align-items-center gap-2" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="avatar-circle">
                                    <?php echo strtoupper(substr(current_username(), 0, 1)); ?>
                                </span>
                                <span class="fw-semibold text-truncate" style="max-width: 120px;"><?php echo htmlspecialchars(current_username()); ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2 mt-2" aria-labelledby="userMenuButton">
                                <li>
                                    <div class="dropdown-header text-uppercase small fw-bold text-muted">Account</div>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="<?php echo $base_path; ?>dashboard.php">
                                        <i class="bi bi-speedometer2 text-primary"></i> My Hub & Notes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger" href="<?php echo $base_path; ?>auth/logout.php">
                                        <i class="bi bi-box-arrow-right"></i> Sign Out
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo $base_path; ?>auth/login.php" class="btn btn-outline-light btn-sm px-3 rounded-pill fw-medium">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Log In
                        </a>
                        <a href="<?php echo $base_path; ?>auth/register.php" class="btn btn-primary-gradient btn-sm px-3 rounded-pill fw-medium shadow-sm">
                            <i class="bi bi-person-plus me-1"></i> Sign Up
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
