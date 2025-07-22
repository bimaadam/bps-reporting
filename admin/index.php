<!-- UI UX BY HIKARI TAKAHASHI SUPPORT FOR YOU GOMME SAN BIMA ADAM -->
<!-- engineerhikari.github.io/portfolio -->

<?php
require 'api/auth.php';
require 'service/koneksi.php';

ob_start();

// Error reporting untuk development
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Eksekusi query utama
if (!empty($params)) {
  $stmt = mysqli_prepare($koneksi, $query);
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  } else {
    die("Error in prepared statement: " . mysqli_error($koneksi));
  }
} else {
  $result = mysqli_query($koneksi, $query);
  if (!$result) {
    die("Error in query: " . mysqli_error($koneksi));
  }
}

// Hitung total anggaran dan realisasi
$query_totals = "SELECT 
    COALESCE(SUM(usulan_anggaran), 0) as total_anggaran,
    COALESCE(SUM(realisasi_anggaran), 0) as total_realisasi,
    COALESCE(SUM(CASE WHEN organik = 1 THEN realisasi_anggaran ELSE 0 END), 0) as organik,
    COALESCE(SUM(CASE WHEN mitra = 1 THEN realisasi_anggaran ELSE 0 END), 0) as mitra,
    COALESCE(SUM(CASE WHEN organik = 0 AND mitra = 0 THEN realisasi_anggaran ELSE 0 END), 0) as lain
    FROM tbl_realisasi_anggaran WHERE 1=1";

if (!empty($conditions)) {
  $query_totals .= " AND " . implode(" AND ", $conditions);
}

if (!empty($params)) {
  $stmt_totals = mysqli_prepare($koneksi, $query_totals);
  if ($stmt_totals) {
    mysqli_stmt_bind_param($stmt_totals, $types, ...$params);
    mysqli_stmt_execute($stmt_totals);
    $result_totals = mysqli_stmt_get_result($stmt_totals);
    $row_totals = mysqli_fetch_assoc($result_totals);
  } else {
    $row_totals = ['total_anggaran' => 0, 'total_realisasi' => 0, 'organik' => 0, 'mitra' => 0, 'lain' => 0];
  }
} else {
  $result_totals = mysqli_query($koneksi, $query_totals);
  if ($result_totals) {
    $row_totals = mysqli_fetch_assoc($result_totals);
  } else {
    $row_totals = ['total_anggaran' => 0, 'total_realisasi' => 0, 'organik' => 0, 'mitra' => 0, 'lain' => 0];
  }
}

$total_anggaran = (int)($row_totals['total_anggaran'] ?? 0);
$total_realisasi = (int)($row_totals['total_realisasi'] ?? 0);
$persentase = ($total_anggaran > 0) ? ($total_realisasi / $total_anggaran) * 100 : 0;
$sisa_anggaran = $total_anggaran - $total_realisasi;

// Data untuk chart
$organik = (int)($row_totals['organik'] ?? 0);
$mitra = (int)($row_totals['mitra'] ?? 0);
$lain = (int)($row_totals['lain'] ?? 0);

// Query untuk data chart bulanan
$current_year = $tahun_filter ?: date('Y');
$query_monthly = "SELECT 
    bulan_kegiatan,
    COALESCE(SUM(usulan_anggaran), 0) as usulan,
    COALESCE(SUM(realisasi_anggaran), 0) as realisasi
    FROM tbl_realisasi_anggaran 
    WHERE tahun_kegiatan = ?
    GROUP BY bulan_kegiatan 
    ORDER BY bulan_kegiatan";

$stmt_monthly = mysqli_prepare($koneksi, $query_monthly);
$monthly_data = [];
if ($stmt_monthly) {
  mysqli_stmt_bind_param($stmt_monthly, 'i', $current_year);
  mysqli_stmt_execute($stmt_monthly);
  $result_monthly = mysqli_stmt_get_result($stmt_monthly);
  while ($row_monthly = mysqli_fetch_assoc($result_monthly)) {
    $monthly_data[] = $row_monthly;
  }
}

// Hitung jumlah karyawan organik dan mitra
$query_employee = "SELECT 
    COUNT(CASE WHEN organik = 1 THEN 1 END) as jumlah_organik,
    COUNT(CASE WHEN mitra = 1 THEN 1 END) as jumlah_mitra
    FROM tbl_realisasi_anggaran WHERE 1=1";

if (!empty($conditions)) {
  $query_employee .= " AND " . implode(" AND ", $conditions);
}

if (!empty($params)) {
  $stmt_employee = mysqli_prepare($koneksi, $query_employee);
  if ($stmt_employee) {
    mysqli_stmt_bind_param($stmt_employee, $types, ...$params);
    mysqli_stmt_execute($stmt_employee);
    $result_employee = mysqli_stmt_get_result($stmt_employee);
    $row_employee = mysqli_fetch_assoc($result_employee);
    $jumlah_organik = (int)($row_employee['jumlah_organik'] ?? 0);
    $jumlah_mitra = (int)($row_employee['jumlah_mitra'] ?? 0);
  } else {
    $jumlah_organik = 0;
    $jumlah_mitra = 0;
  }
} else {
  $result_employee = mysqli_query($koneksi, $query_employee);
  if ($result_employee) {
    $row_employee = mysqli_fetch_assoc($result_employee);
    $jumlah_organik = (int)($row_employee['jumlah_organik'] ?? 0);
    $jumlah_mitra = (int)($row_employee['jumlah_mitra'] ?? 0);
  } else {
    $jumlah_organik = 0;
    $jumlah_mitra = 0;
  }
}

$bulan_nama = [
  'Januari',
  'Februari',
  'Maret',
  'April',
  'Mei',
  'Juni',
  'Juli',
  'Agustus',
  'September',
  'Oktober',
  'November',
  'Desember'
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="Dashboard Sistem Informasi Anggaran" />
  <meta name="author" content="Admin" />

  <title>Dashboard - Realisasi Anggaran</title>

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
  <link href="css/sb-admin-2.min.css" rel="stylesheet" />

  <style>
    .card-custom {
      transition: transform 0.2s;
    }

    .card-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .progress-custom {
      height: 8px;
    }

    .table-responsive {
      border-radius: 0.35rem;
    }

    .filter-section {
      background: var(--bps-blue);
      border-radius: 0.35rem;
      padding: 20px;
      margin-bottom: 20px;
      color: white;
    }

    .chart-container {
      position: relative;
      height: 300px;
    }
  </style>
</head>

<body id="page-top">
  <div class="container-fluid">

    <!-- Filter Section -->
    <div class="filter-section">
      <h4 class="mb-3"><i class="fas fa-filter mr-2"></i>Filter Data Realisasi</h4>
      <form method="GET" class="form-inline">
        <div class="form-group mr-3">
          <label class="mr-2">Tahun:</label>
          <select name="tahun" class="form-control">
            <option value="">Semua Tahun</option>
            <?php for ($y = 2020; $y <= date('Y') + 1; $y++): ?>
              <option value="<?php echo $y; ?>" <?php echo ($tahun_filter == $y) ? 'selected' : ''; ?>><?php echo $y; ?></option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="form-group mr-3">
          <label class="mr-2">Bulan:</label>
          <select name="bulan" class="form-control">
            <option value="">Semua Bulan</option>
            <?php for ($b = 1; $b <= 12; $b++): ?>
              <option value="<?php echo $b; ?>" <?php echo ($bulan_filter == $b) ? 'selected' : ''; ?>>
                <?php echo $bulan_nama[$b - 1]; ?>
              </option>
            <?php endfor; ?>
          </select>
        </div>

        <button type="submit" class="btn btn-light">
          <i class="fas fa-search mr-1"></i>Terapkan Filter
        </button>
        <a href="?" class="btn btn-outline-light ml-2">
          <i class="fas fa-refresh mr-1"></i>Reset
        </a>
      </form>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 card-custom">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  Total Usulan Anggaran
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  Rp <?php echo number_format($total_anggaran, 0, ',', '.'); ?>
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 card-custom">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  Total Realisasi
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  Rp <?php echo number_format($total_realisasi, 0, ',', '.'); ?>
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 card-custom">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Persentase Realisasi</div>
                <div class="row no-gutters align-items-center">
                  <div class="col-auto">
                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo number_format($persentase, 1); ?>%</div>
                  </div>
                  <div class="col">
                    <div class="progress progress-custom progress-sm mr-2">
                      <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $persentase; ?>%"
                        aria-valuenow="<?php echo $persentase; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 card-custom">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  Sisa Anggaran
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  Rp <?php echo number_format($sisa_anggaran, 0, ',', '.'); ?>
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-wallet fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
      <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-chart-area mr-2"></i>Realisasi vs Usulan per Bulan (<?php echo $current_year; ?>)
            </h6>
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="myAreaChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-chart-pie mr-2"></i>Realisasi per Kategori
            </h6>
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="myPieChart"></canvas>
            </div>
            <div class="mt-4 text-center small">
              <span class="mr-2">
                <i class="fas fa-circle text-primary"></i>
                Karyawan Internal (<?php echo $jumlah_organik; ?> orang)
              </span><br>
              <span class="mr-2 mt-1 d-inline-block">
                <i class="fas fa-circle text-success"></i>
                Karyawan Eksternal (<?php echo $jumlah_mitra; ?> orang)
              </span><br>
              <span class="mr-2 mt-1 d-inline-block">
                <i class="fas fa-circle text-info"></i>
                Lain-lain
              </span>
            </div>
            <hr>
            <div class="text-center">
              <h6 class="text-primary">Total Anggaran per Kategori:</h6>
              <p class="mb-1"><strong>Internal:</strong> Rp <?php echo number_format($organik, 0, ',', '.'); ?></p>
              <p class="mb-1"><strong>Eksternal:</strong> Rp <?php echo number_format($mitra, 0, ',', '.'); ?></p>
              <p class="mb-0"><strong>Lain-lain:</strong> Rp <?php echo number_format($lain, 0, ',', '.'); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table -->
    <div class="row">
      <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-table mr-2"></i>10 Data Realisasi Terbaru
            </h6>
            <div class="dropdown no-arrow">
              <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow">
                <div class="dropdown-header">Aksi:</div>
                <a class="dropdown-item" href="#" onclick="window.print()">
                  <i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i>Print
                </a>
                <a class="dropdown-item" href="#" onclick="exportToExcel()">
                  <i class="fas fa-download fa-sm fa-fw mr-2 text-gray-400"></i>Export Excel
                </a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                  <tr>
                    <th><i class="fas fa-calendar mr-1"></i>Tahun</th>
                    <th><i class="fas fa-calendar-alt mr-1"></i>Bulan</th>
                    <th><i class="fas fa-code mr-1"></i>Program</th>
                    <th><i class="fas fa-tasks mr-1"></i>Kegiatan</th>
                    <th>KRO</th>
                    <th>RO</th>
                    <th><i class="fas fa-clipboard-list mr-1"></i>Nama Kegiatan</th>
                    <th><i class="fas fa-user-tie mr-1"></i>Internal</th>
                    <th><i class="fas fa-user-friends mr-1"></i>Eksternal</th>
                    <th class="text-right"><i class="fas fa-money-bill mr-1"></i>Usulan</th>
                    <th class="text-right"><i class="fas fa-check-circle mr-1"></i>Realisasi</th>
                    <th><i class="fas fa-exclamation-triangle mr-1"></i>Kendala</th>
                    <th><i class="fas fa-lightbulb mr-1"></i>Solusi</th>
                    <th><i class="fas fa-info-circle mr-1"></i>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                      <tr>
                        <td><span class="badge badge-secondary"><?php echo htmlspecialchars($row['tahun_kegiatan']); ?></span></td>
                        <td>
                          <?php
                          $bulan = (int)$row['bulan_kegiatan'];
                          if ($bulan >= 1 && $bulan <= 12) {
                            echo '<span class="badge badge-info">' . $bulan_nama[$bulan - 1] . '</span>';
                          } else {
                            echo '<span class="badge badge-danger">Invalid</span>';
                          }
                          ?>
                        </td>
                        <td><code><?php echo htmlspecialchars($row['kode_program']); ?></code></td>
                        <td><code><?php echo htmlspecialchars($row['kode_kegiatan']); ?></code></td>
                        <td><code><?php echo htmlspecialchars($row['kode_kro']); ?></code></td>
                        <td><code><?php echo htmlspecialchars($row['kode_ro']); ?></code></td>
                        <td>
                          <div class="text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($row['nama_kegiatan']); ?>">
                            <?php echo htmlspecialchars($row['nama_kegiatan']); ?>
                          </div>
                        </td>
                        <td>
                          <?php if ($row['organik']): ?>
                            <span class="badge badge-success">Ya</span>
                          <?php else: ?>
                            <span class="badge badge-light">Tidak</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php if ($row['mitra']): ?>
                            <span class="badge badge-success">Ya</span>
                          <?php else: ?>
                            <span class="badge badge-light">Tidak</span>
                          <?php endif; ?>
                        </td>
                        <td class="text-right">
                          <strong class="text-primary">Rp <?php echo number_format($row['usulan_anggaran'], 0, ',', '.'); ?></strong>
                        </td>
                        <td class="text-right">
                          <strong class="text-success">Rp <?php echo number_format($row['realisasi_anggaran'], 0, ',', '.'); ?></strong>
                        </td>
                        <td>
                          <div class="text-truncate" style="max-width: 150px;" title="<?php echo htmlspecialchars($row['kendala']); ?>">
                            <?php echo htmlspecialchars($row['kendala']); ?>
                          </div>
                        </td>
                        <td>
                          <div class="text-truncate" style="max-width: 150px;" title="<?php echo htmlspecialchars($row['solusi']); ?>">
                            <?php echo htmlspecialchars($row['solusi']); ?>
                          </div>
                        </td>
                        <td>
                          <div class="text-truncate" style="max-width: 150px;" title="<?php echo htmlspecialchars($row['keterangan']); ?>">
                            <?php echo htmlspecialchars($row['keterangan']); ?>
                          </div>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="14" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3"></i><br>
                        Tidak ada data realisasi yang ditemukan
                      </td>
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    // Area Chart
    var ctx = document.getElementById("myAreaChart");
    var monthlyData = <?php echo json_encode($monthly_data); ?>;

    var labels = [];
    var usulanData = [];
    var realisasiData = [];

    var bulanNama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
      'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
    ];

    for (var i = 1; i <= 12; i++) {
      labels.push(bulanNama[i - 1]);
      var found = monthlyData.find(function(item) {
        return parseInt(item.bulan_kegiatan) == i;
      });
      usulanData.push(found ? parseInt(found.usulan) : 0);
      realisasiData.push(found ? parseInt(found.realisasi) : 0);
    }

    var myLineChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: "Usulan Anggaran",
          lineTension: 0.3,
          backgroundColor: "rgba(78, 115, 223, 0.05)",
          borderColor: "rgba(78, 115, 223, 1)",
          pointRadius: 3,
          pointBackgroundColor: "rgba(78, 115, 223, 1)",
          pointBorderColor: "rgba(78, 115, 223, 1)",
          pointHoverRadius: 3,
          pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
          pointHoverBorderColor: "rgba(78, 115, 223, 1)",
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: usulanData,
        }, {
          label: "Realisasi Anggaran",
          lineTension: 0.3,
          backgroundColor: "rgba(28, 200, 138, 0.05)",
          borderColor: "rgba(28, 200, 138, 1)",
          pointRadius: 3,
          pointBackgroundColor: "rgba(28, 200, 138, 1)",
          pointBorderColor: "rgba(28, 200, 138, 1)",
          pointHoverRadius: 3,
          pointHoverBackgroundColor: "rgba(28, 200, 138, 1)",
          pointHoverBorderColor: "rgba(28, 200, 138, 1)",
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: realisasiData,
        }],
      },
      options: {
        maintainAspectRatio: false,
        layout: {
          padding: {
            left: 10,
            right: 25,
            top: 25,
            bottom: 0
          }
        },
        scales: {
          x: {
            grid: {
              display: false,
              drawBorder: false
            },
            ticks: {
              maxTicksLimit: 12
            }
          },
          y: {
            ticks: {
              maxTicksLimit: 5,
              padding: 10,
              callback: function(value) {
                return 'Rp ' + value.toLocaleString('id-ID');
              }
            },
            grid: {
              color: "rgb(234, 236, 244)",
              zeroLineColor: "rgb(234, 236, 244)",
              drawBorder: false,
              borderDash: [2],
              zeroLineBorderDash: [2]
            }
          }
        },
        plugins: {
          legend: {
            display: true
          },
          tooltip: {
            backgroundColor: "rgb(255,255,255)",
            bodyColor: "#858796",
            titleMarginBottom: 10,
            titleColor: '#6e707e',
            titleFont: {
              size: 14
            },
            borderColor: '#dddfeb',
            borderWidth: 1,
            displayColors: false,
            intersect: false,
            mode: 'index',
            caretPadding: 10,
            callbacks: {
              label: function(context) {
                return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
              }
            }
          }
        }
      }
    });

    // Pie Chart
    var ctx2 = document.getElementById("myPieChart");
    var organik = <?= $organik ?>;
    var mitra = <?= $mitra ?>;
    var lain = <?= $lain ?>;

    var myPieChart = new Chart(ctx2, {
      type: 'doughnut',
      data: {
        labels: ["Karyawan Internal", "Karyawan Eksternal", "Lain-lain"],
        datasets: [{
          data: [organik, mitra, lain],
          backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
          hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
          hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
      },
      options: {
        maintainAspectRatio: false,
        plugins: {
          tooltip: {
            backgroundColor: "rgb(255,255,255)",
            bodyColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            displayColors: false,
            callbacks: {
              label: function(context) {
                var label = context.label || '';
                var value = context.raw;
                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                var percentage = ((value / total) * 100).toFixed(1);
                return label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
              }
            }
          },
          legend: {
            display: false
          }
        },
        cutout: '60%',
      }
    });

    // Export to Excel function
    function exportToExcel() {
      alert('Fitur export akan segera tersedia');
    }

    // Print function
    function printTable() {
      window.print();
    }

    // Hover effects for cards
    document.addEventListener('DOMContentLoaded', function() {
      const cards = document.querySelectorAll('.card-custom');
      cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
          this.style.transform = 'translateY(-2px)';
          this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
        });

        card.addEventListener('mouseleave', function() {
          this.style.transform = 'translateY(0)';
          this.style.boxShadow = '';
        });
      });
    });

    // Tooltip initialization
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
</body>

</html>
<?php
$content = ob_get_clean();
$title = "Dashboard";
include "layout.php";
?>