<style>
    /* CSS Internal untuk halaman Daftar Pencarian Edit */
    .search-edit-container {
        max-width: 1000px;
        margin: 0 auto 40px 0;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 25px;
    }
    .search-box {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .search-form-centered {
        display: flex;
        gap: 10px;
        max-width: 600px;
    }
    .search-input-large {
        flex-grow: 1;
        padding: 12px 20px;
        border: 1px solid #dcdcdc;
        border-radius: 8px;
        font-size: 1rem;
        outline: none;
        font-family: 'DM Sans', sans-serif;
    }
    .search-input-large:focus {
        border-color: #0c7a5c;
    }
    .btn-search-large {
        background-color: #0c7a5c;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .btn-search-large:hover {
        background-color: #09634a;
    }
    .results-table-wrapper {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        overflow-x: auto;
    }
    .edit-table {
        width: 100%;
        border-collapse: collapse;
    }
    .edit-table th {
        text-align: left;
        padding: 15px;
        background-color: #f8f9fa;
        color: #333;
        font-weight: 700;
        border-bottom: 2px solid #eee;
    }
    .edit-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }
    .btn-action-edit {
        background-color: #2bd64b;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: opacity 0.2s;
    }
    .btn-action-edit:hover {
        opacity: 0.9;
    }
    .no-results {
        text-align: center;
        padding: 40px;
        color: #666;
    }
</style>

<div class="search-edit-container">
    <h2 class="page-title">Pencarian Data untuk Diedit</h2>

    <!-- Form Pencarian Khusus Modul Edit -->
    <div class="search-box">
        <p style="margin-top: 0; margin-bottom: 15px; font-weight: 500; color: #444;">Masukkan No. Arsip atau Nama Suami/Istri</p>
        <form action="<?= site_url('arsip/edit_list') ?>" method="GET" class="search-form-centered">
            <input type="text" name="search_edit" class="search-input-large" placeholder="Cari data..." value="<?= htmlspecialchars($search_edit ?? '') ?>" required>
            <button type="submit" class="btn-search-large">Cari Data</button>
        </form>
    </div>

    <!-- Menampilkan Tabel Hasil Pencarian jika ada input -->
    <?php if (!empty($search_edit)): ?>
    <div class="results-table-wrapper">
        <?php if (count($results) > 0): ?>
        <table class="edit-table">
            <thead>
                <tr>
                    <th>No. Arsip</th>
                    <th>Nama Suami</th>
                    <th>Nama Istri</th>
                    <th>Kelurahan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                <?php
                    $status = $row['edit_request_status'] ?? null;
                    $isApproved = $status === 'approved';
                    $isPending = $status === 'pending';
                ?>
                <tr data-arsip-id="<?= $row['id'] ?>">
                    <td><strong><?= htmlspecialchars($row['no_arsip']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nama_suami']) ?></td>
                    <td><?= htmlspecialchars($row['nama_istri']) ?></td>
                    <td><?php echo htmlspecialchars($row['kelurahan']) ?></td>
                    <td class="status-cell">
                        <?php if ($isApproved): ?>
                            <span class="status-dot status-approved" title="Disetujui"></span> Disetujui
                        <?php elseif ($isPending): ?>
                            <span class="status-dot status-pending" title="Menunggu Persetujuan"></span> Menunggu Persetujuan
                        <?php elseif ($status === 'rejected'): ?>
                            <span class="status-dot status-rejected" title="Ditolak"></span> Ditolak
                        <?php else: ?>
                            <!-- Belum diajukan: tampilkan teks netral tanpa titik merah -->
                            <span class="status-text">Belum Diajukan</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($isApproved): ?>
                            <a href="<?= site_url('arsip/edit/' . $row['id']) ?>" class="btn-action-edit">Edit</a>
                        <?php else: ?>
                            <button type="button" class="btn-action-edit request-btn" data-id="<?php echo $row['id']; ?>">Minta Edit</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <!-- Tampilan jika data tidak ditemukan -->
        <div class="no-results">
            <i class="fas fa-search" style="font-size: 3rem; color: #ddd; margin-bottom: 15px; display: block;"></i>
            <p>Tidak ada data yang ditemukan untuk "<strong><?= htmlspecialchars($search_edit) ?></strong>"</p>
        </div>
        <?php endif; ?>
    </div>
    <?php else: ?>
        <!-- Pesan awal sebelum mencari -->
        <p style="color: #4a5568; font-size: 1.1rem; margin: 0;">Silakan masukkan kata kunci pada kolom pencarian di atas untuk menemukan data.</p>
    <?php endif; ?>
<?php $this->load->view('pengguna/edit_request_modal'); ?>

