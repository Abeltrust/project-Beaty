<?php
include "../includes/db.php";
include "../includes/auth_check.php";

if ($_SESSION['role'] !== 'admin') exit("Access denied");

if ($_POST) {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $desc = $_POST['description'];

  $imgName = time() . "_" . $_FILES['image']['name'];
  move_uploaded_file(
    $_FILES['image']['tmp_name'],
    "../assets/images/products/" . $imgName
  );

  $stmt = $pdo->prepare(
    "INSERT INTO products (name,description,price,image)
     VALUES (?,?,?,?)"
  );
  $stmt->execute([$name,$desc,$price,$imgName]);
}

$products = $pdo->query("SELECT * FROM products")->fetchAll();
?>

<h2>Manage Products</h2>

<form method="post" enctype="multipart/form-data">
  <input name="name" placeholder="Product Name" required>
  <input name="price" placeholder="Price" required>
  <textarea name="description" placeholder="Description"></textarea>
  <input type="file" name="image" required>
  <button>Add Product</button>
</form>

<hr>

<?php foreach($products as $p): ?>
  <div>
    <img src="../assets/images/products/<?= $p['image'] ?>" width="80">
    <?= $p['name'] ?> - ₦<?= $p['price'] ?>
  </div>
<?php endforeach ?>
