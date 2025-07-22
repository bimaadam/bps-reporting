<?php
include '../service/koneksi.php';

$limit = 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$start = ($page - 1) * $limit;

$kode_program = isset($_POST['kode_program']) ? $_POST['kode_program'] : '';
$kode_kegiatan = isset($_POST['kode_kegiatan']) ? $_POST['kode_kegiatan'] : '';

if (!empty($kode_program) && !empty($kode_kegiatan)) {
    $query = $koneksi->prepare("
        SELECT id, tahun_kegiatan, bulan_kegiatan, kode_program, kode_kegiatan, kode_kro, kode_ro, nama_kegiatan, nama_aktivitas, organik, mitra, usulan_anggaran
        FROM tbl_rencana_anggaran
        WHERE kode_program = ? AND kode_kegiatan = ?
        LIMIT ?, ?
    ");
    $query->bind_param("ssii", $kode_program, $kode_kegiatan, $start, $limit);
    $query->execute();
    $result = $query->get_result();

    $count_query = $koneksi->prepare("
        SELECT COUNT(*) as total FROM tbl_rencana_anggaran WHERE kode_program = ? AND kode_kegiatan = ?
    ");
    $count_query->bind_param("ss", $kode_program, $kode_kegiatan);
    $count_query->execute();
    $count_result = $count_query->get_result();
    $total_data = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_data / $limit);

    echo "<div class='table-responsive'>
            <table class='table table-bordered' id='aktivitasTable' width='100%' cellspacing='0'>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>Program</th>
                    <th>Kegiatan</th>
                    <th>KRO</th>
                    <th>RO</th>
                    <th>Nama Kegiatan</th>
                    <th>Nama Aktivitas</th>
                    <th>Organik</th>
                    <th>Mitra</th>
                    <th>Usulan Anggaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>";

    if ($result->num_rows > 0) {
        $no = $start + 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['tahun_kegiatan']}</td>
                    <td>{$row['bulan_kegiatan']}</td>
                    <td>{$row['kode_program']}</td>
                    <td>{$row['kode_kegiatan']}</td>
                    <td>{$row['kode_kro']}</td>
                    <td>{$row['kode_ro']}</td>
                    <td>{$row['nama_kegiatan']}</td>
                    <td>{$row['nama_aktivitas']}</td>
                    <td>{$row['organik']}</td>
                    <td>{$row['mitra']}</td>
                    <td>{$row['usulan_anggaran']}</td>
                    <td>
                        <button
    class='btn btn-success btn-sm tambahBtn'
    data-tahun-kegiatan='{$row['tahun_kegiatan']}'
    data-bulan-kegiatan='{$row['bulan_kegiatan']}'
    data-kode-program='{$row['kode_program']}'
    data-kode-kegiatan='{$row['kode_kegiatan']}'
    data-kode-kro='{$row['kode_kro']}'
    data-kode-ro='{$row['kode_ro']}'
    data-nama-kegiatan='{$row['nama_kegiatan']}'
    data-nama-aktivitas='{$row['nama_aktivitas']}'
    data-organik='{$row['organik']}'
    data-mitra='{$row['mitra']}'
    data-usulan-anggaran='{$row['usulan_anggaran']}'
>Tambah</button>


                        <button class='btn btn-primary btn-sm editBtn' data-id='{$row['kode_ro']}'>Edit</button>
                        <button class='btn btn-danger btn-sm deleteBtn' data-id='{$row['id']}'>Hapus</button>
                    </td>
                  </tr>";
            $no++;
        }
    } else {
        echo "<tr><td colspan='13' class='text-center'>Data tidak ditemukan</td></tr>";
    }

    echo "</tbody></table></div>";
    echo "<nav><ul class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<li class='page-item'><a class='page-link pagination-link' data-page='{$i}' href='#'>{$i}</a></li>";
    }
    echo "</ul></nav>";
} else {
    echo "<tr><td colspan='13' class='text-center'>Silakan pilih kode program & kegiatan</td></tr>";
}
