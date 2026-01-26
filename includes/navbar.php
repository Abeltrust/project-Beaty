<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg editorial-nav fixed-top ">
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
          <li class="nav-item"><a class="nav-link" href="Index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="product.php">Shop</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
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
              <a class="btn btn-auth s-lg-2" href="auth/logout.php">Logout</a>
        <?php endif; ?>  
        <?php else: ?>  
            <a class="btn btn-auth ms-lg-3" href="auth/login.php">Login</a>
            <a class="btn btn-auth ms-lg-2" href="auth/register.php">Sign Up</a>    
          <?php endif; ?>
                  
        </div>
      </div>
  
    </div>
  </nav>
  <script>
  const nav = document.querySelector('.editorial-nav');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 80) {
      nav.classList.add('scrolled');
    } else {
      nav.classList.remove('scrolled');
    }
  });
</script>
