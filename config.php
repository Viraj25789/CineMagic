<?php
// Database config - change as needed
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // change if needed
define('DB_PASS', '');          // change if needed
define('DB_NAME', 'movie_db');

// Initialize the database connection
try {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_errno) {
        throw new Exception("Failed to connect to MySQL: " . $mysqli->connect_error);
    }
    $mysqli->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

session_start();

function is_logged_in() { return isset($_SESSION['user_id']); }
function require_login() { if (!is_logged_in()) { header("Location: login.php"); exit; } }
function is_admin_logged_in() { return isset($_SESSION['admin_id']); }
function require_admin() { if (!is_admin_logged_in()) { header("Location: /admin/admin_login.php"); exit; } }
?>
