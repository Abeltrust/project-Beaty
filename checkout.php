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

/* ================= SAVE ORDER BEFORE WHATSAPP ================= */
if ($items) {
  $stmt = $pdo->prepare(
    "INSERT INTO orders (user_id, total, order_details)
     VALUES (?,?,?)"
  );
  $stmt->execute([$userId, $total, $orderText]);
  $orderId = $pdo->lastInsertId();
}

/* ================= WHATSAPP MESSAGE ================= */
$msg  = "Hello Beauty Multi-Service,%0A%0A";
$msg .= "Order ID: #{$orderId}%0A%0A";
$msg .= nl2br($orderText);
$msg .= "%0ATotal: ₦{$total}";

$wa = "https://wa.me/2347043079022?text=" . urlencode($msg);
?>

<!-- ================= UI ================= -->
<div class="container mt-5">
  <h3 class="text-center mb-4">Checkout</h3>

  <form method="post">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th width="120">Quantity</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $i): ?>
          <tr>
            <td><?= htmlspecialchars($i['name']) ?></td>
            <td>₦<?= number_format($i['price']) ?></td>
            <td>
              <input
                type="number"
                name="qty[<?= $i['product_id'] ?>]"
                value="<?= $i['quantity'] ?>"
                min="1"
                class="form-control"
              >
            </td>
            <td>
              ₦<?= number_format($i['price'] * $i['quantity']) ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center">
      <h5>Total: ₦<?= number_format($total) ?></h5>
      <button name="update_cart" class="btn btn-outline-dark">
        Update Cart
      </button>
    </div>
  </form>

  <div class="text-center mt-4">
    <a href="<?= $wa ?>" target="_blank" class="btn btn-success btn-lg">
      Checkout via WhatsApp
    </a>
  </div>
</div>
