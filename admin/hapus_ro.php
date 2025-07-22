<?php
session_start();
include "service/koneksi.php";

if (isset($_GET['kode_ro'])) {
    $kode_ro = $_GET['kode_ro'];

    $stmt = $koneksi->prepare("DELETE FROM tbl_ro1 WHERE kode_ro = ?");
    $stmt->bind_param("s", $kode_ro);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Data RO berhasil dihapus.'); window.location.href='input-ro.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menghapus data RO! Error: " . $stmt->error . "'); window.location.href='input-ro.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('⚠️ Kode RO tidak ditemukan.'); window.location.href='input-ro.php';</script>";
}
