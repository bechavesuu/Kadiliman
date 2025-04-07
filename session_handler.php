<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function getLoggedInUser() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

// Debugging: Uncomment the following lines to check session data
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

function logout() {
    session_start(); // Ensure the session is started
    session_destroy();
    header("Location: /Kadiliman/REGISTRATION/login.php");
    exit;
}
?>
