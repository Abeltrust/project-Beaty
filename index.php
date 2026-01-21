<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Beauty Multi-Service | Premium Tiles & Interiors</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
<!-- Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  
</head>

<body>
    <div id="loader">
        <div class="spinner"></div>
      </div>
      
<!-- ================= EDITORIAL NAVBAR ================= -->
<nav class="navbar navbar-expand-lg editorial-nav fixed-top">
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
          <li class="nav-item"><a class="nav-link" href="product.php">Shop</a></li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="#cart">Cart</a>
            </li>
          <?php endif; ?>

          <li class="nav-item"><a class="nav-link" href="#about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="#contact.php">Contact</a></li>
        </ul>
  
        <!-- Actions -->
        <div class="nav-actions d-flex flex-column flex-lg-row gap-2 mt-4 mt-lg-0">
            <a class="btn btn-auth ms-lg-3" href="auth/login.php">Login</a>
            <a class="btn btn-auth ms-lg-2" href="auth/register.php">Sign Up</a>                      
        </div>
      </div>
  
    </div>
  </nav>
  

<!-- ================= HERO ================= -->
<section class="hero">
  <div class="container">
    <h1>Premium Tiles & Interior Finishes</h1>
    <p>
      We supply high-quality Spanish and Italian tiles, luxury interior materials,
      and modern building solutions designed to transform spaces.
    </p>
    <a href="#products" class="btn btn-primary-custom mt-4">
      Explore Our Products
    </a>
  </div>
</section>

<!-- ================= ABOUT ================= -->
<section id="about" class="section bg-white">
  <div class="container text-center">
    <h2 class="section-title">What We Do</h2>
    <p class="text-muted mx-auto" style="max-width: 750px;">
      Beauty Multi-Service delivers world-class tiles and interior finishing solutions.
      Our products are carefully selected for durability, beauty, and long-term value —
      perfect for homes, offices, and large developments.
    </p>
  </div>
</section>
 <!-- ======== Floating Feature Pills ====== -->
<section class="hero-features">
      <div class="pill">
        <i class="bi bi-gem"></i> Premium Quality
      </div>
      <div class="pill">
        <i class="bi bi-truck"></i> Fast Delivery
      </div>
      <div class="pill">
        <i class="bi bi-whatsapp"></i> Easy Ordering
      </div>
      <div class="pill">
        <i class="bi bi-house-heart"></i> Interior Experts
      </div>
 </section>

<!-- ================= PRODUCTS PREVIEW ================= -->
<div class="row g-4">
  <div class="col-12 col-md-4">
      <div class="card product-card">
        <img src="https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf" alt="">
        <div class="card-body text-center">
          <h5>Luxury Floor Tiles</h5>
          <p class="text-muted small">Premium imported floor tiles</p>
          <a href="product.php" class="btn btn-outline-brand mt-3">
            View Products
          </a>
        </div>
      </div>
    </div>
  
   <div class="col-12 col-md-4">
      <div class="card product-card">
        <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6" alt="">
        <div class="card-body text-center">
          <h5>Wall & Interior Tiles</h5>
          <p class="text-muted small">Modern wall finishing solutions</p>
          <a href="product.php" class="btn btn-outline-brand mt-3">
            View Products
          </a>
        </div>
      </div>
    </div>
  
   <div class="col-12 col-md-4">
      <div class="card product-card">
        <img src="https://images.unsplash.com/photo-1604014237800-1c9102c219da" alt="">
        <div class="card-body text-center">
          <h5>Exterior Finishes</h5>
          <p class="text-muted small">Strong & weather-resistant designs</p>
          <a href="product.php" class="btn btn-outline-brand mt-3">
            View Products
          </a>
        </div>
      </div>
    </div>
  </div>  

<!-- ================= FOOTER ================= -->
<footer>
  <div class="container text-center">
    <p class="mb-0">© 2026 Beauty Multi-Service. All Rights Reserved.</p>
  </div>
</footer>
<script>
  window.addEventListener("load", () => {
    document.getElementById("loader").style.display = "none";
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
