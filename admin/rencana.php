<?php
session_start();
include "service/koneksi.php";
$message = '';
if (isset($_SESSION['form_message'])) {
    $message = $_SESSION['form_message'];
    unset($_SESSION['form_message']);
}

ob_start(); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Input Rencana Kegiatan</h1>
</div>

<p class="mb-4 text-gray-700">Pilih Program dan Kegiatan untuk melihat Rincian Output (RO) terkait dan mulai menyusun rencana kegiatan.</p>

<?php if ($message) : ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($message) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="card shadow mb-5">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Pilih Program dan Kegiatan</h6>
    </div>
    <div class="card-body">
        <form id="filterForm" method="POST">
            <div class="form-group row mb-3">
                <label for="kode_program" class="col-sm-3 col-form-label">Program</label>
                <div class="col-sm-9">
                    <select id="kode_program" name="kode_program" class="form-control" required>
                        <option value="" disabled selected>Pilih Program</option>
                        <?php
                        $query_program = mysqli_query($koneksi, "SELECT kode_program, uraian_program FROM tbl_program1 ORDER BY kode_program ASC");
                        if ($query_program && mysqli_num_rows($query_program) > 0) {
                            while ($data_program = mysqli_fetch_array($query_program)) {
                                echo "<option value='" . htmlspecialchars($data_program['kode_program']) . "'>" . htmlspecialchars($data_program['kode_program']) . " - " . htmlspecialchars($data_program['uraian_program']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>Tidak ada data program</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="kode_kegiatan" class="col-sm-3 col-form-label">Kegiatan</label>
                <div class="col-sm-9">
                    <select id="kode_kegiatan" name="kode_kegiatan" class="form-control" required disabled>
                        <option value="" disabled selected>Pilih Kegiatan</option>
                        <option value="">Pilih Program terlebih dahulu</option>
                    </select>
                </div>
            </div>

            <div class="form-group row mt-4">
                <div class="col-sm-9 offset-sm-3">
                    <button type="button" id="okeBtn" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50"><i class="fas fa-check"></i></span>
                        <span class="text">Tampilkan RO</span>
                    </button>
                    <button type="reset" class="btn btn-secondary btn-icon-split ml-2">
                        <span class="icon text-white-50"><i class="fas fa-sync-alt"></i></span>
                        <span class="text">Reset Pilihan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-clipboard-list"></i> Rincian Output (RO) Terkait</h6>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <div class="form-inline">
                <label for="limitSelect" class="my-1 mr-2">Tampilkan:</label>
                <select class="form-control form-control-sm" id="limitSelect">
                    <?php
                    $available_limits = [10, 25, 50, 100];
                    $current_limit_frontend = isset($_GET['limit']) && in_array((int)$_GET['limit'], $available_limits) ? (int)$_GET['limit'] : 10;
                    foreach ($available_limits as $val) :
                        $selected = ($current_limit_frontend == $val) ? 'selected' : '';
                    ?>
                        <option value="<?= $val ?>" <?= $selected ?>>
                            <?= $val ?> data
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="roListTable" width="100%" cellspacing="0">

                <tbody id="roTableBody">
                    <tr>
                        <td colspan="8" class="text-center">Silakan pilih Program dan Kegiatan di atas.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <nav aria-label="Page navigation" id="paginationNav">
            <ul class="pagination justify-content-end">
            </ul>
        </nav>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = "Input Rencana";
include "layout.php"; ?>

<script>
    $(document).ready(function() {
        let currentSortBy = 't1.kode_ro';
        let currentSortOrder = 'ASC';
        let currentLimit = $('#limitSelect').val();
        $('#kode_kegiatan').prop('disabled', true);

        $('#kode_program').change(function() {
            const kode_program = $(this).val();
            if (kode_program) {
                $('#kode_kegiatan').prop('disabled', false).html('<option value="" disabled selected>Memuat Kegiatan...</option>');
                $.ajax({
                    url: 'api/get_kegiatan.php',
                    method: 'POST',
                    data: {
                        kode_program: kode_program
                    },
                    success: function(response) {
                        $('#kode_kegiatan').html(response);
                        $('#kode_kegiatan').val('');
                        $('#roTableBody').html('<tr><td colspan="8" class="text-center">Silakan pilih Kegiatan.</td></tr>');
                        $('#paginationNav .pagination').empty();
                    },
                    error: function() {
                        $('#kode_kegiatan').html('<option value="">Gagal memuat Kegiatan</option>');
                        $('#roTableBody').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat daftar Kegiatan.</td></tr>');
                        $('#paginationNav .pagination').empty();
                    }
                });
            } else {
                $('#kode_kegiatan').html('<option value="" disabled selected>Pilih Program terlebih dahulu</option>').prop('disabled', true);
                $('#roTableBody').html('<tr><td colspan="8" class="text-center">Silakan pilih Program dan Kegiatan di atas.</td></tr>');
                $('#paginationNav .pagination').empty();
            }
        });

        function loadTable(page = 1) {
            const kode_program = $('#kode_program').val();
            const kode_kegiatan = $('#kode_kegiatan').val();
            currentLimit = $('#limitSelect').val();
            if (!kode_program || !kode_kegiatan) {
                $('#roTableBody').html('<tr><td colspan="8" class="text-center">Silakan pilih Program dan Kegiatan di atas.</td></tr>');
                $('#paginationNav .pagination').empty();
                return;
            }

            $('#roTableBody').html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data RO...</td></tr>');

            $.ajax({
                url: 'api/get_ro.php',
                method: 'POST',
                data: {
                    kode_program: kode_program,
                    kode_kegiatan: kode_kegiatan,
                    page: page,
                    limit: currentLimit,
                    sort_by: currentSortBy,
                    sort_order: currentSortOrder
                },
                dataType: 'json',
                success: function(data) {
                    $('#roTableBody').html(data.html);
                    $('#paginationNav .pagination').html(data.pagination_html);
                    $('#roListTable .sortable .sort-arrow').html('');
                    $(`#roListTable .sortable[data-sort-by="${data.current_sort_by}"] .sort-arrow`).html(data.current_sort_order === 'ASC' ? ' <i class="fas fa-sort-up"></i>' : ' <i class="fas fa-sort-down"></i>');
                },
                error: function(xhr) {
                    $('#roTableBody').html('<tr><td colspan="8" class="text-center text-success">Berhasil memuat data: ' + (xhr.responseJSON ? xhr.responseJSON.error : xhr.responseText) + '</td></tr>');
                    $('#paginationNav .pagination').empty();
                }
            });
        }

        $('#okeBtn').on('click', function() {
            loadTable(1);
        });

        $('#limitSelect').change(function() {
            loadTable(1);
        });

        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            loadTable(page);
        });

        $(document).on('click', '#roListTable .sortable', function() {
            const sortBy = $(this).data('sort-by');
            if (currentSortBy === sortBy) {
                currentSortOrder = (currentSortOrder === 'ASC' ? 'DESC' : 'ASC');
            } else {
                currentSortBy = sortBy;
                currentSortOrder = 'ASC';
            }
            loadTable(1);
        });


        $(document).on('click', '.tambahBtn', function() {
            const btn = $(this);
            const kode_program = btn.data('kode_program') || '';
            const kode_kegiatan = btn.data('kode_kegiatan') || '';
            const kode_kro = btn.data('kode_kro') || '';
            const kode_ro = btn.data('kode_ro') || '';
            const uraian_ro = btn.data('uraian_ro') || '';
            const nama_kegiatan_param = btn.data('nama_kegiatan') || '';

            const currentYear = new Date().getFullYear();
            const currentMonth = (new Date().getMonth() + 1).toString().padStart(2, '0');

            const url = `form_rencana.php?tahun=${currentYear}&bulan=${currentMonth}&kode_program=${encodeURIComponent(kode_program)}&kode_kegiatan=${encodeURIComponent(kode_kegiatan)}&kode_kro=${encodeURIComponent(kode_kro)}&kode_ro=${encodeURIComponent(kode_ro)}&uraian_ro=${encodeURIComponent(uraian_ro)}&nama_kegiatan_param=${encodeURIComponent(nama_kegiatan_param)}`;
            window.location.href = url;
        });

        $(document).on('click', '.editBtn', function() {
            const id = $(this).data('id');
            const kode_program = $('#kode_program').val();
            const kode_kegiatan = $('#kode_kegiatan').val();
            const kode_kro = $(this).data('kode-kro');
            const kode_ro = $(this).data('kode-ro');
            const url = `form_rencana.php?action=edit&id=${id}&kode_program=${kode_program}&kode_kegiatan=${kode_kegiatan}&kode_kro=${kode_kro}&kode_ro=${kode_ro}`;
            window.location.href = url;
        });

        $(document).on('click', '.deleteBtn', function() {
            const kode_program = $(this).data('kode_program');
            const kode_kegiatan = $(this).data('kode_kegiatan');
            const kode_kro = $(this).data('kode_kro');
            const kode_ro = $(this).data('kode_ro');

            if (confirm('Yakin mau hapus data RO ini?')) {
                $.ajax({
                    url: 'api/delete_ro.php',
                    method: 'POST',
                    data: {
                        kode_program: kode_program,
                        kode_kegiatan: kode_kegiatan,
                        kode_kro: kode_kro,
                        kode_ro: kode_ro
                    },
                    success: function(res) {
                        alert(res);
                        loadTable(1);
                    },
                    error: function() {
                        alert('❌ Error hapus data, coba cek koneksi atau query-nya!');
                    }
                });
            }
        });

    });
</script>