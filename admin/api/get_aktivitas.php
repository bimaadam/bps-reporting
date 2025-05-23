<?php
include "../service/database.php";

if (isset($_POST['nama_aktivitas'])) {
    $nama_aktivitas = $_POST['nama_aktivitas'];
    
    $query = "SELECT * FROM aktivitas WHERE nama_aktivitas = '$nama_aktivitas'";
    $result = mysqli_query($koneksi, $query);
    
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "Data tidak ditemukan"]);
    }
}