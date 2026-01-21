<?php
include "../includes/db.php";
include "../includes/admin_check.php";

/* Fetch stats */
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Beauty Multi-Service</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: #f5f5f5;
    }
    .stat-card {
      border-radius: 18px;
      box-shadow: 0 10px 30px rgba(0,0,0,.08);
    }
  </style>
</head>
<body>

<div class="container py-5">

  <!-- HEADER -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Admin Dashboard</h2>
    <a href="../auth/logout.php" class="btn btn-outline-danger">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </div>

  <!-- STATS -->
  <div class="row g-4 mb-5">

    <div class="col-md-4">
      <div class="card stat-card text-center p-4">
        <i class="bi bi-box fs-1 text-primary"></i>
        <h4 class="mt-2"><?= $totalProducts ?></h4>
        <p class="text-muted">Total Products</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card stat-card text-center p-4">
        <i class="bi bi-people fs-1 text-success"></i>
        <h4 class="mt-2"><?= $totalUsers ?></h4>
        <p class="text-muted">Registered Users</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card stat-card text-center p-4">
        <i class="bi bi-shield-lock fs-1 text-warning"></i>
        <h4 class="mt-2">Admin</h4>
        <p class="text-muted">Access Level</p>
      </div>
    </div>

  </div>

  <!-- ACTIONS -->
  <div class="row g-4">

    <div class="col-md-6">
      <a href="add-product.php" class="card stat-card p-4 text-decoration-none text-dark">
        <h5><i class="bi bi-plus-circle"></i> Add Product</h5>
        <p class="text-muted mb-0">Create new products for the store</p>
      </a>
    </div>

    <div class="col-md-6">
      <a href="add-product.php" class="card stat-card p-4 text-decoration-none text-dark">
        <h5><i class="bi bi-box-seam"></i> Manage Products</h5>
        <p class="text-muted mb-0">Edit or review existing products</p>
      </a>
    </div>

  </div>

</div>

</body>
</html>
