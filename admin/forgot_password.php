<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';
require_admin();
check_csrf();

$message = '';
$adminId = (int) $_SESSION['admin_id'];

$stmt = $mysqli->prepare("SELECT password_hash FROM admin_users WHERE id=?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

if (!$admin) {
    http_response_code(403);
    exit('Administrator account not found. Please sign in again.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    if (!password_verify($currentPassword, $admin['password_hash'])) {
        $message = 'Current password is incorrect.';
    } elseif (strlen($newPassword) < 12) {
        $message = 'New password must contain at least 12 characters.';
    } else {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $update = $mysqli->prepare("UPDATE admin_users SET password_hash=? WHERE id=?");
        $update->bind_param("si", $hash, $adminId);
        $update->execute();
        session_regenerate_id(true);
        $message = 'Administrator password updated successfully.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Change Admin Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <h1 class="h4 mb-3">Change Admin Password</h1>
          <p class="text-muted">Confirm your current password before setting a new one.</p>
          <?php if ($message): ?>
            <div class="alert alert-info"><?= e($message); ?></div>
          <?php endif; ?>
          <form method="post">
            <?php csrf_field(); ?>
            <div class="mb-3">
              <label class="form-label" for="current_password">Current password</label>
              <input class="form-control" id="current_password" type="password" name="current_password" required autocomplete="current-password">
            </div>
            <div class="mb-3">
              <label class="form-label" for="new_password">New password</label>
              <input class="form-control" id="new_password" type="password" name="new_password" required minlength="12" autocomplete="new-password">
            </div>
            <button class="btn btn-primary w-100">Update Password</button>
          </form>
          <a class="btn btn-link mt-3 px-0" href="dashboard.php">Back to admin dashboard</a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
