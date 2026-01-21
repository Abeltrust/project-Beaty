<?php
include "../includes/db.php";
include "../includes/auth_check.php";

if ($_SESSION['role'] !== 'admin') exit("Access denied");

$orders = $pdo->query("
  SELECT o.*, u.name
  FROM orders o
  JOIN users u ON o.user_id = u.id
  ORDER BY o.created_at DESC
")->fetchAll();
?>

<h2>Orders</h2>

<?php foreach ($orders as $o): ?>
  <div>
    <strong>#<?= $o['id'] ?></strong> - <?= $o['name'] ?><br>
    ₦<?= $o['total'] ?><br>
    <pre><?= $o['order_details'] ?></pre>
    <hr>
  </div>
<?php endforeach ?>
