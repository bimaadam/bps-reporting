<?php
require_once 'service/aktivitas_bulanan.php';
?>
<?php
include 'service/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no = $_POST['no'];
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $nama_aktivitas = $_POST['nama_aktivitas'];
    $organik = $_POST['organik'];
    $mitra = $_POST['mitra'];
    $usulan_anggaran = $_POST['usulan_anggaran'];

   $stmt = $koneksi->prepare("INSERT INTO tbl_rencana_anggaran (nama_kegiatan, nama_aktivitas, organik, mitra, usulan_anggaran) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssiid", $nama_kegiatan, $nama_aktivitas, $organik, $mitra, $usulan_anggaran);


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
    <!-- Bootstrap 5 -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- <style>
        body {
            background-color: #f8f9fa;
        }

        .form-wrapper {
            max-width: 1140px;
            margin: 50px auto;
        }

        .card {
            border-radius: 20px;
        }

        textarea {
            min-height: 100px;
        }
    </style> -->
</head>
<body>
    <!-- modal_form_aktivitas_bln.php -->
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0 text-center">Form Input Rencana </h4>
        </div>
        <div class="card-body p-4">
            <form id="formModal" method="POST" enctype="multipart/form-data">
    <div class="row">
        <!-- Hidden Fields (kalau perlu) -->
        <input type="hidden" name="tahun_kegiatan" id="tahun_kegiatan" value="<?= date('Y') ?>">
        <input type="hidden" name="bulan_kegiatan" id="bulan_kegiatan" value="<?= date('n') ?>">
        <input type="hidden" name="kode_program" id="kode_program_hidden">
        <input type="hidden" name="kode_kegiatan" id="kode_kegiatan_hidden">
        <input type="hidden" name="kode_kro" id="kode_kro">
        <input type="hidden" name="kode_ro" id="kode_ro">

        <!-- Kiri -->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">No</label>
                <input type="number" name="no" id="no" class="form-control form-control-lg">
            </div>
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
        </div>

        <!-- Kanan -->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Usulan Anggaran</label>
                <input type="number" step="0.01" name="usulan_anggaran" id="usulan_anggaran" class="form-control form-control-lg" required>
            </div>
        </div>
    </div>

    <div class="text-center pt-3">
        <button type="submit" class="btn btn-success btn-lg px-5">Simpan</button>
    </div>
</form>


        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="../js/bootstrap.bundle.js"></script>
</body>
</html>
