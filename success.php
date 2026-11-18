<!-- success.php - Updated -->
<?php include 'header.php'; require_login();
$booking_id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("
  SELECT b.id, b.total_amount, m.title, s.show_datetime, sc.name as screen_name
  FROM bookings b
  JOIN shows s ON s.id=b.show_id
  JOIN movies m ON m.id=s.movie_id
  JOIN screens sc ON sc.id=s.screen_id
  WHERE b.id=? AND b.user_id=?
");
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$bk = $stmt->get_result()->fetch_assoc();
if(!$bk){ echo "<div class='alert alert-danger'>Booking not found.</div>"; include 'footer.php'; exit; }

$stmt = $mysqli->prepare("
  SELECT GROUP_CONCAT(se.seat_label ORDER BY se.seat_row, se.seat_col SEPARATOR ', ') as seats
  FROM booking_seats bs JOIN seats se ON bs.seat_id=se.id
  WHERE bs.booking_id=?
");
$stmt->bind_param("i",$booking_id); $stmt->execute();
$seats = $stmt->get_result()->fetch_assoc()['seats'];
?>

<div class="text-center py-4">
  <div class="display-1 text-success mb-3"><i class="bi bi-check-circle-fill"></i></div>
  <h1 class="display-6 fw-bold">Booking Confirmed!</h1>
  <p class="lead text-muted">Thank you for your booking. Your tickets are confirmed.</p>
</div>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card booking-card">
      <div class="card-header bg-white text-center">
        <h4 class="mb-0">E-Ticket</h4>
        <small class="text-muted">Booking #<?= $bk['id']; ?></small>
      </div>
      <div class="card-body">
        <div class="text-center mb-4">
          <div class="bg-light rounded p-3">
            <h4 class="text-uppercase fw-bold"><?= e($bk['title']); ?></h4>
            <div class="text-muted"><?= e($bk['screen_name']); ?></div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-6">
            <div class="mb-3">
              <small class="text-muted d-block">Date & Time</small>
              <strong><?= date("D, d M Y", strtotime($bk['show_datetime'])); ?></strong><br>
              <?= date("h:i A", strtotime($bk['show_datetime'])); ?>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <small class="text-muted d-block">Seats</small>
              <strong><?= e($seats); ?></strong>
            </div>
          </div>
        </div>
        
        <div class="border-top pt-3 text-center">
          <div class="d-flex justify-content-between mb-2">
            <span>Total Paid:</span>
            <strong class="text-success">₹<?= number_format($bk['total_amount'],2); ?></strong>
          </div>
          <small class="text-muted">Payment Method: Mock Payment</small>
        </div>
        
        <div class="alert alert-info mt-4 text-center">
          <i class="bi bi-info-circle me-2"></i> Please show this confirmation at the theater entrance.
        </div>
      </div>
    </div>
    
    <div class="d-grid gap-2 d-md-flex justify-content-center mt-4">
      <a href="index.php" class="btn btn-primary">
        <i class="bi bi-house me-2"></i> Back to Home
      </a>
      <a href="my_bookings.php" class="btn btn-outline-secondary">
        <i class="bi bi-ticket-perforated me-2"></i> View All Bookings
      </a>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>