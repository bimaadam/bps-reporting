<?php
session_start();
include "service/koneksi.php";

// Cek apakah parameter dikirim
if (isset($_GET['kode_kegiatan'])) {
    $kode_kegiatan = $_GET['kode_kegiatan'];

    // Prepare delete
    $stmt = $koneksi->prepare("DELETE FROM tbl_kegiatan1 WHERE kode_kegiatan = ?");
    $stmt->bind_param("s", $kode_kegiatan);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Data kegiatan berhasil dihapus.'); window.location.href='input-kegiatan.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menghapus data kegiatan! Error: " . $stmt->error . "'); window.location.href='input-kegiatan.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('⚠️ Kode kegiatan tidak ditemukan.'); window.location.href='input-kegiatan.php';</script>";
}
