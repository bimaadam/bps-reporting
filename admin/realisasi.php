<?php
include "service/database.php";

if (isset($_POST['simpan'])) {
    $kode_program = $_POST['kode_program'];
    $kode_kegiatan = $_POST['kode_kegiatan'];
    $kode_kro = $_POST['kode_kro'];
    $kode_ro = $_POST['kode_ro'];
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $nama_aktivitas = $_POST['nama_aktivitas'];
    $organik = $_POST['organik'];
    $mitra = $_POST['mitra'];
    $usulan_anggaran = $_POST['usulan_anggaran'];

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
        echo "<script>alert('✅ Data berhasil disimpan!'); window.location.href='input_realisasi.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal simpan data: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<?php include("header.php"); ?>
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Input Realisasi</h1>

    <!-- Form Aktivitas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Realisai</h6>
        </div>
        <div class="card-body">
            <form id="aktivitasForm" method="POST">
                <div class="form-group">
                    <label for="kode_program">Program</label>
                    <select id="kode_program" name="kode_program" class="form-control" required>
                        <option value="" disabled selected>Pilih Program</option>
                        <?php 
                        $query = mysqli_query($koneksi, "SELECT * FROM tbl_rencana_anggaran");
                        while ($data = mysqli_fetch_array($query)) {
                            echo "<option value='{$data['kode_program']}'>{$data['kode_program']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="kode_kegiatan">Kegiatan</label>
                    <select id="kode_kegiatan" name="kode_kegiatan" class="form-control" required>
                        <option value="" disabled selected>Pilih Kegiatan</option>
                    </select>
                </div>

                <button type="button" id="okeBtn" class="btn btn-primary">OK</button>
                <button type="reset" class="btn btn-secondary">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Tabel Kegiatan -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Realisasi Terpilih</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="aktivitasTable">
                <tbody>
                    <tr id="noDataRow">
                        <td colspan="4" class="text-center">Belum ada data</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<form method="POST" action="input_realisasi.php">
  <input type="hidden" name="kode_program" value="3201">
  <input type="hidden" name="kode_kegiatan" value="320101">
  <input type="hidden" name="kode_kro" value="32010101">
  <input type="hidden" name="kode_ro" value="3201010101">
  <input type="hidden" name="nama_kegiatan" value="Kegiatan Edukasi Warga">
  <input type="hidden" name="nama_aktivitas" value="Penyuluhan Kesehatan">
  <input type="hidden" name="organik" value="12">
  <input type="hidden" name="mitra" value="8">
  <input type="hidden" name="usulan_anggaran" value="20000000">
</form>


<!-- jQuery harus sebelum Bootstrap -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>

<!-- AJAX Logic -->
<script>
$(document).ready(function () {

    function loadTable(page = 1) {
        const kode_program = $('#kode_program').val();
        const kode_kegiatan = $('#kode_kegiatan').val();

        if (!kode_program || !kode_kegiatan) {
            alert("Silakan pilih program dan kegiatan!");
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
            success: function (response) {
                $('#aktivitasTable tbody').html(response);
            }
        });
    }

    $('#kode_program').change(function () {
        const kode_program = $(this).val();
        $.ajax({
            url: 'api/get_kegiatan_rencana.php',
            method: 'POST',
            data: { kode_program },
            success: function (response) {
                $('#kode_kegiatan').html(response);
            }
        });
    });

    $('#okeBtn').on('click', function () {
        loadTable();
    });

    $(document).on('click', '.pagination-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadTable(page);
    });

    $(document).on('click', '.tambahBtn', function () {
    const data = {
        kode_program: $(this).data('kode_program'),
        kode_kegiatan: $(this).data('kode_kegiatan'),
        kode_kro: $(this).data('kode_kro'),
        kode_ro: $(this).data('kode_ro'),
        nama_kegiatan: $(this).data('nama_kegiatan'),
        nama_aktivitas: $(this).data('nama_aktivitas'),
        organik: $(this).data('organik'),
        mitra: $(this).data('mitra'),
        usulan_anggaran: $(this).data('usulan_anggaran')
    };

const params = new URLSearchParams(data).toString();
window.location.href = `input_realisasi.php?${params}`;


    $.ajax({
        url: 'input_realisasi.php',
        type: 'POST',
        data: data,
        success: function () {
            alert('✅ Data berhasil dimasukkan');
            loadTable(); // reload isi tabel realisasi
        },
        error: function () {
            alert('❌ Gagal menambahkan data');
        }
    });
});


    $(document).on('click', '.editBtn', function () {
        const id = $(this).data('id');
        alert('Edit data dengan ID: ' + id);
    });

    $(document).on('click', '.deleteBtn', function () {
        const id = $(this).data('id');
        if (confirm('Yakin mau hapus data ini?')) {
            $.ajax({
                url: 'delete_ro.php',
                method: 'POST',
                data: { id },
                success: function (response) {
                    alert(response);
                    loadTable();
                }
            });
        }
    });

});
</script>
