<?php include "admin_header.php"; require_admin(); check_csrf();
$action = $_GET['action'] ?? '';
if($action==='add' && $_SERVER['REQUEST_METHOD']==='POST'){
  $stmt = $mysqli->prepare("INSERT INTO shows (movie_id, screen_id, show_datetime, price) VALUES (?,?,?,?)");
  $stmt->bind_param("iisd", $_POST['movie_id'], $_POST['screen_id'], $_POST['show_datetime'], $_POST['price']);
  $stmt->execute(); header("Location: shows.php"); exit;
}
if($action==='delete'){
  $id=(int)$_GET['id']; $mysqli->query("DELETE FROM shows WHERE id=$id"); header("Location: shows.php"); exit;
}
$movies = $mysqli->query("SELECT id, title FROM movies ORDER BY title")->fetch_all(MYSQLI_ASSOC);
$screens = $mysqli->query("SELECT id, name FROM screens ORDER BY id")->fetch_all(MYSQLI_ASSOC);
?>
<h3>Shows</h3>
<div class="row">
  <div class="col-md-5">
    <div class="card p-3 admin-card">
      <h5>Create Show</h5>
      <form method="post" action="shows.php?action=add">
        <?php csrf_field(); ?>
        <div class="mb-2">
          <label class="form-label">Movie</label>
          <select name="movie_id" class="form-select" required>
            <?php foreach($movies as $m): ?><option value="<?= $m['id']; ?>"><?= e($m['title']); ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label">Screen</label>
          <select name="screen_id" class="form-select" required>
            <?php foreach($screens as $s): ?><option value="<?= $s['id']; ?>"><?= e($s['name']); ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label">Date & Time</label>
          <input type="datetime-local" name="show_datetime" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Price (₹)</label>
          <input type="number" step="0.01" name="price" class="form-control" value="200">
        </div>
        <button class="btn btn-success">Create</button>
      </form>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card p-3 admin-card">
      <h5>All Shows</h5>
      <table class="table table-bordered table-sm align-middle">
        <thead><tr><th>#</th><th>Movie</th><th>Screen</th><th>DateTime</th><th>Price</th><th></th></tr></thead>
        <tbody>
          <?php
          $res = $mysqli->query("SELECT sh.id, m.title, sc.name as screen_name, sh.show_datetime, sh.price FROM shows sh JOIN movies m ON m.id=sh.movie_id JOIN screens sc ON sc.id=sh.screen_id ORDER BY sh.show_datetime DESC");
          while($row = $res->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id']; ?></td>
              <td><?= e($row['title']); ?></td>
              <td><?= e($row['screen_name']); ?></td>
              <td><?= e($row['show_datetime']); ?></td>
              <td>₹<?= e(number_format($row['price'],2)); ?></td>
              <td><a class="btn btn-danger btn-sm" href="shows.php?action=delete&id=<?= $row['id']; ?>" onclick="return confirm('Delete this show?')">Delete</a></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="mt-3"><a class="btn btn-secondary" href="dashboard.php">Back to Dashboard</a></div>
  </div>
</div>

<?php include "admin_footer.php"; ?>