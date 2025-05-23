<?php
session_start();

require '../service/database.php';

// Ambil data dari form
$username = $_POST['username'];
$password = $_POST['password'];

// Cek user
$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    
    // Cek password tanpa hash di database
    if ($password === $row['password']) {
        $_SESSION['username'] = $username;
        header("Location: ../index.php");
        exit();
    } else {
        echo "Password salah cok!";
    }
} else {
    echo "User gak ada kontol!";
}

$stmt->close();
$conn->close();
?>
