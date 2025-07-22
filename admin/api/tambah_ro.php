<?php
include '../service/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_program = $_POST['kode_program'];
    $kode_kegiatan = $_POST['kode_kegiatan'];
    $kode_ro = $_POST['kode_ro'];
    $uraian_ro = $_POST['uraian_ro'];

    $query = $koneksi->prepare("INSERT INTO tbl_ro1 (kode_program, kode_kegiatan, kode_ro, uraian_ro) VALUES (?, ?, ?, ?)");
    $query->bind_param("ssss", $kode_program, $kode_kegiatan, $kode_ro, $uraian_ro);

    if ($query->execute()) {
        echo "Data berhasil ditambahkan!";
    } else {
        echo "Gagal menambahkan data!";
    }
}
