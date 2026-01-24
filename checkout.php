
<?php
include "includes/db.php";
include "includes/auth_check.php";

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: auth/login.php");
    exit;
}
$userId = $_SESSION['user_id'];

/* ================= UPDATE CART QUANTITY ================= */
if (isset($_POST['update_cart'])) {
  foreach ($_POST['qty'] as $productId => $quantity) {
    $stmt = $pdo->prepare(
      "UPDATE cart SET quantity=? WHERE user_id=? AND product_id=?"
    );
    $stmt->execute([$quantity, $userId, $productId]);
  }
}

/* ================= FETCH CART ITEMS ================= */
$stmt = $pdo->prepare("
  SELECT c.product_id, p.name, p.price, c.quantity
  FROM cart c
  JOIN products p ON c.product_id = p.id
  WHERE c.user_id=?
");
$stmt->execute([$userId]);
$items = $stmt->fetchAll();

/* ================= BUILD ORDER ================= */
$total = 0;
$orderText = "";

foreach ($items as $i) {
  $sub = $i['price'] * $i['quantity'];
  $total += $sub;
  $orderText .= "{$i['name']} x{$i['quantity']} = ₦{$sub}\n";
}

/* ================= SAVE ORDER ================= */
if ($items) {
  $stmt = $pdo->prepare(
    "INSERT INTO orders (user_id, total, order_details)
     VALUES (?,?,?)"
  );
  $stmt->execute([$userId, $total, $orderText]);
  $orderId = $pdo->lastInsertId();
}

/* ================= WHATSAPP ================= */
$msg  = "Hello Beauty Multi-Service,%0A%0A";
$msg .= "Order ID: #{$orderId}%0A%0A";
$msg .= nl2br($orderText);
$msg .= "%0ATotal: ₦{$total}";

$wa = "https://wa.me/2347043079022?text=" . urlencode($msg);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout | Beauty Multi-Service</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/style.css">
<style>
:root{
  --brand:#c79a3d;
}

/* ================= BASE ================= */
body{
  background:#FAF8F5;
  padding-top:90px;
}

.checkout-wrapper{
  max-width:1100px;
}

/* ================= HEADER ================= */
.checkout-icon{
  font-size:2.2rem;
  color:var(--brand);
}

/* ================= ITEM CARD ================= */
.checkout-item{
  background:#fff;
  border-radius:16px;
  padding:.9rem;
  margin-bottom:.75rem;
  box-shadow:0 10px 25px rgba(0,0,0,.06);
}

.checkout-grid{
  display:grid;
  grid-template-columns:1fr auto;
  gap:.5rem;
  align-items:center;
}

.item-name{
  font-size:.9rem;
  font-weight:600;
}

.item-price{
  font-size:.8rem;
  color:#777;
}

.qty-input{
  width:65px;
  font-size:.8rem;
}

/* ================= SUMMARY ================= */
.summary-card{
  background:#fff;
  border-radius:18px;
  padding:1.6rem;
  box-shadow:0 18px 45px rgba(0,0,0,.08);
  position:sticky;
  top:110px;
}

.summary-row{
  display:flex;
  justify-content:space-between;
  margin-bottom:.5rem;
}

.summary-total{
  font-weight:700;
  font-size:1.1rem;
}

/* ================= MOBILE FIX ================= */
@media(max-width:991px){
  body{padding-top:64px;}
  .summary-card{position:static;}
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
/* ===== REVIEW LIST ===== */
.review-card{
  background:#fff;
  border-radius:18px;
  padding:1.4rem;
  box-shadow:0 14px 40px rgba(0,0,0,.08);
}

.review-title{
  font-weight:600;
}

.review-item{
  display:flex;
  justify-content:space-between;
  padding:.75rem 0;
  border-bottom:1px dashed #e5e5e5;
}

.review-item:last-child{
  border-bottom:none;
}

.review-name{
  font-weight:600;
  font-size:.95rem;
}

.review-meta{
  font-size:.8rem;
  color:#777;
}

.review-right{
  font-weight:600;
}

/* ===== ACTION BUTTONS ===== */
.checkout-actions{
  max-width:600px;
  margin:2rem auto;
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:1rem;
}

.btn-whatsapp{
  background:#25D366;
  color:#fff;
  border-radius:999px;
  padding:.85rem;
  font-weight:600;
  text-align:center;
}

.btn-whatsapp:hover{
  background:#1ebe5d;
  color:#fff;
}

.btn-email{
  background:#fff;
  border:2px solid #000;
  border-radius:999px;
  padding:.85rem;
  font-weight:600;
  text-align:center;
}

.btn-email:hover{
  background:#000;
  color:#fff;
}

/* MOBILE */
@media(max-width:768px){
  .checkout-actions{
    grid-template-columns:1fr;
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
          <a class="nav-link" href="cart.php">
            <span class="bi bi-cart3">Cart</span>
            <!-- <span
              class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle"
            >
              3
            </span> -->
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="about">About</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="contact">Contact</a>
        </li>

      </ul>

      <!-- Actions -->
      <div class="nav-actions d-flex flex-column flex-lg-row gap-2 mt-4 mt-lg-0">
        <a class="btn btn-auth s-lg-2" href="auth/logout.php">Logout</a>
      </div>
    </div>

  </div>
</nav>


<div class="container checkout-wrapper py-4">

  <!-- HEADER -->
  <div class="text-center mb-4">
    <i class="bi bi-bag-check checkout-icon"></i>
  </div>

  <form method="post">
  <div class="row g-4">

    <!-- ITEMS -->
    <div class="col-12 col-lg-7">
      <div class="review-card">

        <h6 class="review-title mb-3">
          <i class="bi bi-receipt me-1"></i> Order Review
        </h6>

        <?php foreach ($items as $i): 
          $sub = $i['price'] * $i['quantity'];
        ?>
          <div class="review-item">
            <div class="review-left">
              <div class="review-name"><?= htmlspecialchars($i['name']) ?></div>
              <div class="review-meta">
                Qty: <?= $i['quantity'] ?> × ₦<?= number_format($i['price']) ?>
              </div>
            </div>

            <div class="review-right">
              ₦<?= number_format($sub) ?>
            </div>
          </div>
        <?php endforeach; ?>

      </div>

    </div>

    <!-- SUMMARY -->
    <div class="col-12 col-lg-5">
     <div class="summary-card">

          <h6 class="mb-3">Order Summary</h6>

          <?php foreach ($items as $i): ?>
            <div class="summary-row">
              <span><?= htmlspecialchars($i['name']) ?> × <?= $i['quantity'] ?></span>
              <span>₦<?= number_format($i['price'] * $i['quantity']) ?></span>
            </div>
          <?php endforeach; ?>

          <hr>

          <div class="summary-row summary-total">
            <span>Total</span>
            <span>₦<?= number_format($total) ?></span>
          </div>

          <a href="cart.php" class="btn btn-outline-dark w-100 mt-3">
            Edit Cart
          </a>

        </div>

  </div>
  </form>

</div>
<div class="checkout-actions">

  <a href="<?= $wa ?>" target="_blank" class="btn btn-whatsapp">
    <i class="bi bi-whatsapp"></i>
    Send Order via WhatsApp
  </a>

  <a href="mailto:beautymultiservice@gmail.com
     ?subject=New Order #<?= $orderId ?>
     &body=<?= urlencode($orderText . "\nTotal: ₦" . $total) ?>"
     class="btn btn-email">
    <i class="bi bi-envelope"></i>
    Send Order via Email
  </a>

</div>

</body>
</html>
