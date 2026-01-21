<?php
include "includes/db.php";
include "includes/auth_check.php";

$products = $pdo->query("SELECT * FROM products")->fetchAll();
$categories = array_unique(array_column($products, 'category'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Products | Beauty Multi-Service</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="assets/css/navbar.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<style>
/* ================= BASE ================= */
body {
  background: #FAF8F5;
  font-family: 'Poppins', sans-serif;
  padding-top: 90px; /* desktop navbar height */
}

@media (max-width: 991px) {
  body {
    padding-top: 64px; /* mobile navbar height */
  }
}

/* ================= FILTER BAR ================= */
.filter-bar {
  background: #ffffff;
  padding: 1rem;
  /* margin-top: 2rem; */
  border-radius: 14px;
  box-shadow: 0 10px 30px rgba(0,0,0,.08);
}

/* Mobile filter bar */
@media (max-width: 576px) {
  .filter-bar {
    padding: 0.75rem;
    /* margin-top: 1rem; */
  }

  .filter-bar .form-control,
  .filter-bar .form-select {
    font-size: 0.85rem;
    padding: 0.45rem 0.6rem;
    border-radius: 10px;
  }
}

/* ================= PRODUCT CARD ================= */
.product-card {
  background: #ffffff;
  border-radius: 14px;
  overflow: hidden;
  cursor: pointer;
  box-shadow: 0 12px 25px rgba(0,0,0,.08);
  transition: transform .2s ease, box-shadow .2s ease;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 18px 35px rgba(0,0,0,.12);
}

.product-card img {
  width: 100%;
  height: 150px;
  object-fit: cover;
}

.product-body {
  padding: 0.6rem;
  text-align: center;
}

.product-body h6 {
  font-size: 0.8rem;
  font-weight: 600;
  margin-bottom: 0.2rem;
}

.price {
  font-size: 0.8rem;
  font-weight: 600;
  color: #c79a3d;
}

/* Mobile product density */
@media (max-width: 576px) {
  .product-card img {
    height: 130px;
  }

  .product-body {
    padding: 0.5rem;
  }

  .product-body h6 {
    font-size: 0.75rem;
  }

  .price {
    font-size: 0.75rem;
  }
}

/* ================= QUICK VIEW OVERLAY ================= */
.quick-view {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,.55);
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity .2s ease;
}

.product-card:hover .quick-view {
  opacity: 1;
}
</style>
</head>
<body>
<!-- ================= EDITORIAL NAVBAR ================= -->
<nav class="navbar w navbar-expand-lg editorial-nav fixed-top">
    <div class="container-fluid px-4">
  
      <!-- Brand -->
      <a class="navbar-brand" href="#">
        BMS
      </a>
  
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#editorialNav">
        <i class="bi bi-list fs-2"></i>
      </button>
  
      <!-- Menu -->
      <div class="collapse navbar-collapse" id="editorialNav">
        <ul class="navbar-nav mx-auto gap-lg-4 text-center mt-4 mt-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
          <li class="nav-item"><a class="nav-link" href="about">About</a></li>
          <li class="nav-item"><a class="nav-link" href="contact">Contact</a></li>
        </ul>
  
        <!-- Actions -->
        <div class="nav-actions d-flex flex-column flex-lg-row gap-2 mt-4 mt-lg-0">
            <a class="btn btn-auth s-lg-2" href="auth/logout.php">Logout</a>                      
        </div>
      </div>
  
    </div>
  </nav>

<div class="container py-4">

<!-- ================= FILTER BAR ================= -->
<div class="filter-bar mb-4">
  <div class="row g-2 align-items-center">

    <div class="col-12 col-md-4">
      <input type="text" id="searchInput" class="form-control" placeholder="Search products...">
    </div>

    <div class="col-6 col-md-3">
      <select id="categoryFilter" class="form-select">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
        <?php endforeach ?>
      </select>
    </div>

    <div class="col-6 col-md-3">
      <select id="priceFilter" class="form-select">
        <option value="">All Prices</option>
        <option value="low">Below ₦50,000</option>
        <option value="mid">₦50,000 - ₦200,000</option>
        <option value="high">Above ₦200,000</option>
      </select>
    </div>

  </div>
</div>

<!-- ================= PRODUCTS GRID ================= -->
<div class="row g-3" id="productGrid">
<!-- ===== DUMMY PRODUCTS (PREVIEW ROW) ===== -->

<div class="col-4 col-lg-2 product-item"
     data-name="luxury marble tile"
     data-category="Tiles"
     data-price="120000">
  <div class="product-card position-relative"
       data-bs-toggle="modal"
       data-bs-target="#quickViewModal"
       data-name="Luxury Marble Tile"
       data-price="120,000"
       data-image="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea">
    <img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea">
    <div class="product-body">
      <h6>Luxury Marble Tile</h6>
      <div class="price">₦120,000</div>
    </div>
    <div class="quick-view"><span>Quick View</span></div>
  </div>
</div>

<div class="col-4 col-lg-2 product-item"
     data-name="spanish ceramic tile"
     data-category="Tiles"
     data-price="85000">
  <div class="product-card position-relative"
       data-bs-toggle="modal"
       data-bs-target="#quickViewModal"
       data-name="Spanish Ceramic Tile"
       data-price="85,000"
       data-image="https://images.unsplash.com/photo-1586023492125-27b2c045efd7">
    <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7">
    <div class="product-body">
      <h6>Spanish Ceramic Tile</h6>
      <div class="price">₦85,000</div>
    </div>
    <div class="quick-view"><span>Quick View</span></div>
  </div>
</div>

<div class="col-4 col-lg-2 product-item"
     data-name="interior wall panel"
     data-category="Panels"
     data-price="65000">
  <div class="product-card position-relative"
       data-bs-toggle="modal"
       data-bs-target="#quickViewModal"
       data-name="Interior Wall Panel"
       data-price="65,000"
       data-image="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6">
    <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6">
    <div class="product-body">
      <h6>Interior Wall Panel</h6>
      <div class="price">₦65,000</div>
    </div>
    <div class="quick-view"><span>Quick View</span></div>
  </div>
</div>

<div class="col-4 col-lg-2 product-item"
     data-name="exterior stone finish"
     data-category="Exterior"
     data-price="210000">
  <div class="product-card position-relative"
       data-bs-toggle="modal"
       data-bs-target="#quickViewModal"
       data-name="Exterior Stone Finish"
       data-price="210,000"
       data-image="https://images.unsplash.com/photo-1604014237800-1c9102c219da">
    <img src="https://images.unsplash.com/photo-1604014237800-1c9102c219da">
    <div class="product-body">
      <h6>Exterior Stone Finish</h6>
      <div class="price">₦210,000</div>
    </div>
    <div class="quick-view"><span>Quick View</span></div>
  </div>
</div>

<div class="col-4 col-lg-2 product-item"
     data-name="premium floor tiles"
     data-category="Tiles"
     data-price="99000">
  <div class="product-card position-relative"
       data-bs-toggle="modal"
       data-bs-target="#quickViewModal"
       data-name="Premium Floor Tiles"
       data-price="99,000"
       data-image="https://images.unsplash.com/photo-1615874959474-d609969a20ed">
    <img src="https://images.unsplash.com/photo-1615874959474-d609969a20ed">
    <div class="product-body">
      <h6>Premium Floor Tiles</h6>
      <div class="price">₦99,000</div>
    </div>
    <div class="quick-view"><span>Quick View</span></div>
  </div>
</div>

<div class="col-4 col-lg-2 product-item"
     data-name="decorative wall tiles"
     data-category="Decor"
     data-price="72000">
  <div class="product-card position-relative"
       data-bs-toggle="modal"
       data-bs-target="#quickViewModal"
       data-name="Decorative Wall Tiles"
       data-price="72,000"
       data-image="https://images.unsplash.com/photo-1600585154340-be6161a56a0c">
    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c">
    <div class="product-body">
      <h6>Decorative Wall Tiles</h6>
      <div class="price">₦72,000</div>
    </div>
    <div class="quick-view"><span>Quick View</span></div>
  </div>
</div>

<!-- ===== END DUMMY PRODUCTS ===== -->

<?php foreach ($products as $p): ?>
<div class="col-4 col-lg-2 product-item"
     data-name="<?= strtolower($p['name']) ?>"
     data-category="<?= $p['category'] ?>"
     data-price="<?= $p['price'] ?>">

  <div class="product-card position-relative"
       data-bs-toggle="modal"
       data-bs-target="#quickViewModal"
       data-name="<?= htmlspecialchars($p['name']) ?>"
       data-price="<?= number_format($p['price']) ?>"
       data-image="assets/images/<?= $p['image'] ?>">

    <img src="assets/images/<?= $p['image'] ?>">

    <div class="product-body">
      <h6><?= htmlspecialchars($p['name']) ?></h6>
      <div class="price">₦<?= number_format($p['price']) ?></div>
    </div>

    <div class="quick-view">
      <span>Quick View</span>
    </div>
  </div>

</div>
<?php endforeach ?>

</div>
</div>

<!-- ================= QUICK VIEW MODAL ================= -->
<div class="modal fade" id="quickViewModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-body text-center">
        <img id="modalImage" class="img-fluid mb-3 rounded">
        <h5 id="modalName"></h5>
        <p class="fw-bold text-warning">₦<span id="modalPrice"></span></p>
        <button class="btn btn-dark w-100 mt-2">Add to Cart</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* SEARCH + FILTER */
const searchInput = document.getElementById("searchInput");
const categoryFilter = document.getElementById("categoryFilter");
const priceFilter = document.getElementById("priceFilter");
const items = document.querySelectorAll(".product-item");

function filterProducts() {
  const search = searchInput.value.toLowerCase();
  const category = categoryFilter.value;
  const price = priceFilter.value;

  items.forEach(item => {
    const name = item.dataset.name;
    const cat = item.dataset.category;
    const cost = parseInt(item.dataset.price);

    let visible = name.includes(search);

    if (category && cat !== category) visible = false;
    if (price === "low" && cost > 50000) visible = false;
    if (price === "mid" && (cost < 50000 || cost > 200000)) visible = false;
    if (price === "high" && cost < 200000) visible = false;

    item.style.display = visible ? "block" : "none";
  });
}

searchInput.addEventListener("input", filterProducts);
categoryFilter.addEventListener("change", filterProducts);
priceFilter.addEventListener("change", filterProducts);

/* QUICK VIEW MODAL */
const modal = document.getElementById("quickViewModal");
modal.addEventListener("show.bs.modal", e => {
  const card = e.relatedTarget;
  document.getElementById("modalName").innerText = card.dataset.name;
  document.getElementById("modalPrice").innerText = card.dataset.price;
  document.getElementById("modalImage").src = card.dataset.image;
});
</script>

</body>
</html>
