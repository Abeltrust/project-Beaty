<?php
session_start();

// Unset all session data
$_SESSION = [];

// Destroy session
session_destroy();

// Redirect
header("Location: ../index.php");
exit;
?>