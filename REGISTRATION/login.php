<?php
// filepath: c:\xampp\htdocs\Kadiliman\login.php
session_start();
$conn = new mysqli("localhost", "root", "", "kadiliman");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'surname' => $user['surname']
            ];
            header("Location: /Kadiliman/Dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = 'invalid_password';
            header("Location: ../Registration.html");
            exit;
        }
    } else {
        $_SESSION['error'] = 'user_not_found';
        header("Location: ../Registration.html");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>