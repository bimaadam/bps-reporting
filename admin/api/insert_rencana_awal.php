<?php
include '../service/database.php';

$tahun = $_POST['tahun_kegiatan'];
$bulan = $_POST['bulan_kegiatan'];
$kode_program = $_POST['kode_program'];
$kode_kegiatan = $_POST['kode_kegiatan'];
$kode_kro = $_POST['kode_kro'];
$kode_ro = $_POST['kode_ro'];

$query = "INSERT INTO tbl_rencana_anggaran (
  tahun_kegiatan, bulan_kegiatan, kode_program, kode_kegiatan, kode_kro, kode_ro
) VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("iissss", $tahun, $bulan, $kode_program, $kode_kegiatan, $kode_kro, $kode_ro);

if ($stmt->execute()) {
    echo "Berhasil insert awal";
} else {
    echo "Gagal insert awal: " . $stmt->error;
}
?>
