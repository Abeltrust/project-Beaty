<?php
include "includes/db.php";
include "includes/auth_check.php";

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
</style>
</head>

<body>

<?php include "includes/navbar.php"; ?>

<div class="container checkout-wrapper py-4">

  <!-- HEADER -->
  <div class="text-center mb-4">
    <i class="bi bi-bag-check checkout-icon"></i>
  </div>

  <form method="post">
  <div class="row g-4">

    <!-- ITEMS -->
    <div class="col-12 col-lg-7">

      <?php foreach ($items as $i): 
        $sub = $i['price'] * $i['quantity'];
      ?>
      <div class="checkout-item">
        <div class="checkout-grid">
          <div>
            <div class="item-name"><?= htmlspecialchars($i['name']) ?></div>
            <div class="item-price">₦<?= number_format($i['price']) ?> each</div>
          </div>

          <div class="text-end">
            <input
              type="number"
              min="1"
              class="form-control qty-input mb-1"
              name="qty[<?= $i['product_id'] ?>]"
              value="<?= $i['quantity'] ?>"
            >
            <small class="fw-semibold">
              ₦<?= number_format($sub) ?>
            </small>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <button name="cart.php" class="btn btn-outline-dark w-100 mt-2">
        Update Cart
      </button>
    </div>

    <!-- SUMMARY -->
    <div class="col-12 col-lg-5">
      <div class="summary-card">

        <h6 class="mb-3">Order Summary</h6>

        <?php foreach ($items as $i): ?>
        <div class="summary-row">
          <span><?= htmlspecialchars($i['name']) ?> x<?= $i['quantity'] ?></span>
          <span>₦<?= number_format($i['price'] * $i['quantity']) ?></span>
        </div>
        <?php endforeach; ?>

        <hr>

        <div class="summary-row summary-total">
          <span>Total</span>
          <span>₦<?= number_format($total) ?></span>
        </div>

        <a href="<?= $wa ?>" target="_blank"
           class="btn btn-success w-100 mt-3">
          <i class="bi bi-whatsapp me-1"></i>
          Checkout via WhatsApp
        </a>

      </div>
    </div>

  </div>
  </form>

</div>

</body>
</html>
