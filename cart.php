<?php
session_start();
include "includes/db.php";

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: auth/login.php");
    exit;
}

$uid = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['increase'])) {
        $cid = (int)$_POST['increase'];
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ? AND user_id = ?");
        $stmt->execute([$cid, $uid]);
        header("Location: cart.php");
        exit;
    }

    if (isset($_POST['decrease'])) {
        $cid = (int)$_POST['decrease'];
        $check = $pdo->prepare("SELECT quantity FROM cart WHERE id = ? AND user_id = ?");
        $check->execute([$cid, $uid]);
        $current = $check->fetch();
        if ($current && $current['quantity'] > 1) {
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity - 1 WHERE id = ? AND user_id = ?");
            $stmt->execute([$cid, $uid]);
        }
        // If quantity is 1, do nothing
        header("Location: cart.php");
        exit;
    }

    if (isset($_POST['delete'])) {
        $cid = (int)$_POST['delete'];
        $check = $pdo->prepare("SELECT quantity FROM cart WHERE id = ? AND user_id = ?");
        $check->execute([$cid, $uid]);
        $current = $check->fetch();
        if ($current && $current['quantity'] > 1) {
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity - 1 WHERE id = ? AND user_id = ?");
            $stmt->execute([$cid, $uid]);
        } else {
            $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
            $stmt->execute([$cid, $uid]);
        }
        header("Location: cart.php");
        exit;
    }
}
$stmt = $pdo->prepare("
  SELECT
    cart.id AS cart_id,
    products.name,
    products.price,
    products.image,
    products.description,
    cart.quantity
  FROM cart
  JOIN products ON cart.product_id = products.id
  WHERE cart.user_id = ?
");
$stmt->execute([$uid]);
$cartItems = $stmt->fetchAll();

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}


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
  align-items:center;
  padding:.15rem .6rem;
  font-weight:700;
  color:var(--brand);
}
.qty-control span{
  padding:0 .6rem;
  font-size:.8rem;
  font-weight:600;
} 
.qty-delete-group{
  display: flex;
  align-items: center;
  gap: .5rem;
  flex-shrink: 0;
}

.cart-meta{
  display: flex;
  align-items: center;
  gap: .6rem;
}

.qty-control{
  flex-shrink: 0;
}

.remove-icon{
  flex-shrink: 0;
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
  overflow-y: auto;
}
.d-c {
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

.d-c span {
  font-weight: 600;
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
.btn-auth1 {
    border: 1px solid var(--brand);
    color: var(--brand);
    padding: 6px 16px;
    border-radius: 30px;
    transition: all 0.3s ease;
  }
  
  .btn-auth1 {
    width: auto;
    max-width: none;
    padding: 6px 16px;
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
        <a class="btn btn-auth1 s-lg-2 ms-lg-2" href="auth/logout.php">Logout</a>
      </div>
    </div>

  </div>
</nav>


<div class="container py-5 mb-5 cart-wrapper">

  <!-- ICON HEADER -->
  
  <div class="cart-header">
       <a class="nav-link position-relative" href="cart.php">
            <i class="bi bi-cart3"></i>
            <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle">
              <?= count($cartItems) ?>
            </span>
         </a>
  </div>

  <div class="row g-2">

    <!-- ITEMS -->
    <div class="col-12 col-lg-8 mb-2">
      <!-- ================= CART ITEMS MOCKUP ================= -->

      <?php if ($cartItems): ?>
        <?php foreach ($cartItems as $item): ?>
          <div class="cart-item-card">
            <div class="cart-grid">
              <img src="assets/images/products/<?= htmlspecialchars($item['image']) ?>" class="cart-img" alt="<?= htmlspecialchars($item['name']) ?>">
              <div class="cart-info">
                <h6><?= htmlspecialchars($item['name']) ?></h6>
                <p class="cart-desc">
                  <?= htmlspecialchars($item['description']) ?>
                </p>
              <div class="cart-meta">
                <span class="price-unit">₦<?= number_format($item['price']) ?> / unit</span>

                <div class="qty-delete-group">
                  <div class="qty-control align-items-center">
                    <!-- DECREASE -->
                    <form method="post">
                      <input type="hidden" name="decrease" value="<?= $item['cart_id'] ?>">
                      <button type="submit">−</button>
                    </form>

                    <span><?= $item['quantity'] ?></span>

                    <!-- INCREASE -->
                    <form method="post">
                      <input type="hidden" name="increase" value="<?= $item['cart_id'] ?>">
                      <button type="submit">+</button>
                    </form>
                  </div>

                  <button
                    type="button"
                    class="btn btn-link remove-icon p-0"
                    data-bs-toggle="modal"
                    data-bs-target="#confirmDeleteModal"
                    data-cart-id="<?= $item['cart_id'] ?>"
                  >
                    <i class="bi bi-trash3"></i>
                  </button>
                </div>
              </div>

              </div>
              <div class="cart-total">
                ₦<?= number_format($item['price'] * $item['quantity']) ?>
              </div>

            </div>
          </div>

        <?php endforeach; ?>
      <?php else: ?>
      <div class="alert alert-light text-center rounded-4">
        <i class="bi bi-cart-x fs-2"></i><br>
        Your cart is empty
      </div>
      <?php endif; ?>
    </div>

    <!-- SUMMARY -->
   <?php if ($cartItems): ?>
    <div class="col-12 col-lg-4">
      <div class="cart-summary-card h-100">
        <h6 class="mb-2">Order Summary</h6>

        <?php foreach($cartItems as $item): ?>
          <div class="d-flex justify-content-between small mb-1">
            <span><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
            <span>₦<?= number_format($item['price'] * $item['quantity']) ?></span>
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
<?php endif; ?>
<!-- MOBILE BAR -->
<div class="mobile-checkout d-lg-6 gap-4">
  <a href="product.php" class="btn btn-outline-dark <?php if ($cartItems): ?>w-50 <?php else: ?>w-100 <?php endif; ?>">
          <i class="bi bi-arrow-left"></i>
  </a>
  <?php if ($cartItems): ?>
  <a href="checkout.php" class="btn btn-primary-custom w-50">
    Checkout
  </a>
<?php endif; ?>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">

      <div class="modal-header border-0">
        <h5 class="modal-title">Remove Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">
        <i class="bi bi-trash3 fs-1 text-danger mb-3"></i>
        <p class="mb-0">
          This will reduce the quantity by 1 or remove the item if it's the last one.
        </p>
      </div>

      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          Cancel
        </button>

        <form method="post">
          <input type="hidden" name="delete" id="deleteCartId">
          <button type="submit" class="btn btn-danger">
            Yes, Proceed
          </button>
        </form>
      </div>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const deleteModal = document.getElementById('confirmDeleteModal');

  deleteModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const cartId = button.getAttribute('data-cart-id');
    document.getElementById('deleteCartId').value = cartId;
  });
});
</script>

</body>
</html>
