
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us | Beauty Multi-Service</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Navbar & Main Style -->
  <link rel="stylesheet" href="assets/css/navbar.css">
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    body{
      background:#FAF8F5;
      font-family:'Poppins',sans-serif;
      padding-top:90px;
    }

    @media(max-width:991px){
      body{ padding-top:64px; }
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
    /* HERO */
    .about-hero{
      background:
        linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.75)),
        url("https://images.unsplash.com/photo-1618220179428-22790b461013")
        center/cover no-repeat;
      color:#fff;
      border-radius:24px;
      padding:4rem 2rem;
    }

    .about-hero h1{
      font-weight:700;
      font-size:clamp(2rem,4vw,3rem);
    }

    .about-hero p{
      max-width:720px;
      opacity:.95;
      font-size:1.05rem;
    }

    /* SECTION */
    .about-section{
      padding:4rem 0;
    }

    .about-card{
      background:#fff;
      border-radius:20px;
      padding:2rem;
      box-shadow:0 20px 45px rgba(0,0,0,.08);
      height:100%;
    }

    .about-card i{
      font-size:1.8rem;
      color:var(--brand);
      margin-bottom:1rem;
    }

    .about-card h5{
      font-weight:600;
      margin-bottom:.5rem;
    }

    /* STATS */
    .stat-box{
      background:#fff;
      border-radius:18px;
      padding:1.5rem;
      text-align:center;
      box-shadow:0 15px 35px rgba(0,0,0,.07);
    }

    .stat-box h3{
      color:var(--brand);
      font-weight:700;
      margin-bottom:.2rem;
    }

    /* CTA */
    .about-cta{
      background:#fff;
      border-radius:28px;
      padding:3rem 2rem;
      box-shadow:0 30px 60px rgba(0,0,0,.1);
      text-align:center;
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
  </style>
</head>
<body>

<?php include "includes/navbar.php"; ?>

<div class="container mt-5">

  <!-- HERO -->
  <section class="about-hero ">
    <h1>About Beauty Multi-Service</h1>
    <p class="mt-2">
      We are a premium dealer and distributor of CDK Porcelain, 
      committed to supplying high-quality tiles, finishes, and building solutions that 
      transform spaces into timeless works of beauty.
    </p>
  </section>

  <!-- WHO WE ARE -->
  <section class="about-section">
    <div class="row g-4">
      <div class="col-12 col-md-4">
        <div class="about-card">
          <i class="bi bi-gem"></i>
          <h5>Premium Quality</h5>
          <p class="text-muted">
            We carefully source Spanish and Italian tiles,
            durable finishes, and modern materials that meet
            the highest standards.
          </p>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="about-card">
          <i class="bi bi-people"></i>
          <h5>Customer-First</h5>
          <p class="text-muted">
            From homeowners to developers, we support every
            client with expert guidance and stress-free ordering.
          </p>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="about-card">
          <i class="bi bi-building"></i>
          <h5>Built for Projects</h5>
          <p class="text-muted">
            Whether small renovations or large developments,
            our materials are trusted for long-term performance.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- STATS -->
  <section class="about-section">
    <div class="row g-4">
      <div class="col-6 col-md-3">
        <div class="stat-box">
          <h3>500+</h3>
          <small class="text-muted">Products</small>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="stat-box">
          <h3>300+</h3>
          <small class="text-muted">Happy Clients</small>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="stat-box">
          <h3>50+</h3>
          <small class="text-muted">Large Projects</small>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="stat-box">
          <h3>10+</h3>
          <small class="text-muted">Years Experience</small>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="about-section">
    <div class="about-cta">
      <h3 class="mb-3">Let’s Build Something Beautiful</h3>
      <p class="text-muted mb-4">
        Explore our products or speak with our team to find the
        perfect materials for your next project.
      </p>

      <div class="stat-box ">
        <a href="product.php" class="btn btn-primary-custom1 me-2 mb-1">
            Browse Products
        </a>

        <a href="contact.php" class="btn btn-outline-dark ">
            Talk to Us
        </a>
      </div>
    </div>
  </section>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
