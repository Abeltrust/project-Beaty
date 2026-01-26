<?php
$host = "localhost";
$db   = "beauty_db";
$user = "root";
$pass = "";
//#X#f}~hR(1?~
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