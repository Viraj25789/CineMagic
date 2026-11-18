<!-- movie.php - Updated -->
<?php include 'header.php'; check_csrf();
$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT * FROM movies WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
if(!$movie){ echo "<div class='alert alert-danger'>Movie not found</div>"; include 'footer.php'; exit; }
?>

<div class="row">
  <div class="col-md-4 mb-4">
    <div class="card">
      <img src="<?= e($movie['poster_url']); ?>" class="card-img-top" alt="Poster">
    </div>
  </div>
  <div class="col-md-8">
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active"><?= e($movie['title']); ?></li>
      </ol>
    </nav>
    
    <h2 class="fw-bold"><?= e($movie['title']); ?></h2>
    <div class="d-flex flex-wrap gap-2 mb-3">
      <span class="badge bg-secondary"><?= e($movie['language']); ?></span>
      <span class="badge bg-light text-dark"><?= e($movie['genre']); ?></span>
      <span class="badge bg-info"><?= e($movie['duration_mins']); ?> mins</span>
      <span class="badge bg-dark"><?= e($movie['rating']); ?></span>
    </div>

    <h4 class="mt-4 mb-3 border-bottom pb-2">About Movie</h4>

    <p class="lead"><?= nl2br(e($movie['description'])); ?></p>
    
    <h4 class="mt-4 mb-3 border-bottom pb-2">Upcoming Shows</h4>
    
    <?php
    $stmt = $mysqli->prepare("
      SELECT s.id as show_id, s.show_datetime, s.price, sc.name as screen_name
      FROM shows s JOIN screens sc ON sc.id=s.screen_id
      WHERE s.movie_id=? AND s.show_datetime >= NOW()
      ORDER BY s.show_datetime ASC
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $shows = $stmt->get_result();
    if($shows->num_rows==0): ?>
      <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i> No upcoming shows scheduled.
      </div>
    <?php else: ?>
      <div class="list-group">
        <?php while($sh = $shows->fetch_assoc()):
          $dt = date("D, d M Y h:i A", strtotime($sh['show_datetime']));
        ?>
          <a class="list-group-item list-group-item-action showtime-card p-3" href="select_seats.php?show_id=<?= $sh['show_id']; ?>">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="mb-1"><?= e($sh['screen_name']); ?></h5>
                <p class="mb-1 text-muted"><?= e($dt); ?></p>
              </div>
              <div class="text-end">
                <div class="fw-bold fs-5">₹<?= e(number_format($sh['price'],2)); ?></div>
                <small class="text-primary">Select Seats <i class="bi bi-chevron-right"></i></small>
              </div>
            </div>
          </a>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>