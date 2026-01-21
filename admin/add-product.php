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
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
      $error = "Please upload a valid image file.";
    } else {
  
      $allowedTypes = ['image/jpeg','image/png','image/webp'];
      $fileType = mime_content_type($_FILES['image']['tmp_name']);
  
      if (!in_array($fileType, $allowedTypes)) {
        $error = "Only JPG, PNG, or WEBP images allowed.";
      } elseif ($_FILES['image']['size'] > 10 * 1024 * 1024) {
        $error = "Image must be less than 2MB.";
      } else {
  
        $uploadDir = __DIR__ . "/../assets/storage/products";

        /* Create directory safely */
        if (!is_dir($uploadDir)) {
          if (!mkdir($uploadDir, 0755, true)) {
            $error = "Server permission error: Unable to create storage folder.";
          }
        }
        
        /* Final permission check */
        if (empty($error) && !is_writable($uploadDir)) {
          $error = "Storage folder exists but is not writable.";
        }
        
  
        if (empty($error)) {
  
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
          } else {
            $error = "Failed to upload image.";
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
  <title>Admin Dashboard | Beauty Multi-Service</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/navbar.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      background: #F5F5F5;
      font-family: 'Poppins', sans-serif;
      padding-top: 90px;
    }

    .admin-container {
      max-width: 1200px;
      margin: auto;
    }

    .admin-title {
      font-weight: 700;
      margin-bottom: .3rem;
    }

    .admin-sub {
      color: #666;
      font-size: .95rem;
      margin-bottom: 2rem;
    }

    .card-soft {
      border: none;
      border-radius: 18px;
      box-shadow: 0 10px 30px rgba(0,0,0,.06);
    }

    .form-control {
      border-radius: 12px;
      padding: .7rem 1rem;
    }

    .btn-brand {
      background: #c79a3d;
      border: none;
      color: #000;
      font-weight: 600;
      border-radius: 30px;
      padding: .7rem 2rem;
    }

    .btn-brand:hover {
      background: #ddb55a;
    }

    .product-img {
      height: 150px;
      object-fit: cover;
      border-radius: 12px;
    }

    .badge-admin {
      background: #eee;
      color: #333;
      font-size: .75rem;
    }
  </style>
</head>
<body>

<?php include "../includes/navbar.php"; ?>

<div class="admin-container container py-4">

  <!-- HEADER -->
  <div class="mb-4">
    <h2 class="admin-title">Admin Dashboard</h2>
    <p class="admin-sub">Manage products and store content</p>
  </div>

  <!-- ADD PRODUCT -->
  <div class="card card-soft mb-5">
    <div class="card-body">
      <h5 class="mb-3">Add New Product</h5>

      <form method="post" action="add-product.php" enctype="multipart/form-data">
        <div class="row g-3">

          <div class="col-md-6">
            <input class="form-control" name="name" placeholder="Product name" required>
          </div>

          <div class="col-md-6">
            <input class="form-control" name="price" type="number" step="0.01" placeholder="Price (₦)" required>
          </div>

          <div class="col-12">
            <textarea class="form-control" name="description" rows="3" placeholder="Product description"></textarea>
          </div>

          <div class="col-12">
            <input class="form-control" type="file" name="image" required>
          </div>

          <div class="col-12">
            <button class="btn btn-brand w-100">
              Add Product
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>

  <!-- PRODUCTS -->
  <h5 class="mb-3">Existing Products</h5>

  <div class="row g-4">
    <?php foreach ($products as $p): ?>
      <div class="col-12 col-md-3">
        <div class="card card-soft h-100 p-3 text-center">
          <img
            src="../assets/storage/products/<?= htmlspecialchars($p['image']) ?>"
            class="product-img mb-3"
          >

          <h6 class="mb-1"><?= htmlspecialchars($p['name']) ?></h6>
          <p class="text-muted small mb-2">₦<?= number_format($p['price']) ?></p>

          <span class="badge badge-admin mb-2">ID #<?= $p['id'] ?></span>

          <div class="d-flex gap-2 mt-2">
            <a href="edit-product.php?id=<?= $p['id'] ?>" class="btn btn-outline-secondary btn-sm w-100">
              Edit
            </a>
            <a href="delete-product.php?id=<?= $p['id'] ?>" class="btn btn-outline-danger btn-sm w-100">
              Delete
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>

</body>
</html>
