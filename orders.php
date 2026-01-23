<?php
session_start();
include "includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$uid = $_SESSION['user_id'];

// Fetch orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$uid]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order History | Beauty Multi-Service</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/navbar.css">
<style>
:root { --brand:#c79a3d; }

body{
  background:#FAF8F5;
  font-family:'Poppins',sans-serif;
  padding-top:90px;
}

.order-card{
  background:#fff;
  border-radius:16px;
  padding:1.5rem;
  box-shadow:0 12px 30px rgba(0,0,0,.08);
  margin-bottom:1rem;
}

.btn-primary-custom{
  color:#000;
  background:var(--brand);
  border: 1px solid #000;
  border-radius: 50;
  font-weight:300;
  transition:all 0.25s ease;
}
.btn-primary-custom:hover{
  color:#000;
  border: 2px var(--brand);
  border: 1px solid #000;
}
</style>
</head>

<body>
<!-- ================= EDITORIAL NAVBAR ================= -->
<nav class="navbar w navbar-expand-lg editorial-nav fixed-top">
  <div class="container-fluid px-4">

    <!-- Brand -->
    <a class="navbar-brand" href="#">
      BMS
    </a>

    <!-- Toggler -->
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#editorialNav"
    >
      <i class="bi bi-list fs-2"></i>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse" id="editorialNav">
      <ul class="navbar-nav mx-auto gap-lg-4 text-center mt-4 mt-lg-0">

        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>

        <li class="nav-item position-relative">
          <a class="nav-link" href="product.php">
            <span class="bi">Shop</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="contact.php">Contact</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="profile.php">Profile</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="orders.php">Orders</a>
        </li>

      </ul>

      <!-- Actions -->
      <div class="nav-actions d-flex flex-column flex-lg-row gap-2 mt-4 mt-lg-0">
        <a class="btn btn-auth1" href="auth/logout.php">Logout</a>
      </div>
    </div>

  </div>
</nav>

<div class="container py-5">
  <h2 class="text-center mb-4">My Order History</h2>
  <?php if ($orders): ?>
    <div class="row">
      <?php foreach ($orders as $order): ?>
        <div class="col-md-6">
          <div class="order-card">
            <h5>Order #<?= $order['id'] ?></h5>
            <p><strong>Total:</strong> ₦<?= number_format($order['total']) ?></p>
            <p><strong>Details:</strong> <?= htmlspecialchars($order['order_details']) ?></p>
            <small class="text-muted">Ordered on: <?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></small>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="text-center">
      <i class="bi bi-receipt fs-1 text-muted"></i>
      <p class="mt-3">No orders yet. <a href="product.php">Start shopping!</a></p>
    </div>
  <?php endif; ?>
  <div class="text-center mt-4">
    <a href="product.php" class="btn btn-primary-custom">Continue Shopping</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>