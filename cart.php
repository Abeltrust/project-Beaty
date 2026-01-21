<?php
include "includes/db.php";
include "includes/auth_check.php";

$uid = $_SESSION['user_id'];
$pid = $_POST['product_id'];

$stmt = $pdo->prepare(
  "SELECT * FROM cart WHERE user_id=? AND product_id=?"
);
$stmt->execute([$uid,$pid]);

if ($stmt->rowCount()) {
  $pdo->prepare(
    "UPDATE cart SET quantity=quantity+1 WHERE user_id=? AND product_id=?"
  )->execute([$uid,$pid]);
} else {
  $pdo->prepare(
    "INSERT INTO cart (user_id, product_id, quantity)
     VALUES (?,?,1)"
  )->execute([$uid,$pid]);
}

header("Location: checkout.php");
