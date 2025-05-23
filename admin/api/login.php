<?php
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "db_kerja");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

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
    
    // Cek password (tanpa hash dulu ya, biar gampang test)
    if ($password === $row['password']) {
        $_SESSION['username'] = $username;
        header("Location: ../index.php"); // ganti ke halaman dashboard lo
        exit();
    } else {
        echo "Password salah";
    }
} else {
    echo "User gak ada";
}

$stmt->close();
$conn->close();
?>
