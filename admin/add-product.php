<?php
include "../includes/db.php";
include "../includes/admin_check.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $name  = trim($_POST['name']);
  $price = floatval($_POST['price']);
  $desc  = trim($_POST['description']);

  if (!$name || !$price) {
    $error = "Product name and price are required.";
  }
  elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $error = "Upload error code: " . ($_FILES['image']['error'] ?? 'unknown');
  }
  else {

    $allowedTypes = ['image/jpeg','image/png','image/webp'];
    $fileType = $_FILES['image']['type'];

    if (!in_array($fileType, $allowedTypes)) {
      $error = "Invalid image type: $fileType";
    }
    elseif ($_FILES['image']['size'] > 10 * 1024 * 1024) {
      $error = "Image must be less than 10MB.";
    }
    else {

      $uploadDir = realpath(__DIR__ . "../assets/images/products");

      if (!$uploadDir || !is_writable($uploadDir)) {
        $error = "Upload directory is not writable.";
      }
      else {

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imgName = uniqid("prod_", true) . "." . strtolower($ext);
        $target = $uploadDir . "/" . $imgName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {

          $stmt = $pdo->prepare(
            "INSERT INTO products (name, description, price, image)
             VALUES (?, ?, ?, ?)"
          );
          $stmt->execute([$name, $desc, $price, $imgName]);

          $success = "Product added successfully.";
        }
        else {
          $error = "move_uploaded_file() failed. Temp dir issue.";
        }
      }
    }
  }
}

  

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin | Manage Products</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

  <!-- HEADER -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-0">Product Management</h2>
      <small class="text-muted">Admin Control Panel</small>
    </div>
    <a href="dashboard.php" class="btn btn-outline-secondary">
      ← Back to Dashboard
    </a>
  </div>

  <!-- ALERTS -->
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <!-- ADD PRODUCT -->
  <div class="card shadow-sm mb-5">
    <div class="card-body">
      <h5 class="mb-4">Add New Product</h5>

      <form method="post" enctype="multipart/form-data">
        <div class="row g-3">

          <div class="col-md-6">
            <label class="form-label">Product Name</label>
            <input class="form-control" name="name" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Price (₦)</label>
            <input class="form-control" name="price" type="number" step="0.01" required>
          </div>

          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="3"></textarea>
          </div>

          <div class="col-12">
            <label class="form-label">Product Image</label>
            <input class="form-control" type="file" name="image" required>
            <small class="text-muted">JPG, PNG, WEBP — max 10MB</small>
          </div>

          <div class="col-12 text-end">
            <button class="btn btn-dark px-4" type="submit">
              Add Product
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>

  <!-- PRODUCT LIST -->
  <h5 class="mb-3">Existing Products</h5>

  <div class="row g-4">
    <?php foreach ($products as $p): ?>
      <div class="col-md-3">
        <div class="card shadow-sm h-100">
          <img
            src="../assets/images/products/<?= htmlspecialchars($p['image']) ?>"
            class="card-img-top"
            style="height:160px;object-fit:cover"
          >
          <div class="card-body text-center">
            <h6 class="mb-1"><?= htmlspecialchars($p['name']) ?></h6>
            <small class="text-muted">₦<?= number_format($p['price']) ?></small>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>

</body>

</html>
