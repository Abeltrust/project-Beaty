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
  <link rel="stylesheet" href="assets/css/navbar.css">
  
  <style>
    .hero-features {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
      margin-top: 2rem;
      margin-left:2rem;
      margin-right:2rem;
      margin-bottom: 2rem;
    }

    @media (min-width: 768px) {
      .hero-features {
        grid-template-columns: repeat(4, 1fr);
      }
    }

    .pill {
      padding: 1rem 0.5rem;
      font-size: 0.75rem;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 0.5rem;
      font-weight: 500;
      color: #333;
      transition: all 0.3s ease;
      min-height: 100px;
      justify-content: center;
    }

    .pill i {
      color: #c79a3d;
      font-size: 1.5rem;
    }

    .pill:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }
    html, body {
  overflow-x: hidden;
  width: 100%;
}
.product-card{
    margin-left: 2rem;
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
    .nowrap-title{
  white-space: nowrap;
}

  </style>
  
</head>

<body>
    <div id="loader">
        <div class="spinner"></div>
      </div>
      
<!-- ================= EDITORIAL NAVBAR ================= -->
<nav class="navbar navbar-expand-lg editorial-nav fixed-top">
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
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#editorialNav">
        <i class="bi bi-list fs-2"></i>
      </button>
  
      <!-- Menu -->
      <div class="collapse navbar-collapse" id="editorialNav">
        <ul class="navbar-nav mx-auto gap-lg-4 text-center mt-4 mt-lg-0">
          <li class="nav-item"><a class="nav-link" href="product.php">Shop</a></li>
          <?php if (session_start())?>
              <?php if ( isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                   <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                </li>
              <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        </ul>

        <!-- Actions -->
        <div class="nav-actions d-flex flex-column flex-lg-row gap-2 mt-4 mt-lg-0">
       <?php if (isset($_SESSION['user_id'])): ?>
        <?php if ($_SESSION['role'] === 'admin'): ?>
              <a class="btn btn-auth ms-lg-3" href="admin/dashboard.php">Admin Dashboard</a>
               <a href="auth/logout.php" class="btn btn-outline-danger ms-lg-3">
                <i class="bi bi-box-arrow-right"></i> Logout
              </a>
        <?php elseif ($_SESSION['role'] === 'user'): ?>
            <a class="btn btn-auth ms-lg-3" href="auth/logout.php">Logout</a>
        <?php else: ?>  
            <a class="btn btn-auth ms-lg-3" href="auth/login.php">Login</a>
            <a class="btn btn-auth ms-lg-2" href="auth/register.php">Sign Up</a>    
        <?php endif; ?>       
         <?php endif; ?>                   
        </div>
      </div>
  
    </div>
  </nav>
  

<!-- ================= HERO ================= -->
<section class="hero">
  <div class="container text-center" style="max-width:700px">
    <h1 class="nowrap-title">Beauty Multi-Service</h1>
    <h3>Premium Tiles & Interior Finishes</h3>
    <p>
      We supply high-quality Spanish and Italian tiles, luxury interior materials,
      and modern building solutions designed to transform spaces.
    </p>
    <a href="product.php" class="btn btn-primary-custom mt-4">
      Explore Our Products
    </a>
  </div>
</section>

<!-- ================= ABOUT ================= -->
<section id="about" class="section bg-white">
  <div class="container text-center">
    <h2 class="section-title">What We Do</h2>
    <p class="text-muted mx-auto" style="max-width: 750px;">
      We are a premium dealer and distributor of CDK Porcelain, 
      committed to supplying high-quality tiles, finishes, and building solutions that
       transform spaces into timeless works of beauty.
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
        <img src="assets/images/luxury.png" alt="Luxury Floor Tiles">
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
        <img src="assets/images/wall.png" alt="Wall & Interior Tiles">
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
        <img src="assets/images/exterior.png" alt="Exterior Finishes">
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
<footer class="bg-dark text-light py-5">
  <div class="container">
    <div class="row g-4">
      <!-- Company Info -->
      <div class="col-md-4">
        <h5 class="mb-3">Beauty Multi-Service</h5>
        <p class="mb-0 small">
          Your trusted partner for premium tiles and interior finishes.
          Quality materials for exceptional spaces.
        </p>
      </div>
      
      <!-- Quick Links -->
      <div class="col-md-4">
        <h5 class="mb-3">Quick Links</h5>
        <ul class="list-unstyled small">
          <li><a href="product.php" class="text-light text-decoration-none">Shop</a></li>
          <li><a href="about.php" class="text-light text-decoration-none">About Us</a></li>
          <li><a href="contact.php" class="text-light text-decoration-none">Contact</a></li>
        </ul>
      </div>
      
      <!-- Contact Info -->
      <div class="col-md-4">
        <h5 class="mb-3">Get in Touch</h5>
        <p class="small mb-1"><i class="bi bi-telephone me-2"></i>+234(0)8065500623</p>
        <p class="small mb-1"><i class="bi bi-envelope me-2"></i>info@beautymultiservice.com.ng</p>
        <p class="small mb-0"><i class="bi bi-geo-alt me-2"></i>Port Harcourt, Nigeria</p>
      </div>
    </div>
    
    <hr class="my-4">
    
    <div class="text-center small">
      <p class="mb-0">© 2026 Beauty Multi-Service. All Rights Reserved.</p>
    </div>
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
