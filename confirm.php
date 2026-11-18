<?php include 'header.php'; require_login(); check_csrf();
if($_SERVER['REQUEST_METHOD'] !== 'POST'){ header("Location: index.php"); exit; }
$user_id = $_SESSION['user_id'];
$show_id = (int)($_POST['show_id'] ?? 0);
$seat_ids = $_POST['seat_ids'] ?? [];
if(!$show_id || !is_array($seat_ids) || count($seat_ids)==0){ echo "<div class='alert alert-danger'>Invalid request.</div>"; include 'footer.php'; exit; }

// Get price
$stmt = $mysqli->prepare("SELECT price FROM shows WHERE id=?");
$stmt->bind_param("i",$show_id); $stmt->execute();
$show = $stmt->get_result()->fetch_assoc();
if(!$show){ echo "<div class='alert alert-danger'>Show not found.</div>"; include 'footer.php'; exit; }
$price = (float)$show['price'];
$total = $price * count($seat_ids);

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
  if($mysqli->errno == 1062){
    echo "<div class='alert alert-danger'>One or more seats were just booked by someone else. Please pick different seats.</div><a href='select_seats.php?show_id=".$show_id."' class='btn btn-secondary mt-3'>Back</a>";
  } else {
    echo "<div class='alert alert-danger'>Failed to confirm booking. ".$mysqli->error."</div>";
  }
}
include 'footer.php';
?>
