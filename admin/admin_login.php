<?php require_once __DIR__."/../config.php"; 
require_once __DIR__."/../helpers.php"; check_csrf(); ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login</title>
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
          <h3 class="mt-2 mb-0">Admin Login</h3>
          <p class="text-muted">Only for Admin</p>  
        </div>

        <?php
        $errors=[];
        if($_SERVER['REQUEST_METHOD']==='POST'){
          $email = trim($_POST['email'] ?? '');
          $pass = $_POST['password'] ?? '';
          $stmt = $mysqli->prepare("SELECT id, password_hash FROM admin_users WHERE email=?");
          $stmt->bind_param("s",$email); $stmt->execute();
          $a = $stmt->get_result()->fetch_assoc();
          if(!$a || !password_verify($pass, $a['password_hash'])){ $errors[]="Invalid credentials"; }
          else { session_regenerate_id(true); $_SESSION['admin_id']=$a['id']; header("Location:dashboard.php"); exit; }
        }
        foreach($errors as $e){ echo "<div class='alert alert-danger'>".htmlspecialchars($e)."</div>"; }
        ?>

        <form method="post">
          <?php csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-4">
              <label class="form-label">Password</label>
              <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" class="form-control" name="password" required>
            </div>
          </div>
          <button class="btn btn-primary w-100 btn-lg">Login</button>
        </form>

        <div class="text-center mt-4">
          <p class="mb-3">Forgot Password?<a href="forgot_password.php" class="text-decoration-none"> Change Here </a></p>
          <p class="mb-0"><a href="../index.php" class="btn btn-secondary w-50 btn-lg"> Home </a></p>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>