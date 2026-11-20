<!-- checkout.php - Updated -->
<?php include 'header.php'; require_login(); check_csrf();
if($_SERVER['REQUEST_METHOD'] !== 'POST'){ header("Location: index.php"); exit; }

$show_id = (int)($_POST['show_id'] ?? 0);
$seats_labels = $_POST['seats'] ?? [];
if(!$show_id || !is_array($seats_labels) || count($seats_labels)==0){
  echo "<div class='alert alert-warning'>Please select at least one seat.</div><a href='index.php' class='btn btn-secondary mt-3'>Back</a>";
  include 'footer.php'; exit;
}

$stmt = $mysqli->prepare("SELECT s.id as show_id, s.price, s.show_datetime, sc.id as screen_id, m.title FROM shows s JOIN screens sc ON sc.id=s.screen_id JOIN movies m ON m.id=s.movie_id WHERE s.id=?");
$stmt->bind_param("i",$show_id); $stmt->execute();
$show = $stmt->get_result()->fetch_assoc();
if(!$show){ echo "<div class='alert alert-danger'>Invalid show.</div>"; include 'footer.php'; exit; }

// Map seat labels to seat IDs safely (one query per seat - simple and reliable)
$seat_ids = [];
$stmtSeat = $mysqli->prepare("SELECT id FROM seats WHERE seat_label=? AND screen_id=?");
foreach($seats_labels as $lbl){
  $label = strtoupper(trim($lbl));
  $stmtSeat->bind_param("si", $label, $show['screen_id']);
  $stmtSeat->execute();
  $resSeat = $stmtSeat->get_result();
  if($row = $resSeat->fetch_assoc()){
    $seat_ids[] = (int)$row['id'];
  }
}

if(count($seat_ids) == 0 || count($seat_ids) !== count($seats_labels) || count($seat_ids) !== count(array_unique($seat_ids))){
  http_response_code(400);
  echo "<div class='alert alert-danger'>One or more selected seats are invalid or duplicated.</div>"; include 'footer.php'; exit;
}

$qty = count($seat_ids);
$price = (float)$show['price'];
$premium_qty = count(array_filter($seats_labels, function($label) {
  return in_array(strtoupper(substr(trim($label), 0, 1)), ['A', 'B'], true);
}));
$standard_qty = $qty - $premium_qty;
$amount = ($standard_qty * $price) + ($premium_qty * $price * 1.5);
?>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card booking-card">
      <div class="card-header bg-white">
        <h3 class="mb-0"><i class="bi bi-cart-check me-2"></i> Checkout</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <h5>Booking Details</h5>
            <div class="mb-3">
              <strong>Movie:</strong><br>
              <?= e($show['title']); ?>
            </div>
            <div class="mb-3">
              <strong>Showtime:</strong><br>
              <?= date("D, d M Y h:i A", strtotime($show['show_datetime'])); ?>
            </div>
            <div class="mb-3">
              <strong>Seats:</strong><br>
              <?= e(implode(', ', $seats_labels)); ?>
            </div>
          </div>
          <div class="col-md-6">
            <h5>Payment Summary</h5>
            <div class="border rounded p-3">
              <div class="d-flex justify-content-between mb-2">
                <span>Standard seats (<?= $standard_qty; ?>):</span>
                <span>₹<?= number_format($standard_qty * $price,2); ?></span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span>Front seats (<?= $premium_qty; ?>):</span>
                <span>₹<?= number_format($premium_qty * $price * 1.5,2); ?></span>
              </div>
              <hr>
              <div class="d-flex justify-content-between fw-bold fs-5">
                <span>Total Amount:</span>
                <span class="text-primary">₹<?= number_format($amount,2); ?></span>
              </div>
            </div>
          </div>
        </div>
        
        <form method="post" action="confirm.php" class="mt-4">
          <?php csrf_field(); ?>
          <input type="hidden" name="show_id" value="<?= $show_id; ?>">
          <?php foreach($seat_ids as $sid): ?>
            <input type="hidden" name="seat_ids[]" value="<?= $sid; ?>">
          <?php endforeach; ?>
          
          <div class="d-grid gap-2 d-md-flex">
            <button class="btn btn-success btn-lg flex-fill">
              <i class="bi bi-credit-card me-2"></i> Pay & Confirm (Mock)
            </button>
            <a class="btn btn-outline-secondary" href="select_seats.php?show_id=<?= $show_id; ?>">
              <i class="bi bi-arrow-left me-2"></i> Back
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>