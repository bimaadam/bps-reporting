<?php
// by Hikari Takahashi - Sidebar with Toggle
?>

<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <img class="logo" src="img/logo.svg" alt="BPS Logo">
        </div>
        <div class="sidebar-brand-text mx-0">BPS TASIKMALAYA</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= $current_page == 'index.php' ? 'active' : '' ?>">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu Input
    </div>

    <!-- Nav Item - Input Program -->
    <li class="nav-item <?= $current_page == 'input-program.php' ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProgram"
            aria-expanded="true" aria-controls="collapseProgram">
            <i class="fas fa-fw fa-table"></i>
            <span>Input Program</span>
        </a>
        <div id="collapseProgram" class="collapse <?= $current_page == 'input-program.php' ? 'show' : '' ?>"
            aria-labelledby="headingProgram" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Program Management:</h6>
                <a class="collapse-item <?= $current_page == 'input-program.php' ? 'active' : '' ?>"
                    href="input-program.php">
                    <i class="fas fa-plus-circle mr-2"></i>Input Program
                </a>
                <a class="collapse-item" href="list-program.php">
                    <i class="fas fa-list mr-2"></i>List Program
                </a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Input KRO -->
    <li class="nav-item <?= $current_page == 'input-kro.php' ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKRO"
            aria-expanded="true" aria-controls="collapseKRO">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Input KRO</span>
        </a>
        <div id="collapseKRO" class="collapse <?= $current_page == 'input-kro.php' ? 'show' : '' ?>"
            aria-labelledby="headingKRO" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">KRO Management:</h6>
                <a class="collapse-item <?= $current_page == 'input-kro.php' ? 'active' : '' ?>"
                    href="input-kro.php">
                    <i class="fas fa-plus-circle mr-2"></i>Input KRO
                </a>
                <a class="collapse-item" href="list-kro.php">
                    <i class="fas fa-list mr-2"></i>List KRO
                </a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Input Kegiatan -->
    <li class="nav-item <?= $current_page == 'input-kegiatan.php' ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKegiatan"
            aria-expanded="true" aria-controls="collapseKegiatan">
            <i class="fas fa-fw fa-pencil-alt"></i>
            <span>Input Kegiatan</span>
        </a>
        <div id="collapseKegiatan" class="collapse <?= $current_page == 'input-kegiatan.php' ? 'show' : '' ?>"
            aria-labelledby="headingKegiatan" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Kegiatan Management:</h6>
                <a class="collapse-item <?= $current_page == 'input-kegiatan.php' ? 'active' : '' ?>"
                    href="input-kegiatan.php">
                    <i class="fas fa-plus-circle mr-2"></i>Input Kegiatan
                </a>
                <a class="collapse-item" href="list-kegiatan.php">
                    <i class="fas fa-list mr-2"></i>List Kegiatan
                </a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Input RO -->
    <li class="nav-item <?= $current_page == 'input-ro.php' ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRO"
            aria-expanded="true" aria-controls="collapseRO">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Input RO</span>
        </a>
        <div id="collapseRO" class="collapse <?= $current_page == 'input-ro.php' ? 'show' : '' ?>"
            aria-labelledby="headingRO" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">RO Management:</h6>
                <a class="collapse-item <?= $current_page == 'input-ro.php' ? 'active' : '' ?>"
                    href="input-ro.php">
                    <i class="fas fa-plus-circle mr-2"></i>Input RO
                </a>
                <a class="collapse-item" href="list-ro.php">
                    <i class="fas fa-list mr-2"></i>List RO
                </a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu Laporan
    </div>

    <!-- Nav Item - Rencana -->
    <li class="nav-item <?= $current_page == 'rencana.php' ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRencana"
            aria-expanded="true" aria-controls="collapseRencana">
            <i class="fas fa-fw fa-list"></i>
            <span>Rencana</span>
        </a>
        <div id="collapseRencana" class="collapse <?= $current_page == 'rencana.php' ? 'show' : '' ?>"
            aria-labelledby="headingRencana" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Rencana Management:</h6>
                <a class="collapse-item <?= $current_page == 'rencana.php' ? 'active' : '' ?>"
                    href="rencana.php">
                    <i class="fas fa-calendar-alt mr-2"></i>View Rencana
                </a>
                <a class="collapse-item" href="tambah-rencana.php">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Rencana
                </a>
                <a class="collapse-item" href="laporan-rencana.php">
                    <i class="fas fa-file-pdf mr-2"></i>Laporan Rencana
                </a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Realisasi -->
    <li class="nav-item <?= $current_page == 'realisasi.php' ? 'active' : '' ?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRealisasi"
            aria-expanded="true" aria-controls="collapseRealisasi">
            <i class="fas fa-fw fa-check-circle"></i>
            <span>Realisasi</span>
        </a>
        <div id="collapseRealisasi" class="collapse <?= $current_page == 'realisasi.php' ? 'show' : '' ?>"
            aria-labelledby="headingRealisasi" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Realisasi Management:</h6>
                <a class="collapse-item <?= $current_page == 'realisasi.php' ? 'active' : '' ?>"
                    href="realisasi.php">
                    <i class="fas fa-eye mr-2"></i>View Realisasi
                </a>
                <a class="collapse-item" href="tambah-realisasi.php">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Realisasi
                </a>
                <a class="collapse-item" href="laporan-realisasi.php">
                    <i class="fas fa-file-pdf mr-2"></i>Laporan Realisasi
                </a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu Lainnya
    </div>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Utilities</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Utilities:</h6>
                <a class="collapse-item" href="backup.php">
                    <i class="fas fa-database mr-2"></i>Backup Data
                </a>
                <a class="collapse-item" href="import-export.php">
                    <i class="fas fa-file-import mr-2"></i>Import/Export
                </a>
                <a class="collapse-item" href="settings.php">
                    <i class="fas fa-cog mr-2"></i>Pengaturan
                </a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Help -->
    <li class="nav-item">
        <a class="nav-link" href="help.php">
            <i class="fas fa-fw fa-question-circle"></i>
            <span>Help & Support</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>

    <!-- Sidebar Message -->
    <!-- <div class="sidebar-card d-none d-lg-flex">
        <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
        <p class="text-center mb-2">
            <strong>BPS Admin Pro</strong> dikembangkan oleh <strong>Hikari Takahashi</strong>
        </p>
        <a class="btn btn-success btn-sm" href="https://github.com/hikari-takahashi" target="_blank">
            <i class="fab fa-github mr-1"></i>
            GitHub
        </a>
    </div> -->
</ul>

<style>
    /* Custom Toggle Styles by Hikari Takahashi */
    #sidebarToggle {
        background: linear-gradient(135deg, var(--bps-blue), var(--bps-green));
        color: white;
        width: 2.5rem;
        height: 2.5rem;
        transition: all 0.3s ease;
        border: none !important;
        outline: none !important;
    }

    #sidebarToggle:hover {
        background: linear-gradient(135deg, var(--bps-green), var(--bps-blue));
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    #sidebarToggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 184, 148, 0.5);
    }

    /* Collapse Items */
    .collapse-item {
        padding: 0.5rem 1rem;
        margin: 0.125rem 0;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .collapse-item:hover {
        background-color: var(--bps-light) !important;
        color: var(--bps-blue) !important;
        transform: translateX(5px);
    }

    .collapse-item.active {
        background-color: var(--bps-blue) !important;
        color: white !important;
        font-weight: bold;
    }

    /* Collapse Header */
    .collapse-header {
        color: var(--bps-blue);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Sidebar Card */
    .sidebar-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        border-radius: 1rem;
        padding: 1.5rem;
        margin: 1rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-card .btn {
        transition: all 0.3s ease;
    }

    .sidebar-card .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .sidebar-card {
            display: none !important;
        }
    }

    /* Animation for sidebar toggle */
    .sidebar.toggled {
        width: 6.5rem;
    }

    .sidebar.toggled .sidebar-brand-text {
        display: none;
    }

    .sidebar.toggled .nav-link span {
        display: none;
    }

    .sidebar.toggled .sidebar-heading {
        display: none;
    }

    .sidebar.toggled .sidebar-card {
        display: none !important;
    }
</style>

<script>
    // by Hikari Takahashi - Enhanced Sidebar Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('accordionSidebar');
        const toggleIcon = sidebarToggle.querySelector('i');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();

                // Toggle sidebar
                sidebar.classList.toggle('toggled');

                // Toggle icon
                if (sidebar.classList.contains('toggled')) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                } else {
                    toggleIcon.classList.remove('fa-chevron-right');
                    toggleIcon.classList.add('fa-chevron-left');
                }

                // Save state to localStorage
                localStorage.setItem('sidebarToggled', sidebar.classList.contains('toggled'));
            });
        }

        // Restore sidebar state from localStorage
        const sidebarToggled = localStorage.getItem('sidebarToggled');
        if (sidebarToggled === 'true') {
            sidebar.classList.add('toggled');
            toggleIcon.classList.remove('fa-chevron-left');
            toggleIcon.classList.add('fa-chevron-right');
        }

        // Auto-collapse on mobile
        if (window.innerWidth < 768) {
            sidebar.classList.add('toggled');
        }
    });
</script>