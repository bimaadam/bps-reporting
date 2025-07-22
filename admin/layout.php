<?php
// by Hikari Takahashi

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Dashboard' ?> - BPS Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        :root {
            --bps-blue: #005690;
            --bps-green: #00b894;
            --bps-light: #f0f4f8;
            --hover-dark: #003b63;
        }

        .logo {
            height: 40px;
            width: 40px;
        }

        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, var(--bps-blue), var(--bps-green));
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar-brand-icon {
            transition: transform 0.3s ease;
        }

        .sidebar-brand-icon:hover {
            transform: scale(1.1);
        }

        .nav-item .nav-link {
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 2px 8px;
        }

        .nav-item .nav-link:hover {
            background-color: var(--hover-dark);
            transform: translateX(5px);
        }

        .nav-item.active>.nav-link {
            background-color: #fff;
            color: var(--bps-blue) !important;
            font-weight: bold;
        }

        .sidebar-divider {
            border-top: 1px dashed rgba(255, 255, 255, 0.4);
        }

        /* Navbar Styles */
        .navbar {
            background-color: var(--bps-blue) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1000;
        }

        .navbar .nav-link,
        .navbar .navbar-brand {
            color: white !important;
        }

        .navbar .nav-link:hover {
            color: var(--bps-green) !important;
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        /* Dropdown Fixes */
        .dropdown-menu {
            border-radius: 10px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 1050 !important;
            position: absolute !important;
            top: 100% !important;
            right: 0 !important;
            left: auto !important;
            transform: none !important;
            margin-top: 5px;
        }

        .dropdown-menu.show {
            display: block !important;
        }

        .dropdown-item {
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: var(--bps-light);
            color: var(--bps-blue);
            transform: translateX(3px);
        }

        /* Profile Image */
        .img-profile {
            width: 2rem;
            height: 2rem;
            border: 2px solid rgba(255, 255, 255, 0.5);
            transition: border-color 0.3s ease;
        }

        .img-profile:hover {
            border-color: white;
        }

        /* Content Area */
        .container-fluid {
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* Footer */
        .sticky-footer {
            background: linear-gradient(135deg, var(--bps-blue), var(--bps-green));
            color: white;
        }

        /* Scroll Button */
        .scroll-to-top {
            background: linear-gradient(135deg, var(--bps-blue), var(--bps-green));
            transition: all 0.3s ease;
        }

        .scroll-to-top:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        /* Modal Styles */
        .modal-header {
            background: linear-gradient(135deg, var(--bps-blue), var(--bps-green));
            color: white;
        }

        .modal-content {
            border-radius: 10px;
            border: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dropdown-menu {
                position: fixed !important;
                top: 60px !important;
                right: 10px !important;
                left: auto !important;
                width: 250px;
            }
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-dark topbar static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-white small"><?= htmlspecialchars($username) ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg" alt="Profile">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    <?= $content ?? '' ?>
                </div>
            </div>

            <footer class="sticky-footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; BPS Tasik <?= date('Y') ?> </span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Siap untuk Keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Pilih "Keluar" di bawah jika Anda siap untuk mengakhiri sesi Anda saat ini.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="api/logout.php">Keluar</a>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        // by Hikari Takahashi - Dropdown fix
        $(document).ready(function() {
            // Ensure dropdown stays on top
            $('.dropdown-toggle').on('click', function(e) {
                e.preventDefault();
                var dropdown = $(this).next('.dropdown-menu');
                $('.dropdown-menu').not(dropdown).removeClass('show');
                dropdown.toggleClass('show');
            });

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('show');
                }
            });
        });
    </script>
</body>

</html>