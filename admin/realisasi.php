<?php

include "service/database.php";

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kode_program_ajax_submit'])) {

    $tahun_kegiatan_raw  = $_POST['tahun_kegiatan'] ?? '';
    $bulan_kegiatan_raw  = $_POST['bulan_kegiatan'] ?? '';
    $kode_program_raw    = $_POST['kode_program'] ?? '';
    $kode_kegiatan_raw   = $_POST['kode_kegiatan'] ?? '';
    $kode_kro_raw        = $_POST['kode_kro'] ?? '';
    $kode_ro_raw         = $_POST['kode_ro'] ?? '';
    $nama_kegiatan_raw   = $_POST['nama_kegiatan'] ?? '';
    $nama_aktivitas_raw  = $_POST['nama_aktivitas'] ?? '';
    $organik_raw         = $_POST['organik'] ?? '';
    $mitra_raw           = $_POST['mitra'] ?? '';
    $usulan_anggaran_raw = $_POST['usulan_anggaran'] ?? '';

    $bulan_realisasi_raw = $bulan_kegiatan_raw;

    $tahun_kegiatan  = mysqli_real_escape_string($koneksi, $tahun_kegiatan_raw);
    $bulan_kegiatan  = mysqli_real_escape_string($koneksi, $bulan_kegiatan_raw);
    $bulan_realisasi = mysqli_real_escape_string($koneksi, $bulan_realisasi_raw);
    $kode_program    = mysqli_real_escape_string($koneksi, $kode_program_raw);
    $kode_kegiatan   = mysqli_real_escape_string($koneksi, $kode_kegiatan_raw);
    $kode_kro        = mysqli_real_escape_string($koneksi, $kode_kro_raw);
    $kode_ro         = mysqli_real_escape_string($koneksi, $kode_ro_raw);
    $nama_kegiatan   = mysqli_real_escape_string($koneksi, $nama_kegiatan_raw);
    $nama_aktivitas  = mysqli_real_escape_string($koneksi, $nama_aktivitas_raw);
    $organik         = mysqli_real_escape_string($koneksi, $organik_raw);
    $mitra           = mysqli_real_escape_string($koneksi, $mitra_raw);
    $usulan_anggaran = mysqli_real_escape_string($koneksi, $usulan_anggaran_raw);


    $query = "INSERT INTO tbl_realisasi_anggaran (
        tahun_kegiatan, bulan_kegiatan, bulan_realisasi,
        kode_program, kode_kegiatan, kode_kro, kode_ro,
        nama_kegiatan, nama_aktivitas,
        organik, mitra, usulan_anggaran
    ) VALUES (
        '$tahun_kegiatan', '$bulan_kegiatan', '$bulan_realisasi',
        '$kode_program', '$kode_kegiatan', '$kode_kro', '$kode_ro',
        '$nama_kegiatan', '$nama_aktivitas',
        '$organik', '$mitra', '$usulan_anggaran'
    )";

    if (mysqli_query($koneksi, $query)) {
        echo "✅ Data berhasil disimpan!";
    } else {
        echo "❌ Gagal simpan data: " . mysqli_error($koneksi);
    }
    exit();
}

$tahun_kegiatan_get = $_GET['tahun_kegiatan'] ?? date('Y');
$bulan_kegiatan_get = $_GET['bulan_kegiatan'] ?? date('m');
$kode_kegiatan_get  = $_GET['kode_kegiatan'] ?? '';
$kode_kro_get       = $_GET['kode_kro'] ?? '';
$kode_ro_get        = $_GET['kode_ro'] ?? '';
$nama_kegiatan_get  = $_GET['nama_kegiatan'] ?? '';
$nama_aktivitas_get = $_GET['nama_aktivitas'] ?? '';
$organik_get        = $_GET['organik'] ?? '';
$mitra_get          = $_GET['mitra'] ?? '';
$usulan_anggaran_get = $_GET['usulan_anggaran'] ?? '';

ob_start();
?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Input Realisasi Kegiatan</h1>
    </div>

    <p class="mb-4 text-gray-700">Pilih Program dan Kegiatan untuk melihat Rincian Output (RO) terkait dan mulai menyusun realisasi kegiatan.</p>

    <?php if ($message) : ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-5">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Pilih Program dan Kegiatan</h6>
        </div>
        <div class="card-body">
            <form id="realisasiForm" method="POST">
                <div class="form-group row mb-3">
                    <label for="kode_program" class="col-sm-3 col-form-label">Program</label>
                    <div class="col-sm-9">
                        <select id="kode_program" name="kode_program" class="form-control" required>
                            <option value="" disabled selected>Pilih Program</option>
                            <?php
                            $query_program = mysqli_query($koneksi, "SELECT kode_program, uraian_program FROM tbl_program1 ORDER BY kode_program ASC");
                            if ($query_program && mysqli_num_rows($query_program) > 0) {
                                while ($data_program = mysqli_fetch_array($query_program)) {
                                    echo "<option value='" . htmlspecialchars($data_program['kode_program']) . "'>" . htmlspecialchars($data_program['kode_program']) . " - " . htmlspecialchars($data_program['uraian_program']) . "</option>";
                                }
                            } else {
                                echo "<option value=''>Tidak ada data program</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label for="kode_kegiatan" class="col-sm-3 col-form-label">Kegiatan</label>
                    <div class="col-sm-9">
                        <select id="kode_kegiatan" name="kode_kegiatan" class="form-control" required>
                            <option value="" disabled selected>Pilih Kegiatan</option>
                        </select>
                    </div>
                </div>

                <input type="hidden" name="tahun_kegiatan" value="<?= htmlspecialchars($tahun_kegiatan_get) ?>">
                <input type="hidden" name="bulan_kegiatan" value="<?= htmlspecialchars($bulan_kegiatan_get) ?>">
                <input type="hidden" name="kode_kegiatan" value="<?= htmlspecialchars($kode_kegiatan_get) ?>">
                <input type="hidden" name="kode_kro" value="<?= htmlspecialchars($kode_kro_get) ?>">
                <input type="hidden" name="kode_ro" value="<?= htmlspecialchars($kode_ro_get) ?>">
                <input type="hidden" name="nama_kegiatan" value="<?= htmlspecialchars($nama_kegiatan_get) ?>">
                <input type="hidden" name="nama_aktivitas" value="<?= htmlspecialchars($nama_aktivitas_get) ?>">
                <input type="hidden" name="organik" value="<?= htmlspecialchars($organik_get) ?>">
                <input type="hidden" name="mitra" value="<?= htmlspecialchars($mitra_get) ?>">
                <input type="hidden" name="usulan_anggaran" value="<?= htmlspecialchars($usulan_anggaran_get) ?>">

                <div class="d-flex justify-content-end">
                    <button type="button" id="okeBtn" class="btn btn-primary mr-2"><i class="fas fa-check"></i> OK</button>
                    <button type="reset" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-clipboard-list"></i> Data Rencana Kegiatan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="aktivitasTable" width="100%" cellspacing="0">
                    <tbody>
                        <tr id="noDataRow">
                            <td colspan="12" class="text-center">Silakan pilih Program dan Kegiatan, lalu klik OK.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = "Input Realisasi";
include "layout.php";
?>

<script>
    $(document).ready(function() {

        function loadTable(page = 1) {
            const kode_program = $('#kode_program').val();
            const kode_kegiatan = $('#kode_kegiatan').val();

            if (!kode_program || !kode_kegiatan) {
                $('#aktivitasTable tbody').html('<tr><td colspan="12" class="text-center">Silakan pilih Program dan Kegiatan, lalu klik OK.</td></tr>');
                return;
            }

            $.ajax({
                url: 'api/get_rencana.php',
                method: 'POST',
                data: {
                    kode_program: kode_program,
                    kode_kegiatan: kode_kegiatan,
                    page: page
                },
                success: function(response) {
                    $('#aktivitasTable tbody').html(response);
                    if ($('#aktivitasTable tbody').children().length === 0) {
                        $('#aktivitasTable tbody').html('<tr><td colspan="12" class="text-center">Tidak ada data rencana untuk pilihan ini.</td></tr>');
                    }
                },
                error: function() {
                    $('#aktivitasTable tbody').html('<tr><td colspan="12" class="text-center text-danger">Gagal memuat data.</td></tr>');
                }
            });
        }

        $('#kode_program').change(function() {
            const kode_program = $(this).val();
            $.ajax({
                url: 'api/get_kegiatan.php',
                method: 'POST',
                data: {
                    kode_program: kode_program
                },
                success: function(response) {
                    $('#kode_kegiatan').html(response);
                    $('#kode_kegiatan').val('').trigger('change');

                    $('#aktivitasTable tbody').html('<tr><td colspan="12" class="text-center">Silakan pilih Kegiatan, lalu klik OK.</td></tr>');
                },
                error: function(xhr, status, error) {
                    $('#kode_kegiatan').html('<option value="">Error memuat kegiatan</option>');
                    console.error("Error fetching kegiatan:", status, error, xhr.responseText);
                }
            });
        });

        $('#okeBtn').on('click', function() {
            loadTable();
        });

        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            loadTable(page);
        });

        $(document).on('click', '.tambahBtn', function() {

            const data = {
                tahun_kegiatan: $(this).data('tahun-kegiatan'),
                bulan_kegiatan: $(this).data('bulan-kegiatan'),
                kode_program: $(this).data('kode-program'),
                kode_kegiatan: $(this).data('kode-kegiatan'),
                kode_kro: $(this).data('kode-kro'),
                kode_ro: $(this).data('kode-ro'),
                nama_kegiatan: $(this).data('nama-kegiatan'),
                nama_aktivitas: $(this).data('nama-aktivitas'),
                organik: $(this).data('organik'),
                mitra: $(this).data('mitra'),
                usulan_anggaran: $(this).data('usulan-anggaran')
            };


            const queryParams = new URLSearchParams(data).toString();
            window.location.href = `input_realisasi.php?${queryParams}`;
        });


        $(document).on('click', '.editBtn', function() {
            const id = $(this).data('id');
            alert('Edit data dengan ID: ' + id);

        });

        $(document).on('click', '.deleteBtn', function() {
            const id = $(this).data('id');
            if (confirm('Yakin mau hapus data ini?')) {
                $.ajax({
                    url: 'hapus_data_rencana.php',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        alert(response);
                        loadTable()
                    },
                    error: function() {
                        alert('Gagal menghapus data.');
                    }
                });
            }
        });

    });
</script>