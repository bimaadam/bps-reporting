<?php
header('Content-Type: application/json');
include '../service/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Validasi input
    $required = [
        'tahun_kegiatan', 'bulan_kegiatan', 'kode_program', 'kode_kegiatan', 
        'kode_kro', 'kode_ro', 'nama_kegiatan', 'nama_aktivitas', 
        'organik', 'mitra', 'usulan_anggaran'
    ];
    
    foreach ($required as $field) {
        if (!isset($_POST[$field]) || $_POST[$field] === '') {
            throw new Exception("Field $field harus diisi");
        }
    }


    $tahun = (int)$_POST['tahun_kegiatan'];
    $bulan = (int)$_POST['bulan_kegiatan'];
    $tanggal_kegiatan = date('Y-m-d', strtotime("$tahun-$bulan-01"));
    
    $query = "INSERT INTO tbl_rencana_anggaran (
        tahun_kegiatan, bulan_kegiatan, kode_program, kode_kegiatan,
        kode_kro, kode_ro, nama_kegiatan, nama_aktivitas,
        organik, mitra, usulan_anggaran
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $koneksi->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: {$koneksi->error}");
    }
    
    $kode_program = $_POST['kode_program'];
    $kode_kegiatan = $_POST['kode_kegiatan'];
    $kode_kro = $_POST['kode_kro'];
    $kode_ro = $_POST['kode_ro'];
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $nama_aktivitas = $_POST['nama_aktivitas'];
    $organik = (int)$_POST['organik'];
    $mitra = (int)$_POST['mitra'];
    $usulan_anggaran = (float)$_POST['usulan_anggaran'];

    $stmt->bind_param(
        "ssiiiisssid",  
        $tahun,         
        $tanggal_kegiatan, 
        $kode_program,
        $kode_kegiatan,
        $kode_kro,
        $kode_ro,
        $nama_kegiatan,  
        $nama_aktivitas, 
        $organik,
        $mitra,
        $usulan_anggaran
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
}

$stmt->close();
$koneksi->close();