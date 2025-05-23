<?php
include '../service/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validasi minimal
if (
    empty($_POST['nama_kegiatan']) ||
    empty($_POST['nama_aktivitas']) ||
    !isset($_POST['organik']) ||
    !isset($_POST['mitra']) ||
    !isset($_POST['usulan_anggaran'])
) {
    http_response_code(400);
    echo "Data tidak lengkap untuk insert detail.";
    exit;
}

$tahun = $_POST['tahun_kegiatan'];
$bulan = $_POST['bulan_kegiatan'];
$kode_program = $_POST['kode_program'];
$kode_kegiatan = $_POST['kode_kegiatan'];
$kode_kro = $_POST['kode_kro'];
$kode_ro = $_POST['kode_ro'];
$nama_kegiatan = $_POST['nama_kegiatan'];
$nama_aktivitas = $_POST['nama_aktivitas'];
$organik = $_POST['organik'];
$mitra = $_POST['mitra'];
$anggaran = $_POST['usulan_anggaran'];

$query = "UPDATE tbl_rencana_anggaran SET 
    nama_kegiatan = ?, 
    nama_aktivitas = ?, 
    organik = ?, 
    mitra = ?, 
    usulan_anggaran = ?
WHERE 
    tahun_kegiatan = ? AND 
    bulan_kegiatan = ? AND 
    kode_program = ? AND 
    kode_kegiatan = ? AND 
    kode_kro = ? AND 
    kode_ro = ?";

$stmt = $koneksi->prepare($query);
$stmt->bind_param(
    "ssiiiiiisss", 
    $nama_kegiatan,
    $nama_aktivitas,
    $organik,
    $mitra,
    $anggaran,
    $tahun,
    $bulan,
    $kode_program,
    $kode_kegiatan,
    $kode_kro,
    $kode_ro
);

if ($stmt->execute()) {
    echo "Berhasil update detail";
} else {
    echo "Gagal update detail: " . $stmt->error;
}

$stmt->close();
$koneksi->close();
