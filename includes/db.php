<?php
$host = "localhost";
$db   = "beautyservices_beauty_db";
$user = "beautyservices_beautyservices";
$pass = "#X#f}~hR(1?~";

try {
  $pdo = new PDO(
    "mysql:host=$host;dbname=$db;charset=utf8",
    $user,
    $pass,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
} catch (PDOException $e) {
  die("DB Error");
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>