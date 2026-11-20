<?php 
require_once __DIR__ . "/config.php"; require_once __DIR__ . "/helpers.php"; 
ob_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Movie Tickets</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  
  <style>
    :root {
      --primary: #0d6efd;
      --secondary: #6c757d;
      --success: #198754;
      --danger: #dc3545;
      --warning: #ffc107;
      --info: #0dcaf0;
      --light: #f8f9fa;
      --dark: #212529;
      --cinema-red: #e50914;
      --cinema-dark: #141414;
    }

    body {
      background-color: rgba(255, 250, 246, 1);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
    }
    
    .cinema-bg {
      background-color: var(--cinema-dark);
    }
    
    .btn-cinema {
      background-color: var(--cinema-red);
      color: white;
      border: none;
    }
    
    .btn-cinema:hover {
      background-color: #b80710;
      color: white;
    }
    
    .seat { 
      width: 40px; 
      height: 40px; 
      border-radius: 6px; 
      display:inline-flex; 
      align-items:center; 
      justify-content:center; 
      border:1px solid #ddd; 
      margin:4px; 
      cursor:pointer; 
      font-size: 0.8rem;
      transition: all 0.2s;
    }
    
    .seat.selected { 
      background: var(--primary); 
      color:#fff; 
      border-color: var(--primary);
    }
    
    .seat.booked { 
      background:#adb5bd; 
      color:#fff; 
      cursor:not-allowed; 
    }
    
    .seat.available:hover { 
      border-color: var(--primary); 
      transform: scale(1.05);
    }
    
    .screen-bar { 
      height: 4px; 
      background: linear-gradient(90deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.1) 100%); 
      border-radius: 8px; 
      margin: 24px 0; 
      position: relative;
    }
    
    .screen-bar:before {
      content: 'SCREEN';
      position: absolute;
      top: -25px;
      left: 50%;
      transform: translateX(-50%);
      color: #6c757d;
      font-size: 0.8rem;
    }
    
    .movie-card {
      transition: transform 0.3s, box-shadow 0.3s;
      border: none;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    
    .movie-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    
    .movie-poster {
      height: 300px;
      object-fit: cover;
      transition: transform 0.5s;
    }
    
    .movie-card:hover .movie-poster {
      transform: scale(1.05);
    }
    
    .card-title {
      font-weight: 600;
    }
    
    .showtime-card {
      border-left: 4px solid var(--primary);
      transition: all 0.2s;
    }
    
    .showtime-card:hover {
      background-color: #f1f8ff;
    }
    
    .booking-card {
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      border: none;
      border-radius: 12px;
    }
  </style>
</head>
<body> 
<nav class="navbar navbar-expand-lg navbar-dark cinema-bg py-3">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <i class="bi bi-camera-reels me-2"></i>
      <span class="d-none d-sm-inline">CineMagic</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house me-1"></i> Home</a></li>
        <?php if(is_logged_in()): ?>
        <li class="nav-item"><a class="nav-link" href="my_bookings.php"><i class="bi bi-ticket-perforated me-1"></i> My Bookings</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="admin/admin_login.php"><i class="bi bi-gear me-1"></i> Admin Panel</a></li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if(is_logged_in()): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle me-1"></i> <?= e($_SESSION['user_name']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="my_bookings.php">My Bookings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><form method="post" action="logout.php" class="px-3 py-1"><?php csrf_field(); ?><button class="dropdown-item px-0">Logout</button></form></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="register.php"><i class="bi bi-person-plus me-1"></i> Register</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right me-1"></i> Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container my-4">