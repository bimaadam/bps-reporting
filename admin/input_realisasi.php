<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Validasi parameter GET
$required_params = ['tahun_kegiatan', 'bulan_kegiatan', 'kode_program', 'kode_kegiatan', 'kode_kro', 'kode_ro'];
foreach ($required_params as $param) {
    if (!isset($_GET[$param]) || empty($_GET[$param])) {
        die("Parameter $param tidak valid. Mohon kembali ke halaman sebelumnya.");
    }
}

$tahun = $_GET['tahun_kegiatan'] ?? date('Y');
$bulan = $_GET['bulan_kegiatan'] ?? date('m');
$kode_program = $_GET['kode_program'] ?? '';
$kode_kegiatan = $_GET['kode_kegiatan'] ?? '';
$kode_kro = $_GET['kode_kro'] ?? '';
$kode_ro = $_GET['kode_ro'] ?? '';
$nama_kegiatan = $_GET['nama_kegiatan'] ?? '';
$nama_aktivitas = $_GET['nama_aktivitas'] ?? '';
$organik = $_GET['organik'] ?? '';
$mitra = $_GET['mitra'] ?? '';
$usulan_anggaran = $_GET['usulan_anggaran'] ?? '';

ob_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Input Realisasi Anggaran</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="content">
        <div id="content-wrapper" class="d-flex flex-column">
            <div class="container-fluid mt-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Form Realisasi Anggaran</h6>
                    </div>

                    <div class="card-body">
                        <form id="realisasiFormSubmit" method="POST" action="api/insert_realisasi.php">
                            <input type="hidden" name="tahun_kegiatan" value="<?= htmlspecialchars($tahun) ?>">
                            <input type="hidden" name="bulan_kegiatan" value="<?= htmlspecialchars($bulan) ?>">
                            <input type="hidden" name="kode_program" value="<?= htmlspecialchars($kode_program) ?>">
                            <input type="hidden" name="kode_kegiatan" value="<?= htmlspecialchars($kode_kegiatan) ?>">
                            <input type="hidden" name="kode_kro" value="<?= htmlspecialchars($kode_kro) ?>">
                            <input type="hidden" name="kode_ro" value="<?= htmlspecialchars($kode_ro) ?>">
                            <input type="hidden" name="nama_kegiatan" value="<?= htmlspecialchars($nama_kegiatan) ?>">
                            <input type="hidden" name="nama_aktivitas" value="<?= htmlspecialchars($nama_aktivitas) ?>">
                            <input type="hidden" name="organik" value="<?= htmlspecialchars($organik) ?>">
                            <input type="hidden" name="mitra" value="<?= htmlspecialchars($mitra) ?>">

                            <div class="mb-3">
                                <label class="form-label" for="usulan_anggaran"></label>
                                <input type="hidden" step="0.01" name="usulan_anggaran" id="usulan_anggaran" class="form-control form-control-lg" value="<?= htmlspecialchars($usulan_anggaran) ?>" readonly required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="realisasi_anggaran">Realisasi Anggaran</label>
                                <input type="number" step="0.01" name="realisasi_anggaran" id="realisasi_anggaran" class="form-control form-control-lg" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="kendala">Kendala</label>
                                <input type="text" class="form-control form-control-lg" name="kendala" id="kendala" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="solusi">Solusi</label>
                                <input type="text" class="form-control form-control-lg" name="solusi" id="solusi">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="keterangan">Keterangan</label>
                                <input type="text" class="form-control form-control-lg" name="keterangan" id="keterangan" required>
                            </div>

                            <div class="text-center pt-3">
                                <button type="submit" class="btn btn-success btn-lg px-5">Simpan Realisasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('#realisasiFormSubmit').on('submit', function(e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('' + response.message);
                            window.location.href = '../admin/realisasi.php';
                        } else {
                            alert('Gagal simpan data: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan saat menyimpan data.');
                        console.error("AJAX Error:", status, error, xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>

</html>

<?php
$content = ob_get_clean();
$title = "Form Rencana Realisasi";
include "layout.php";
?>