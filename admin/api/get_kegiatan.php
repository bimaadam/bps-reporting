<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../service/database.php";
if (!$koneksi) {
    echo "<option value=''>Error: Koneksi database gagal.</option>";
    exit();
}

if (isset($_POST['kode_program'])) {
    $kode_program = $_POST['kode_program'];

    if ($koneksi instanceof mysqli) {
        $query = $koneksi->prepare("SELECT kode_kegiatan, uraian_kegiatan FROM tbl_kegiatan1 WHERE kode_program = ? ORDER BY kode_kegiatan ASC");

        if ($query === false) {
            echo "<option value=''>Error: Gagal mempersiapkan query. " . $koneksi->error . "</option>";
            exit();
        }

        $query->bind_param("s", $kode_program);
        $query->execute();
        $result = $query->get_result();

        echo "<option value='' disabled selected>Pilih Kegiatan</option>";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row['kode_kegiatan']) . "'>"
                    . htmlspecialchars($row['kode_kegiatan']) . " - "
                    . htmlspecialchars($row['uraian_kegiatan']) .
                    "</option>";
            }
        } else {
            echo "<option value=''>Tidak ada kegiatan untuk program ini</option>";
        }

        $query->close();
    } else {
        echo "<option value=''>Error: Objek koneksi database tidak valid.</option>";
    }
} else {
    echo "<option value='' disabled selected>Pilih Kegiatan</option>";
}
