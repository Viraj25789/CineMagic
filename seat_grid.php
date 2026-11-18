<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Seat Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #0a0a1a;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container {
            max-width: 1000px;
        }
        
        .screen-container {
            perspective: 1000px;
            margin-bottom: 40px;
        }
        
        .screen {
            background: linear-gradient(to bottom, #bdc3c7, #95a5a6);
            height: 80px;
            width: 80%;
            margin: 0 auto;
            border-radius: 8px;
            transform: rotateX(45deg);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0a0a1a;
            font-weight: bold;
            font-size: 24px;
            letter-spacing: 4px;
        }
        
        .cinema-layout {
            background: rgba(30, 30, 60, 0.8);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        
        .seating-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        
        .row {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .row-label {
            color: #7f8c8d;
            font-weight: bold;
            width: 25px;
            text-align: center;
        }
        
        .seat {
            width: 35px;
            height: 35px;
            background-color: #2c3e50;
            border-radius: 8px 8px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ecf0f1;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .seat:not(.occupied):hover {
            background-color: #3498db;
            transform: scale(1.1);
        }
        
        .seat.selected {
            background-color: #e74c3c;
        }
        
        .seat.occupied {
            background-color: #7f8c8d;
            cursor: not-allowed;
        }
        
        .seat.vip {
            background: linear-gradient(to bottom, #f1b50fff, #fccf8dff);
        }
        
        /* Fix for premium seats when selected */
        .seat.vip.selected {
            background: linear-gradient(to bottom, #e74c3c, #c0392b);
        }
        
        .seat.couple {
            width: 75px;
            background: linear-gradient(to right, #e74c3c, #c0392b);
        }
        
        .aisle {
            width: 40px;
        }
        
        .walkway {
            height: 30px;
            width: 100%;
            background: rgba(52, 73, 94, 0.3);
            border-radius: 4px;
            margin: 15px 0;
        }
        
        .legroom {
            width: 100%;
            height: 20px;
        }
        
        .info-panel {
            background: rgba(30, 30, 60, 0.8);
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .seat-info {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .seat-sample {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 4px;
            vertical-align: middle;
            margin-right: 5px;
        }
        
        .summary {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            padding: 15px;
        }
        
        .instructions {
            color: #bdc3c7;
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
        }
        
        .section-label {
            text-align: center;
            color: #f1c40f;
            font-weight: bold;
            margin: 10px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-4">Cinema Seat Selection</h1>
        
        <div class="screen-container">
            <div class="screen">SCREEN</div>
        </div>
        
        <div class="cinema-layout">
            <p class="text-center mb-4">Click on available seats to select them</p>
            
            <div class="seating-area">
                <!-- Premium Section -->
                <div class="section-label">FRONT SEATING</div>
                
                <!-- Row A (VIP) -->
                <div class="row">
                    <div class="row-label">A</div>
                    <div class="seat vip" data-seat="A1">1</div>
                    <div class="seat vip" data-seat="A2">2</div>
                    <div class="seat vip" data-seat="A3">3</div>
                    <div class="aisle"></div>
                    <div class="seat vip" data-seat="A4">4</div>
                    <div class="seat vip" data-seat="A5">5</div>
                    <div class="seat vip" data-seat="A6">6</div>
                    <div class="aisle"></div>
                    <div class="seat vip" data-seat="A7">7</div>
                    <div class="seat vip" data-seat="A8">8</div>
                    <div class="seat vip" data-seat="A9">9</div>
                </div>
                
                <!-- Row B (VIP) -->
                <div class="row">
                    <div class="row-label">B</div>
                    <div class="seat vip" data-seat="B1">1</div>
                    <div class="seat vip" data-seat="B2">2</div>
                    <div class="seat vip" data-seat="B3">3</div>
                    <div class="aisle"></div>
                    <div class="seat vip" data-seat="B4">4</div>
                    <div class="seat vip" data-seat="B5">5</div>
                    <div class="seat vip" data-seat="B6">6</div>
                    <div class="aisle"></div>
                    <div class="seat vip" data-seat="B7">7</div>
                    <div class="seat vip" data-seat="B8">8</div>
                    <div class="seat vip" data-seat="B9">9</div>
                </div>
                
                <div class="walkway"></div>
                
                <!-- Standard Section -->
                <div class="section-label">STANDARD SEATING</div>
                
                <!-- Row C -->
                <div class="row">
                    <div class="row-label">C</div>
                    <div class="seat" data-seat="C1">1</div>
                    <div class="seat" data-seat="C2">2</div>
                    <div class="seat" data-seat="C3">3</div>
                    <div class="seat" data-seat="C4">4</div>
                    <div class="aisle"></div>
                    <div class="seat" data-seat="C5">5</div>
                    <div class="seat" data-seat="C6">6</div>
                    <div class="seat" data-seat="C7">7</div>
                    <div class="seat" data-seat="C8">8</div>
                    <div class="aisle"></div>
                    <div class="seat" data-seat="C9">9</div>
                    <div class="seat" data-seat="C10">10</div>
                    <div class="seat" data-seat="C11">11</div>
                    <div class="seat" data-seat="C12">12</div>
                </div>
                
                <!-- Row D -->
                <div class="row">
                    <div class="row-label">D</div>
                    <div class="seat" data-seat="D1">1</div>
                    <div class="seat" data-seat="D2">2</div>
                    <div class="seat" data-seat="D3">3</div>
                    <div class="seat" data-seat="D4">4</div>
                    <div class="aisle"></div>
                    <div class="seat" data-seat="D5">5</div>
                    <div class="seat" data-seat="D6">6</div>
                    <div class="seat" data-seat="D7">7</div>
                    <div class="seat" data-seat="D8">8</div>
                    <div class="aisle"></div>
                    <div class="seat" data-seat="D9">9</div>
                    <div class="seat" data-seat="D10">10</div>
                    <div class="seat" data-seat="D11">11</div>
                    <div class="seat" data-seat="D12">12</div>
                </div>
                
                <!-- Row E -->
                <div class="row">
                    <div class="row-label">E</div>
                    <div class="seat" data-seat="E1">1</div>
                    <div class="seat" data-seat="E2">2</div>
                    <div class="seat" data-seat="E3">3</div>
                    <div class="seat" data-seat="E4">4</div>
                    <div class="aisle"></div>
                    <div class="seat occupied" data-seat="E5">5</div>
                    <div class="seat occupied" data-seat="E6">6</div>
                    <div class="seat" data-seat="E7">7</div>
                    <div class="seat" data-seat="E8">8</div>
                    <div class="aisle"></div>
                    <div class="seat" data-seat="E9">9</div>
                    <div class="seat" data-seat="E10">10</div>
                    <div class="seat" data-seat="E11">11</div>
                    <div class="seat" data-seat="E12">12</div>
                </div>
                
                <div class="walkway"></div>
                
                <!-- Row F -->
                <div class="row">
                    <div class="row-label">F</div>
                    <div class="seat" data-seat="F1">1</div>
                    <div class="seat" data-seat="F2">2</div>
                    <div class="seat" data-seat="F3">3</div>
                    <div class="seat" data-seat="F4">4</div>
                    <div class="aisle"></div>
                    <div class="seat" data-seat="F5">5</div>
                    <div class="seat" data-seat="F6">6</div>
                    <div class="seat" data-seat="F7">7</div>
                    <div class="seat" data-seat="F8">8</div>
                    <div class="aisle"></div>
                    <div class="seat" data-seat="F9">9</div>
                    <div class="seat" data-seat="F10">10</div>
                    <div class="seat" data-seat="F11">11</div>
                    <div class="seat" data-seat="F12">12</div>
                </div>
                
                <!-- Row G -->
                <div class="row">
                    <div class="row-label">G</div>
                    <div class="seat" data-seat="G1">1</div>
                    <div class="seat" data-seat="G2">2</div>
                    <div class="seat" data-seat="G3">3</div>
                    <div class="seat" data-seat="G4">4</div>
                    <div class="aisle"></div>
                    <div class="seat occupied" data-seat="G5">5</div>
                    <div class="seat occupied" data-seat="G6">6</div>
                    <div class="seat occupied" data-seat="G7">7</div>
                    <div class="seat" data-seat="G8">8</div>
                    <div class="aisle"></div>
                    <div class="seat" data-seat="G9">9</div>
                    <div class="seat" data-seat="G10">10</div>
                    <div class="seat" data-seat="G11">11</div>
                    <div class="seat" data-seat="G12">12</div>
                </div>
                
                <!-- Couple Seats Section -->
                <div class="walkway"></div>
                <div class="section-label">COUPLE SEATS</div>
                
                <!-- Row H (Couple Seats) -->
                <div class="row">
                    <div class="row-label">H</div>
                    <div class="seat couple" data-seat="H1">C1</div>
                    <div class="seat couple" data-seat="H2">C2</div>
                    <div class="aisle"></div>
                    <div class="seat couple" data-seat="H3">C3</div>
                    <div class="seat couple" data-seat="H4">C4</div>
                    <div class="aisle"></div>
                    <div class="seat couple" data-seat="H5">C5</div>
                    <div class="seat couple occupied" data-seat="H6">C6</div>
                </div>
            </div>
        </div>
        
        <div class="info-panel">
            <div class="seat-info">
                <div><span class="seat-sample" style="background-color: #2c3e50;"></span> Available</div>
                <div><span class="seat-sample" style="background-color: #e74c3c;"></span> Selected</div>
                <div><span class="seat-sample" style="background-color: #7f8c8d;"></span> Occupied</div>
                <div><span class="seat-sample" style="background: linear-gradient(to bottom, #f1c40f, #f39c12);"></span> VIP</div>
                <div><span class="seat-sample" style="background: linear-gradient(to right, #e74c3c, #c0392b); width: 30px;"></span> Couple</div>
            </div>
            
            <div class="summary">
                <h5>Your Selection</h5>
                <p id="selected-seats">No seats selected</p>
                <p id="total-price">Total: ₹0.00</p>
                <button class="btn btn-primary w-100" id="proceed-btn" disabled>Proceed to Checkout</button>
            </div>
        </div>
        
        <p class="instructions">
            <i class="bi bi-info-circle"></i> Select your seats by clicking on them. Aisles provide walking space between sections.
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const seats = document.querySelectorAll('.seat:not(.occupied)');
            const selectedSeatsElement = document.getElementById('selected-seats');
            const totalPriceElement = document.getElementById('total-price');
            const proceedBtn = document.getElementById('proceed-btn');
            
            let selectedSeats = [];
            const seatPrices = {
                'vip': 350,
                'couple': 600,
                'standard': 200
            };
            
            seats.forEach(seat => {
                seat.addEventListener('click', () => {
                    const seatNumber = seat.getAttribute('data-seat');
                    
                    if (selectedSeats.includes(seatNumber)) {
                        // Deselect seat
                        selectedSeats = selectedSeats.filter(s => s !== seatNumber);
                        seat.classList.remove('selected');
                    } else {
                        // Select seat
                        selectedSeats.push(seatNumber);
                        seat.classList.add('selected');
                    }
                    
                    updateSelection();
                });
            });
            
            function updateSelection() {
                if (selectedSeats.length === 0) {
                    selectedSeatsElement.textContent = 'No seats selected';
                    totalPriceElement.textContent = 'Total: ₹0.00';
                    proceedBtn.disabled = true;
                } else {
                    selectedSeatsElement.textContent = 'Seats: ' + selectedSeats.join(', ');
                    
                    // Calculate total price
                    let total = 0;
                    selectedSeats.forEach(seat => {
                        if (seat.startsWith('A') || seat.startsWith('B')) {
                            total += seatPrices.vip;
                        } else if (seat.startsWith('H')) {
                            total += seatPrices.couple;
                        } else {
                            total += seatPrices.standard;
                        }
                    });
                    
                    totalPriceElement.textContent = `Total: ₹${total.toFixed(2)}`;
                    proceedBtn.disabled = false;
                }
            }
            
            // Initialize with some occupied seats
            const occupiedSeats = ['E5', 'E6', 'G5', 'G6', 'G7', 'H6'];
            occupiedSeats.forEach(seatId => {
                const seat = document.querySelector(`.seat[data-seat="${seatId}"]`);
                if (seat) {
                    seat.classList.add('occupied');
                }
            });
        });
    </script>
</body>
</html>