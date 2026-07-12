<?php
$kelurahan_filter = $_GET['kelurahan'] ?? '';
$search = $_GET['search'] ?? '';

$query = "SELECT * FROM arsip WHERE 1=1";
$params = [];

if ($kelurahan_filter) {
    $query .= " AND kelurahan = ?";
    $params[] = $kelurahan_filter;
}

if ($search) {
    $query .= " AND (nama_suami LIKE ? OR nama_istri LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$query .= " ORDER BY id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$data = $stmt->fetchAll();
?>

<style>
.data-container {
    max-width: 100%;
}
.data-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start; 
    flex-direction: column;
    margin-bottom: 20px;
}
.data-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #000;
    margin-bottom: 20px;
}
.search-form-kelurahan {
    display: flex;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 4px;
    width: 450px;
    align-self: flex-end; 
}
.search-input-kelurahan {
    border: none;
    outline: none;
    padding: 10px 15px;
    flex-grow: 1;
    font-size: 0.95rem;
    background: transparent;
    font-family: 'Inter', sans-serif;
}
.search-input-kelurahan::placeholder {
    color: #999;
}
.btn-cari-kelurahan {
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
.btn-cari-kelurahan:hover {
    background-color: #0069d9;
}

.table-wrapper {
    border: 1px solid #dcdcdc;
    border-radius: 8px;
    padding: 15px;
    background-color: #fff;
    overflow-x: auto;
    margin-bottom: 20px;
}
.data-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
    font-size: 0.95rem;
}
.data-table th {
    background-color: #dcdcdc;
    padding: 12px;
    font-weight: 700;
    color: #000;
}
.data-table th:first-child {
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
}
.data-table th:nth-child(6) {
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
}
.data-table th.action-th {
    background-color: transparent; 
    width: 220px;
}
.data-table td {
    padding: 12px 12px 20px 12px;
    vertical-align: top;
    color: #000;
    border-bottom: 1px solid #f0f0f0;
}
.data-table td.action-td {
    padding: 12px 0 20px 12px;
}
.action-buttons-row {
    display: flex;
    gap: 8px;
}
.btn-edit-row {
    background-color: #2bd64b;
    color: white;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.85rem;
    flex: 1;
    text-align: center;
    border: 1px solid #28c746;
}
.btn-hapus-row {
    background-color: #ff5f56;
    color: white;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.85rem;
    flex: 1;
    text-align: center;
    border: 1px solid #fa5248;
}
.btn-cetak-row {
    background-color: #ffb822;
    color: white;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.85rem;
    flex: 1;
    text-align: center;
    border: 1px solid #f5b01d;
}

.bottom-actions {
    display: flex;
    margin-top: 20px;
}
.btn-kembali {
    background-color: #ff5f56; 
    color: white;
    padding: 12px 25px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    display: inline-block;
    font-family: 'Inter', sans-serif;
    transition: background-color 0.2s;
}
.btn-kembali:hover {
    background-color: #e04e46;
}

@media (max-width: 768px) {
    .search-form-kelurahan {
        width: 100%;
        align-self: flex-start;
    }
}
</style>

<div class="data-container">
    <div class="data-header">
        <h2 class="data-title">Data Arsip <?= $kelurahan_filter ? " - " . htmlspecialchars($kelurahan_filter) : "" ?></h2>
        
        <form action="index.php" method="GET" class="search-form-kelurahan">
            <input type="hidden" name="page" value="data_kelurahan">
            <?php if($kelurahan_filter): ?>
                <input type="hidden" name="kelurahan" value="<?= htmlspecialchars($kelurahan_filter) ?>">
            <?php endif; ?>
            <input type="text" name="search" class="search-input-kelurahan" placeholder="Cari" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn-cari-kelurahan">Cari</button>
            <?php if($search): ?>
                <a href="index.php?page=data_kelurahan<?= $kelurahan_filter ? '&kelurahan='.$kelurahan_filter : '' ?>" style="display:flex; align-items:center; padding: 0 15px; color: #d63031; text-decoration:none;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div style="background-color: #e5f9ed; color: #2ecc71; padding: 15px; border-radius: 8px; border: 1px solid #ccf2dc; margin-bottom: 20px;">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Arsip</th>
                    <th>Kelurahan</th>
                    <th>Tanggal Daftar</th>
                    <th>Nama Suami</th>
                    <th>Nama Istri</th>
                    <th class="action-th"></th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($data) > 0): ?>
                    <?php $no = 1; foreach($data as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['no_arsip']) ?></td>
                        <td><?= htmlspecialchars($row['kelurahan']) ?></td>
                        <td><?= date('d/m/Y', strtotime($row['tgl_daftar'])) ?></td>
                        <td><?= nl2br(htmlspecialchars(str_replace(' ', "\n", $row['nama_suami']))) ?></td>
                        <td><?= nl2br(htmlspecialchars(str_replace(' ', "\n", $row['nama_istri']))) ?></td>
                        <td class="action-td">
                            <div class="action-buttons-row">
                                <a href="index.php?page=edit&id=<?= $row['id'] ?>" class="btn-edit-row">Edit</a>
                                <a href="proses/arsip.php?action=hapus&id=<?= $row['id'] ?>" class="btn-hapus-row" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                <a href="pdf/cetak.php?id=<?= $row['id'] ?>" class="btn-cetak-row" target="_blank">Cetak</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px;">Data tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="bottom-actions">
        <a href="index.php" class="btn-kembali">Kembali ke Dashboard</a>
    </div>
</div>
