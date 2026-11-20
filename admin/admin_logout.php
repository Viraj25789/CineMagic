<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Method not allowed.');
}
check_csrf();
unset($_SESSION['admin_id']);
session_regenerate_id(true);
header('Location: ../index.php');
exit;
