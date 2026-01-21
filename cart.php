<?php

include "includes/db.php";

// Protect cart page
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: auth/login.php");
    exit;
}

$uid = $_SESSION['user_id'];

// ================= FETCH CART ITEMS =================
$stmt = $pdo->prepare("
  SELECT 
    cart.id AS cart_id,
    cart.quantity,
    products.name,
    products.price,
    products.image
  FROM cart
  JOIN products ON cart.product_id = products.id
  WHERE cart.user_id = ?
");
$stmt->execute([$uid]);
$cartItems = $stmt->fetchAll();

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart | Beauty Multi-Service</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    body {
      background: #f5f5f5;
    }

    .cart-wrapper {
      max-width: 900px;
      margin: auto;
    }

    .cart-card {
      background: #fff;
      border-radius: 18px;
      padding: 1.5rem;
      box-shadow: 0 15px 40px rgba(0,0,0,.06);
    }

    .cart-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid #eee;
      padding: 1rem 0;
    }

    .cart-item:last-child {
      border-bottom: none;
    }

    .cart-img {
      width: 90px;
      height: 90px;
      object-fit: cover;
      border-radius: 14px;
    }

    .qty-pill {
      background: #f1f1f1;
      padding: .3rem .9rem;
      border-radius: 999px;
      font-weight: 600;
      font-size: .9rem;
    }

    .total-box {
      background: #fff;
      border-radius: 18px;
      padding: 1.5rem;
      box-shadow: 0 15px 40px rgba(0,0,0,.06);
    }
  </style>
</head>
<body>

<?php include "includes/navbar.php"; ?>

<div class="container py-5 cart-wrapper">

  <h2 class="mb-4">Your Cart</h2>

  <?php if (!$cartItems): ?>
    <div class="alert alert-light text-center rounded-4">
      <i class="bi bi-cart-x fs-3 d-block mb-2"></i>
      Your cart is empty.
    </div>
  <?php else: ?>

    <!-- CART ITEMS -->
    <div class="cart-card mb-4">
      <?php foreach ($cartItems as $item): 
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
      ?>
        <div class="cart-item">
          <div class="d-flex align-items-center gap-3">
            <img
              src="assets/storage/products/<?= htmlspecialchars($item['image']) ?>"
              class="cart-img"
              alt=""
            >
            <div>
              <h6 class="mb-1"><?= htmlspecialchars($item['name']) ?></h6>
              <small class="text-muted">
                ₦<?= number_format($item['price']) ?>
              </small>
            </div>
          </div>

          <div class="text-end">
            <div class="qty-pill mb-1">
              Qty: <?= $item['quantity'] ?>
            </div>
            <strong>
              ₦<?= number_format($subtotal) ?>
            </strong>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- TOTAL -->
    <div class="total-box d-flex justify-content-between align-items-center mb-4">
      <h5 class="mb-0">Total</h5>
      <h4 class="mb-0">₦<?= number_format($total) ?></h4>
    </div>

    <!-- ACTIONS -->
    <div class="d-flex justify-content-between flex-wrap gap-3">
      <a href="product.php" class="btn btn-outline-dark rounded-pill px-4">
        Continue Shopping
      </a>
      <a href="checkout.php" class="btn btn-primary-custom rounded-pill px-5">
        Proceed to Checkout
      </a>
    </div>

  <?php endif; ?>

</div>

</body>
</html>