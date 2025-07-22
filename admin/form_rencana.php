<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "service/koneksi.php";
$tahun              = $_GET['tahun'] ?? date('Y');
$bulan              = $_GET['bulan'] ?? date('m');
$kode_program       = $_GET['kode_program'] ?? '';
$kode_kegiatan      = $_GET['kode_kegiatan'] ?? '';
$kode_kro           = $_GET['kode_kro'] ?? '';
$kode_ro            = $_GET['kode_ro'] ?? '';
$uraian_ro          = $_GET['uraian_ro'] ?? '';
$nama_kegiatan_param = $_GET['nama_kegiatan_param'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tahun_post         = $_POST['tahun_kegiatan'];
    $tanggal_bulan = $tahun_post . '-' . str_pad($bulan_post, 2, '0', STR_PAD_LEFT) . '-01';
    $kode_program_post  = $_POST['kode_program'];
    $kode_kegiatan_post = $_POST['kode_kegiatan'];
    $kode_kro_post      = $_POST['kode_kro'];
    $kode_ro_post       = $_POST['kode_ro'];

    $nama_kegiatan    = $_POST['nama_kegiatan'];
    $nama_aktivitas   = $_POST['nama_aktivitas'];
    $organik          = (int)$_POST['organik'];
    $mitra            = (int)$_POST['mitra'];
    $usulan_anggaran  = (float)$_POST['usulan_anggaran'];
    $stmt = $koneksi->prepare("
    INSERT INTO tbl_rencana_anggaran
    (tahun_kegiatan, bulan_kegiatan, kode_program, kode_kegiatan, kode_kro, kode_ro, nama_kegiatan, nama_aktivitas, organik, mitra, usulan_anggaran)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

    $stmt->bind_param(
        "isssssssiid",
        $tahun_post,
        $tanggal_bulan,
        $kode_program_post,
        $kode_kegiatan_post,
        $kode_kro_post,
        $kode_ro_post,
        $nama_kegiatan,
        $nama_aktivitas,
        $organik,
        $mitra,
        $usulan_anggaran
    );


    if ($stmt->execute()) {
        $_SESSION['form_message'] = 'Data rencana berhasil disimpan!';
        echo "<script>window.location.href='rencana.php';</script>";
        $stmt->close();
        exit();
    } else {
        $_SESSION['form_message'] = ' Gagal menyimpan data: ' . $stmt->error;
        echo "<script>window.location.href='rencana.php';</script>";
        $stmt->close();
        exit();
    }
}

ob_start(); ?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Input Rencana Kegiatan</h1>
    </div>

    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">Form Rencana Anggaran</h6>
        </div>

        <div class="card-body">
            <form id="rencanaForm" method="POST" action="">
                <input type="hidden" name="tahun_kegiatan" value="<?= htmlspecialchars($tahun) ?>">
                <input type="hidden" name="bulan_kegiatan" value="<?= htmlspecialchars($bulan) ?>">
                <input type="hidden" name="kode_program" value="<?= htmlspecialchars($kode_program) ?>">
                <input type="hidden" name="kode_kegiatan" value="<?= htmlspecialchars($kode_kegiatan) ?>">
                <input type="hidden" name="kode_kro" value="<?= htmlspecialchars($kode_kro) ?>">
                <input type="hidden" name="kode_ro" value="<?= htmlspecialchars($kode_ro) ?>">

                <div class="mb-3">
                    <label class="form-label">Tahun Kegiatan</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($tahun) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bulan Kegiatan</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($bulan) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kode Program</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($kode_program) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kode Kegiatan</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($kode_kegiatan) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kode KRO</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($kode_kro) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kode RO</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($kode_ro) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control form-control-lg" value="<?= htmlspecialchars($nama_kegiatan_param) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Aktivitas (Uraian RO)</label>
                    <input type="text" name="nama_aktivitas" id="nama_aktivitas" class="form-control form-control-lg" value="<?= htmlspecialchars($uraian_ro) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Organik</label>
                    <input type="number" name="organik" id="organik" class="form-control form-control-lg" value="0" required min="0">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mitra</label>
                    <input type="number" name="mitra" id="mitra" class="form-control form-control-lg" value="0" required min="0">
                </div>

                <div class="mb-3">
                    <label class="form-label">Usulan Anggaran</label>
                    <input type="number" step="0.01" name="usulan_anggaran" id="usulan_anggaran" class="form-control form-control-lg" value="0.00" required min="0">
                </div>

                <div class="text-center pt-3">
                    <button type="submit" class="btn btn-success btn-lg px-5">Simpan</button>
                    <button type="button" class="btn btn-secondary btn-lg px-5" onclick="history.back()">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = "Form Rencana Anggaran";
include "layout.php"; ?>