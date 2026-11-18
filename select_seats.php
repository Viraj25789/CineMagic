<!-- select_seats.php - Responsive Version -->
<?php include 'header.php'; check_csrf();
$show_id = (int)($_GET['show_id'] ?? 0);
$stmt = $mysqli->prepare("
  SELECT s.id as show_id, s.price, s.show_datetime, sc.id as screen_id, sc.name as screen_name, sc.total_rows, sc.total_cols, m.title
  FROM shows s
  JOIN screens sc ON sc.id=s.screen_id
  JOIN movies m ON m.id=s.movie_id
  WHERE s.id=?
");
$stmt->bind_param("i",$show_id); $stmt->execute();
$show = $stmt->get_result()->fetch_assoc();
if(!$show){ echo "<div class='alert alert-danger'>Show not found</div>"; include 'footer.php'; exit; }

// fetch booked seats for this show
$booked = [];
$stmt = $mysqli->prepare("
  SELECT se.seat_row, se.seat_col FROM booking_seats bs
  JOIN seats se ON se.id=bs.seat_id
  WHERE bs.show_id=?
");
$stmt->bind_param("i",$show_id); $stmt->execute();
$res = $stmt->get_result();
while($r = $res->fetch_assoc()){ $booked[$r['seat_row'].$r['seat_col']] = true; }
?>

<style>
    .cinema-container {
        background: #0a0a1a;
        color: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        margin-bottom: 30px;
        overflow: hidden;
    }
    
    .screen-display {
        perspective: 300px;
        margin-bottom: 30px;
        text-align: center;
    }
    
    .screen {
        background: linear-gradient(to bottom, #bdc3c7, #95a5a6);
        height: 60px;
        max-width: 80%;
        margin: 0 auto;
        border-radius: 8px;
        transform: rotateX(45deg);
        box-shadow: 0 10px 30px rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0a0a1a;
        font-weight: bold;
        font-size: 18px;
        letter-spacing: 3px;
    }
    
    .seating-area {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 15px 0;
        width: 100%;
        overflow-x: auto;
    }
    
    .seat-row {
        display: flex;
        gap: 6px;
        align-items: center;
        margin-bottom: 8px;
        width: 100%;
        justify-content: center;
    }
    
    .row-label {
        color: #7f8c8d;
        font-weight: bold;
        width: 25px;
        text-align: center;
        flex-shrink: 0;
    }
    
    .seat {
        width: 32px;
        height: 32px;
        background-color: #2c3e50;
        border-radius: 6px 6px 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ecf0f1;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        flex-shrink: 0;
    }
    
    .seat:not(.booked):hover {
        background-color: #3498db;
        transform: scale(1.05);
    }
    
    .seat.selected {
        background-color: #e74c3c;
    }

    .seat.premium-seat.selected {
    background: linear-gradient(to bottom, #e74c3c, #c0392b) !important;
    }
    
    .seat.booked {
        background-color: #7f8c8d;
        cursor: not-allowed;
    }
    
    .aisle {
        width: 20px;
        flex-shrink: 0;
    }
    
    .section-label {
        text-align: center;
        color: #f1c40f;
        font-weight: bold;
        margin: 15px 0 8px;
        font-size: 14px;
        width: 100%;
    }
    
    .walkway {
        height: 15px;
        width: 80%;
        background: rgba(52, 73, 94, 0.3);
        border-radius: 3px;
        margin: 10px 0;
    }
    
    .seat-info-panel {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
        margin: 15px 0;
    }
    
    .seat-sample {
        display: inline-block;
        width: 16px;
        height: 16px;
        border-radius: 3px;
        vertical-align: middle;
        margin-right: 4px;
    }
    
    .instructions {
        color: #bdc3c7;
        font-size: 12px;
        text-align: center;
        margin-top: 15px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
        .cinema-container {
            padding: 15px;
        }
        
        .screen {
            height: 50px;
            font-size: 16px;
        }
        
        .seat {
            width: 28px;
            height: 28px;
            font-size: 10px;
        }
        
        .aisle {
            width: 15px;
        }
    }
    
    @media (max-width: 768px) {
        .cinema-container {
            padding: 12px;
            border-radius: 10px;
        }
        
        .screen {
            height: 40px;
            font-size: 14px;
            letter-spacing: 2px;
        }
        
        .seat {
            width: 24px;
            height: 24px;
            font-size: 9px;
            border-radius: 4px 4px 0 0;
        }
        
        .row-label {
            width: 20px;
            font-size: 12px;
        }
        
        .section-label {
            font-size: 13px;
        }
        
        .seat-info-panel {
            gap: 8px;
            font-size: 14px;
        }
        
        .seat-sample {
            width: 14px;
            height: 14px;
        }
    }
    
    @media (max-width: 576px) {
        .seating-area {
            padding: 10px 0;
            gap: 8px;
        }
        
        .seat-row {
            gap: 4px;
            margin-bottom: 6px;
        }
        
        .seat {
            width: 22px;
            height: 22px;
            font-size: 8px;
        }
        
        .aisle {
            width: 12px;
        }
        
        .walkway {
            height: 12px;
            margin: 8px 0;
        }
        
        .seat-info-panel {
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }
    }
    
    /* Scrollbar styling for seating area */
    .seating-area::-webkit-scrollbar {
        height: 8px;
    }
    
    .seating-area::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }
    
    .seating-area::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 4px;
    }
    
    .seating-area::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
</style>

<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
    <li class="breadcrumb-item"><a href="movie.php?id=<?= $movie_id ?? ''; ?>">Movie</a></li>
    <li class="breadcrumb-item active">Select Seats</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-8">
    <div class="cinema-container">
      <h4 class="text-center mb-3"><?= e($show['title']); ?></h4>
      <p class="text-center text-muted mb-4">Screen: <?= e($show['screen_name']); ?> • <?= date("D, d M Y h:i A", strtotime($show['show_datetime'])); ?></p>
      
      <div class="screen-display">
        <div class="screen">SCREEN</div>
      </div>
      
      <form method="post" action="checkout.php" id="seatForm">
        <?php csrf_field(); ?>
        <input type="hidden" name="show_id" value="<?= $show_id; ?>">
        
        <div class="seating-area">
          <!-- Premium Section -->
          <div class="section-label">FRONT SEATING</div>
          
          <!-- First two rows as premium -->
          <?php for($r=0; $r<min(2, $show['total_rows']); $r++): 
            $row = chr(65+$r); 
          ?>
            <div class="seat-row">
              <div class="row-label"><?= $row; ?></div>
              <?php for($c=1; $c<=$show['total_cols']; $c++):
                $label = $row.$c;
                $isBooked = isset($booked[$label]);
                
                // Add aisle after the middle column
                $addAisle = ($c == floor($show['total_cols']/2) || $c == floor($show['total_cols']/2)+1);
              ?>
                <?php if($addAisle): ?>
                  <div class="aisle"></div>
                <?php endif; ?>
                
                <label class="seat <?= $isBooked ? 'booked' : 'available'; ?> premium-seat">
                  <input type="checkbox" name="seats[]" value="<?= $label; ?>" class="seat-checkbox d-none" <?= $isBooked ? 'disabled' : ''; ?>>
                  <?= $c; ?>
                </label>
              <?php endfor; ?>
            </div>
          <?php endfor; ?>
          
          <div class="walkway"></div>
          
          <!-- Standard Section -->
          <div class="section-label">STANDARD SEATING</div>
          
          <!-- Remaining rows as standard -->
          <?php for($r=2; $r<$show['total_rows']; $r++): 
            $row = chr(65+$r); 
          ?>
            <div class="seat-row">
              <div class="row-label"><?= $row; ?></div>
              <?php for($c=1; $c<=$show['total_cols']; $c++):
                $label = $row.$c;
                $isBooked = isset($booked[$label]);
                
                // Add aisle after the middle column
                $addAisle = ($c == floor($show['total_cols']/2) || $c == floor($show['total_cols']/2)+1);
              ?>
                <?php if($addAisle): ?>
                  <div class="aisle"></div>
                <?php endif; ?>
                
                <label class="seat <?= $isBooked ? 'booked' : 'available'; ?>">
                  <input type="checkbox" name="seats[]" value="<?= $label; ?>" class="seat-checkbox d-none" <?= $isBooked ? 'disabled' : ''; ?>>
                  <?= $c; ?>
                </label>
              <?php endfor; ?>
            </div>
          <?php endfor; ?>
        </div>
        
        <div class="seat-info-panel">
          <div><span class="seat-sample" style="background-color: #2c3e50;"></span> Available</div>
          <div><span class="seat-sample" style="background-color: #e74c3c;"></span> Selected</div>
          <div><span class="seat-sample" style="background-color: #7f8c8d;"></span> Booked</div>
          <div><span class="seat-sample" style="background-color: #f1c40f;"></span> FRONT</div>
        </div>
        
        <div class="text-center mt-4">
          <button class="btn btn-primary btn-lg" id="proceedBtn" disabled>
            <i class="bi bi-ticket-perforated me-2"></i> Proceed to Checkout
          </button>
        </div>
      </form>
      
      <p class="instructions">
        <i class="bi bi-info-circle"></i> Click on available seats to select them. Aisles provide walking space between sections.
      </p>
    </div>
  </div>
  
  <div class="col-lg-4">
    <div class="card booking-card sticky-top" style="top: 20px;">
      <div class="card-header bg-white">
        <h5 class="mb-0">Booking Summary</h5>
      </div>
      <div class="card-body">
        <h6><?= e($show['title']); ?></h6>
        <p class="text-muted small mb-2"><?= e($show['screen_name']); ?></p>
        <p class="text-muted small"><?= date("D, d M Y h:i A", strtotime($show['show_datetime'])); ?></p>
        
        <hr>
        
        <div id="selectedSeatsInfo">
          <p class="text-center text-muted my-4">No seats selected yet</p>
        </div>
        
        <div class="d-none" id="priceSummary">
          <div class="d-flex justify-content-between mb-1">
            <span>Standard Seats:</span>
            <span id="standardCount">0</span>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <span>FRONT Seats:</span>
            <span id="premiumCount">0</span>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <span>Price per standard seat:</span>
            <span>₹<?= number_format($show['price'], 2); ?></span>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <span>Price per FRONT seat:</span>
            <span>₹<?= number_format($show['price'] * 1.5, 2); ?></span>
          </div>
          <hr>
          <div class="d-flex justify-content-between fw-bold">
            <span>Total:</span>
            <span id="totalAmount">₹0.00</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const checkboxes = document.querySelectorAll('.seat-checkbox:not(:disabled)');
  const proceedBtn = document.getElementById('proceedBtn');
  const selectedSeatsInfo = document.getElementById('selectedSeatsInfo');
  const priceSummary = document.getElementById('priceSummary');
  const standardCount = document.getElementById('standardCount');
  const premiumCount = document.getElementById('premiumCount');
  const totalAmount = document.getElementById('totalAmount');
  const standardPrice = <?= $show['price']; ?>;
  const premiumPrice = <?= $show['price'] * 1.5; ?>;
  
  let selectedSeats = [];
  
  checkboxes.forEach(cb => {
    cb.addEventListener('change', function() {
      const label = this.value;
      const seatElement = this.closest('.seat');
      
      if (this.checked) {
        selectedSeats.push(label);
        seatElement.classList.add('selected');
      } else {
        selectedSeats = selectedSeats.filter(seat => seat !== label);
        seatElement.classList.remove('selected');
      }
      
      // Update UI
      if (selectedSeats.length > 0) {
        proceedBtn.disabled = false;
        selectedSeatsInfo.innerHTML = `<h6>Selected Seats:</h6><p class="fw-bold">${selectedSeats.join(', ')}</p>`;
        priceSummary.classList.remove('d-none');
        
        // Count standard and premium seats
        let standardSeats = 0;
        let premiumSeats = 0;
        
        selectedSeats.forEach(seat => {
          const row = seat.charAt(0);
          if (row === 'A' || row === 'B') {
            premiumSeats++;
          } else {
            standardSeats++;
          }
        });
        
        standardCount.textContent = standardSeats;
        premiumCount.textContent = premiumSeats;
        
        const total = (standardSeats * standardPrice) + (premiumSeats * premiumPrice);
        totalAmount.textContent = '₹' + total.toFixed(2);
      } else {
        proceedBtn.disabled = true;
        selectedSeatsInfo.innerHTML = '<p class="text-center text-muted my-4">No seats selected yet</p>';
        priceSummary.classList.add('d-none');
      }
    });
  });
  
  // Handle seat click for available seats
  document.querySelectorAll('.seat.available').forEach(el => {
    el.addEventListener('click', (e) => {
      e.preventDefault();
      const cb = el.querySelector('input.seat-checkbox');
      if(cb.disabled) return;
      cb.checked = !cb.checked;
      cb.dispatchEvent(new Event('change'));
    });
  });
  
  // Add premium styling to first two rows
document.querySelectorAll('.premium-seat:not(.booked)').forEach(seat => {
    seat.style.background = 'linear-gradient(to bottom, #f1c40f, #f39c12)';
});
});
</script>

<?php include 'footer.php'; ?>