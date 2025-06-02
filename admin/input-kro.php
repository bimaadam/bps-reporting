<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Tambah KRO - BPS</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>


<?php include("header.php"); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
     <div>
        <h1 class="h3 mb-2 text-gray-800">Tambah KRO</h1>
        <p class="mb-4">Silahkan isi data KRO baru pada form di bawah ini.</p>
    <div class="card p-4">
        <form action="input_kro.php" method="POST">
            <div class="mb-3">
                <label for="kode_program" class="form-label">Kode Program</label>
                <input type="text" class="form-control" id="kode_program" name="kode_program" required>
            </div>
            <div class="mb-3">
                <label for="kode_kegiatan" class="form-label">Kode Kegiatan</label>
                <input type="text" class="form-control" id="kode_kegiatan" name="kode_kegiatan" required>
            </div>
            <div class="mb-3">
                <label for="kode_kro" class="form-label">Kode KRO</label>
                <input type="text" class="form-control" id="kode_kro" name="kode_kro" required>
            </div>
            <div class="mb-3">
                <label for="uraian_kro" class="form-label">Uraian KRO</label>
                <input type="text" class="form-control" id="uraian_kro" name="uraian_kro" required>
            </div>
            <div class="mb-3">
                <label for="kro_2" class="form-label">KRO 2</label>
                <input type="text" class="form-control" id="kro_2" name="kro_2" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
            </div>
        </form>
    </div>
    </div>
    <div class="table-container">
               <div class="table-container mt-4">
    <h3>List KRO</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Program</th>
                <th>Kode Kegiatan</th>
                <th>Kode KRO</th>
                <th>Uraian KRO</th>
                <th>KRO 2</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "service/database.php";
            $no = 1;
            $query = "SELECT * FROM tbl_kro1 ORDER BY kode_program ASC";
            $result = $koneksi->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$no++."</td>";
                    echo "<td>".$row['kode_program']."</td>";
                    echo "<td>".$row['kode_kegiatan']."</td>";
                    echo "<td>".$row['kode_kro']."</td>";
                    echo "<td>".$row['uraian_kro']."</td>";
                    echo "<td>".$row['kro_2']."</td>";
                    echo "<td>
                            <a href='edit_kro.php?kode_kro=".$row['kode_kro']."' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='hapus_kro.php?kode_kro=".$row['kode_kro']."' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus data ini?');\">Hapus</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>Belum ada data</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>