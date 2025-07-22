<?php
include '../service/koneksi.php';

$limit = 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$start = ($page - 1) * $limit;

$kode_program = isset($_POST['kode_program']) ? $_POST['kode_program'] : '';
$kode_kegiatan = isset($_POST['kode_kegiatan']) ? $_POST['kode_kegiatan'] : '';

if (!empty($kode_program) && !empty($kode_kegiatan)) {
    $query = $koneksi->prepare("
        SELECT kode_program, kode_kegiatan, kode_kro, kode_ro, uraian_ro 
        FROM tbl_ro1 
        WHERE kode_program = ? AND kode_kegiatan = ? 
        LIMIT ?, ?
    ");
    $query->bind_param("ssii", $kode_program, $kode_kegiatan, $start, $limit);
    $query->execute();
    $result = $query->get_result();

    $count_query = $koneksi->prepare("
        SELECT COUNT(*) as total FROM tbl_ro1 WHERE kode_program = ? AND kode_kegiatan = ?
    ");
    $count_query->bind_param("ss", $kode_program, $kode_kegiatan);
    $count_query->execute();
    $count_result = $count_query->get_result();
    $total_data = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_data / $limit);

    echo "<table class='table table-bordered'>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Program</th>
                    <th>Kode Kegiatan</th>
                    <th>Kode KRO</th>
                    <th>Kode RO</th>
                    <th>Uraian RO</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>";

    if ($result->num_rows > 0) {
        $no = $start + 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['kode_program']}</td>
                    <td>{$row['kode_kegiatan']}</td>
                    <td>{$row['kode_kro']}</td>
                    <td>{$row['kode_ro']}</td>
                    <td>{$row['uraian_ro']}</td>
                    <td>
                        <button 
    class='btn btn-success btn-sm tambahBtn'
    data-kode_program='{$row['kode_program']}'
    data-kode_kegiatan='{$row['kode_kegiatan']}'
    data-kode_kro='{$row['kode_kro']}'
    data-kode_ro='{$row['kode_ro']}'
>Tambah</button>


                        <button class='btn btn-primary btn-sm editBtn' data-id='{$row['kode_ro']}'>Edit</button>
                        <button class='btn btn-danger btn-sm deleteBtn' data-id='{$row['kode_ro']}'>Hapus</button>
                    </td>
                  </tr>";
            $no++;
        }
    } else {
        echo "<tr><td colspan='7' class='text-center'>Data tidak ditemukan</td></tr>";
    }

    echo "</tbody></table>";

    echo "<nav><ul class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<li class='page-item'><a class='page-link pagination-link' data-page='{$i}' href='#'>{$i}</a></li>";
    }
    echo "</ul></nav>";
} else {
    echo "<tr><td colspan='7' class='text-center'>Silakan pilih kode program & kegiatan</td></tr>";
}
