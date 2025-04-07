<?php
session_start();
$conn = new mysqli("localhost", "root", "", "kadiliman");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $surname = $_POST['surname'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, first_name, surname, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $first_name, $surname, $password);

    if ($stmt->execute()) {
        $_SESSION['user'] = [
            'id' => $conn->insert_id,
            'username' => $username,
            'email' => $email,
            'first_name' => $first_name,
            'surname' => $surname
        ];
        echo "Registration successful. <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>