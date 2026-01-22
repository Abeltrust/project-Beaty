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
<link rel="stylesheet" href="assets/css/navbar.php">
<style>
:root { --brand:#c79a3d; }

body{
  background:#FAF8F5;
  font-family:'Poppins',sans-serif;
  padding-top:90px;
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
  padding:.65rem .7rem;
  display:flex;
  flex-direction:column;
  gap:.25rem;
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
              3
            </span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="about">About</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="contact">Contact</a>
        </li>

      </ul>

      <!-- Actions -->
      <div class="nav-actions d-flex flex-column flex-lg-row gap-2 mt-4 mt-lg-0">
        <a class="btn btn-auth s-lg-2" href="auth/logout.php">Logout</a>
      </div>
    </div>

  </div>
</nav>

<div class="container py-4">

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

<!-- PRODUCTS GRID -->
<div class="row g-3" id="productGrid">
  <!-- PRODUCT 0-->
<div class="col-6 col-md-4  col-lg-2 product-item"
 data-name="luxury marble tile" data-category="Tiles" data-price="120000">
 <div class="product-card" data-bs-toggle="modal" data-bs-target="#quickView"
  data-name="Luxury Marble Tile" data-price="120,000"
  data-image="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea">
  <span class="badge-new">NEW</span>
  <div class="product-img">
    <img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea">
  </div>
  <div class="float-icon"><i class="bi bi-eye"></i></div>
  <div class="product-body">
    <h6>Luxury Marble Tile</h6>
    <div class="price">₦120,000</div>
  </div>
  <div class="quick-view"><span>Quick View</span></div>
 </div>
</div>
<!-- PRODUCT 1 -->
<div class="col-6 col-md-4  col-lg-2 product-item"
 data-name="luxury marble tile" data-category="Tiles" data-price="120000">
 <div class="product-card" data-bs-toggle="modal" data-bs-target="#quickView"
  data-name="Luxury Marble Tile" data-price="120,000"
  data-image="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea">
  <span class="badge-new">NEW</span>
  <div class="product-img">
    <img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea">
  </div>
  <div class="float-icon"><i class="bi bi-eye"></i></div>
  <div class="product-body">
    <h6>Luxury Marble Tile</h6>
    <div class="price">₦120,000</div>
  </div>
  <div class="quick-view"><span>Quick View</span></div>
 </div>
</div>

<!-- PRODUCT 2 -->
<div class="col-6 col-md-4  col-lg-2 product-item"
 data-name="luxury marble tile" data-category="Tiles" data-price="120000">
 <div class="product-card" data-bs-toggle="modal" data-bs-target="#quickView"
  data-name="Luxury Marble Tile" data-price="120,000"
  data-image="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea">
  <span class="badge-new">NEW</span>
  <div class="product-img">
    <img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea">
  </div>
  <div class="float-icon"><i class="bi bi-eye"></i></div>
  <div class="product-body">
    <h6>Luxury Marble Tile</h6>
    <div class="price">₦120,000</div>
  </div>
  <div class="quick-view"><span>Quick View</span></div>
 </div>
</div>
<div class="col-6 col-md-4 col-lg-2 product-item">
  <div class="product-card"
       data-bs-toggle="modal"
       data-bs-target="#quickView"
       data-name="Luxury Marble Tile"
       data-price="120,000"
       data-image="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea">

    <span class="badge-new">NEW</span>

    <div class="product-img">
      <img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea">
    </div>

    <div class="float-icon">
      <i class="bi bi-eye"></i>
    </div>

    <div class="product-body">
      <h6>Luxury Marble Tile</h6>
      <div class="price">₦120,000</div>
    </div>

    <div class="quick-view">
      <span>Quick View</span>
    </div>

  </div>
</div>

<!-- PRODUCT 3 -->
<div class="col-6 col-md-4 col-lg-2 product-item"
 data-name="interior wall panel" data-category="Panels" data-price="65000">
 <div class="product-card" data-bs-toggle="modal" data-bs-target="#quickView"
  data-name="Interior Wall Panel" data-price="65,000"
  data-image="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6">
  <span class="badge-new">NEW</span>
  <div class="product-img">
    <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6">
  </div>
  <div class="float-icon"><i class="bi bi-eye"></i></div>
  <div class="product-body">
    <h6>Interior Wall Panel</h6>
    <div class="price">₦65,000</div>
  </div>
  <div class="quick-view"><span>Quick View</span></div>
 </div>
</div>

</div>
</div>
<div class="mobile-checkout d-lg-none">
  <span>Total: ₦108,500</span>
  <a href="cart.php" class="btn btn-primary-custom">
     <i class="bi bi-cart3"></i>
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
    <button class="btn btn-dark w-100">
      <i class="bi bi-cart-plus"></i> Add to Cart
    </button>
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
document.getElementById('quickView').addEventListener('show.bs.modal',e=>{
 const c=e.relatedTarget;
 mName.innerText=c.dataset.name;
 mPrice.innerText=c.dataset.price;
 mImg.src=c.dataset.image;
});
</script>
</body>
</html>
