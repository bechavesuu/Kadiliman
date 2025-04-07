<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['error']); // Clear the error after retrieving it
echo json_encode(['error' => $error]);
?>
