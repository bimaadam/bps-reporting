<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Belum login, tendang ke login page
    header("Location: login.html");
    exit();
}
