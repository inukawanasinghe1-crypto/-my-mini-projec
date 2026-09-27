<?php
/**
 * Resource Hub & Dashboard Page (dashboard.php)
 * EduHub - University Study Note & Resource Hub
 * Rajarata University of Sri Lanka | ICT 2209 Web Technologies
 */

$base_path = '';
$page_title = 'Resource Hub & Study Notes — EduHub RUSL';
$active_page = 'dashboard';

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$errors = [];

// -----------------------------------------------------------------------------
// Handle Resource Creation (POST)
// -----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_resource') {
    require_login(); // Ensure user is authenticated

    $subject_code = strtoupper(trim($_POST['subject_code'] ?? ''));
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? 'Lecture Notes');
    $description = trim($_POST['description'] ?? '');

    // Validation
    if (empty($subject_code)) {
        $errors[] = 'Subject code is required (e.g. ICT 2209).';
    }
    if (empty($title)) {
        $errors[] = 'Note title is required.';
    }
    if (empty($description)) {
        $errors[] = 'Detailed description or notes content is required.';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO resources (subject_code, title, category, description, user_id, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $subject_code,
                $title,
                $category,
                $description,
                current_user_id()
            ]);

            set_flash('success', "Study resource <strong>" . htmlspecialchars($title) . "</strong> uploaded successfully!");
            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Failed to publish resource. Please try again.';
        }
    }
}

// -----------------------------------------------------------------------------
// Handle Resource Deletion (POST)
// -----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_resource') {
    require_login();

    $resource_id = (int)($_POST['resource_id'] ?? 0);

    try {
        // Ensure only the original author can delete the resource
        $checkStmt = $pdo->prepare("SELECT user_id FROM resources WHERE id = ?");
        $checkStmt->execute([$resource_id]);
        $resAuthor = $checkStmt->fetch();

        if ($resAuthor && (int)$resAuthor['user_id'] === current_user_id()) {
            $deleteStmt = $pdo->prepare("DELETE FROM resources WHERE id = ?");
            $deleteStmt->execute([$resource_id]);
            set_flash('success', 'Resource removed successfully.');
        } else {
            set_flash('danger', 'Unauthorized action. You can only delete resources you posted.');
        }
    } catch (PDOException $e) {
        set_flash('danger', 'Database error when removing resource.');
    }

    header("Location: dashboard.php");
    exit;
}

// -----------------------------------------------------------------------------
// Fetch All Resources with Author Information
// -----------------------------------------------------------------------------
try {
    $stmt = $pdo->query("
        SELECT r.*, u.username, u.email 
        FROM resources r 
        JOIN users u ON r.user_id = u.id 
        ORDER BY r.created_at DESC
    ");
    $resources = $stmt->fetchAll();
} catch (PDOException $e) {
    $resources = [];
}

include_once __DIR__ . '/includes/header.php';
?>

<!-- Dashboard Top Banner -->
<div class="dashboard-header-banner">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill fw-semibold mb-2">
                    <i class="bi bi-collection me-1"></i> Central Knowledge Repository
                </span>
                <h2 class="fw-bold mb-1 text-white">University Resource Hub</h2>
                <p class="text-white-50 mb-0 small">
                    Browse lecture guides, lab manuals, and exam materials published by RUSL ICT students.
                </p>
            </div>
            <div>
                <?php if (is_logged_in()): ?>
                    <button class="btn btn-primary-gradient px-4 py-2 rounded-pill fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addResourceModal">
                        <i class="bi bi-plus-lg me-1"></i> Add Study Note
                    </button>
                <?php else: ?>
                    <a href="auth/login.php" class="btn btn-outline-light px-4 py-2 rounded-pill fw-semibold">
                        <i class="bi bi-lock me-1"></i> Sign In to Add Notes
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Area -->
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

    <!-- Interactive Search & Filter Controls -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="row g-3 align-items-center">
            <!-- Live Search Bar -->
            <div class="col-lg-5 col-md-6">
                <div class="search-wrapper">
                    <i class="bi bi-search search-icon-pos"></i>
                    <input type="text" id="resourceSearchInput" class="form-control search-input-field" 
                           placeholder="Type to filter by module code, topic, or keyword...">
                </div>
            </div>

            <!-- Category Filter Pills -->
            <div class="col-lg-7 col-md-6">
                <div class="d-flex flex-wrap gap-2 justify-content-md-end align-items-center">
                    <span class="small text-muted fw-bold d-none d-xl-inline me-1">Filter:</span>
                    <button type="button" class="filter-pill-btn active" data-filter="all">All Notes</button>
                    <button type="button" class="filter-pill-btn" data-filter="Lecture Notes">Lectures</button>
                    <button type="button" class="filter-pill-btn" data-filter="Past Papers">Past Papers</button>
                    <button type="button" class="filter-pill-btn" data-filter="Lab Sheets">Lab Sheets</button>
                    <button type="button" class="filter-pill-btn" data-filter="Summary">Summaries</button>
                    <button type="button" class="filter-pill-btn" data-filter="ICT 2209">ICT 2209</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Counters & Status Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 px-1">
        <div class="small text-muted">
            Displaying <span id="resourceDisplayCount" class="fw-bold text-dark"><?php echo count($resources); ?></span> of <span class="fw-bold"><?php echo count($resources); ?></span> academic resources
        </div>
        <div class="small text-muted d-none d-sm-block">
            <i class="bi bi-info-circle me-1"></i> Instant real-time DOM filtering enabled
        </div>
    </div>

    <!-- Dynamic Resources Grid -->
    <div class="row g-4" id="resourceGrid">
        <?php if (!empty($resources)): ?>
            <?php foreach ($resources as $res): ?>
                <div class="col-lg-4 col-md-6 resource-item-col" 
                     data-subject="<?php echo htmlspecialchars($res['subject_code']); ?>"
                     data-category="<?php echo htmlspecialchars($res['category']); ?>"
                     data-title="<?php echo htmlspecialchars($res['title']); ?>"
                     data-content="<?php echo htmlspecialchars($res['description']); ?>">
                    <div class="resource-card h-100">
                        <div class="resource-card-top-bar"></div>
                        <div class="resource-card-body">
                            <!-- Subject & Category Badges -->
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge-subject"><?php echo htmlspecialchars($res['subject_code']); ?></span>
                                <span class="badge-category"><?php echo htmlspecialchars($res['category']); ?></span>
                            </div>

                            <!-- Note Title -->
                            <h5 class="resource-card-title"><?php echo htmlspecialchars($res['title']); ?></h5>

                            <!-- Note Preview -->
                            <p class="resource-card-desc"><?php echo htmlspecialchars($res['description']); ?></p>

                            <!-- Actions Row -->
                            <div class="mt-auto pt-3 d-flex align-items-center justify-content-between border-top border-light-subtle">
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-medium btn-view-note"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#viewNoteModal"
                                        data-title="<?php echo htmlspecialchars($res['title']); ?>"
                                        data-subject="<?php echo htmlspecialchars($res['subject_code']); ?>"
                                        data-category="<?php echo htmlspecialchars($res['category']); ?>"
                                        data-author="<?php echo htmlspecialchars($res['username']); ?>"
                                        data-date="<?php echo format_date($res['created_at']); ?>"
                                        data-content="<?php echo htmlspecialchars($res['description']); ?>">
                                    <i class="bi bi-eye me-1"></i> Read Note
                                </button>

                                <?php if (is_logged_in() && (int)$res['user_id'] === current_user_id()): ?>
                                    <!-- Delete Button for Author -->
                                    <form action="dashboard.php" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this resource?');" class="d-inline">
                                        <input type="hidden" name="action" value="delete_resource">
                                        <input type="hidden" name="resource_id" value="<?php echo $res['id']; ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-2" title="Delete note" data-bs-toggle="tooltip">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>

                            <!-- Card Author & Date Footer -->
                            <div class="resource-card-footer mt-2">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bi bi-person text-secondary"></i>
                                    <span class="fw-medium text-dark"><?php echo htmlspecialchars($res['username']); ?></span>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bi bi-calendar3 text-muted"></i>
                                    <span><?php echo format_date($res['created_at']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 border p-4 shadow-sm">
                    <i class="bi bi-folder2-open display-4 text-muted mb-3 d-block"></i>
                    <h4 class="fw-bold">No Resources in Library Yet</h4>
                    <p class="text-muted">Be the first to share a lecture guide or lab exercise with your batchmates.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Empty State for Live Search Filtering (Toggled by JS) -->
    <div id="noResultsMessage" class="text-center py-5 bg-white rounded-4 border p-4 shadow-sm my-4" style="display: none;">
        <i class="bi bi-search display-5 text-muted mb-3 d-block"></i>
        <h4 class="fw-bold text-dark">No Matching Study Materials Found</h4>
        <p class="text-muted mb-3">Try adjusting your keyword, course code, or clearing your category filters.</p>
        <button class="btn btn-outline-primary btn-sm rounded-pill px-4" onclick="document.getElementById('resourceSearchInput').value=''; document.querySelectorAll('.filter-pill-btn')[0].click();">
            Reset Filters
        </button>
    </div>
</div>

<!-- ========================================================================= -->
<!-- Modal: Detailed Note Viewer                                              -->
<!-- ========================================================================= -->
<div class="modal fade custom-modal" id="viewNoteModal" tabindex="-1" aria-labelledby="viewNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-2">
                    <span id="modalNoteSubject" class="badge bg-info text-dark fw-bold px-2 py-1 rounded"></span>
                    <span id="modalNoteCategory" class="badge bg-light text-dark fw-medium px-2 py-1 rounded"></span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <h4 id="modalNoteTitle" class="fw-bold text-dark mb-3"></h4>
                
                <div class="d-flex flex-wrap gap-4 text-muted small border-bottom pb-3 mb-4">
                    <div><i class="bi bi-person-circle text-primary me-1"></i> Contributed by: <strong id="modalNoteAuthor" class="text-dark"></strong></div>
                    <div><i class="bi bi-calendar-event text-secondary me-1"></i> Published: <span id="modalNoteDate"></span></div>
                    <div><i class="bi bi-shield-check text-success me-1"></i> Verified RUSL Material</div>
                </div>

                <div class="mb-4">
                    <h6 class="text-uppercase small fw-bold text-muted mb-2 tracking-wider">Note Content & Breakdown</h6>
                    <div id="modalNoteContent" class="p-3 bg-light rounded-3 text-secondary" style="white-space: pre-wrap; line-height: 1.7; font-size: 0.95rem;"></div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2">
                    <button type="button" id="modalCopyBtn" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        <i class="bi bi-clipboard me-1"></i> Copy Note Details
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- Modal: Add New Study Resource (Authenticated Users)                      -->
<!-- ========================================================================= -->
<?php if (is_logged_in()): ?>
<div class="modal fade custom-modal" id="addResourceModal" tabindex="-1" aria-labelledby="addResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-cloud-plus-fill fs-4 text-info"></i>
                    <h5 class="modal-title fw-bold" id="addResourceModalLabel">Share a Study Resource</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="dashboard.php" method="POST" id="addResourceForm" novalidate>
                <input type="hidden" name="action" value="add_resource">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Subject Code -->
                        <div class="col-md-6">
                            <label for="res_subject" class="form-label">Subject Code *</label>
                            <input type="text" name="subject_code" id="res_subject" class="form-control" 
                                   placeholder="e.g. ICT 2209" required>
                            <div class="invalid-feedback">Please enter the subject code.</div>
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <label for="res_category" class="form-label">Resource Category *</label>
                            <select name="category" id="res_category" class="form-select" required>
                                <option value="Lecture Notes">Lecture Notes</option>
                                <option value="Past Papers">Past Papers</option>
                                <option value="Lab Sheets">Lab Sheets</option>
                                <option value="Summary">Summary Cheatsheet</option>
                            </select>
                        </div>

                        <!-- Title -->
                        <div class="col-12">
                            <label for="res_title" class="form-label">Resource Title *</label>
                            <input type="text" name="title" id="res_title" class="form-control" 
                                   placeholder="e.g. PHP Session Authentication and CSRF Protection Guide" required>
                            <div class="invalid-feedback">Please enter a descriptive title.</div>
                        </div>

                        <!-- Detailed Description / Content -->
                        <div class="col-12">
                            <label for="res_description" class="form-label">Note Description & Detailed Content *</label>
                            <textarea name="description" id="res_description" rows="5" class="form-control" 
                                      placeholder="Provide the core study summary, code examples, concepts, or instructions for this resource..." required></textarea>
                            <div class="invalid-feedback">Please provide note details or content.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-gradient rounded-pill px-4 fw-semibold">
                        <i class="bi bi-cloud-arrow-up me-1"></i> Publish Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
