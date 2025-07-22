<?php
session_start();
include "service/koneksi.php";

// Cek apakah parameter kode_kro dikirim
if (isset($_GET['kode_kro'])) {
    $kode_kro = $_GET['kode_kro'];

    // Query hapus
    $stmt = $koneksi->prepare("DELETE FROM tbl_kro1 WHERE kode_kro = ?");
    $stmt->bind_param("s", $kode_kro);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Data KRO berhasil dihapus.'); window.location.href='input-kro.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menghapus data KRO! Error: " . $stmt->error . "'); window.location.href='input-kro.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('⚠️ Kode KRO tidak ditemukan.'); window.location.href='input-kro.php';</script>";
}
