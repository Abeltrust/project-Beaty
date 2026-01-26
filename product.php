
<?php
session_start();
include "includes/db.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {

    $pid = (int)$_POST['product_id'];
    $uid = $_SESSION['user_id'];

    // Check if product already exists in cart
    $check = $pdo->prepare(
        "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?"
    );
    $check->execute([$uid, $pid]);
    $existing = $check->fetch();

    if ($existing) {
        // Product exists → update quantity
        $update = $pdo->prepare(
            "UPDATE cart SET quantity = quantity + 1 WHERE id = ?"
        );
        $update->execute([$existing['id']]);
    } else {
        // Product does not exist → insert new row
        $insert = $pdo->prepare(
            "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)"
        );
        $insert->execute([$uid, $pid]);
    }

    $_SESSION['cart_message'] = "Product added to cart!";
    header("Location: product.php");
    exit;
}




$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();

// Fetch cart data
$cartStmt = $pdo->prepare("SELECT COUNT(cart.id) as count, SUM(products.price * cart.quantity) as total FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?");
$cartStmt->execute([$_SESSION['user_id']]);
$cartData = $cartStmt->fetch();
$cartCount = $cartData['count'] ?? 0;
$cartTotal = $cartData['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Products | Beauty Multi-Service</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/navbar.css">
<style>
:root { --brand:#c79a3d; }

body{
  background:#FAF8F5;
  font-family:'Poppins',sans-serif;
  padding-top:90px;
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
/* FILTER BAR */
.filter-bar{
  background:#fff;
  padding:1.1rem;
  border-radius:18px;
  box-shadow:0 12px 30px rgba(0,0,0,.07);
}

/* ================= PRODUCT CARD ================= */
.product-card{
  position:relative;
  background:#fff;
  border-radius:16px;
  overflow:hidden;
  box-shadow:0 12px 30px rgba(0,0,0,.08);
  transition:.25s ease;
  height:100%;
}

/* Desktop = square */
@media (min-width: 992px){
  .product-card{
    aspect-ratio:1 / 1;
  }
}

/* Hover (desktop only) */
@media (hover:hover){
  .product-card:hover{
    transform:translateY(-6px);
    box-shadow:0 22px 45px rgba(0,0,0,.14);
  }
}

/* ================= IMAGE ================= */
.product-img{
  width:100%;
  overflow:hidden;
}

/* Mobile image height */
.product-img img{
  width:100%;
  height:120px;
  object-fit:cover;
  transition:.35s ease;
}

/* Desktop image height */
@media (min-width: 992px){
  .product-img img{
    height:65%;
  }
  .product-card:hover img{
    transform:scale(1.08);
  }
}

/* ================= BODY ================= */
.product-body{
  padding:.63rem .7rem;
  display:flex;
  flex-direction:column;
  gap:.25rem;
}

/* Desktop: side by side */
@media (min-width: 992px){
  .product-body{
    flex-direction:row;
    align-items:center;
    justify-content:space-between;
  }
  .product-text{
    flex:1;
  }
}

.product-body h6{
  font-size:.78rem;
  font-weight:600;
  margin:0;
  line-height:1.3;
  color:#2b2b2b;
}

.price{
  font-size:.8rem;
  font-weight:600;
  color:var(--brand);
}

/* ================= BADGE ================= */
.badge-new{
  position:absolute;
  top:8px;
  left:8px;
  background:var(--brand);
  color:#000;
  font-size:.6rem;
  font-weight:700;
  padding:.25rem .45rem;
  border-radius:6px;
  z-index:2;
}

/* ================= FLOAT ICON ================= */
.float-icon{
  position:absolute;
  top:8px;
  right:8px;
  width:30px;
  height:30px;
  background:#fff;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  box-shadow:0 8px 20px rgba(0,0,0,.15);
  opacity:0;
  transition:.25s ease;
}

@media (hover:hover){
  .product-card:hover .float-icon{
    opacity:1;
  }
}

/* ================= QUICK VIEW ================= */
.quick-view{
  position:absolute;
  inset:0;
  background:rgba(0,0,0,.45);
  display:flex;
  align-items:center;
  justify-content:center;
  opacity:0;
  transition:.25s ease;
}

@media (hover:hover){
  .product-card:hover .quick-view{
    opacity:1;
  }
}

.quick-view span{
  background:#fff;
  padding:.4rem 1.1rem;
  border-radius:999px;
  font-size:.7rem;
  font-weight:600;
}
/* MOBILE */
@media(max-width:768px){
  .cart-grid{ grid-template-columns:56px 1fr auto; }
  .cart-img{ width:56px; height:56px; }
  .cart-summary-card{ position:static; }
  #quickView .modal-dialog {
    max-width: 90%;
  }
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
.btn-primary-custom1{
  color:#000;
  background:var(--brand);
  border: 1px solid #000;
  border-radius: 50;
  font-weight:300;
  transition:all 0.25s ease;
}
.btn-primary-custom1:hover{
  color:#000;
  border: 2px var(--brand);
  border: 1px solid #000;
}
* HEADER ICON */
.cart-header{
  display:flex;
  justify-content:center;
  margin-bottom:2rem;
}
.cart-header i{
  font-size:2.4rem;
  color:var(--brand);
}
/* MOBILE FILTER SIZE */
@media (max-width: 768px) {

  .filter-bar{
    padding: .6rem;
    border-radius: 12px;
  }

  .filter-bar .form-control,
  .filter-bar .form-select{
    font-size: .75rem;
    padding: .35rem .5rem;
    height: 32px;
  }

}


</style>
</head>

<body>
<!-- ================= EDITORIAL NAVBAR ================= -->
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
            <span class="bi">Cart</span>
            <span
              class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle"
            >
              <?= $cartCount ?>
            </span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="contact">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="profile.php">Profile</a>
        </li>

        <!-- <li class="nav-item">
          <a class="nav-link" href="orders.php">Orders</a>
        </li> -->
      </ul>

      <!-- Actions -->
      <div class="nav-actions d-flex flex-column flex-lg-row gap-2 mt-4 mt-lg-0">
  
            <?php if ($_SESSION['role'] === 'admin'): ?>
              <a class="btn btn-auth ms-lg-3" href="admin/dashboard.php">Admin Dashboard</a>
               <a href="auth/logout.php" class="btn btn-outline-danger ms-lg-3">
                <i class="bi bi-box-arrow-right"></i> Logout
              </a>
            <?php elseif ($_SESSION['role'] === 'user'): ?>
              <a class="btn btn-auth s-lg-2" href="auth/logout.php">Logout</a>
        <?php endif; ?>  
      </div>
    </div>

  </div>
</nav>

<div class="container py-1 mb-5">

  <!-- <div class="text-center cart-header mb-2">
     <i class="bi bi-shop"></i>
  </div> -->
<!-- FILTER -->
<div class="filter-bar mb-4">
  <div class="row g-2">
    <div class="col-12 col-md-4">
      <input id="searchInput" class="form-control" placeholder="Search products">
    </div>
    <div class="col-6 col-md-3">
      <select id="categoryFilter" class="form-select">
        <option value="">All Categories</option>
        <option value="Tiles">Tiles</option>
        <option value="Panels">Panels</option>
      </select>
    </div>
    <div class="col-6 col-md-3">
      <select id="priceFilter" class="form-select">
        <option value="">All Prices</option>
        <option value="low">Below ₦50k</option>
        <option value="mid">₦50k – ₦200k</option>
        <option value="high">Above ₦200k</option>
      </select>
    </div>
  </div>
</div>

<?php if (isset($_SESSION['cart_message'])): ?>
  <div class="alert alert-success text-center">
    <?= htmlspecialchars($_SESSION['cart_message']) ?>
  </div>
  <?php unset($_SESSION['cart_message']); ?>
<?php endif; ?>

<!-- PRODUCTS GRID -->
<div class="row g-0 g-sm-1 mb-5" id="productGrid">
  <?php foreach ($products as $p): ?>
  <div class="col-12 col-sm-4 col-lg-3 product-item mb-2"
       data-name="<?= htmlspecialchars(strtolower($p['name'])) ?>"
       data-category="Product"
       data-price="<?= $p['price'] ?>">

    <div class="product-card" data-id="<?= $p['id'] ?>">
      <span class="badge-new">NEW</span>

      <!-- IMAGE (OPENS MODAL) -->
      <div class="product-img">
        <img
          src="assets/images/products/<?= htmlspecialchars($p['image']) ?>"
          alt="<?= htmlspecialchars($p['name']) ?>"
          data-bs-toggle="modal"
          data-bs-target="#quickView"
          data-id="<?= $p['id'] ?>"
          data-name="<?= htmlspecialchars($p['name']) ?>"
          data-price="<?= number_format($p['price']) ?>"
          data-image="assets/images/products/<?= htmlspecialchars($p['image']) ?>"
          data-description="<?= htmlspecialchars($p['description']) ?>"
        >
      </div>

      <!-- FLOAT EYE ICON (OPENS MODAL) -->
      <button
        type="button"
        class="float-icon border-0 bg-white"
        data-bs-toggle="modal"
        data-bs-target="#quickView"
        data-id="<?= $p['id'] ?>"
        data-name="<?= htmlspecialchars($p['name']) ?>"
        data-price="<?= number_format($p['price']) ?>"
        data-image="assets/images/products/<?= htmlspecialchars($p['image']) ?>"
        data-description="<?= htmlspecialchars($p['description']) ?>"
      >
        <i class="bi bi-eye"></i>
      </button>

      <div class="product-body">
        <div class="product-text">
          <h6><?= htmlspecialchars($p['name']) ?></h6>
          <div class="price">₦<?= number_format($p['price']) ?></div>
        </div>

        <div class="mt-2 d-flex gap-1">
          <!-- INFO BUTTON (OPENS MODAL) -->
          <button
            type="button"
            class="btn btn-sm btn-outline-primary"
            data-bs-toggle="modal"
            data-bs-target="#quickView"
            data-id="<?= $p['id'] ?>"
            data-name="<?= htmlspecialchars($p['name']) ?>"
            data-price="<?= number_format($p['price']) ?>"
            data-image="assets/images/products/<?= htmlspecialchars($p['image']) ?>"
           data-description="<?= htmlspecialchars($p['description'], ENT_QUOTES) ?>"
          >
            <i class="bi bi-info-circle"></i>
          </button>

          <!-- ADD TO CART (DIRECT, NO MODAL) -->
          <form method="post" class="flex-grow-1" onclick="event.stopPropagation();">
            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
            <button type="submit" name="add_to_cart" class="btn btn-sm btn-dark w-100">
              <i class="bi bi-cart-plus"></i>
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="mobile-checkout d-lg d-flex gap-4">
  <span >Total: ₦<?= number_format($cartTotal) ?></span>
  <a href="cart.php" class="btn w-50 btn-primary-custom1">
     <i class="bi bi-cart3"></i>
     <span>Cart<span>
  </a>
</div>
<!-- MODAL -->
<div class="modal fade" id="quickView" tabindex="-1">
 <div class="modal-dialog modal-dialog-centered">
  <div class="modal-content rounded-4">
   <div class="modal-body text-center">
    <img id="mImg" class="img-fluid rounded mb-3">
    <h5 id="mName"></h5>
    <p class="fw-bold text-warning">₦<span id="mPrice"></span></p>
    <p id="mDescription" class="text-muted small"></p>
    <form method="post">
      <input type="hidden" name="product_id" id="mProductId">
      <button type="submit" name="add_to_cart" class="btn btn-dark w-100">
        <i class="bi bi-cart-plus"></i> Add to Cart
      </button>
    </form>

   </div>
  </div>
 </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* FILTER */
const items=document.querySelectorAll('.product-item');
['searchInput','categoryFilter','priceFilter'].forEach(id=>{
 document.getElementById(id).addEventListener('input',filter)
});
function filter(){
 const s=searchInput.value.toLowerCase(),
 c=categoryFilter.value,
 p=priceFilter.value;
 items.forEach(i=>{
  let ok=i.dataset.name.includes(s);
  if(c && i.dataset.category!==c) ok=false;
  const pr=+i.dataset.price;
  if(p==='low'&&pr>50000) ok=false;
  if(p==='mid'&&(pr<50000||pr>200000)) ok=false;
  if(p==='high'&&pr<200000) ok=false;
  i.style.display=ok?'block':'none';
 });
}

/* MODAL */
document.getElementById('quickView').addEventListener('show.bs.modal', e => {
  const c = e.relatedTarget;
  mName.innerText = c.dataset.name;
mPrice.innerText = c.dataset.price;
mImg.src = c.dataset.image;
mDescription.innerText = c.dataset.description;
document.getElementById('mProductId').value = c.dataset.id;
});

</script>
</body>
</html>
