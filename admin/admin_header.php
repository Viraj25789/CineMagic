<?php 
require_once __DIR__ . "/../config.php"; 
require_once __DIR__ . "/../helpers.php"; 
ob_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Panel - Movie Tickets</title>
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
    }

    body {
      background-color: rgba(255, 250, 246, 1);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .admin-card {
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      border: none;
      border-radius: 12px;
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
<nav class="navbar navbar-dark bg-dark py-3">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
      <i class="bi bi-camera-reels me-2"></i>
      <span>CineMagic Admin</span>
    </a>

    <a class="btn btn-outline-secondary bi bi-lock me-2" href="admin_logout.php"> Logout </a>
    
  </div>
</nav>
<div class="container my-4"></div>