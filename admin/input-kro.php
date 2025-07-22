<?php
session_start();
include "service/koneksi.php";

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'kode_program';
$sort_order = isset($_GET['sort_order']) && in_array(strtoupper($_GET['sort_order']), ['ASC', 'DESC']) ? strtoupper($_GET['sort_order']) : 'ASC';

$allowed_sort_columns = ['kode_program', 'kode_kegiatan', 'kode_kro', 'uraian_kro', 'kro_2'];
if (!in_array($sort_by, $allowed_sort_columns)) {
    $sort_by = 'kode_program';
}

if (isset($_POST["simpan"])) {
    $kode_program = $_POST["kode_program"];
    $kode_kegiatan = $_POST["kode_kegiatan"];
    $kode_kro = $_POST["kode_kro"];
    $uraian_kro = $_POST["uraian_kro"];
    $kro_2 = $_POST["kro_2"];

    $stmt = $koneksi->prepare("INSERT INTO tbl_kro1 (kode_program, kode_kegiatan, kode_kro, uraian_kro, kro_2) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $kode_program, $kode_kegiatan, $kode_kro, $uraian_kro, $kro_2);

    if ($stmt->execute()) {
        echo "<script>alert('Data KRO berhasil disimpan!'); window.location.href='input-kro.php';</script>";
        exit;
    } else {
        echo "<script>alert('Data KRO gagal disimpan! Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

ob_start();
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen KRO</h1>
</div>

<p class="mb-4 text-gray-700">Silakan kelola data KRO (Keluaran Rincian Output) BPS di halaman ini. Anda dapat menambah, melihat, mengedit, dan menghapus data KRO.</p>

<div class="card shadow mb-5">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus-circle"></i> Tambah Data KRO Baru</h6>
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
                <label for="uraian_kro" class="col-sm-3 col-form-label">Uraian KRO</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="uraian_kro" name="uraian_kro" placeholder="Contoh: Dokumen Survei" required>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="kro_2" class="col-sm-3 col-form-label">KRO 2</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="kro_2" name="kro_2" placeholder="Contoh: Data Primer" required>
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
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list"></i> Data KRO Tersedia</h6>
    </div>
    <div class="card-body">
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
                            'uraian_kro' => 'Uraian KRO',
                            'kro_2' => 'KRO 2'
                        ];
                        foreach ($columns as $col_name => $col_label) {
                            $next_order = ($sort_by == $col_name && $sort_order == 'ASC') ? 'DESC' : 'ASC';
                            $arrow = '';
                            if ($sort_by == $col_name) {
                                $arrow = ($sort_order == 'ASC') ? ' <i class="fas fa-sort-up"></i>' : ' <i class="fas fa-sort-down"></i>';
                            }
                            echo "<th class='text-center'><a href='?sort_by={$col_name}&sort_order={$next_order}&page={$page}' class='text-white text-decoration-none'>{$col_label}{$arrow}</a></th>";
                        }
                        ?>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_query = "SELECT COUNT(*) AS total FROM tbl_kro1";
                    $total_result = $koneksi->query($total_query);
                    $total_rows = $total_result->fetch_assoc()['total'];
                    $total_pages = ceil($total_rows / $limit);

                    $query = "SELECT * FROM tbl_kro1 ORDER BY {$sort_by} {$sort_order} LIMIT {$start}, {$limit}";
                    $result = $koneksi->query($query);

                    if ($result && $result->num_rows > 0) {
                        $no = $start + 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='text-center'>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['kode_program']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['kode_kegiatan']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['kode_kro']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['uraian_kro']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['kro_2']) . "</td>";
                            echo "<td class='text-center'>
                                <a href='edit_kro.php?kode_kro=" . urlencode($row['kode_kro']) . "' class='btn btn-warning btn-sm mr-1' title='Edit Data'><i class='fas fa-edit'></i> Edit</a>
                                <a href='hapus_kro.php?kode_kro=" . urlencode($row['kode_kro']) . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?');\" title='Hapus Data'><i class='fas fa-trash'></i> Hapus</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>Belum ada data KRO yang tersedia.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&sort_by=<?= $sort_by ?>&sort_order=<?= $sort_order ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&sort_by=<?= $sort_by ?>&sort_order=<?= $sort_order ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&sort_by=<?= $sort_by ?>&sort_order=<?= $sort_order ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = "Input KRO";
include "layout.php";
?>