<?php include 'header.php'; require_login();
$user_id = $_SESSION['user_id'];
$res = $mysqli->prepare("
  SELECT b.id, b.total_amount, b.status, b.created_at,
         m.title, s.show_datetime
  FROM bookings b
  JOIN shows s ON s.id=b.show_id
  JOIN movies m ON m.id=s.movie_id
  WHERE b.user_id=?
  ORDER BY b.created_at DESC
");
$res->bind_param("i",$user_id);
$res->execute();
$rows = $res->get_result();
?>
<h2>My Bookings</h2>
<?php if($rows->num_rows === 0): ?>
  <div class="alert alert-info">No bookings yet.</div>
<?php else: ?>
  <div class="list-group">
  <?php while($b = $rows->fetch_assoc()): 
    $stmt = $mysqli->prepare("
      SELECT GROUP_CONCAT(se.seat_label ORDER BY se.seat_row, se.seat_col SEPARATOR ', ') as seats
      FROM booking_seats bs JOIN seats se ON bs.seat_id=se.id
      WHERE bs.booking_id=?
    ");
    $stmt->bind_param("i",$b['id']); $stmt->execute();
    $seats = $stmt->get_result()->fetch_assoc()['seats'];
  ?>
    <div class="list-group-item">
      <div class="d-flex justify-content-between">
        <div>
          <strong><?= e($b['title']); ?></strong>
          <div class="small text-muted"><?= date("D, d M Y h:i A", strtotime($b['show_datetime'])); ?></div>
          <div>Seats: <?= e($seats ?: '-'); ?></div>
        </div>
        <div class="text-end">
          <div class="fw-bold">₹<?= e(number_format($b['total_amount'],2)); ?></div>
          <div class="badge bg-success"><?= e($b['status']); ?></div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
  </div>
<?php endif; ?>
<?php include 'footer.php'; ?>
