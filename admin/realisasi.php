<?php
include "service/database.php";

if (isset($_POST['oke'])) {
    $kode_program = mysqli_real_escape_string($koneksi, $_POST['kode_program']);
    $kode_kegiatan = mysqli_real_escape_string($koneksi, $_POST['kode_kegiatan']);

    $query = "INSERT INTO tbl_aktivitas (kode_program, kode_kegiatan) VALUES (?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $kode_program, $kode_kegiatan);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Data berhasil disimpan!'); window.location.href='input_aktivitas.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data: " . mysqli_error($koneksi) . "');</script>";
    }

    mysqli_stmt_close($stmt);
}
?>

<?php include("header.php"); ?>
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Input Aktivitas</h1>

    <!-- Form Aktivitas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Aktivitas</h6>
        </div>
        <div class="card-body">
            <form id="aktivitasForm" method="POST">
                <div class="form-group">
                    <label for="kode_program">Program</label>
                    <select id="kode_program" name="kode_program" class="form-control" required>
                        <option value="" disabled selected>Pilih Program</option>
                        <?php 
                        $query = mysqli_query($koneksi, "SELECT * FROM tbl_program1");
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
            <h6 class="m-0 font-weight-bold text-primary">Kegiatan Terpilih</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="aktivitasTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>RO</th>
                        <th>Uraian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="noDataRow">
                        <td colspan="4" class="text-center">Belum ada data</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Data</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php include "modal_form_rencana.php"; ?>
            </div>
        </div>
    </div>
</div>

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
            url: 'api/get_ro.php',
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
            url: 'api/get_kegiatan.php',
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
        const id = $(this).data('id');
        $('#tambahModal').modal('show');
        $('#tambahModalLabel').text('Tambah Data untuk ID: ' + id);
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
