<?php
session_start();
include "service/database.php";
if (isset($_POST["simpan"])) {
    $kode_program = $_POST["kode_program"];
    $uraian_program = $_POST["uraian_program"];

    $stmt = $koneksi->prepare("INSERT INTO tbl_program1 (kode_program, uraian_program) VALUES (?, ?)");
    $stmt->bind_param("ss", $kode_program, $uraian_program);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan!'); window.location.href='input-program.php';</script>";
        exit;
    } else {
        echo "<script>alert('Data gagal disimpan! Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

ob_start();
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Program</h1>
</div>

<p class="mb-4 text-gray-700">Silakan kelola data program BPS di halaman ini. Anda bisa menambah, melihat, mengedit, dan menghapus data program.</p>

<div class="card shadow mb-5">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus-circle"></i> Tambah Program Baru</h6>
    </div>
    <div class="card-body">
        <form action="" method="POST">
            <div class="form-group row">
                <label for="kode_program" class="col-sm-3 col-form-label">Kode Program</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="kode_program" name="kode_program" placeholder="Masukkan Kode Program (mis: 1234)" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="uraian_program" class="col-sm-3 col-form-label">Uraian Program</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="uraian_program" name="uraian_program" placeholder="Masukkan Uraian Program (mis: Dukungan Manajemen)" required>
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
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-table"></i> Data Program Tersedia</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode Program</th>
                        <th class="text-center">Uraian Program</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = "SELECT * FROM tbl_program1 ORDER BY kode_program ASC";
                    $result = $koneksi->query($query);
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td class='text-center'>{$no}</td>
                                <td>{$row['kode_program']}</td>
                                <td>{$row['uraian_program']}</td>
                                <td class='text-center'>
                                    <a href='edit_program.php?id=" . urlencode($row['kode_program']) . "' class='btn btn-warning btn-sm mr-1' title='Edit Data'><i class='fas fa-edit'></i> Edit</a>
                                    <a href='hapus_program.php?id=" . urlencode($row['kode_program']) . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?')\" title='Hapus Data'><i class='fas fa-trash'></i> Hapus</a>
                                </td>
                            </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>Belum ada data program yang tersedia.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = "Input Program";
include "layout.php"; ?>