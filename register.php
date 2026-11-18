<?php include 'header.php'; check_csrf();
$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!hash_equals($_POST['csrf'] ?? '', $_SESSION['csrf'] ?? '')){ $errors[]="Invalid CSRF"; }
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  if($name==='') $errors[]="Name is required";
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[]="Valid email required";
  if(strlen($pass) < 6) $errors[]="Password must be at least 6 chars";
  if(!$errors){
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    if($stmt->get_result()->num_rows){ $errors[]="Email already exists"; }
    else{
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $stmt = $mysqli->prepare("INSERT INTO users (name,email,password_hash) VALUES (?,?,?)");
      $stmt->bind_param("sss",$name,$email,$hash);
      $stmt->execute();
      $_SESSION['user_id'] = $stmt->insert_id;
      $_SESSION['user_name'] = $name;
      header("Location: index.php"); exit;
    }
  }
}
?>
<div class="row justify-content-center py-5">
  <div class="col-md-5 col-lg-4">
    <div class="card booking-card">
      <div class="card-body p-5">
        <div class="text-center mb-4">
          <i class="bi bi-person-circle display-4 text-primary"></i>
          <h2 class="mt-2 mb-0">Create account</h2>
          <p class="text-muted">Welcome to CineMagic</p>
        </div>

        <?php foreach($errors as $e): ?>
          <div class="alert alert-danger"><?= e($e); ?></div>
        <?php endforeach; ?>

        <form method="post">
          <?php csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label">Name</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
              <input class="form-control" name="name" required>
            </div>
          </div>
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
              <input type="password" class="form-control" name="password" required minlength="6">
            </div>
          </div>
          <button class="btn btn-primary w-100 btn-lg">Register</button>
        </form>

        <div class="text-center mt-4">
          <p class="mb-0">Already have an account? <a href="login.php" class="text-decoration-none">Login</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
