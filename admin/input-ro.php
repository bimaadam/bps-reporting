<?php
session_start();
include "service/database.php";

$available_limits = [10, 25, 50, 100];
$limit = isset($_GET['limit']) && in_array((int)$_GET['limit'], $available_limits) ? (int)$_GET['limit'] : 25;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'kode_program';
$sort_order = isset($_GET['sort_order']) && in_array(strtoupper($_GET['sort_order']), ['ASC', 'DESC']) ? strtoupper($_GET['sort_order']) : 'ASC';

$allowed_sort_columns = ['kode_program', 'kode_kegiatan', 'kode_kro', 'kode_ro', 'uraian_ro'];
if (!in_array($sort_by, $allowed_sort_columns)) {
    $sort_by = 'kode_program';
}

if (isset($_POST["simpan"])) {
    $kode_program = $_POST["kode_program"];
    $kode_kegiatan = $_POST["kode_kegiatan"];
    $kode_kro = $_POST["kode_kro"];
    $kode_ro = $_POST["kode_ro"];
    $uraian_ro = $_POST["uraian_ro"];

    $stmt = $koneksi->prepare("INSERT INTO tbl_ro1 (kode_program, kode_kegiatan, kode_kro, kode_ro, uraian_ro) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $kode_program, $kode_kegiatan, $kode_kro, $kode_ro, $uraian_ro);

    if ($stmt->execute()) {
        echo "<script>alert('Data RO berhasil disimpan!'); window.location.href='input-ro.php';</script>";
        exit;
    } else {
        echo "<script>alert('Data RO gagal disimpan! Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

ob_start();
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Rincian Output (RO)</h1>
</div>

<p class="mb-4 text-gray-700">Silakan kelola data Rincian Output (RO) BPS di halaman ini. Anda dapat menambah, melihat, mengurutkan, dan menghapus data RO.</p>

<div class="card shadow mb-5">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus-circle"></i> Tambah Data RO Baru</h6>
    </div>
    <div class="card-body">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="form-group row mb-3">
                <label for="kode_program" class="col-sm-3 col-form-label">Kode Program</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="kode_program" name="kode_program" placeholder="Contoh: 1234" required>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="kode_kegiatan" class="col-sm-3 col-form-label">Kode Kegiatan</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="kode_kegiatan" name="kode_kegiatan" placeholder="Contoh: 5678" required>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="kode_kro" class="col-sm-3 col-form-label">Kode KRO</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="kode_kro" name="kode_kro" placeholder="Contoh: 001" required>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="kode_ro" class="col-sm-3 col-form-label">Kode RO</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="kode_ro" name="kode_ro" placeholder="Contoh: 0001" required>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="uraian_ro" class="col-sm-3 col-form-label">Uraian RO</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="uraian_ro" name="uraian_ro" placeholder="Contoh: Laporan Analisis Data" required>
                </div>
            </div>
            <div class="form-group row mt-4">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary btn-icon-split" name="simpan">
                        <span class="icon text-white-50">
                            <i class="fas fa-save"></i>
                        </span>
                        <span class="text">Simpan Data</span>
                    </button>
                    <button type="reset" class="btn btn-secondary btn-icon-split ml-2">
                        <span class="icon text-white-50">
                            <i class="fas fa-sync-alt"></i>
                        </span>
                        <span class="text">Reset Form</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-stream"></i> Data Rincian Output (RO) Tersedia</h6>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <div class="form-inline">
                <label for="limitSelect" class="my-1 mr-2">Tampilkan:</label>
                <select class="form-control form-control-sm" id="limitSelect" onchange="window.location.href = this.value;">
                    <?php foreach ($available_limits as $val) : ?>
                        <option value="?page=1&sort_by=<?= $sort_by ?>&sort_order=<?= $sort_order ?>&limit=<?= $val ?>" <?= ($limit == $val) ? 'selected' : '' ?>>
                            <?= $val ?> data
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">No</th>
                        <?php
                        $columns = [
                            'kode_program' => 'Kode Program',
                            'kode_kegiatan' => 'Kode Kegiatan',
                            'kode_kro' => 'Kode KRO',
                            'kode_ro' => 'Kode RO',
                            'uraian_ro' => 'Uraian RO'
                        ];
                        foreach ($columns as $col_name => $col_label) {
                            $next_order = ($sort_by == $col_name && $sort_order == 'ASC') ? 'DESC' : 'ASC';
                            $arrow = '';
                            if ($sort_by == $col_name) {
                                $arrow = ($sort_order == 'ASC') ? ' <i class="fas fa-sort-up"></i>' : ' <i class="fas fa-sort-down"></i>';
                            }
                            echo "<th class='text-center'><a href='?page={$page}&sort_by={$col_name}&sort_order={$next_order}&limit={$limit}' class='text-white text-decoration-none'>{$col_label}{$arrow}</a></th>";
                        }
                        ?>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalQuery = "SELECT COUNT(*) AS total FROM tbl_ro1";
                    $totalResult = $koneksi->query($totalQuery);
                    $totalData = $totalResult->fetch_assoc()['total'];
                    $totalPages = ceil($totalData / $limit);

                    $query = "SELECT * FROM tbl_ro1 ORDER BY {$sort_by} {$sort_order} LIMIT {$start}, {$limit}";
                    $result = $koneksi->query($query);

                    if ($result && $result->num_rows > 0) {
                        $no = $start + 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='text-center'>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['kode_program']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['kode_kegiatan']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['kode_kro']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['kode_ro']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['uraian_ro']) . "</td>";
                            echo "<td class='text-center'>
                                <a href='edit_ro.php?kode_ro=" . urlencode($row['kode_ro']) . "' class='btn btn-warning btn-sm mr-1' title='Edit Data'><i class='fas fa-edit'></i> Edit</a>
                                <a href='hapus_ro.php?kode_ro=" . urlencode($row['kode_ro']) . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?');\" title='Hapus Data'><i class='fas fa-trash'></i> Hapus</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>Belum ada data RO yang tersedia.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&sort_by=<?= $sort_by ?>&sort_order=<?= $sort_order ?>&limit=<?= $limit ?>">Previous</a>
                </li>
                <?php
                $visible_pages = 5;
                $start_page = max(1, $page - floor($visible_pages / 2));
                $end_page = min($totalPages, $start_page + $visible_pages - 1);

                if ($end_page - $start_page + 1 < $visible_pages) {
                    $start_page = max(1, $end_page - $visible_pages + 1);
                }

                if ($start_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?page=1&sort_by=' . $sort_by . '&sort_order=' . $sort_order . '&limit=' . $limit . '">1</a></li>';
                    if ($start_page > 2) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                }

                for ($i = $start_page; $i <= $end_page; $i++) : ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&sort_by=<?= $sort_by ?>&sort_order=<?= $sort_order ?>&limit=<?= $limit ?>"><?= $i ?></a>
                    </li>
                <?php endfor;

                if ($end_page < $totalPages) {
                    if ($end_page < $totalPages - 1) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '&sort_by=' . $sort_by . '&sort_order=' . $sort_order . '&limit=' . $limit . '">' . $totalPages . '</a></li>';
                }
                ?>
                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&sort_by=<?= $sort_by ?>&sort_order=<?= $sort_order ?>&limit=<?= $limit ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = "Input RO";
include "layout.php";
?>