<?php
session_start();

// Check if we have a login required message
$login_required_message = "";
if (isset($_SESSION['login_required'])) {
    $login_required_message = $_SESSION['login_required'];
    // Clear the message so it doesn't appear again on refresh
    unset($_SESSION['login_required']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "kadiliman"; // Replace with your database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
        exit;
    }

    $inputUsername = $_POST['signin-username'];
    $inputPassword = $_POST['signin-password'];

    $stmt = $conn->prepare("SELECT id, username, firstname, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($inputPassword, $row['password'])) {
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['username'] = $row['username']; // Add this line
            $_SESSION['user_id'] = $row['id'];
            echo json_encode(['success' => true]);
        }
    }

    $stmt->close();
    $conn->close();
}
