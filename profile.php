<?php
session_start();
include "includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$uid = $_SESSION['user_id'];

// Fetch user data
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$uid]);
$user = $stmt->fetch();

// Fetch orders
$orderStmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$orderStmt->execute([$uid]);
$orders = $orderStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Profile | Beauty Multi-Service</title>
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

.profile-card{
  background:#fff;
  border-radius:18px;
  padding:2rem;
  box-shadow:0 12px 30px rgba(0,0,0,.08);
  max-width:800px;
  margin:0 auto;
}

.profile-info{
  background: linear-gradient(135deg, var(--brand), #f4a261);
  color: white;
  padding: 1.5rem;
  border-radius: 12px;
  margin-bottom: 2rem;
}

.profile-info h4{
  margin-bottom: 1rem;
  font-weight: 600;
}

.profile-info p{
  margin: 0.5rem 0;
  font-size: 1.1rem;
}

.orders-section{
  margin-top: 2rem;
}

.order-card{
  background:#fff;
  border-radius:16px;
  padding:1.5rem;
  box-shadow:0 8px 20px rgba(0,0,0,.06);
  margin-bottom:1rem;
  border-left: 5px solid var(--brand);
}

.order-card h5{
  color: var(--brand);
  margin-bottom: 0.5rem;
}

.order-details {
  background: #f8f9fa;
  padding: 0.75rem;
  border-radius: 6px;
  margin-top: 0.5rem;
  white-space: pre-wrap;
  word-wrap: break-word;
  font-size: 0.9rem;
  line-height: 1.4;
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

/* Mobile responsiveness */
@media (max-width: 768px) {
  .profile-card {
    padding: 1rem;
    margin: 0 1rem;
  }
  
  .profile-info {
    padding: 1rem;
  }
  
  .profile-info p {
    font-size: 1rem;
  }
  
  .order-card {
    padding: 1rem;
    margin-bottom: 0.75rem;
  }
  
  .order-card h5 {
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
  }
  
  .orders-section h4 {
    font-size: 1.2rem;
  }
  
  .order-details {
    font-size: 0.9rem;
    line-height: 1.4;
    background: #f8f9fa;
    padding: 0.5rem;
    border-radius: 6px;
    margin-top: 0.5rem;
    white-space: pre-wrap;
    word-wrap: break-word;
  }
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
  <div class="profile-card">
    <h2 class="text-center mb-4">My Profile</h2>
    
    <div class="profile-info">
      <h4><i class="bi bi-person-circle me-2"></i>User Information</h4>
      <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    </div>

    <div class="orders-section">
      <h4 class="mb-3"><i class="bi bi-receipt me-2"></i>Order History</h4>
      <?php if (empty($orders)): ?>
        <div class="text-center py-4">
          <i class="bi bi-cart-x fs-1 text-muted"></i>
          <p class="text-muted mt-2">No orders yet. Start shopping!</p>
          <a href="product.php" class="btn btn-primary-custom">Browse Products</a>
        </div>
      <?php else: ?>
        <?php foreach ($orders as $order): ?>
          <div class="order-card">
            <h5>Order #<?= $order['id'] ?></h5>
            <p><strong>Total:</strong> $<?= number_format($order['total'], 2) ?></p>
            <p><strong>Date:</strong> <?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></p>
            <p><strong>Items:</strong></p>
            <pre class="order-details"><?= htmlspecialchars($order['order_details']) ?></pre>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>