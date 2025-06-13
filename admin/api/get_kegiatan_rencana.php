<?php
include "../service/database.php";

if (isset($_POST['kode_program'])) {
    $kode_program = mysqli_real_escape_string($koneksi, $_POST['kode_program']);

    $query = mysqli_query($koneksi, "SELECT DISTINCT kode_kegiatan FROM tbl_rencana_anggaran WHERE kode_program = '$kode_program'");

    echo '<option value="" disabled selected>Pilih Kegiatan</option>';
    while ($data = mysqli_fetch_array($query)) {
        echo "<option value='{$data['kode_kegiatan']}'>{$data['kode_kegiatan']}</option>";
    }
}
?>
