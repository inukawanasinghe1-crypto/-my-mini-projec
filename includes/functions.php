<?php
/**
 * Helper Functions (functions.php)
 * EduHub - University Study Note & Resource Hub
 * Rajarata University of Sri Lanka | ICT 2209 Web Technologies
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Sanitize text inputs against XSS and malicious characters
 * @param string $data
 * @return string
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Check if a user is currently logged in
 * @return bool
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get the current user's ID
 * @return int|null
 */
function current_user_id() {
    return is_logged_in() ? (int)$_SESSION['user_id'] : null;
}

/**
 * Get the current user's username
 * @return string
 */
function current_username() {
    return is_logged_in() ? $_SESSION['username'] : 'Guest';
}

/**
 * Restrict page access to logged-in users only
 * @param string $redirect_to
 */
function require_login($redirect_to = 'auth/login.php') {
    if (!is_logged_in()) {
        set_flash('warning', 'Please sign in to access this page.');
        header("Location: {$redirect_to}");
        exit;
    }
}

/**
 * Set a flash message for display on the next page view
 * @param string $type ('success', 'danger', 'warning', 'info')
 * @param string $message
 */
function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Retrieve and clear the current flash message
 * @return array|null
 */
function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Render flash message as a Bootstrap alert component
 */
function display_flash() {
    $flash = get_flash();
    if ($flash) {
        $type = htmlspecialchars($flash['type']);
        $icon = 'bi-info-circle-fill';
        if ($type === 'success') $icon = 'bi-check-circle-fill';
        if ($type === 'danger') $icon = 'bi-exclamation-triangle-fill';
        if ($type === 'warning') $icon = 'bi-exclamation-circle-fill';

        echo "<div class='alert alert-{$type} alert-dismissible fade show shadow-sm border-0 d-flex align-items-center mb-4' role='alert'>
                <i class='bi {$icon} me-2 fs-5'></i>
                <div class='flex-grow-1'>{$flash['message']}</div>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}

/**
 * Format timestamp into readable format
 * @param string $timestamp
 * @return string
 */
function format_date($timestamp) {
    $time = strtotime($timestamp);
    return date('M j, Y', $time);
}

/**
 * Generate human-friendly time elapsed string (e.g., '2 hours ago')
 * @param string $datetime
 * @return string
 */
function time_ago($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M j, Y', $timestamp);
    }
}
?>
