<?php require_once __DIR__."/../config.php"; 
require_once __DIR__."/../helpers.php"; check_csrf(); 

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $newpass = $_POST['new_password'];

    // Check if admin exists
    $stmt = $mysqli->prepare("SELECT id FROM admin_users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res) {
        // Update with new password
        $hash = password_hash($newpass, PASSWORD_DEFAULT);
        $update = $mysqli->prepare("UPDATE admin_users SET password_hash=? WHERE email=?");
        $update->bind_param("ss", $hash, $email);
        $update->execute();
        $message = "✅ Password updated successfully.";
    } else {
        $message = "❌ No admin account found with this email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Forgot Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <style>
    body {
      background-color: rgba(255, 250, 246, 1);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .admin-card {
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      border: none;
      border-radius: 12px;
    }
  </style>
</head>
<body>
<div class="row justify-content-center py-5">
  <div class="col-md-5 col-lg-4">
    <div class="card admin-card">
      <div class="card-body p-5">
        <div class="text-center mb-4">
          <i class="bi bi-person-circle display-4 text-primary"></i>
        <h3 class="mt-2 mb-0">Forgot Password</h3>
        <p class="text-muted">Only for Admin</p>  
        </div>

        <?php 
        if($message): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php 
        endif; ?>

        <form method="post">
          <?php csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-4">
              <label class="form-label">New Password</label>
              <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" name="new_password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100 btn-lg">Reset Password</button>
        </form>

        <div class="text-center mt-4">
        <p class="mb-3"><a href="admin_login.php" class="text-decoration-none"> Back to Login </a></p>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>