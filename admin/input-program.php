<?php 
include "service/database.php";

if (isset($_POST["simpan"])) {
    $kode_program = $_POST["kode_program"];
    $uraian_program = $_POST["uraian_program"];

    $sql = "INSERT INTO tbl_program1 (kode_program, uraian_program) VALUES ('$kode_program', '$uraian_program')";

    if ($koneksi->query($sql)) {
        echo "<script>alert('Data berhasil disimpan!'); window.location.href='input_program.php';</script>";
    } else {
        echo "<script>alert('Data gagal disimpan!');</script>";
    }
}
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
                    <h1 class="h3 mb-2 text-gray-800">Tambah Program</h1>
                    <p class="mb-4">Silahkan Input Program disini.</p>
                    <form action="input_program.php" method="POST">
                        <div class="mb-3">
                            <label for="kode_program" class="form-label">Kode Program</label>
                            <input type="text" class="form-control" id="kode_program" name="kode_program" placeholder="Masukkan Kode Program" required>
                        </div>
                        <div class="mb-3">
                            <label for="uraian_program" class="form-label">Uraian Program</label>
                            <input type="text" class="form-control" id="uraian_program" name="uraian_program" placeholder="Masukkan Uraian Program" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary mb-3" name="simpan">Simpan</button>
                        </div>
                    </form>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Kode Program</th>
                                            <th>Urairan Program</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $query = "SELECT kode_program, uraian_program FROM tbl_program1 ORDER BY kode_program ASC";
                                        $result = $koneksi->query($query);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>{$no}</td>";
                                                echo "<td>{$row['kode_program']}</td>";
                                                echo "<td>{$row['uraian_program']}</td>";
                                                echo "<td>
                                    <a href='edit_program.php?id={$row['kode_program']}' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='hapus_program.php?id={$row['kode_program']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus data ini?');\">Hapus</a>
                                  </td>";
                                                echo "</tr>";
                                                $no++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='4' class='text-center'>Belum ada data</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

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