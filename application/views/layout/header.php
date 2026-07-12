<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Sistem Arsip Akta Nikah KUA Jetis</title>
    <!-- Memuat Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=El+Messiri:wght@400..700&family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Space+Grotesk:wght@300..700&family=TikTok+Sans:opsz,wght@12..36,300..900&family=Zalando+Sans+SemiExpanded:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">
    <!-- Memuat Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Memuat file CSS utama -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
/* Badge */
.pending-badge {display:none; background:linear-gradient(135deg,#ff6b6b,#f06595); color:#fff; padding:2px 6px; border-radius:12px; font-size:0.75rem; margin-left:6px;}
/* Notification */
.notification-menu {position: relative; margin-left: auto; display: flex; align-items: center;}
.notification-toggle {background: transparent; border: none; color: white; cursor: pointer; font-size: 1.1rem; position: relative; display: inline-flex; align-items: center; gap: 6px;}
.notification-toggle .pending-badge {margin-left: 4px;}
.notification-dropdown {position: absolute; top: 38px; right: 0; width: 320px; max-height: 420px; overflow-y: auto; background: #ffffff; border: 1px solid #dae1e7; border-radius: 12px; box-shadow: 0 20px 50px rgba(0,0,0,0.12); display: none; z-index: 999;}
.notification-dropdown.open {display: block;}
.notification-item {padding: 14px 16px; border-bottom: 1px solid #edf2f7;}
.notification-item:last-child {border-bottom: none;}
.notification-item h4 {margin: 0 0 6px; font-size: 0.95rem; color: #1f2937;}
.notification-item p {margin: 0 6px 10px; color: #4b5563; font-size: 0.88rem; line-height: 1.4;}
.notification-item small {display: block; color: #6b7280; margin-bottom: 10px;}
.notification-action {display: flex; justify-content: flex-end; gap: 8px;}
.notification-action button {background: #0c7a5c; color: #fff; border: none; border-radius: 8px; padding: 8px 12px; font-size: 0.88rem; cursor: pointer;}
.notification-action button:hover {background: #0a684f;}
.notification-action button.reject-btn {background: #dc3545;}
.notification-action button.reject-btn:hover {background: #c62828;}
.notification-empty {padding: 20px; text-align: center; color: #6b7280;}

/* Status dot */
.status-dot {display:inline-block; width:12px; height:12px; border-radius:50%; margin-right:4px;}
.status-pending {background:#f1c40f;}
.status-approved {background:#2ecc71;}
.status-rejected {background:#e74c3c;}

        /* Styling dasar Layout */
        body {
            margin: 0;
            padding: 0;
            font-family: 'DM Sans', sans-serif;
            background-color: #f8f9fa;
        }

        /* Styling Navbar Atas */
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
            background-color: transparent;
            object-fit: contain;
        }

        /* Container utama Aplikasi */
        .app-container {
            display: flex;
            min-height: calc(100vh - 60px);
        }

        /* Styling Sidebar (Menu Samping) */
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

        /* Konten Utama di sebelah kanan sidebar */
        .main-content {
            flex-grow: 1;
            padding: 40px;
            background-color: #f8f9fa;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <!-- Navbar Atas -->
    <div class="top-navbar">
        <img src="<?= base_url('assets/images/kemenag.png') ?>" alt="Logo" class="logo"
            onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjMDAwIi8+PC9zdmc+'">
        <span
            style="color: white; font-weight: 700; margin-left: 15px; font-size: 1.1rem; letter-spacing: 0.5px;">Sistem
            Arsip Akta Nikah KUA Jetis</span>
        <?php if (in_array($this->session->userdata('role'), ['koor', 'petugas'])): ?>
        <div class="notification-menu">
            <button id="notificationToggle" class="notification-toggle" type="button" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span>Notifikasi</span>
                <span id="notificationBadge" class="pending-badge"></span>
            </button>
            <div id="notificationDropdown" class="notification-dropdown">
                <div class="notification-empty">Memuat permintaan...</div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="app-container">
        <!-- Sidebar Menu Navigasi -->
        <aside class="sidebar">
            <a href="<?= site_url('dashboard') ?>" class="<?= $this->uri->segment(1) == 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="<?= site_url('arsip/tambah') ?>"
                class="<?= $this->uri->segment(1) == 'arsip' && $this->uri->segment(2) == 'tambah' ? 'active' : '' ?>">
                <i class="fas fa-plus"></i> Tambah Arsip
            </a>
            <a href="<?= site_url('arsip') ?>"
                class="<?= $this->uri->segment(1) == 'arsip' && $this->uri->segment(2) == '' ? 'active' : '' ?>">
                <i class="fas fa-eye"></i> Lihat Arsip
            </a>
            <a href="<?php echo site_url('arsip/edit_list') ?>" class="<?php echo $this->uri->segment(1) == 'arsip' && $this->uri->segment(2) == 'edit_list' ? 'active' : '' ?>">
                <i class="fas fa-pen"></i> Edit Arsip</a>
            <a href="<?= site_url('arsip_scan') ?>"
                class="<?= $this->uri->segment(1) == 'arsip_scan' ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i> Arsip Scan
            </a>
            <a href="<?= site_url('arsip/cetak_arsip') ?>"
                class="<?= $this->uri->segment(1) == 'arsip' && $this->uri->segment(2) == 'cetak_arsip' ? 'active' : '' ?>">
                <i class="fas fa-print"></i> Cetak Arsip
            </a>
            <!-- Menu Khusus Koordinator -->
            <?php if ($this->session->userdata('role') == 'koor'): ?>
                <a href="<?= site_url('pengguna') ?>" class="<?= $this->uri->segment(1) == 'pengguna' ? 'active' : '' ?>">
                    <i class="fas fa-users-cog"></i> Manajemen Pengguna
                </a>
            <?php endif; ?>
        </aside>

        <!-- Area Konten Utama -->
        <main class="main-content">
            <!-- Alert Pesan Sukses -->
            <?php if ($this->session->flashdata('success')): ?>
                <div
                    style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>
            <!-- Alert Pesan Error -->
            <?php if ($this->session->flashdata('error')): ?>
                <div
                    style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>