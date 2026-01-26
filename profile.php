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

.order-card{
  background:#fff;
  border-radius:16px;
  padding:1rem;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
  cursor:pointer;
  transition:.25s ease;
  border:1px solid #eee;
}

.order-card:hover{
  transform:translateY(-4px);
  box-shadow:0 18px 40px rgba(0,0,0,.12);
}

.order-id{
  font-weight:600;
}

.order-meta{
  font-size:.85rem;
  color:#777;
}

.order-total{
  font-weight:600;
  color:#c79a3d;
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
  
}
.brand-logo{
      height: 40px;        /* desktop size */
      width: auto;
      object-fit: contain;
    }

    /* Mobile tweak */
    @media (max-width: 768px){
      .brand-logo{
        height: 32px;
      }
    }
</style>
</head>

<body>
<!-- ================= EDITORIAL NAVBAR ================= -->
<nav class="navbar w navbar-expand-lg editorial-nav fixed-top">
  <div class="container-fluid px-4">

    <!-- Brand -->
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img 
          src="assets/images/logo.png" 
          alt="Beauty Multi-Service Logo"
          class="brand-logo"
        >
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

        <!-- <li class="nav-item">
          <a class="nav-link" href="orders.php">Orders</a>
        </li> -->

      </ul>

      <!-- Actions -->
      <div class="nav-actions d-flex flex-column flex-lg-row gap-2 mt-4 mt-lg-0">
        <?php if ($_SESSION['role'] === 'user'): ?>
            <a class="btn btn-auth ms-lg-3" href="auth/logout.php">Logout</a>
        <?php endif; ?>  
        <a class="btn btn-auth1" href="auth/logout.php">Logout</a>`
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

    <?php 
    $totalOrders = count($orders);
    $sn = $totalOrders;
    ?>
    <h3 class="mb-4">My Orders (<?= $totalOrders ?>)</h3>
    <div class="row g-4">
   <?php foreach ($orders as $order): ?>
    <?php $displayCode = "BMS-" . str_pad($sn, 3, "0", STR_PAD_LEFT); ?>
    <div class="order-card"
      data-id="<?= $displayCode ?>"
      data-total="<?= number_format($order['total'],2) ?>"
      data-date="<?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?>"
      data-items="<?= htmlspecialchars($order['order_details']) ?>"
      onclick="openOrderModal(this)">

    <div class="d-flex justify-content-between align-items-center">
      <div>
        <div class="order-id">Order <?= $displayCode ?></div>
        <div class="order-meta">
          <?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?>
        </div>
      </div>

      <div class="order-total">
        ₦<?= number_format($order['total'],2) ?>
      </div>
    </div>
  </div>
  <?php $sn--; endforeach; ?>
  <?php if ($totalOrders == 0): ?>
    <div class="text-center mt-4">
      <p class="text-muted">You have no orders yet.</p>
    </div>
  <?php endif; ?>
  </div>
</div>

<div class="modal fade" id="orderModal" tabindex="-1">
 <div class="modal-dialog modal-dialog-centered">
  <div class="modal-content rounded-4">

   <div class="modal-header">
     <h5 class="modal-title">Order Details</h5>
     <button class="btn-close" data-bs-dismiss="modal"></button>
   </div>

   <div class="modal-body">
     <p><strong>Order ID:</strong> <span id="mOrderId"></span></p>
     <p><strong>Date:</strong> <span id="mOrderDate"></span></p>
     <p><strong>Total:</strong> ₦<span id="mOrderTotal"></span></p>
     <hr>
     <pre id="mOrderItems" style="white-space:pre-wrap;"></pre>
   </div>

  </div>
 </div>
</div>
<script>
function openOrderModal(card){

  document.getElementById("mOrderId").innerText = card.dataset.id;
  document.getElementById("mOrderDate").innerText = card.dataset.date;
  document.getElementById("mOrderTotal").innerText = card.dataset.total;
  document.getElementById("mOrderItems").innerText = card.dataset.items;

  new bootstrap.Modal(
    document.getElementById("orderModal")
  ).show();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>