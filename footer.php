</div> <!-- closing container div -->
<footer class="bg-dark text-white py-5 mt-5">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4 mb-md-0">
        <h5 class="fw-bold mb-3"><i class="bi bi-camera-reels me-2"></i> CineMagic</h5>
        <p>Your ultimate movie experience with comfortable seats, premium sound, and the latest blockbusters.</p> <!-- class="text-muted" -->
      </div>
      <div class="col-md-2 mb-4 mb-md-0">
        <h6 class="fw-bold mb-3">Quick Links</h6>
        <ul class="list-unstyled">
          <li><a href="index.php" class="text-decoration-none ">Home</a></li><!-- class="text-muted" -->
          <li><a href="my_bookings.php" class="text-decoration-none ">My Bookings</a></li><!-- class="text-muted" -->
          <li><a href="contact.php" class="text-decoration-none ">Contact Us</a></li><!-- class="text-muted" -->
        </ul>
      </div>
      <div class="col-md-3 mb-4 mb-md-0">
        <h6 class="fw-bold mb-3">Contact Info</h6>
        <ul class="list-unstyled "><!-- class="text-muted" -->
          <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> 123 Cinema Street, Movie City</li>
          <li class="mb-2"><i class="bi bi-telephone me-2"></i> (123) 456-7890</li>
          <li><i class="bi bi-envelope me-2"></i> info@cinemagic.com</li>
        </ul>
      </div>
      <div class="col-md-3">
        <h6 class="fw-bold mb-3">Follow Us</h6>
        <div class="d-flex gap-3">
          <a href="#" class="fs-5"><i class="bi bi-facebook"></i></a><!-- class="text-muted" -->
          <a href="#" class="fs-5"><i class="bi bi-twitter-x"></i></a><!-- class="text-muted" -->
          <a href="#" class="fs-5"><i class="bi bi-instagram"></i></a><!-- class="text-muted" -->
          <a href="#" class="fs-5"><i class="bi bi-youtube"></i></a><!-- class="text-muted" -->
        </div>
      </div>
      <div class="text-center "><!-- class="text-muted" -->
      <small>&copy; <?= date('Y'); ?> @CineMagic. All rights reserved.</small>
      </div>
    </div>
  </div>
  
</footer>
<?php 
// Flush the output buffer
ob_end_flush();
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>