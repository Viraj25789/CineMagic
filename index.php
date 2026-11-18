<?php include 'header.php'; check_csrf(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h2 fw-bold">Now Showing</h1>
  <?php
  // Fixed: Use the correct variable name and check if it exists
  if(isset($res) && is_object($res)): 
  ?>
    <div class="d-none d-md-block">
      <span class="badge bg-primary"><?= $res->num_rows ?> Movies</span>
    </div>
  <?php endif; ?>
</div>

<div class="row g-4">
<?php
// Execute query and store result
$res = $mysqli->query("SELECT id, title, description, poster_url, language, genre, duration_mins, rating FROM movies ORDER BY created_at DESC");

// Check if query was successful
if($res && $res->num_rows > 0):
  while ($m = $res->fetch_assoc()): ?>
  <div class="col-12 col-md-6 col-lg-4 col-xl-3">
    <div class="card movie-card h-100">
      <a href="movie.php?id=<?= $m['id']; ?>" class="text-decoration-none text-dark">
      <div class="position-relative overflow-hidden">
        <img src="<?= e($m['poster_url'] ?: 'https://picsum.photos/seed/'.e($m['id']).'/400/600'); ?>" class="card-img-top movie-poster" alt="<?= e($m['title']); ?>">
        <div class="position-absolute top-0 end-0 m-2">
          <span class="badge bg-dark"><?= e($m['rating']); ?></span>
        </div>
      </div>
      <div class="card-body d-flex flex-column">
        <h5 class="card-title"><?= e($m['title']); ?></h5>
        <div class="d-flex flex-wrap gap-1 mb-2">
          <span class="badge bg-secondary"><?= e($m['language']); ?></span>
          <span class="badge bg-light text-dark"><?= e($m['genre']); ?></span>
          <span class="badge bg-info"><?= e($m['duration_mins']); ?> mins</span>
        </div>
      </div>
      </a>
    </div>
  </div>
  <?php endwhile; ?>
</div>

<?php else: ?>
<div class="text-center py-5 my-5">
  <i class="bi bi-film display-1 text-muted"></i>
  <h3 class="text-muted mt-3">No Movies Available</h3>
  <p class="text-muted">Check back later for new releases.</p>
</div>
<?php endif; ?>
<?php include 'footer.php'; ?>