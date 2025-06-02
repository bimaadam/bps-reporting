 <!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Input Program - BPS</title>

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
 <div class="container-fluid">
 <div class="form-container">
                <h2>Tambah Kegiatan</h2>
                <form action="input_kegiatan.php" method="POST">
                    <div class="mb-3 m-0">
                        <label for="kode_program" class="form-label">Kode Program</label>
                        <input type="text" class="form-control" id="kode_program" name="kode_program" placeholder="Masukkan Kode Program" required>
                    </div>
                    <div class="mb-3">
                        <label for="kode_kegiatan" class="form-label">Kode Kegiatan</label>
                        <input type="text" class="form-control" id="kode_kegiatan" name="kode_kegiatan" placeholder="Masukkan Kode Kegiatan" required>
                    </div>
                    <div class="mb-3">
                        <label for="uraian_kegiatan" class="form-label">Uraian Kegiatan</label>
                        <input type="text" class="form-control" id="uraian_kegiatan" name="uraian_kegiatan" placeholder="Masukkan Uraian Kegiatan" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                    </div>
                </form>
            </div>
            <div class="table-container">
                <div class="table-container">
    <h3 class="m-3">List Kegiatan</h3>
    <table class="table table-bordered" id="aktivitasTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Program</th>
                <th>Kode Kegiatan</th>
                <th>Uraian Kegiatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
         <tbody>
            <?php
            include "service/database.php";
            $no = 1;
            $query = "SELECT kode_program, kode_kegiatan, uraian_kegiatan FROM tbl_kegiatan1 ORDER BY kode_program ASC";
            $result = $koneksi->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$no++."</td>";
                    echo "<td>".$row['kode_program']."</td>";
                    echo "<td>".$row['kode_kegiatan']."</td>";
                    echo "<td>".$row['uraian_kegiatan']."</td>";
                    echo "<td>
                            <a href='edit_kegiatan.php?kode_kegiatan=".$row['kode_kegiatan']."' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='hapus_kegiatan.php?kode_kegiatan=".$row['kode_kegiatan']."' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus data ini?');\">Hapus</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Belum ada data</td></tr>";
            }
            ?>
        </tbody>
        </table>
        </div>