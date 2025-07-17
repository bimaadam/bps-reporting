<?php
include '../service/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $required = [
        'tahun_kegiatan',
        'bulan_kegiatan',
        'kode_program',
        'kode_kegiatan',
        'kode_kro',
        'kode_ro',
        'nama_kegiatan',
        'nama_aktivitas',
        'organik',
        'mitra',
        'usulan_anggaran',
        'realisasi_anggaran',
        'kendala',
        'solusi',
        'keterangan'
    ];

    foreach ($required as $field) {
        if (!isset($_POST[$field]) || $_POST[$field] === '') {
            throw new Exception("Field $field harus diisi");
        }
    }

    $tahun = (int)$_POST['tahun_kegiatan'];
    $bulan_kegiatan_db = $_POST['bulan_kegiatan'];
    $kode_program = $_POST['kode_program'];
    $kode_kegiatan = $_POST['kode_kegiatan'];
    $kode_kro = $_POST['kode_kro'];
    $kode_ro = $_POST['kode_ro'];
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $nama_aktivitas = $_POST['nama_aktivitas'];
    $organik = (int)$_POST['organik'];
    $mitra = (int)$_POST['mitra'];
    $usulan_anggaran = (float)$_POST['usulan_anggaran'];
    $realisasi_anggaran = (float) $_POST['realisasi_anggaran'];
    $kendala = (string) $_POST['kendala'];
    $solusi = (string) $_POST['solusi'];
    $keterangan = (string)$_POST['keterangan'];

    $query = "INSERT INTO tbl_realisasi_anggaran (
        tahun_kegiatan, bulan_kegiatan, kode_program, kode_kegiatan,
        kode_kro, kode_ro, nama_kegiatan, nama_aktivitas,
        organik, mitra, usulan_anggaran, realisasi_anggaran, kendala, solusi, keterangan
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: {$koneksi->error}");
    }

    $stmt->bind_param(
        "isssssssiiddsss",
        $tahun,
        $bulan_kegiatan_db,
        $kode_program,
        $kode_kegiatan,
        $kode_kro,
        $kode_ro,
        $nama_kegiatan,
        $nama_aktivitas,
        $organik,
        $mitra,
        $usulan_anggaran,
        $realisasi_anggaran,
        $kendala,
        $solusi,
        $keterangan
    );

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil disimpan',
            'insert_id' => $stmt->insert_id
        ]);
    } else {
        throw new Exception("Execute failed: {$stmt->error}");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_details' => $koneksi->error ?? null
    ]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($koneksi)) {
        $koneksi->close();
    }
}
