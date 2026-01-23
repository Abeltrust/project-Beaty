<?php
include "../includes/db.php";

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

$stmt = $pdo->prepare("SELECT id FROM users WHERE verification_token = ? AND email_verified = 0");
$stmt->execute([$token]);
$user = $stmt->fetch();

if ($user) {
    $pdo->prepare("UPDATE users SET email_verified = 1, verification_token = NULL WHERE id = ?")->execute([$user['id']]);
    echo "Email verified successfully! <a href='login.php'>Login here</a>";
} else {
    echo "Invalid or expired token.";
}
?>