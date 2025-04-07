<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    if (empty($_POST['signup-username']) || empty($_POST['signup-email']) || empty($_POST['signup-firstname']) || empty($_POST['signup-surname']) || empty($_POST['signup-branch']) || empty($_POST['signup-password'])) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "kadiliman"; // Replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
        exit;
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (username, email, firstname, surname, branch, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $email, $firstname, $surname, $branch, $hashedPassword);

    // Set parameters and execute
    $username = $_POST['signup-username'];
    $email = $_POST['signup-email'];
    $firstname = $_POST['signup-firstname'];
    $surname = $_POST['signup-surname'];
    $branch = $_POST['signup-branch'];
    $password = $_POST['signup-password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>