<?php
include "../includes/db.php";
include "../includes/csrf.php";

if (isset($_SESSION['user_id'])) {
  header("Location: ../product.php");
  exit;
}

$error = "";

if (!empty($_POST)) {
  // Check if email already exists
  $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
  $check->execute([$_POST['email']]);

  if ($check->fetch()) {
    $error = "An account with this email already exists.";
  } else {
    // Generate verification token
    $token = bin2hex(random_bytes(32));

    // Insert new user
    $stmt = $pdo->prepare(
      "INSERT INTO users (name, email, password, email_verified, verification_token) VALUES (?, ?, ?, 0, ?)"
    );

    $stmt->execute([
      $_POST['name'],
      $_POST['email'],
      password_hash($_POST['password'], PASSWORD_DEFAULT),
      $token
    ]);

    // Send verification email (for development, display link instead)
    // $subject = "Verify Your Email - Beauty Multi-Service";
    // $message = "Hi " . htmlspecialchars($_POST['name']) . ",\n\nPlease verify your email by clicking the link: http://localhost/project-Beaty/auth/verify.php?token=" . $token;
    // $headers = "From: noreply@beautymultiservice.com";
    // mail($_POST['email'], $subject, $message, $headers);

    $success = "Registration successful! Please verify your email by clicking this link: <a href='http://localhost/project-Beaty/auth/verify.php?token=" . $token . "'>Verify Email</a>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Account | Beauty Multi-Service</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/navbar.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body {
      min-height: 100vh;
      background:
        linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.75)),
        url("https://images.unsplash.com/photo-1600585154340-be6161a56a0c")
        center / cover no-repeat;
      font-family: 'Poppins', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .register-card {
      background: #ffffff;
      border-radius: 22px;
      padding: 2.5rem;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 30px 80px rgba(0,0,0,.35);
      animation: fadeUp .6s ease;
    }

    .register-card h3 {
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      margin-bottom: .4rem;
      margin-left: 2.5rem;
    }

    .register-card p {
      color: #666;
      font-size: .95rem;
      margin-bottom: 1.8rem;
    }

    .form-control {
      border-radius: 14px;
      padding: .75rem 1rem;
      border: 1px solid #ddd;
    }

    .form-control:focus {
      border-color: #c79a3d;
      box-shadow: none;
    }

    .btn-register {
      background: #c79a3d;
      color: #000;
      border-radius: 10px;
      padding: .75rem;
      font-weight: 600;
      border: none;
      margin-top: .5rem;
    }

    .btn-register:hover {
      background: #ddb55a;
    }

    .register-footer {
      text-align: center;
      margin-top: 1.5rem;
      font-size: .9rem;
    }

    .register-footer a {
      color: #c79a3d;
      text-decoration: none;
      font-weight: 500;
    }

    .register-footer a:hover {
      text-decoration: underline;
    }

    .brand {
      position: absolute;
      top: 30px;
      left: 40px;
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      color: #fff;
      letter-spacing: 1px;
      text-decoration: none;
    }

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(15px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    @media (max-width: 576px) {

body {
  padding-left: 0.75rem;
  padding-right: 0.75rem;
}

.register-card {
  padding: 1.25rem 1rem;
  border-radius: 14px;
  max-width: 100%;
}

.register-card h3 {
  font-size: 1.2rem;
  margin-bottom: 0.25rem;
}

.register-card p {
  font-size: 0.85rem;
  margin-bottom: 1rem;
}

.form-control {
  padding: 0.55rem 0.75rem;
  font-size: 0.9rem;
  border-radius: 10px;
}

.btn-register {
  padding: 0.6rem;
  font-size: 0.9rem;
}

.register-footer {
  font-size: 0.8rem;
  margin-top: 1rem;
}
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

  </style>
</head>
<body>
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
          <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="../about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="../contact.php">Contact</a></li>
        </ul>
  
        <!-- Actions -->
        <div class="nav-actions d-flex flex-column flex-lg-row gap-2 mt-4 mt-lg-0">
            <a class="btn btn-auth ms-lg-3" href="auth/login.php">Login</a>                    
        </div>
      </div>
  
    </div>
  </nav>

<div class="register-card">
  <h3>Create Your Account</h3>
  <p>Join us to explore premium interior materials.</p>
<?php if (!empty($error)): ?>
  <div class="alert alert-danger text-center">
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>
<?php if (!empty($success)): ?>
  <div class="alert alert-success text-center">
    <?= htmlspecialchars($success) ?>
  </div>
<?php endif; ?>

  <form method="post">
    <input
      class="form-control mb-3"
      name="name"
      placeholder="Full Name"
      required
    >

    <input
      class="form-control mb-3"
      name="email"
      type="email"
      placeholder="Email address"
      required
    >

    <input
      class="form-control mb-3"
      name="password"
      type="password"
      placeholder="Password"
      required
    >

    <button class="btn btn-register w-100">
      Create Account
    </button>
  </form>

  <div class="register-footer">
    Already have an account?
    <a href="login.php">Login</a>
  </div>
</div>

</body>
</html>
