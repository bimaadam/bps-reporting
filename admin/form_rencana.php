<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan semua parameter GET ada
$required_params = ['tahun', 'bulan', 'kode_program', 'kode_kegiatan', 'kode_kro', 'kode_ro'];
foreach ($required_params as $param) {
    if (!isset($_GET[$param]) || empty($_GET[$param])) {
        die("Parameter $param tidak valid");
    }
}

$tahun = $_GET['tahun'];
$bulan = $_GET['bulan'];
$kode_program = $_GET['kode_program'];

$tahun = $_GET['tahun'] ?? '';
$bulan = $_GET['bulan'] ?? '';
$kode_program = $_GET['kode_program'] ?? '';
$kode_kegiatan = $_GET['kode_kegiatan'] ?? '';
$kode_kro = $_GET['kode_kro'] ?? '';
$kode_ro = $_GET['kode_ro'] ?? '';


require_once 'service/aktivitas_bulanan.php';
require_once 'service/database.php'; // Use require_once to ensure $koneksi is always included and initialized

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kegiatan    = $_POST['nama_kegiatan'];
    $nama_aktivitas   = $_POST['nama_aktivitas'];
    $organik          = $_POST['organik'];
    $mitra            = $_POST['mitra'];
    $usulan_anggaran  = $_POST['usulan_anggaran'];

    $stmt = $koneksi->prepare("
        INSERT INTO tbl_rencana_anggaran 
        (no, nama_kegiatan, nama_aktivitas, organik, mitra, usulan_anggaran) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("issiid", $no, $nama_kegiatan, $nama_aktivitas, $organik, $mitra, $usulan_anggaran);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan!'); window.location.href='input_aktivitas.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Rencana Anggaran</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div id="content">
    <div id="content-wrapper" class="d-flex flex-column">
        <?php include("header.php"); ?>

        <!-- Form Rencana -->
        <div class="card shadow mb-4 mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Form Rencana</h6>
            </div>

            <div class="card-body">
            <form id="rencanaForm" method="POST" action="api/insert_rencana_detail.php">
    <!-- Tambahkan input hidden untuk parameter GET -->
    <input type="hidden" name="tahun_kegiatan" value="<?= $tahun ?>">
    <input type="hidden" name="bulan_kegiatan" value="<?= $bulan ?>">
    <input type="hidden" name="kode_program" value="<?= $kode_program ?>">
    <input type="hidden" name="kode_kegiatan" value="<?= $kode_kegiatan ?>">
    <input type="hidden" name="kode_kro" value="<?= $kode_kro ?>">
    <input type="hidden" name="kode_ro" value="<?= $kode_ro ?>">
                    
                    <!-- <div class="mb-3">
                        <label class="form-label">No</label>
                        <input type="number" name="no" id="no" class="form-control form-control-lg">
                    </div> -->

                    <div class="mb-3">
                        <label class="form-label">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control form-control-lg" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Aktivitas</label>
                        <select name="nama_aktivitas" id="nama_aktivitas" class="form-control" required>
                            <option value="">Pilih Aktivitas</option>
                            <?php
                            $aktivitas = mysqli_query($koneksi, "SELECT DISTINCT nama_aktivitas FROM aktivitas");
                            while ($row = mysqli_fetch_assoc($aktivitas)) {
                                echo "<option value='{$row['nama_aktivitas']}'>{$row['nama_aktivitas']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Organik</label>
                        <input type="number" name="organik" id="organik" class="form-control form-control-lg" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mitra</label>
                        <input type="number" name="mitra" id="mitra" class="form-control form-control-lg" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Usulan Anggaran</label>
                        <input type="number" step="0.01" name="usulan_anggaran" id="usulan_anggaran" class="form-control form-control-lg" required>
                    </div>

                    <div class="text-center pt-3">
                        <button type="submit" class="btn btn-success btn-lg px-5">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../js/bootstrap.bundle.js"></script>
</body>
</html>
