<?php
/**
 * Database Connection File (db.php)
 * EduHub - University Study Note & Resource Hub
 * Rajarata University of Sri Lanka | ICT 2209 Web Technologies
 */

// Database configuration settings (Default XAMPP / WAMP credentials)
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'eduhub_db';

try {
    // Attempt PDO connection with UTF-8 character encoding
    $dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    // Check if the error is due to database not existing yet (error code 1049)
    if ($e->getCode() == 1049) {
        die("
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Database Setup Required - EduHub</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body class='bg-light d-flex align-items-center justify-content-center' style='min-height: 100vh;'>
            <div class='card shadow-lg border-0 p-4' style='max-width: 580px; border-radius: 16px;'>
                <div class='card-body text-center'>
                    <div class='display-4 text-warning mb-3'>⚠️</div>
                    <h3 class='fw-bold text-dark'>Database Not Found</h3>
                    <p class='text-muted'>The database <code>eduhub_db</code> has not been imported yet.</p>
                    <div class='alert alert-info text-start small mb-4'>
                        <strong>Quick Setup Instructions:</strong>
                        <ol class='mb-0 mt-2 ps-3'>
                            <li>Open <strong>phpMyAdmin</strong> (<a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a>).</li>
                            <li>Click on <strong>New</strong> and create a database named <code>eduhub_db</code>.</li>
                            <li>Click on <strong>Import</strong>, choose the <code>database.sql</code> file included in this project, and click <strong>Go</strong>.</li>
                        </ol>
                    </div>
                    <a href='index.php' class='btn btn-primary px-4 py-2 fw-semibold'>Reload Page</a>
                </div>
            </div>
        </body>
        </html>
        ");
    } else {
        // Generic database connection error
        die("<div style='font-family: sans-serif; padding: 2rem; background: #fff5f5; color: #c53030; border-left: 4px solid #e53e3e;'>
            <h3>Database Connection Failed</h3>
            <p><strong>Error Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
            <p>Please ensure that MySQL is running in your XAMPP or WAMP control panel.</p>
        </div>");
    }
}
?>
