<?php

$search = $_GET['search'] ?? '';


$kelurahans = ['Canden', 'Patalan', 'Sumberagung', 'Trimulyo'];


$counts = [];
foreach ($kelurahans as $kel) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM arsip WHERE kelurahan = ?");
    $stmt->execute([$kel]);
    $counts[$kel] = $stmt->fetch()['total'];
}

$filter = $_GET['filter'] ?? 'all';
$where_clauses = [];
$params = [];

if (!empty($search)) {
    $where_clauses[] = "(no_arsip LIKE ? OR no_akta LIKE ? OR nama_suami LIKE ? OR nama_istri LIKE ? OR nama_saksi LIKE ? OR nik_suami LIKE ? OR nik_istri LIKE ? OR nama_penghulu LIKE ? OR alamat_suami LIKE ? OR alamat_istri LIKE ?)";
    $search_param = "%$search%";
    for ($i = 0; $i < 10; $i++) {
        $params[] = $search_param;
    }
}

if ($filter == '1day') {
    $where_clauses[] = "created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
} elseif ($filter == '7days') {
    $where_clauses[] = "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
} elseif ($filter == '1month') {
    $where_clauses[] = "created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
}

$where_sql = "";
if (!empty($where_clauses)) {
    $where_sql = "WHERE " . implode(" AND ", $where_clauses);
}

$stmt = $pdo->prepare("SELECT * FROM arsip $where_sql ORDER BY created_at DESC");
$stmt->execute($params);
$results = $stmt->fetchAll();
?>

<style>

.user-card {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #e0e0e0;
    margin-bottom: 50px;
}
.user-info {
    display: flex;
    align-items: center;
    gap: 15px;
}
.user-avatar {
    width: 50px;
    height: 50px;
    background-color: #000;
    border-radius: 50%;
}
.user-text h3 {
    margin: 0 0 5px 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: #000;
}
.user-text p {
    margin: 0;
    font-size: 0.9rem;
    color: #666;
}
.logout-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #333;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    transition: color 0.2s;
}
.logout-btn:hover {
    color: #d63031;
}
.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}
.btn-tambah {
    background-color: #0c7a5c; 
    color: #fff;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 600;
    transition: background-color 0.2s;
}
.btn-tambah:hover {
    background-color: #09634a;
}
.search-form {
    display: flex;
    gap: 0;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 4px;
    width: 450px;
}
.search-input {
    border: none;
    outline: none;
    padding: 10px 15px;
    flex-grow: 1;
    font-size: 0.95rem;
    background: transparent;
    font-family: 'Inter', sans-serif;
}
.search-input::placeholder {
    color: #999;
}
.btn-cari {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 30px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 500;
    font-family: 'Inter', sans-serif;
}
.btn-cari:hover {
    background-color: #0069d9;
}
.kelurahan-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
.kelurahan-card {
    background-color: #bfeadd; 
    border-radius: 12px;
    padding: 25px 20px;
    text-decoration: none;
    color: #000;
    display: block;
    transition: transform 0.2s, box-shadow 0.2s;
}
.kelurahan-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.kelurahan-card h4 {
    margin: 0 0 8px 0;
    font-size: 1.1rem;
    font-weight: 600;
}
.kelurahan-card p {
    margin: 0;
    font-size: 0.95rem;
    color: #333;
}

/* Responsif */
@media (max-width: 1024px) {
    .kelurahan-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .action-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }
    .search-form {
        width: 100%;
    }
}
@media (max-width: 768px) {
    .kelurahan-grid {
        grid-template-columns: 1fr;
    }
    .user-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }
}

.filter-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    align-items: center;
}
.filter-btn {
    padding: 8px 16px;
    border-radius: 20px;
    border: 1px solid #dcdcdc;
    background: #fff;
    color: #666;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s;
}
.filter-btn.active {
    background-color: #0c7a5c;
    color: #fff;
    border-color: #0c7a5c;
}
.filter-btn:hover:not(.active) {
    background-color: #f4f5f7;
}
</style>

<div class="user-card">
    <div class="user-info">
        
        <div class="user-avatar"></div>
        <div class="user-text">
            <h3>Selamat bekerja</h3>
            <p><?= htmlspecialchars($_SESSION['username'] ?? 'username'); ?></p>
        </div>
    </div>
    <a href="auth/logout.php" class="logout-btn" onclick="return confirm('Apakah anda yakin ingin keluar?');">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        Logout
    </a>
</div>

<div class="action-bar">
    <a href="index.php?page=tambah" class="btn-tambah">Tambah Arsip Baru</a>
    <form action="index.php" method="GET" class="search-form">
        <input type="text" name="search" class="search-input" placeholder="Cari" value="<?= htmlspecialchars($search) ?>" required>
        <button type="submit" class="btn-cari">Cari</button>
    </form>
</div>

<div class="kelurahan-grid">
    <?php foreach ($kelurahans as $kel): ?>
    <a href="index.php?page=data_kelurahan&kelurahan=<?= $kel ?>" class="kelurahan-card">
        <h4><?= $kel ?></h4>
        <p><?= $counts[$kel] ?> Arsip</p>
    </a>
    <?php endforeach; ?>
</div>

<div style="margin-top: 50px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 1.2rem; font-weight: 600; color: #000;">
            <?= !empty($search) ? 'Hasil Pencarian: "' . htmlspecialchars($search) . '"' : 'Data Terkini' ?>
        </h3>
        
        <div class="filter-bar">
            <span style="font-size: 0.9rem; color: #666; margin-right: 5px;">Filter:</span>
            <a href="index.php?filter=all<?= !empty($search) ? '&search='.urlencode($search) : '' ?>" class="filter-btn <?= $filter == 'all' ? 'active' : '' ?>">Semua</a>
            <a href="index.php?filter=1day<?= !empty($search) ? '&search='.urlencode($search) : '' ?>" class="filter-btn <?= $filter == '1day' ? 'active' : '' ?>">Hari ini</a>
            <a href="index.php?filter=7days<?= !empty($search) ? '&search='.urlencode($search) : '' ?>" class="filter-btn <?= $filter == '7days' ? 'active' : '' ?>">7 Hari</a>
            <a href="index.php?filter=1month<?= !empty($search) ? '&search='.urlencode($search) : '' ?>" class="filter-btn <?= $filter == '1month' ? 'active' : '' ?>">1 Bulan</a>
        </div>
    </div>

    <?php if (count($results) > 0): ?>
        <div style="border: 1px solid #dcdcdc; border-radius: 8px; padding: 15px; background-color: #fff; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
                <thead>
                    <tr>
                        <th style="background-color: #dcdcdc; padding: 12px; font-weight: 700; color: #000; border-top-left-radius: 4px; border-bottom-left-radius: 4px;">No</th>
                        <th style="background-color: #dcdcdc; padding: 12px; font-weight: 700; color: #000;">No. Arsip</th>
                        <th style="background-color: #dcdcdc; padding: 12px; font-weight: 700; color: #000;">Kelurahan</th>
                        <th style="background-color: #dcdcdc; padding: 12px; font-weight: 700; color: #000;">Tanggal Daftar</th>
                        <th style="background-color: #dcdcdc; padding: 12px; font-weight: 700; color: #000;">Nama Suami</th>
                        <th style="background-color: #dcdcdc; padding: 12px; font-weight: 700; color: #000;">Nama Istri</th>
                        <th style="padding: 12px; width: 220px;">Aksi</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($results as $row): ?>
                        <tr>
                            <td style="padding: 12px; vertical-align: top; color: #000; border-bottom: 1px solid #f0f0f0;"><?= $no++ ?></td>
                            <td style="padding: 12px; vertical-align: top; color: #000; border-bottom: 1px solid #f0f0f0;"><?= htmlspecialchars($row['no_arsip']) ?></td>
                            <td style="padding: 12px; vertical-align: top; color: #000; border-bottom: 1px solid #f0f0f0;"><?= htmlspecialchars($row['kelurahan']) ?></td>
                            <td style="padding: 12px; vertical-align: top; color: #000; border-bottom: 1px solid #f0f0f0;"><?= date('d/m/Y', strtotime($row['tgl_daftar'])) ?></td>
                            <td style="padding: 12px; vertical-align: top; color: #000; border-bottom: 1px solid #f0f0f0;"><?= htmlspecialchars($row['nama_suami']) ?></td>
                            <td style="padding: 12px; vertical-align: top; color: #000; border-bottom: 1px solid #f0f0f0;"><?= htmlspecialchars($row['nama_istri']) ?></td>
                            <td style="padding: 12px; vertical-align: top; border-bottom: 1px solid #f0f0f0;">
                                <div style="display: flex; gap: 8px;">
                                    <a href="index.php?page=edit&id=<?= $row['id'] ?>" style="background-color: #2bd64b; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.85rem;">Edit</a>
                                    <a href="proses/arsip.php?action=hapus&id=<?= $row['id'] ?>" style="background-color: #ff5f56; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.85rem;" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                    <a href="pdf/cetak.php?id=<?= $row['id'] ?>" style="background-color: #ffb822; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.85rem;" target="_blank">Cetak</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div style="background-color: #f8f9fa; color: #666; padding: 30px; border-radius: 8px; border: 1px solid #e0e0e0; text-align: center;">
            Tidak ada data yang ditemukan.
        </div>
    <?php endif; ?>
</div>
