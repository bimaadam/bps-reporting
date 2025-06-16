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

<link href="css/bootstrap.min.css" rel="stylesheet">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>

<?php include("header.php"); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Input Rencana</h1>

    <div class="row">
        <div class="col-12">
            <!-- Form Aktivitas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Rencana</h6>
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

            <!-- Spacer -->
            <div class="my-4"></div>



            <!-- Tabel Kegiatan -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kegiatan Terpilih</h6>
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

        <!-- AJAX Logic -->
        <script>
            $(document).ready(function() {
                // Handle form submission for Rencana
                $('form#rencanaForm').on('submit', function(e) {
                    e.preventDefault();

                    const formData = $(this).serialize();
                    $.ajax({
                        url: 'api/insert_rencana_detail.php',
                        method: 'POST',
                        data: formData,
                        success: function(res) {
                            alert('Data lengkap berhasil disimpan!');
                            $('#tambahModal').modal('hide');
                        },
                        error: function(xhr) {
                            alert('Gagal simpan: ' + xhr.responseText);
                        }
                    });
                });

                // Load table data
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
                        success: function(response) {
                            $('#aktivitasTable tbody').html(response);
                        }
                    });
                }

                // Load kegiatan based on selected program
                $('#kode_program').change(function() {
                    const kode_program = $(this).val();
                    $.ajax({
                        url: 'api/get_kegiatan.php',
                        method: 'POST',
                        data: {
                            kode_program
                        },
                        success: function(response) {
                            $('#kode_kegiatan').html(response);
                        }
                    });
                });

                // Load table on OK button click
                $('#okeBtn').on('click', function() {
                    loadTable();
                });

                // Handle pagination
                $(document).on('click', '.pagination-link', function(e) {
                    e.preventDefault();
                    const page = $(this).data('page');
                    loadTable(page);
                });

                // Handle adding new data
                $(document).on('click', '.tambahBtn', function() {
                    const btn = $(this);

                    const data = {
                        tahun_kegiatan: new Date().getFullYear(),
                        bulan_kegiatan: new Date().getMonth() + 1,
                        kode_program: btn.data('kode_program'),
                        kode_kegiatan: btn.data('kode_kegiatan'),
                        kode_kro: btn.data('kode_kro'),
                        kode_ro: btn.data('kode_ro')
                    };

                    // Langsung redirect ke form_rencana.php dengan parameter di URL
                    const url = `form_rencana.php?tahun=${data.tahun_kegiatan}&bulan=${data.bulan_kegiatan}&kode_program=${data.kode_program}&kode_kegiatan=${data.kode_kegiatan}&kode_kro=${data.kode_kro}&kode_ro=${data.kode_ro}`;

                    window.location.href = url;


                    $.ajax({
                        url: 'api/insert_rencana_detail.php',
                        method: 'POST',
                        data: data,
                        success: function(res) {
                            console.log("Insert awal:", res);
                        },
                        error: function(xhr) {
                            console.error("Insert error:", xhr.responseText);
                        }
                    });

                    $('#tambahModal').modal('show');
                    $('#tambahModalLabel').text('Tambah Data untuk RO: ' + data.kode_ro);
                    $('#kode_kegiatan').val(data.kode_kegiatan);
                    $('#nama_aktivitas').val(data.kode_ro);
                });

                // Handle edit button click
                $(document).on('click', '.editBtn', function() {
                    const id = $(this).data('id');
                    alert('Edit data dengan ID: ' + id);
                });

                // Handle delete button click
                $(document).on('click', '.deleteBtn', function() {
                    const id = $(this).data('id');
                    if (confirm('Yakin mau hapus data ini?')) {
                        $.ajax({
                            url: 'delete_ro.php',
                            method: 'POST',
                            data: {
                                id
                            },
                            success: function(response) {
                                alert(response);
                                loadTable();
                            }
                        });
                    }
                });
            });
        </script>