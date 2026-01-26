<?php
include "../includes/db.php";

$token = $_GET['token'] ?? '';
$msg = "";

/* Validate token exists */
if(empty($token)){
  die("Invalid reset link.");
}

/* Find user */
$stmt = $pdo->prepare(
 "SELECT id FROM users WHERE reset_token=? AND reset_expires > NOW()"
);
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$user){
  die("Invalid or expired token.");
}

/* Handle reset */
if($_SERVER["REQUEST_METHOD"]==="POST"){

  $password = $_POST['password'] ?? '';
  $confirm  = $_POST['confirm'] ?? '';

  if($password !== $confirm){
    $msg = "Passwords do not match.";
  }
  elseif(strlen($password) < 6){
    $msg = "Password must be at least 6 characters.";
  }
  else {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $upd = $pdo->prepare(
      "UPDATE users 
       SET password=?, reset_token=NULL, reset_expires=NULL 
       WHERE id=?"
    );
    $upd->execute([$hash, $user['id']]);

    $msg = "Password updated successfully. <a href='login.php'>Login</a>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
  padding: 1rem;
}

.card{
  border-radius: 16px;
}

/* Mobile optimization */
@media (max-width: 576px){

  .card{
    padding: 1.25rem !important;
  }

  h5{
    font-size: 1.1rem;
  }

  input{
    font-size: .9rem;
    padding: .55rem .7rem;
  }

  button{
    padding: .55rem;
    font-size: .9rem;
  }
}
</style>
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 w-100" style="max-width:400px;">

<h5 class="text-center mb-3">Reset Password</h5>

<?php if($msg): ?>
<div class="alert alert-info text-center">
  <?= htmlspecialchars($msg) ?>
</div>
<?php endif; ?>

<form method="post">
  <input 
    class="form-control mb-3" 
    type="password" 
    name="password" 
    placeholder="New password" 
    required
  >

  <input 
    class="form-control mb-3" 
    type="password" 
    name="confirm" 
    placeholder="Confirm password" 
    required
  >

  <button class="btn btn-dark w-100">
    Update Password
  </button>
</form>

</div>

</body>
</html>
