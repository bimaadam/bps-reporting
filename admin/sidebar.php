<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    .logo {
        height: 40px;
        width: 40px;
    }
</style>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <img class="logo" src="img/logo.svg">
        </div>
        <div class="sidebar-brand-text mx-0">BPS TASIKMALAYA</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= $current_page == 'index.php' ? 'active' : '' ?>">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <div class="sidebar-heading">
        Menu Input
    </div>
    <li class="nav-item <?= $current_page == 'input-program.php' ? 'active' : '' ?>">
    <a class="nav-link" href="input-program.php">
        <i class="fas fa-fw fa-table"></i>
        <span>Input Program</span>
    </a>
</li>

<li class="nav-item <?= $current_page == 'input-kro.php' ? 'active' : '' ?>">
    <a class="nav-link" href="input-kro.php">
        <i class="fas fa-fw fa-tasks"></i>
        <span>Input KRO</span>
    </a>
</li>

<li class="nav-item <?= $current_page == 'input-kegiatan.php' ? 'active' : '' ?>">
    <a class="nav-link" href="input-kegiatan.php">
        <i class="fas fa-fw fa-pencil-alt"></i>
        <span>Input Kegiatan</span>
    </a>
</li>

<li class="nav-item <?= $current_page == 'input-ro.php' ? 'active' : '' ?>">
    <a class="nav-link" href="input-ro.php">
        <i class="fas fa-fw fa-chart-line"></i>
        <span>Input RO</span>
    </a>
</li>

<li class="nav-item <?= $current_page == 'rencana.php' ? 'active' : '' ?>">
    <a class="nav-link" href="rencana.php">
        <i class="fas fa-fw fa-list"></i>
        <span>Rencana</span>
    </a>
</li>

<li class="nav-item <?= $current_page == 'realisasi.php' ? 'active' : '' ?>">
    <a class="nav-link" href="realisasi.php">
        <i class="fas fa-fw fa-pencil-alt"></i>
        <span>Realisasi</span>
    </a>
</li>
<li class="nav-item <?= $current_page == 'form_rencana.php' ? 'active' : '' ?>">
    <a class="nav-link" href="form_rencana.php">
        <i class="fas fa-fw fa-book"></i>
        <span>Form Rencana</span>
    </a>
</li>
   

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
