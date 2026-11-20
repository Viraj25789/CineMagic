<!-- login.php - Updated -->
<?php include 'header.php'; check_csrf();
$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  $stmt = $mysqli->prepare("SELECT id, name, password_hash FROM users WHERE email=?");
  $stmt->bind_param("s",$email);
  $stmt->execute();
  $user = $stmt->get_result()->fetch_assoc();
  if(!$user || !password_verify($pass, $user['password_hash'])){
    $errors[]="Invalid credentials";
  } else {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    header("Location: index.php"); exit;
  }
}
?>
<div class="row justify-content-center py-5">
  <div class="col-md-5 col-lg-4">
    <div class="card booking-card">
      <div class="card-body p-5">
        <div class="text-center mb-4">
          <i class="bi bi-person-circle display-4 text-primary"></i>
          <h2 class="mt-2 mb-0">Login</h2>
          <p class="text-muted">Welcome back to CineMagic</p>  
        </div>
        
        <?php foreach($errors as $e): ?>
          <div class="alert alert-danger"><?= e($e); ?></div>
        <?php endforeach; ?>
        
        <form method="post">
          <?php csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" class="form-control" name="email" required>
            </div>
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
          <p class="mb-0">No account? <a href="register.php" class="text-decoration-none">Register here</a></p>
        </div>
        
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>