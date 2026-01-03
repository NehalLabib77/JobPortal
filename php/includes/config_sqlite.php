<?php

/**
 * JobPortal - SQLite Configuration File
 * Use this instead of config_sqlite.php for SQLite database
 */

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration for persistence
ini_set('session.cookie_lifetime', 86400 * 30); // 30 days
ini_set('session.gc_maxlifetime', 86400 * 30); // 30 days
ini_set('session.cookie_secure', false); // Set to true if using HTTPS
ini_set('session.cookie_httponly', true);
ini_set('session.use_only_cookies', true);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration - SQLite
define('DB_TYPE', 'sqlite');
define('DB_PATH', __DIR__ . '/jobportal.db');

// Site Configuration
define('SITE_NAME', 'JobPortal');
define('SITE_URL', 'http://localhost/JobPortal');
define('UPLOAD_PATH', __DIR__ . '/../../uploads/');

// Create database connection (SQLite)
function getDBConnection()
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $pdo = new PDO('sqlite:' . DB_PATH);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            // Enable foreign keys for SQLite
            $pdo->exec('PRAGMA foreign_keys = ON;');
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    return $pdo;
}

// Helper Functions
function redirect($url)
{
    header("Location: $url");
    exit();
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isEmployer()
{
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'employer';
}

function isCandidate()
{
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'candidate';
}

function isAdmin()
{
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateSlug($string)
{
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    return $slug;
}

function formatDate($date)
{
    return date('M d, Y', strtotime($date));
}

function timeAgo($datetime)
{
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    return date('M d, Y', $time);
}

