<?php include 'header.php'; require_login(); check_csrf();
if($_SERVER['REQUEST_METHOD'] !== 'POST'){ header("Location: index.php"); exit; }
$user_id = $_SESSION['user_id'];
$show_id = (int)($_POST['show_id'] ?? 0);
$seat_ids = $_POST['seat_ids'] ?? [];
if(!$show_id || !is_array($seat_ids) || count($seat_ids)==0){ echo "<div class='alert alert-danger'>Invalid request.</div>"; include 'footer.php'; exit; }

// Get price
$stmt = $mysqli->prepare("SELECT price, screen_id FROM shows WHERE id=?");
$stmt->bind_param("i",$show_id); $stmt->execute();
$show = $stmt->get_result()->fetch_assoc();
if(!$show){ echo "<div class='alert alert-danger'>Show not found.</div>"; include 'footer.php'; exit; }
$price = (float)$show['price'];

// Validate the submitted seat IDs against this show's screen. Never trust
// hidden form fields: a modified request must not reserve seats in another screen.
$validSeatInput = true;
foreach ($seat_ids as $submittedSeatId) {
  if (!is_scalar($submittedSeatId) || !ctype_digit((string)$submittedSeatId) || (int)$submittedSeatId < 1) {
    $validSeatInput = false;
    break;
  }
}
if (!$validSeatInput) {
  http_response_code(400);
  exit('Invalid seat selection.');
}
$seatIds = array_map('intval', $seat_ids);
if (count($seatIds) !== count(array_unique($seatIds)) || in_array(0, $seatIds, true)) {
  http_response_code(400);
  exit('Invalid seat selection.');
}
$seatCheck = $mysqli->prepare("SELECT seat_row FROM seats WHERE id=? AND screen_id=?");
$total = 0.0;
foreach ($seatIds as $seatId) {
  $screenId = (int)$show['screen_id'];
  $seatCheck->bind_param("ii", $seatId, $screenId);
  $seatCheck->execute();
  $seat = $seatCheck->get_result()->fetch_assoc();
  if (!$seat) {
    http_response_code(400);
    exit('One or more selected seats are invalid for this show.');
  }
  $isFrontRow = in_array($seat['seat_row'], ['A', 'B'], true);
  $total += $price * ($isFrontRow ? 1.5 : 1.0);
}
$seat_ids = $seatIds;

// Transaction: create booking and reserve seats. If any seat already taken, rollback.
$mysqli->begin_transaction();
try {
  $stmt = $mysqli->prepare("INSERT INTO bookings (user_id, show_id, total_amount) VALUES (?,?,?)");
  $stmt->bind_param("iid", $user_id, $show_id, $total);
  $stmt->execute();
  $booking_id = $stmt->insert_id;

  // reserve seats
  $stmt = $mysqli->prepare("INSERT INTO booking_seats (booking_id, show_id, seat_id) VALUES (?,?,?)");
  foreach($seat_ids as $sid){
    $sid = (int)$sid;
    $stmt->bind_param("iii", $booking_id, $show_id, $sid);
    $stmt->execute();
  }

  // mock payment
  $stmt = $mysqli->prepare("INSERT INTO payments (booking_id, amount, method, status) VALUES (?,?, 'MOCK', 'SUCCESS')");
  $stmt->bind_param("id", $booking_id, $total);
  $stmt->execute();

  $mysqli->commit();

  header("Location: success.php?id=".$booking_id);
  exit;

} catch (Throwable $e) {
  $mysqli->rollback();
  error_log('CineMagic booking confirmation failed: ' . $e->getMessage());
  if($e instanceof mysqli_sql_exception && (int)$e->getCode() === 1062){
    echo "<div class='alert alert-danger'>One or more seats were just booked by someone else. Please pick different seats.</div><a href='select_seats.php?show_id=".$show_id."' class='btn btn-secondary mt-3'>Back</a>";
  } else {
    echo "<div class='alert alert-danger'>Booking could not be confirmed. Please try again.</div>";
  }
}
include 'footer.php';
?>
