<?php
include "../includes/db.php";


$error = "";
$success = "";
//jjjhh
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  if (isset($_POST['update_product'])) {
    $id = intval($_POST['edit_id']);
    $name = trim($_POST['edit_name']);
    $price = floatval($_POST['edit_price']);
    $quantity = intval($_POST['edit_quantity']);
    $desc = trim($_POST['edit_description']);

    if (!$name || !$price || $quantity < 0) {
      $error = "Invalid data.";
    } else {
      $updateData = [$name, $desc, $price, $quantity, $id];
      $updateQuery = "UPDATE products SET name = ?, description = ?, price = ?, quantity = ? WHERE id = ?";

      if (isset($_FILES['edit_image']) && $_FILES['edit_image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg','image/png','image/webp'];
        $fileType = $_FILES['edit_image']['type'];
        if (in_array($fileType, $allowedTypes) && $_FILES['edit_image']['size'] <= 10 * 1024 * 1024) {
          $ext = pathinfo($_FILES['edit_image']['name'], PATHINFO_EXTENSION);
          $imgName = uniqid("prod_", true) . "." . strtolower($ext);
          $target = realpath(__DIR__ . "/../assets/images/products") . "/" . $imgName;
          if (move_uploaded_file($_FILES['edit_image']['tmp_name'], $target)) {
            $updateQuery = "UPDATE products SET name = ?, description = ?, price = ?, quantity = ?, image = ? WHERE id = ?";
            $updateData = [$name, $desc, $price, $quantity, $imgName, $id];
          } else {
            $error = "Image upload failed.";
          }
        } else {
          $error = "Invalid image.";
        }
      }

      if (!$error) {
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute($updateData);
        $success = "Product updated successfully.";
        header("Location: add-product.php");
        exit;
      }
    }
  }

  // if (isset($_POST['delete_id'])) {
  //   $id = intval($_POST['delete_id']);
  //   $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
  //   $stmt->execute([$id]);
  //   $success = "Product deleted successfully.";
  //   header("Location: add-product.php");
  //   exit;
  // }

  if (isset($_POST['delete_id'])) {

  $id = intval($_POST['delete_id']);

  try {

    // Remove product from carts first (avoid foreign key crash)
    $clear = $pdo->prepare("DELETE FROM cart WHERE product_id = ?");
    $clear->execute([$id]);

    // Now delete product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);

    $success = "Product deleted successfully.";
    header("Location: add-product.php");
    exit;

  } catch (PDOException $e) {

    $error = "Delete failed.";
    header("Location: add-product.php");
    exit;
  }
}


  // Add product logic
  $name  = trim($_POST['name']);
  $price = floatval($_POST['price']);
  $desc  = trim($_POST['description']);
  $quantity = intval($_POST['quantity']);

  if (!$name || !$price || $quantity < 0) {
    $error = "Product name, price, and valid quantity are required.";
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

      $uploadDir = realpath(__DIR__ . "/../assets/images/products");

      if (!$uploadDir || !is_writable($uploadDir)) {
        $error = "Upload directory is not writable.";
      }
      else {

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imgName = uniqid("prod_", true) . "." . strtolower($ext);
        $target = $uploadDir . "/" . $imgName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {

          $stmt = $pdo->prepare(
            "INSERT INTO products (name, description, price, image, quantity)
             VALUES (?, ?, ?, ?, ?)"
          );
          $stmt->execute([$name, $desc, $price, $imgName, $quantity]);

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

          <div class="col-md-12">
            <label class="form-label">Quantity</label>
            <input class="form-control" name="quantity" type="number" min="0" required>
          </div>

          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="3"></textarea>
          </div>

          <div class="col-12">
            <label class="form-label">Product Image</label>
            <div id="uploadArea" class="border border-dashed p-4 text-center bg-light rounded">
              <div id="uploadContent">
                <i class="bi bi-cloud-upload-fill fs-1 text-primary mb-2"></i>
                <p class="mb-2">Drag & drop your image here or <span class="text-primary fw-bold" style="cursor:pointer;" id="uploadLink">click to browse</span></p>
                <small class="text-muted">JPG, PNG, WEBP — max 10MB</small>
              </div>
              <img id="imagePreview" class="img-fluid d-none rounded" style="max-height:200px;">
            </div>
            <input type="file" id="imageInput" name="image" accept="image/*" class="d-none" required>
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
            <small class="text-muted">₦<?= number_format($p['price']) ?> | Qty: <?= $p['quantity'] ?? 0 ?></small>
            <div class="mt-2">
              <button class="btn btn-sm btn-outline-primary me-1" onclick="editProduct(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['name'])) ?>', '<?= htmlspecialchars(addslashes($p['description'])) ?>', <?= $p['price'] ?>, <?= $p['quantity'] ?? 0 ?>, '<?= htmlspecialchars($p['image']) ?>')">Edit</button>
              <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(<?= $p['id'] ?>)">Delete</button>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this product? This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form method="post" style="display:inline;">
          <input type="hidden" name="delete_id" id="deleteId">
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="edit_id" id="editId">
          <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input class="form-control" name="edit_name" id="editName" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Price (₦)</label>
            <input class="form-control" name="edit_price" id="editPrice" type="number" step="0.01" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input class="form-control" name="edit_quantity" id="editQuantity" type="number" min="0" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="edit_description" id="editDescription" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Product Image (leave empty to keep current)</label>
            <input class="form-control" type="file" name="edit_image" id="editImageInput" accept="image/*">
            <img id="editImagePreview" class="img-fluid mt-2 rounded" style="max-height:100px;">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="update_product" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const uploadArea = document.getElementById('uploadArea');
  const uploadContent = document.getElementById('uploadContent');
  const imagePreview = document.getElementById('imagePreview');
  const imageInput = document.getElementById('imageInput');
  const uploadLink = document.getElementById('uploadLink');

  // Click to browse
  uploadLink.addEventListener('click', () => imageInput.click());

  // Drag over
  uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('border-primary', 'bg-light');
  });

  // Drag enter
  uploadArea.addEventListener('dragenter', (e) => {
    e.preventDefault();
    uploadArea.classList.add('border-primary', 'bg-light');
  });

  // Drag leave
  uploadArea.addEventListener('dragleave', (e) => {
    uploadArea.classList.remove('border-primary', 'bg-light');
  });

  // Drop
  uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('border-primary', 'bg-light');
    const files = e.dataTransfer.files;
    if (files.length) {
      handleFile(files[0]);
    }
  });

  // File input change
  imageInput.addEventListener('change', (e) => {
    if (e.target.files.length) {
      handleFile(e.target.files[0]);
    }
  });

  function handleFile(file) {
    if (!file.type.startsWith('image/')) {
      alert('Please select an image file.');
      return;
    }
    if (file.size > 10 * 1024 * 1024) {
      alert('File size must be less than 10MB.');
      return;
    }
    // Set the input
    const dt = new DataTransfer();
    dt.items.add(file);
    imageInput.files = dt.files;
    // Preview
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.src = e.target.result;
      imagePreview.classList.remove('d-none');
      uploadContent.classList.add('d-none');
    };
    reader.readAsDataURL(file);
  }
});

function editProduct(id, name, desc, price, quantity, image) {
  document.getElementById('editId').value = id;
  document.getElementById('editName').value = name;
  document.getElementById('editPrice').value = price;
  document.getElementById('editQuantity').value = quantity;
  document.getElementById('editDescription').value = desc;
  document.getElementById('editImagePreview').src = '../assets/images/products/' + image;
  document.getElementById('editImageInput').value = '';
  new bootstrap.Modal(document.getElementById('editModal')).show();
}

function deleteProduct(id) {
  document.getElementById('deleteId').value = id;
  new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Edit image preview
document.getElementById('editImageInput').addEventListener('change', (e) => {
  if (e.target.files.length) {
    const file = e.target.files[0];
    if (file.type.startsWith('image/') && file.size <= 10 * 1024 * 1024) {
      const reader = new FileReader();
      reader.onload = (e) => {
        document.getElementById('editImagePreview').src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  }
});
</script>

</body>

</html>
