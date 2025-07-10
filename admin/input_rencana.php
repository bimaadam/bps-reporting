<?php
include "service/database.php";


?>
<!DOCTYPE html>
<html lang="en">

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include("header.php"); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Tambah Rencana Kegiatan</h1>
                    <form action="input_rencana.php" method="POST">
                        <div class="mb-3">
                            <label for="kode_program" class="form-label">Kode Program</label>
                            <input type="text" class="form-control" id="kode_program" name="kode_program" placeholder="Masukkan Kode Program" required>
                        </div>
                        <div class="mb-3">
                            <label for="kode_kegiatan" class="form-label">Kode kegiatan</label>
                            <input type="text" class="form-control" id="kode_kegiatan" name="kode_kegiatan" placeholder="Masukkan kode kegiatan" required>
                        </div>
                        <div class="mb-3">
                            <label for="kode_kro" class="form-label">Kode KRO</label>
                            <input type="text" class="form-control" id="kode_kro" name="kode_kro" placeholder="Masukkan kode kro" required>
                        </div>
                        <div class="mb-3">
                            <label for="kode_ro" class="form-label">Kode RO</label>
                            <input type="text" class="form-control" id="kode_ro" name="kode_ro" placeholder="Masukkan kode ro" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                            <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" placeholder="Masukkan Nama kegiatan" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_aktivitas" class="form-label">Nama Aktivitas</label>
                            <select id="nama_aktivitas" name="nama_aktivitas" class="form-control" required>
                                <option value="" disabled selected>Pilih aktivitas</option>
                                <?php
                                $query = mysqli_query($koneksi, "SELECT * FROM aktivitas");
                                while ($data = mysqli_fetch_array($query)) {
                                    echo "<option value='{$data['nama_aktivitas']}'>{$data['nama_aktivitas']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="uraian_program" class="form-label">Jumlah Pegawai Organik</label>
                            <input type="text" class="form-control" id="organik" name="organik" placeholder="Masukkan Jumlah Pegawai" required>
                        </div>
                        <div class="mb-3">
                            <label for="uraian_program" class="form-label">Jumlah Pegawai Mitra</label>
                            <input type="text" class="form-control" id="mitra" name="mitra" placeholder="Masukkan Jumlah Pegawai" required>
                        </div>
                        <div class="mb-3">
                            <label for="uraian_program" class="form-label">Usulan Anggaran</label>
                            <input type="text" class="form-control" id="usulan_anggaran" name="usulan_anggaran" placeholder="Masukkan usulan anggaran" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary mb-3" name="simpan">Simpan</button>
                        </div>
                    </form>



                </div>
                <!-- End of Main Content -->

                <?php include("footer.php"); ?>

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

</body>

</html>