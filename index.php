<?php
/**
 * Main Landing Page (index.php)
 * EduHub - University Study Note & Resource Hub
 * Rajarata University of Sri Lanka | ICT 2209 Web Technologies
 */

$base_path = '';
$page_title = 'EduHub — University Study Note & Resource Hub | RUSL';
$active_page = 'home';

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Fetch quick platform statistics from database
$total_resources = 0;
$total_users = 0;
$recent_resources = [];

try {
    $resCountStmt = $pdo->query("SELECT COUNT(*) FROM resources");
    $total_resources = (int)$resCountStmt->fetchColumn();

    $userCountStmt = $pdo->query("SELECT COUNT(*) FROM users");
    $total_users = (int)$userCountStmt->fetchColumn();

    // Fetch 3 most recent resources with uploader name
    $recentStmt = $pdo->query("
        SELECT r.*, u.username 
        FROM resources r 
        JOIN users u ON r.user_id = u.id 
        ORDER BY r.created_at DESC 
        LIMIT 3
    ");
    $recent_resources = $recentStmt->fetchAll();
} catch (PDOException $e) {
    // Graceful fallback if database connection encounters an issue
    $recent_resources = [];
}

include_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container position-relative" style="z-index: 2;">
        <?php display_flash(); ?>

        <div class="row align-items-center g-5">
            <!-- Left Hero Text & CTA -->
            <div class="col-lg-7">
                <div class="hero-pill-badge mb-3">
                    <i class="bi bi-mortarboard text-warning"></i>
                    <span>RUSL ICT Academic Resource Network</span>
                </div>
                <h1 class="hero-headline">
                    Access, Share & Elevate Your <br>
                    <span class="text-gradient">University Study Materials</span>
                </h1>
                <p class="hero-subtext mb-4">
                    The dedicated peer-learning knowledge hub for Rajarata University of Sri Lanka ICT undergraduates. Discover curated lecture notes, lab sheets, and exam past papers in one seamless hub.
                </p>

                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="dashboard.php" class="btn btn-primary-gradient btn-lg px-4 py-3 rounded-pill fw-semibold shadow">
                        <i class="bi bi-search me-2"></i> Explore Resource Hub
                    </a>
                    <?php if (!is_logged_in()): ?>
                        <a href="auth/register.php" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill fw-semibold">
                            <i class="bi bi-person-plus me-2"></i> Join EduHub Free
                        </a>
                    <?php else: ?>
                        <a href="dashboard.php#addNoteSection" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill fw-semibold">
                            <i class="bi bi-plus-circle me-2"></i> Share a Note
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Quick Stats Row -->
                <div class="row g-3">
                    <div class="col-sm-4 col-6">
                        <div class="stats-card-mini">
                            <div class="h3 fw-bold text-white mb-0"><?php echo $total_resources > 0 ? $total_resources : '6+'; ?></div>
                            <div class="text-white-50 small">Study Resources</div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-6">
                        <div class="stats-card-mini">
                            <div class="h3 fw-bold text-white mb-0"><?php echo $total_users > 0 ? $total_users : '15+'; ?></div>
                            <div class="text-white-50 small">Student Contributors</div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="stats-card-mini">
                            <div class="h3 fw-bold text-info mb-0">100%</div>
                            <div class="text-white-50 small">Open Peer Access</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Interactive Featured Courses Carousel -->
            <div class="col-lg-5">
                <div class="carousel-card-wrapper shadow-lg">
                    <div id="eduHubHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4500">
                        <!-- Indicators -->
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#eduHubHeroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#eduHubHeroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#eduHubHeroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>

                        <!-- Carousel Inner Slides -->
                        <div class="carousel-inner">
                            <!-- Slide 1 -->
                            <div class="carousel-item active">
                                <div class="custom-carousel-slide carousel-slide-1">
                                    <div class="slide-content-overlay">
                                        <span class="slide-badge bg-primary">ICT 2209 Module</span>
                                        <h4 class="fw-bold mb-2">Web Technologies</h4>
                                        <p class="small text-white-50 mb-3">Mastering HTML5, Bootstrap 5, Vanilla JavaScript, and PHP/MySQL backend data architectures.</p>
                                        <a href="dashboard.php" class="btn btn-sm btn-outline-light rounded-pill align-self-start">
                                            View Module Notes <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 2 -->
                            <div class="carousel-item">
                                <div class="custom-carousel-slide carousel-slide-2">
                                    <div class="slide-content-overlay">
                                        <span class="slide-badge bg-success">ICT 2201 & 2203</span>
                                        <h4 class="fw-bold mb-2">Data Structures & Databases</h4>
                                        <p class="small text-white-50 mb-3">BST algorithms, normalizations, relational schemas, and hands-on lab sheet solutions.</p>
                                        <a href="dashboard.php" class="btn btn-sm btn-outline-light rounded-pill align-self-start">
                                            Explore Exercises <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Slide 3 -->
                            <div class="carousel-item">
                                <div class="custom-carousel-slide carousel-slide-3">
                                    <div class="slide-content-overlay">
                                        <span class="slide-badge bg-info text-dark">Exam Prep & Past Papers</span>
                                        <h4 class="fw-bold mb-2">Verified Past Exam Solutions</h4>
                                        <p class="small text-white-50 mb-3">Accelerate your exam revision with peer-verified solutions and module breakdown summaries.</p>
                                        <a href="dashboard.php" class="btn btn-sm btn-outline-light rounded-pill align-self-start">
                                            Browse Papers <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carousel Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#eduHubHeroCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#eduHubHeroCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Highlights Section -->
<section class="py-5 bg-white border-bottom">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold mb-2">Key Highlights</span>
            <h2 class="fw-bold">Engineered for Seamless Academic Collaboration</h2>
            <p class="text-muted">A dedicated platform conforming to modern responsive web standards and secure database integrations.</p>
        </div>

        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Instant Dynamic Search</h5>
                    <p class="text-muted small mb-0">
                        Filter materials instantaneously using real-time JavaScript DOM updates. Search across course codes, titles, and note summaries without reloading.
                    </p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon-box bg-success-subtle text-success">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Peer Contribution Portal</h5>
                    <p class="text-muted small mb-0">
                        Registered students can contribute study guides, lab exercises, and past papers directly to their fellow university peers with ease.
                    </p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon-box bg-info-subtle text-info">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Secure Authentication</h5>
                    <p class="text-muted small mb-0">
                        Built with enterprise-standard password hashing, parameterized SQL queries via PDO, and complete protection against unauthorized access.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Resources Section -->
<section class="py-5 bg-canvas">
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
            <div>
                <span class="badge bg-indigo-subtle text-primary px-3 py-1 rounded-pill fw-semibold mb-2">Fresh In Library</span>
                <h3 class="fw-bold mb-1">Recently Uploaded Study Notes</h3>
                <p class="text-muted mb-0 small">Latest academic materials shared by RUSL ICT students</p>
            </div>
            <a href="dashboard.php" class="btn btn-outline-primary rounded-pill px-4 fw-medium">
                View Full Library <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <?php if (!empty($recent_resources)): ?>
            <div class="row g-4">
                <?php foreach ($recent_resources as $res): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="resource-card">
                            <div class="resource-card-top-bar"></div>
                            <div class="resource-card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge-subject"><?php echo htmlspecialchars($res['subject_code']); ?></span>
                                    <span class="badge-category"><?php echo htmlspecialchars($res['category']); ?></span>
                                </div>
                                <h5 class="resource-card-title"><?php echo htmlspecialchars($res['title']); ?></h5>
                                <p class="resource-card-desc"><?php echo htmlspecialchars($res['description']); ?></p>
                                <div class="resource-card-footer">
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-person-circle text-muted"></i>
                                        <span><?php echo htmlspecialchars($res['username']); ?></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-clock text-muted"></i>
                                        <span><?php echo time_ago($res['created_at']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center py-4 rounded-4 shadow-sm">
                <i class="bi bi-journal-x fs-3 d-block mb-2"></i>
                No resources uploaded yet. Be the first to share your notes!
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5 bg-white border-top">
    <div class="container py-3">
        <div class="p-5 rounded-4 shadow-sm text-center text-white position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);">
            <h2 class="fw-bold mb-3">Ready to Excel in Your Academic Semester?</h2>
            <p class="text-white-50 max-w-600 mx-auto mb-4">
                Join our university community on EduHub. Access verified course materials, prepare for practical exams, and collaborate with your peers.
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="dashboard.php" class="btn btn-primary-gradient px-4 py-2 rounded-pill fw-semibold">
                    <i class="bi bi-journal-text me-1"></i> Browse All Materials
                </a>
                <a href="contact.php" class="btn btn-outline-light px-4 py-2 rounded-pill fw-semibold">
                    <i class="bi bi-question-circle me-1"></i> Inquire or Request Notes
                </a>
            </div>
        </div>
    </div>
</section>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
