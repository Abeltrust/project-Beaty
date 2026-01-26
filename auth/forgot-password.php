<?php
include "../includes/db.php";

$msg = "";


if($_SERVER["REQUEST_METHOD"]==="POST"){

  $email = trim($_POST['email']);

  if(empty($email)){
    $msg = "Please enter your email.";
  } else {

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){

      $token = bin2hex(random_bytes(32));
      $expires = date("Y-m-d H:i:s", time()+3600);

      $save = $pdo->prepare(
        "UPDATE users SET reset_token=?, reset_expires=? WHERE id=?"
      );
      $save->execute([$token,$expires,$user['id']]);

      /* Dev Mode Reset Link */
      $link = "http://localhost/project-Beaty/auth/reset-password.php?token=".$token;

    //   $msg = "Reset link (dev mode): <a href='$link'>Reset Password</a>";
      mail($email,"Password Reset","Click here: $link");
      $msg = "Password reset link sent to your email.";   


    } else {
      $msg = "Email not found.";
    }

  }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{
  padding: 1rem;
  background:#f5f5f5;
}

/* Card */
.card{
  border-radius:18px;
  box-shadow:0 20px 50px rgba(0,0,0,.15);
  background:#fff;
}

/* Title */
h5{
  font-weight:600;
  text-align:center;
}

/* Inputs */
.form-control{
  border-radius:12px;
  padding:.7rem .9rem;
  font-size:.95rem;
}

.form-control:focus{
  box-shadow:none;
  border-color:#c79a3d;
}

/* Button */
button{
  border-radius:12px;
  padding:.7rem;
  font-weight:600;
}

/* Alerts */
.alert{
  font-size:.9rem;
}

/* ================= MOBILE ================= */
@media (max-width: 576px){

  body{
    padding:.75rem;
  }

  .card{
    padding:1.25rem !important;
  }

  h5{
    font-size:1.05rem;
  }

  .form-control{
    font-size:.9rem;
    padding:.55rem .7rem;
  }

  button{
    font-size:.9rem;
    padding:.55rem;
  }

  .alert{
    font-size:.85rem;
  }

}

</style>
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 w-100" style="max-width:400px;">

<h5 class="text-center mb-3">Recover Password</h5>

<?php if($msg): ?>
<div class="alert alert-info text-center">
  <?= htmlspecialchars($msg) ?>
</div>
<?php endif; ?>

<form method="post">

  <input 
    class="form-control mb-3" 
    type="email" 
    name="email" 
    placeholder="Enter your email" 
    required
  >

  <button class="btn btn-dark w-100">
    Send Reset Link
  </button>

</form>

</div>

</body>
</html>
