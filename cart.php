<?php
include "includes/db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: auth/login.php");
    exit;
}

$uid = $_SESSION['user_id'];

$stmt = $pdo->prepare("
  SELECT 
    cart.id AS cart_id,
    cart.quantity,
    products.name,
    products.price,
    products.image,
    products.description
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
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/navbar.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root{ --brand:#c79a3d; }

body{ background:#f5f5f5; }

.cart-wrapper{ max-width:1100px; }

/* HEADER ICON */
.cart-header{
  display:flex;
  justify-content:center;
  margin-bottom:2rem;
}
.cart-header i{
  font-size:2.4rem;
  color:var(--brand);
}

/* ITEM CARD */
.cart-item-card{
  background:#fff;
  border-radius:16px;
  padding:.9rem;
  margin-bottom:1rem;
  box-shadow:0 10px 30px rgba(0,0,0,.06);
}

.cart-grid{
  display:grid;
  grid-template-columns:64px 1fr auto;
  gap:.75rem;
  align-items:center;
}

.cart-img{
  width:64px;
  height:64px;
  border-radius:12px;
  object-fit:cover;
}

.cart-info h6{ font-size:.95rem; margin:0; }
.cart-desc{ font-size:.8rem; color:#666; margin:.2rem 0; }

.cart-meta{
  display:flex;
  gap:.6rem;
  flex-wrap:wrap;
  align-items:center;
}

.price-unit{ font-size:.8rem; color:#888; }

/* QTY */
.qty-control{
  display:flex;
  border:1px solid #ddd;
  border-radius:999px;
  overflow:hidden;
}
.qty-control button{
  background:none;
  border:none;
  padding:.15rem .6rem;
  font-weight:700;
  color:var(--brand);
}
.qty-control span{
  padding:0 .6rem;
  font-size:.8rem;
  font-weight:600;
}

/* DELETE */
.remove-icon{
  color:#c0392b;
  font-size:.85rem;
}

/* ITEM TOTAL */
.cart-total{
  font-weight:600;
  font-size:.95rem;
  white-space:nowrap;
}

/* SUMMARY */
.cart-summary-card{
  background:#fff;
  border-radius:18px;
  padding:1.6rem;
  box-shadow:0 18px 45px rgba(0,0,0,.08);
  position:sticky;
  top:110px;
}

/* MOBILE */
@media(max-width:768px){
  .cart-grid{ grid-template-columns:56px 1fr auto; }
  .cart-img{ width:56px; height:56px; }
  .cart-summary-card{ position:static; }
}
.mobile-checkout {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: #fff;
  border-top: 1px solid #eee;
  padding: .75rem 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  z-index: 999;
}

.mobile-checkout span {
  font-weight: 600;
}
.btn-primary-custom{
  color:var(--brand);
  border: 2px var(--brand);
  hover: var(--brand);
}
</style>
</head>

<body>

<?php include "includes/navbar.php"; ?>

<div class="container py-5 cart-wrapper">

  <!-- ICON HEADER -->
  
  <div class="cart-header">
       <a class="nav-link position-relative" href="cart.php">
            <i class="bi bi-cart3"></i>
            <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle">
              3
            </span>
         </a>
  </div>

  <div class="row g-4">

    <!-- ITEMS -->
    <div class="col-12 col-lg-8">
<!-- ================= CART ITEMS MOCKUP ================= -->

<div class="cart-item-card">
  <div class="cart-grid">

    <img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea" class="cart-img">

    <div class="cart-info">
      <h6>Luxury Marble Tile</h6>
      <p class="cart-desc">
        Polished premium marble tile suitable for modern interior flooring.
      </p>

      <div class="cart-meta">
        <span class="price-unit">₦45,000 / box</span>

        <div class="qty-control">
          <button>-</button>
          <span>2</span>
          <button>+</button>
        </div>

        <a href="#" class="remove-icon">
          <i class="bi bi-trash3"></i>
        </a>
      </div>
    </div>

    <div class="cart-total">
      ₦90,000
    </div>

  </div>
</div>

<div class="cart-item-card">
  <div class="cart-grid">

    <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7" class="cart-img">

    <div class="cart-info">
      <h6>Interior Wall Paint</h6>
      <p class="cart-desc">
        Smooth matte interior paint with long-lasting color protection.
      </p>

      <div class="cart-meta">
        <span class="price-unit">₦18,500 / bucket</span>

        <div class="qty-control">
          <button>-</button>
          <span>1</span>
          <button>+</button>
        </div>

        <a href="#" class="remove-icon">
          <i class="bi bi-trash3"></i>
        </a>
      </div>
    </div>

    <div class="cart-total">
      ₦18,500
    </div>

  </div>
</div>

<div class="cart-item-card">
  <div class="cart-grid">

    <img src="https://images.unsplash.com/photo-1615874959474-d609969a20ed" class="cart-img">

    <div class="cart-info">
      <h6>Decorative Wall Panel</h6>
      <p class="cart-desc">
        Contemporary decorative wall panel for premium accent walls.
      </p>

      <div class="cart-meta">
        <span class="price-unit">₦32,000 / panel</span>

        <div class="qty-control">
          <button>-</button>
          <span>3</span>
          <button>+</button>
        </div>

        <a href="#" class="remove-icon">
          <i class="bi bi-trash3"></i>
        </a>
      </div>
    </div>

    <div class="cart-total">
      ₦96,000
    </div>

  </div>
</div>

      <!-- <?php if(!$cartItems): ?>
        <div class="alert alert-light text-center rounded-4">
          <i class="bi bi-cart-x fs-2"></i><br>
          Your cart is empty
        </div>
      <?php endif; ?> -->

      <?php foreach($cartItems as $item): 
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
      ?>
      <div class="cart-item-card">
        <div class="cart-grid">
          <img src="assets/storage/products/<?= htmlspecialchars($item['image']) ?>" class="cart-img">

          <div class="cart-info">
            <h6><?= htmlspecialchars($item['name']) ?></h6>
            <p class="cart-desc"><?= htmlspecialchars(substr($item['description'],0,70)) ?>...</p>

            <div class="cart-meta">
              <span class="price-unit">₦<?= number_format($item['price']) ?></span>

              <div class="qty-control">
                <button>-</button>
                <span><?= $item['quantity'] ?></span>
                <button>+</button>
              </div>

              <a href="#" class="remove-icon">
                <i class="bi bi-trash3"></i>
              </a>
            </div>
          </div>

          <div class="cart-total">
            ₦<?= number_format($subtotal) ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

    </div>

    <!-- SUMMARY -->
    <div class="col-12 col-lg-4">
      <div class="cart-summary-card">
        <h6 class="mb-3">Order Summary</h6>

        <?php foreach($cartItems as $item): ?>
          <div class="d-flex justify-content-between small mb-1">
            <span><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
            <span>₦<?= number_format($item['price']*$item['quantity']) ?></span>
          </div>
        <?php endforeach; ?>

        <hr>

        <div class="d-flex justify-content-between fw-bold mb-3">
          <span>Total</span>
          <span>₦<?= number_format($total) ?></span>
        </div>
 
      </div>
    </div>

  </div>
</div>

<!-- MOBILE BAR -->
<div class="mobile-checkout d-lg-none">
  <a href="product.php" class="btn btn-outline-dark w-50">
          <i class="bi bi-arrow-left"></i>
  </a>
  <a href="checkout.php" class="btn btn-primary-custom w-50">
    Checkout
  </a>
</div>


</body>
</html>
