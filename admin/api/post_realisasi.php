<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_program = $_POST['kode_program'];
    $kode_kegiatan = $_POST['kode_kegiatan'];
    $kode_kro = $_POST['kode_kro'];
    $kode_ro = $_POST['kode_ro'];
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $nama_aktivitas = $_POST['nama_aktivitas'];
    $organik = $_POST['organik'];
    $mitra = $_POST['mitra'];
    $usulan_anggaran = $_POST['usulan_anggaran'];

    $stmt = $koneksi->prepare("INSERT INTO tbl_realisasi_anggaran (kode_program, kode_kegiatan, kode_kro, kode_ro, nama_kegiatan, nama_aktivitas, organik, mitra, usulan_anggaran) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $kode_program, $kode_kegiatan, $kode_kro, $kode_ro, $nama_kegiatan, $nama_aktivitas, $organik, $mitra, $usulan_anggaran);

    if ($stmt->execute()) {
        http_response_code(200);
        echo "Data berhasil ditambahkan";
    } else {
        http_response_code(500);
        echo "Gagal: " . $stmt->error;
    }

    $stmt->close();
}
?>