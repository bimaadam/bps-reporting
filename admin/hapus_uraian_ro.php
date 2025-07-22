<?php
include '../service/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_program = $_POST['kode_program'] ?? '';
    $kode_kegiatan = $_POST['kode_kegiatan'] ?? '';
    $kode_kro = $_POST['kode_kro'] ?? '';
    $kode_ro = $_POST['kode_ro'] ?? '';

    if (!empty($kode_program) && !empty($kode_kegiatan) && !empty($kode_kro) && !empty($kode_ro)) {
        $stmt = $koneksi->prepare("DELETE FROM tbl_ro1 WHERE kode_program = ? AND kode_kegiatan = ? AND kode_kro = ? AND kode_ro = ?");
        $stmt->bind_param("ssss", $kode_program, $kode_kegiatan, $kode_kro, $kode_ro);

        if ($stmt->execute()) {
            echo "✅ Berhasil hapus data RO";
        } else {
            echo "❌ Gagal hapus: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "⚠️ Data tidak lengkap buat hapus cok!";
    }
} else {
    echo "❌ Request tidak valid!";
}
