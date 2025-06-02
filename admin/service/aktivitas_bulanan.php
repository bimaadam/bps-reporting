<?php
require_once 'database.php';

function simpanAktivitasBulanan($data, $file) {
    global $conn;

    $tahun_kegiatan = $data['tahun_kegiatan'];
    $bulan_kegiatan = $data['bulan_kegiatan'];
    $no = $data['no'];
    $nama_kegiatan = $data['nama_kegiatan'];
    $nama_aktivitas = $data['nama_aktivitas'];
    $organik = $data['organik'];
    $mitra = $data['mitra'];
    $usulan_anggaran = $data['usulan_anggaran'];
    $realisasi_anggaran = $data['realisasi_anggaran'];
    $realisasi_kegiatan = $data['realisasi_kegiatan'];
    $kendala = $data['kendala'];
    $solusi = $data['solusi'];

    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($file["name"]);
    move_uploaded_file($file["tmp_name"], $target_file);
    $input_text_file = $target_file;

    $sql = "INSERT INTO tbl_aktivitas_bulanan (tahun_kegiatan, bulan_kegiatan, no, nama_kegiatan, nama_aktivitas, organik, mitra, usulan_anggaran, realisasi_anggaran, realisasi_kegiatan, kendala, solusi, input_text_file) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isissiiidddss", $tahun_kegiatan, $bulan_kegiatan, $no, $nama_kegiatan, $nama_aktivitas, $organik, $mitra, $usulan_anggaran, $realisasi_anggaran, $realisasi_kegiatan, $kendala, $solusi, $input_text_file);

    if ($stmt->execute()) {
    echo "Data berhasil disimpan!";
} else {
    echo "Error: " . $stmt->error;
}

}
