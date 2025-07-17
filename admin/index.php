<?php
require 'api/auth.php';
require 'service/database.php';

ob_start();

// Error reporting untuk development
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Inisialisasi filter
$tahun_filter = isset($_GET['tahun']) ? (int)$_GET['tahun'] : null;
$bulan_filter = isset($_GET['bulan']) ? (int)$_GET['bulan'] : null;

// Query untuk data realisasi dengan prepared statement
$query = "SELECT 
            tahun_kegiatan,
            bulan_kegiatan,
            bulan_realisasi,
            kode_program,
            kode_kegiatan,
            kode_kro,
            kode_ro,
            nama_kegiatan,
            nama_aktivitas,
            organik,
            mitra,
            usulan_anggaran,
            realisasi_anggaran,
            kendala,
            solusi,
            keterangan
          FROM tbl_realisasi_anggaran WHERE 1=1";

$conditions = [];
$types = '';
$params = [];

if ($tahun_filter) {
  $conditions[] = "tahun_kegiatan = ?";
  $types .= 'i';
  $params[] = $tahun_filter;
}

if ($bulan_filter) {
  $conditions[] = "bulan_kegiatan = ?";
  $types .= 'i';
  $params[] = $bulan_filter;
}

if (!empty($conditions)) {
  $query .= " AND " . implode(" AND ", $conditions);
}

$query .= " ORDER BY tahun_kegiatan DESC, bulan_kegiatan DESC LIMIT 10";

$stmt = mysqli_prepare($koneksi, $query);

if ($stmt) {
  if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
  }
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
} else {
  die("Error in prepared statement: " . mysqli_error($conn));
}

// Hitung total anggaran dan realisasi
$query_totals = "SELECT 
    SUM(usulan_anggaran) as total_anggaran,
    SUM(realisasi_anggaran) as total_realisasi,
    SUM(CASE WHEN organik = 1 THEN realisasi_anggaran ELSE 0 END) as organik,
    SUM(CASE WHEN mitra = 1 THEN realisasi_anggaran ELSE 0 END) as mitra,
    SUM(CASE WHEN organik = 0 AND mitra = 0 THEN realisasi_anggaran ELSE 0 END) as lain
    FROM tbl_realisasi_anggaran";

$result_totals = mysqli_query($koneksi, $query_totals);
$row_totals = mysqli_fetch_assoc($result_totals);

$total_anggaran = $row_totals['total_anggaran'] ?? 0;
$total_realisasi = $row_totals['total_realisasi'] ?? 0;
$persentase = ($total_anggaran > 0) ? ($total_realisasi / $total_anggaran) * 100 : 0;
$sisa_anggaran = $total_anggaran - $total_realisasi;

// Data untuk chart
$organik = $row_totals['organik'] ?? 0;
$mitra = $row_totals['mitra'] ?? 0;
$lain = $row_totals['lain'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="Dashboard Sistem Informasi Anggaran" />
  <meta name="author" content="Your Name" />

  <title>Admin - Dashboard</title>

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet" />

  <link href="css/sb-admin-2.min.css" rel="stylesheet" />
</head>

<body id="page-top">
  <!-- Bagian wrapper dan header tetap sama -->

  <div class="container-fluid">
    <!-- Bagian cards dashboard -->
    <div class="row">
      <!-- Card Total Usulan Anggaran -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  Total Usulan Anggaran</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_anggaran, 0, ',', '.') ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Card lainnya tetap sama -->
    </div>

    <!-- Grafik -->
    <!-- <div class="row">
      <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Realisasi Anggaran per Bulan</h6>
          </div>
          <div class="card-body">
            <canvas id="myAreaChart"></canvas>
          </div>
        </div>
      </div> -->

    <!-- <div class="col-xl-4 col-lg-5">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Realisasi per Kategori</h6>
        </div>
        <div class="card-body">
          <canvas id="myPieChart"></canvas>
          <div class="mt-4 text-center small">
            <span class="mr-2">
              <i class="fas fa-circle text-primary"></i> Organik (Rp <?= number_format($organik, 0, ',', '.') ?>)
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-success"></i> Mitra (Rp <?= number_format($mitra, 0, ',', '.') ?>)
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-info"></i> Lain-lain (Rp <?= number_format($lain, 0, ',', '.') ?>)
            </span>
          </div>
        </div>
      </div>
    </div>
  </div> -->

    <!-- Tabel Realisasi -->
    <div class="row">
      <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">10 Realisasi Terbaru</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <form method="GET" class="form-inline mb-3">
                <select name="tahun" class="form-control mr-2">
                  <option value="">-- Tahun --</option>
                  <?php for ($y = 2020; $y <= date('Y'); $y++): ?>
                    <option value="<?= $y ?>" <?= $tahun_filter == $y ? 'selected' : '' ?>><?= $y ?></option>
                  <?php endfor; ?>
                </select>

                <select name="bulan" class="form-control mr-2">
                  <option value="">-- Bulan --</option>
                  <?php for ($b = 1; $b <= 12; $b++): ?>
                    <option value="<?= $b ?>" <?= $bulan_filter == $b ? 'selected' : '' ?>>
                      <?= date("F", mktime(0, 0, 0, $b, 10)) ?>
                    </option>
                  <?php endfor; ?>
                </select>

                <button type="submit" class="btn btn-primary">Filter</button>
              </form>

              <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>Program</th>
                    <th>Kegiatan</th>
                    <th>KRO</th>
                    <th>RO</th>
                    <th>Nama Kegiatan</th>
                    <th>Organik</th>
                    <th>Mitra</th>
                    <th>Usulan</th>
                    <th>Realisasi</th>
                    <th>Kendala</th>
                    <th>Solusi</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                      <tr>
                        <td><?= htmlspecialchars($row['tahun_kegiatan']) ?></td>
                        <?php
                        $bulan = (int)$row['bulan_kegiatan'];
                        if ($bulan >= 1 && $bulan <= 12) {
                          echo date("F", mktime(0, 0, 0, $bulan, 10));
                        } else {
                          echo 'Invalid Month';
                        }
                        ?>
                        <td><?= htmlspecialchars($row['kode_program']) ?></td>
                        <td><?= htmlspecialchars($row['kode_kegiatan']) ?></td>
                        <td><?= htmlspecialchars($row['kode_kro']) ?></td>
                        <td><?= htmlspecialchars($row['kode_ro']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kegiatan']) ?></td>
                        <td><?= $row['organik'] ? 'Ya' : 'Tidak' ?></td>
                        <td><?= $row['mitra'] ? 'Ya' : 'Tidak' ?></td>
                        <td>Rp <?= number_format($row['usulan_anggaran'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($row['realisasi_anggaran'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row['kendala']) ?></td>
                        <td><?= htmlspecialchars($row['solusi']) ?></td>
                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="14" class="text-center">Tidak ada data realisasi</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.min.js"></script>
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
</body>

</html>
<?php
$content = ob_get_clean();
$title = "Dashboard";
include "layout.php";
?>