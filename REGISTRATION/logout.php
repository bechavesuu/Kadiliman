<?php
// filepath: c:\xampp\htdocs\Kadiliman\logout.php
session_start();
session_destroy();
header("Location: /Kadiliman/Homepage.html?logout=success");
exit;
?>