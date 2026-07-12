<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: landing.php");
    exit;
}

require_once 'config/database.php';

$page = $_GET['page'] ?? 'dashboard';

$admin_pages = ['petugas', 'tambah_petugas', 'edit_petugas'];
if (in_array($page, $admin_pages) && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'koor')) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Arsip Pernikahan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .top-navbar {
            background-color: #0c7a5c;
            height: 60px;
            display: flex;
            align-items: center;
            padding: 0 30px;
        }
        .top-navbar img.logo {
            width: 40px;
            height: 40px;
            background-color: transparent
            object-fit: contain;
        }
        .app-container {
            display: flex;
            min-height: calc(100vh - 60px);
        }
        .sidebar {
            width: 250px;
            background-color: #f8f9fa;
            padding: 30px 20px;
            box-sizing: border-box;
            border-right: 1px solid transparent;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            color: #333333;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 12px 20px;
            margin-bottom: 5px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .sidebar a i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }
        .sidebar a:hover {
            background-color: #e9ecef;
        }
        .sidebar a.active {
            background-color: #0c7a5c;
            color: #ffffff;
        }
        .sidebar a.active i {
            color: #ffffff;
        }
        .main-content {
            flex-grow: 1;
            padding: 40px;
            background-color: #f8f9fa;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <div class="top-navbar">
        <!-- Logo (Kotak Hitam) -->
        <img src="kemenag.png" alt="Logo" class="logo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjMDAwIi8+PC9zdmc+'">
    </div>

    <div class="app-container">
        <aside class="sidebar">
            <a href="index.php?page=dashboard" class="<?= $page == 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="index.php?page=tambah" class="<?= $page == 'tambah' ? 'active' : '' ?>">
                <i class="fas fa-plus"></i> Tambah Arsip
            </a>
            <a href="index.php?page=data_kelurahan" class="<?= $page == 'data_kelurahan' ? 'active' : '' ?>">
                <i class="fas fa-eye"></i> Lihat Arsip
            </a>
            <a href="index.php?page=edit_arsip" class="<?= $page == 'edit_arsip' ? 'active' : '' ?>">
                <i class="fas fa-pen"></i> Edit Arsip
            </a>
            <a href="index.php?page=arsip_scan" class="<?= $page == 'arsip_scan' ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i> Arsip Scan
            </a>
            <a href="index.php?page=cetak_arsip" class="<?= $page == 'cetak_arsip' ? 'active' : '' ?>">
                <i class="fas fa-print"></i> Cetak Arsip
            </a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'koor'): ?>
            <a href="index.php?page=petugas" class="<?= in_array($page, ['petugas', 'tambah_petugas', 'edit_petugas']) ? 'active' : '' ?>">
                <i class="fas fa-users-cog"></i> Manajemen Petugas
            </a>
            <?php endif; ?>
        </aside>

        <main class="main-content">
            <?php
            $file = "pages/{$page}.php";
            if (file_exists($file)) {
                include $file;
            } else {
                echo "<div class='alert alert-danger'>Halaman tidak ditemukan!</div>";
            }
            ?>
        </main>
    </div>
</body>

</html>