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
<div class="content">
        <h2 class="text-center mb-4">Tambah RO</h2>
        <?php if(isset($message)) echo $message; ?>
        <div class="card p-4">
            <form action="input_ro.php" method="POST">
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
                    <label for="kode_ro" class="form-label">Kode RO</label>
                    <input type="text" class="form-control" id="kode_ro" name="kode_ro" required>
                </div>
                <div class="mb-3">
                    <label for="uraian_ro" class="form-label">Uraian RO</label>
                    <input type="text" class="form-control" id="uraian_ro" name="uraian_ro" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                </div>
            </form>
        </div>
        <div class="table-container m-3">
    <h3>List RO</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Program</th>
                <th>Kode Kegiatan</th>
                <th>Kode KRO</th>
                <th>Kode RO</th>
                <th>Uraian RO</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
include "service/database.php";

$limit = 25; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Ambil total data
$totalQuery = "SELECT COUNT(*) as total FROM tbl_ro1";
$totalResult = $koneksi->query($totalQuery);
$totalData = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalData / $limit);

// Ambil data untuk halaman saat ini
$query = "SELECT * FROM tbl_ro1 ORDER BY kode_program ASC LIMIT $start, $limit";
$result = $koneksi->query($query);
$no = $start + 1;

            if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$no++."</td>";
            echo "<td>".$row['kode_program']."</td>";
            echo "<td>".$row['kode_kegiatan']."</td>";
            echo "<td>".$row['kode_kro']."</td>";
            echo "<td>".$row['kode_ro']."</td>";
            echo "<td>".$row['uraian_ro']."</td>";
            echo "<td>
                    <a href='edit_ro.php?kode_ro=".$row['kode_ro']."' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='hapus_ro.php?kode_ro=".$row['kode_ro']."' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus data ini?');\">Hapus</a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7' class='text-center'>Belum ada data</td></tr>";
    }
    ?>
        </tbody>
    </table>
    <nav>
    <ul class="pagination justify-content-center">
        <?php if($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>">Sebelumnya</a>
            </li>
        <?php endif; ?>

        <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Berikutnya</a>
            </li>
        <?php endif; ?>
    </ul>
</nav></tbody>
        <tbody>