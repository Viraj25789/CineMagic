<?php 
include "admin_header.php"; 
require_admin(); 
check_csrf();

$action = $_GET['action'] ?? '';

// Process actions first before any output
if($action==='add' && $_SERVER['REQUEST_METHOD']==='POST'){
  $stmt = $mysqli->prepare("INSERT INTO movies (title,description,language,genre,duration_mins,rating,poster_url) VALUES (?,?,?,?,?,?,?)");
  $stmt->bind_param("ssssiss", $_POST['title'], $_POST['description'], $_POST['language'], $_POST['genre'], $_POST['duration_mins'], $_POST['rating'], $_POST['poster_url']);
  if($stmt->execute()){
    header("Location: movies.php");
    exit;
  }
}

if($action==='delete'){
  $id=(int)$_GET['id']; 
  $mysqli->query("DELETE FROM movies WHERE id=$id"); 
  header("Location: movies.php"); 
  exit;
}
?>

<h3>Movies</h3>
<div class="row">
  <div class="col-md-5">
    <div class="card p-3 admin-card">
      <h5>Add Movie</h5>
      <form method="post" action="movies.php?action=add">
        <?php csrf_field(); ?>
        <div class="mb-2"><label class="form-label">Title</label><input name="title" class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
        <div class="mb-2"><label class="form-label">Language</label><input name="language" class="form-control" value="English"></div>
        <div class="mb-2"><label class="form-label">Genre</label><input name="genre" class="form-control" value="Drama"></div>
        <div class="mb-2"><label class="form-label">Duration (mins)</label><input type="number" name="duration_mins" class="form-control" value="120"></div>
        <div class="mb-2"><label class="form-label">Rating</label><input name="rating" class="form-control" value="UA"></div>
        <div class="mb-2"><label class="form-label">Poster URL</label><input name="poster_url" class="form-control" placeholder="https://..."></div>
        <button class="btn btn-success">Add</button>
      </form>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card p-3 admin-card">
      <h5>All Movies</h5>
      <table class="table table-bordered table-sm align-middle">
        <thead><tr><th>#</th><th>Title</th><th>Lang</th><th>Genre</th><th>Actions</th></tr></thead>
        <tbody>
          <?php $res=$mysqli->query("SELECT * FROM movies ORDER BY created_at DESC");
            while($m=$res->fetch_assoc()): ?>
            <tr>
              <td><?= $m['id']; ?></td>
              <td><?= e($m['title']); ?></td>
              <td><?= e($m['language']); ?></td>
              <td><?= e($m['genre']); ?></td>
              <td><a class="btn btn-danger btn-sm" href="movies.php?action=delete&id=<?= $m['id']; ?>" onclick="return confirm('Delete this movie?')">Delete</a></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="mt-3"><a class="btn btn-secondary" href="dashboard.php">Back to Dashboard</a></div>
  </div>
</div>
<?php include "admin_footer.php"; ?>