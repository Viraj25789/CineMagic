<?php include "admin_header.php"; require_admin(); check_csrf();
$action = $_GET['action'] ?? '';
if($action==='add' && $_SERVER['REQUEST_METHOD']==='POST'){
  $rows = max(1, (int)$_POST['rows']); $cols = max(1, (int)$_POST['cols']);
  $stmt = $mysqli->prepare("INSERT INTO screens (name, total_rows, total_cols) VALUES (?,?,?)");
  $stmt->bind_param("sii", $_POST['name'], $rows, $cols);$stmt->execute();
  $screen_id = $stmt->insert_id;
  // Generate seats
  for($r=0; $r<$rows; $r++){
    $rowchar = chr(65+$r);
    for($c=1; $c<=$cols; $c++){
      $stmt2 = $mysqli->prepare("INSERT INTO seats (screen_id, seat_row, seat_col) VALUES (?,?,?)");
      $stmt2->bind_param("isi", $screen_id, $rowchar, $c); $stmt2->execute();
    }
  }
  header("Location: screens.php"); exit;
}
if($action==='delete' && $_SERVER['REQUEST_METHOD']==='POST'){
  $id=(int)($_POST['id'] ?? 0); $mysqli->query("DELETE FROM screens WHERE id=$id"); header("Location: screens.php"); exit;
}
?>
<h3>Screens</h3>
<div class="row">
  <div class="col-md-5">
    <div class="card p-3 admin-card">
      <h5>Add Screen</h5>
      <form method="post" action="screens.php?action=add">
        <?php csrf_field(); ?>
        <div class="mb-2"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
        <div class="mb-2"><label class="form-label">Rows</label><input type="number" name="rows" class="form-control" value="6"></div>
        <div class="mb-2"><label class="form-label">Cols</label><input type="number" name="cols" class="form-control" value="10"></div>
        <button class="btn btn-success">Add</button>
      </form>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card p-3 admin-card">
      <h5>All Screens</h5>
      <table class="table table-bordered table-sm align-middle">
        <thead><tr><th>#</th><th>Name</th><th>Rows</th><th>Cols</th><th>Actions</th></tr></thead>
        <tbody>
          <?php $res=$mysqli->query("SELECT * FROM screens ORDER BY id DESC");
            while($s=$res->fetch_assoc()): ?>
            <tr>
              <td><?= $s['id']; ?></td>
              <td><?= e($s['name']); ?></td>
              <td><?= e($s['total_rows']); ?></td>
              <td><?= e($s['total_cols']); ?></td>
              <td><form method="post" action="screens.php?action=delete" class="d-inline" onsubmit="return confirm('Delete this screen?')"><?php csrf_field(); ?><input type="hidden" name="id" value="<?= $s['id']; ?>"><button class="btn btn-danger btn-sm">Delete</button></form></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="mt-3"><a class="btn btn-secondary" href="dashboard.php">Back to Dashboard</a></div>
  </div>
</div>

<?php include "admin_footer.php"; ?>