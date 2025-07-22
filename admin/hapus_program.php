<?php
session_start();
include "service/koneksi.php";

// Validasi data GET
if (isset($_GET['id'])) {
    $kode_program = $_GET['id'];

    // Eksekusi query hapus
    $stmt = $koneksi->prepare("DELETE FROM tbl_program1 WHERE kode_program = ?");
    $stmt->bind_param("s", $kode_program);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Data program berhasil dihapus.'); window.location.href='input-program.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menghapus data! Error: " . $stmt->error . "'); window.location.href='input-program.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('⚠️ Data tidak ditemukan.'); window.location.href='input-program.php';</script>";
}
