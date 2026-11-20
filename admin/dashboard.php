<?php include "admin_header.php"; require_admin(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0">Admin Dashboard</h2>
  <a class="btn btn-outline-primary btn-sm" href="forgot_password.php">Change Password</a>
  
</div>
<div class="row g-4">
  <div class="col-md-4">
    <div class="card text-center h-100">
      <div class="card-body p-4">
        <i class="bi bi-film display-4 text-primary mb-3"></i>
        <h5>Movies</h5>
        <p class="text-muted">Manage movie listings</p>
        <a class="btn btn-primary btn-sm stretched-link" href="movies.php">Manage Movies</a>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-center h-100">
      <div class="card-body p-4">
        <i class="bi bi-easel display-4 text-success mb-3"></i>
        <h5>Screens</h5>
        <p class="text-muted">Manage theater screens</p>
        <a class="btn btn-success btn-sm stretched-link" href="screens.php">Manage Screens</a>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-center h-100">
      <div class="card-body p-4">
        <i class="bi bi-calendar-event display-4 text-info mb-3"></i>
        <h5>Shows</h5>
        <p class="text-muted">Manage show times</p>
        <a class="btn btn-info btn-sm stretched-link" href="shows.php">Manage Shows</a>
      </div>
    </div>
  </div>
</div>

<div class="mt-5">
  <h4 class="mb-3">Recent Bookings</h4>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Movie</th>
              <th>User</th>
              <th>Seats</th>
              <th>Amount</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $res = $mysqli->query("
              SELECT b.id, m.title, u.name, GROUP_CONCAT(se.seat_label) as seats, b.total_amount, b.created_at
              FROM bookings b
              JOIN shows s ON s.id=b.show_id
              JOIN movies m ON m.id=s.movie_id
              JOIN users u ON u.id=b.user_id
              JOIN booking_seats bs ON bs.booking_id=b.id
              JOIN seats se ON se.id=bs.seat_id
              GROUP BY b.id
              ORDER BY b.created_at DESC LIMIT 5
            ");
            while($r = $res->fetch_assoc()): ?>
              <tr>
                <td><?= $r['id']; ?></td>
                <td><?= e($r['title']); ?></td>
                <td><?= e($r['name']); ?></td>
                <td><?= e($r['seats']); ?></td>
                <td>₹<?= number_format($r['total_amount'],2); ?></td>
                <td><?= date("M d, Y", strtotime($r['created_at'])); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include "admin_footer.php"; ?>