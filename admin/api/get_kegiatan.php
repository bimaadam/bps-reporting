<?php
include "../service/database.php";

if (isset($_POST['kode_program'])) {
    $kode_program = $_POST['kode_program'];
    $query = $koneksi->prepare("SELECT kode_kegiatan FROM tbl_kegiatan1 WHERE kode_program = ?");
    $query->bind_param("s", $kode_program);
    $query->execute();
    $result = $query->get_result();
    
    echo "<option value=''>Pilih Kegiatan</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['kode_kegiatan'] . "'>" . $row['kode_kegiatan'] . "</option>";
    }
}
?>
