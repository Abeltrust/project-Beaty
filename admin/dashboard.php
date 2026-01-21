<?php
include "../includes/db.php";
include "../includes/auth_check.php";

if ($_SESSION['role'] !== 'admin') exit("Access denied");
?>

<h2>Admin Dashboard</h2>
<ul>
  <li><a href="products.php">Manage Products</a></li>
  <li><a href="orders.php">View Orders</a></li>
</ul>
