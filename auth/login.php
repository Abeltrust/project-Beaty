<?php
session_start();

include "../includes/db.php";
include "../includes/csrf.php";

$error = "";

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../product.php");
    exit;
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // CSRF validation
    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        $error = "Invalid request. Please refresh and try again.";
    } else {

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $error = "Please fill in all fields.";
        } else {

            $stmt = $pdo->prepare(
                "SELECT id, password, role FROM users WHERE email = ? LIMIT 1"
            );
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                if ($_SESSION['role'] === 'admin') {
                  header("Location: ../admin/dashboard.php");
                } else {
                  header("Location: ../product.php");
                }

            } else {
                $error = "Invalid email or password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Beauty Multi-Service</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/navbar.css">
  <link rel="stylesheet" href="../assets/css/style.css">

  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
 body {
  min-height: 100vh;
  background:
    linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.75)),
    url("https://images.unsplash.com/photo-1618220179428-22790b461013")
    center / cover no-repeat;

  font-family: 'Poppins', sans-serif;

  padding-top: 90px; /* space for fixed navbar */
}



    .login-card {
      background: #ffffff;
      border-radius: 22px;
      padding: 2.5rem;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 30px 80px rgba(0,0,0,.35);
    }

    .login-card h3 {
      font-family: 'Playfair Display', serif;
      font-weight: 700;
    }

    .form-control {
      border-radius: 14px;
      padding: .75rem 1rem;
    }

    .btn-login {
      background: #c79a3d;
      color: #000;
      border-radius: 10px;
      padding: .75rem;
      font-weight: 600;
      border: none;
    }

    .btn-login:hover {
      background: #ddb55a;
    }

    .login-footer {
      text-align: center;
      margin-top: 1.5rem;
      font-size: .9rem;
    }

    .login-footer a {
      color: #c79a3d;
      text-decoration: none;
      font-weight: 500;
    }
    @media (max-width: 576px) {

body {
  padding-left: 0.75rem;
  padding-right: 0.75rem;
}

.login-card {
  padding: 1.25rem 1rem;
  border-radius: 14px;
  max-width: 100%;
}

.login-card h3 {
  font-size: 1.2rem;
  margin-bottom: 0.25rem;
}

.login-card p {
  font-size: 0.85rem;
  margin-bottom: 1rem;
}

.form-control {
  padding: 0.55rem 0.75rem;
  font-size: 0.9rem;
  border-radius: 10px;
}

.btn-login {
  padding: 0.6rem;
  font-size: 0.9rem;
}

.login-footer {
  font-size: 0.8rem;
  margin-top: 1rem;
}
}
@media (max-width: 576px) {
  body {
    padding-top: 64px;
  }
}

  </style>
</head>
<body>

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
          <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="../about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="../contact.php">Contact</a></li>
        </ul>
  
        <!-- Actions -->
        <div class="nav-actions d-flex flex-column flex-lg-row gap-2 mt-4 mt-lg-0">
            <a class="btn btn-auth ms-lg-2" href="register.php">Sign Up</a>                      
        </div>
      </div>
  
    </div>
  </nav>
  

<div class="container d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 90px);">
  <div class="login-card">

  <h3 class="text-center">Welcome Back</h3>
<p class="text-muted text-center">
  Login to access premium materials.
</p>

  <?php if ($error): ?>
    <div class="alert alert-danger py-2">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <form method="post">
  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <input class="form-control mb-3" name="email" type="email" placeholder="Email address" required>
    <input class="form-control mb-3" name="password" type="password" placeholder="Password" required>

    <button class="btn btn-login w-100" type="submit">
      Login
    </button>
  </form>
    <div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="remember">
    <label class="form-check-label">
        Remember me
    </label>
    </div>

    <div class="login-footer">
        Don’t have an account?
        <a href="register.php">Create one</a>
    </div>
</div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
