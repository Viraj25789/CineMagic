<?php
// Database settings can be supplied through environment variables. The
// defaults are intended only for a local XAMPP/WAMP-style development setup.
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_NAME', getenv('DB_NAME') ?: 'movie_db');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $mysqli->set_charset('utf8mb4');
} catch (Throwable $e) {
    error_log('CineMagic database connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection unavailable. Check the server configuration and logs.');
}

// Harden session handling before starting the session.
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
session_start();

function is_logged_in() { return isset($_SESSION['user_id']); }
function require_login() { if (!is_logged_in()) { header('Location: login.php'); exit; } }
function is_admin_logged_in() { return isset($_SESSION['admin_id']); }
function require_admin() { if (!is_admin_logged_in()) { header('Location: /admin/admin_login.php'); exit; } }
?>
