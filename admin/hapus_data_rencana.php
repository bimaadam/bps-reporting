<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'service/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $sql = "DELETE FROM tbl_rencana_anggaran WHERE id = '$id'";



    if (mysqli_query($koneksi, $sql)) {
        echo " Data berhasil dihapus.";
    } else {
        echo " Gagal hapus data: " . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);
}
