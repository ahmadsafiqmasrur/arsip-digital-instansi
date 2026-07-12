<style>
    /* CSS Internal untuk halaman Daftar Pencarian Cetak */
    .cetak-container {
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
        border-color: #ffb822; 
    }
    .btn-search-large {
        background-color: #ffb822;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .btn-search-large:hover {
        background-color: #e6a71e;
    }
    .results-table-wrapper {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        overflow-x: auto;
    }
    .cetak-table {
        width: 100%;
        border-collapse: collapse;
    }
    .cetak-table th {
        text-align: left;
        padding: 15px;
        background-color: #f8f9fa;
        color: #333;
        font-weight: 700;
        border-bottom: 2px solid #eee;
    }
    .cetak-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }
    .btn-action-cetak {
        background-color: #ffb822;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: opacity 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-action-cetak:hover {
        opacity: 0.9;
    }
    .no-results {
        text-align: center;
        padding: 40px;
        color: #666;
    }
</style>

<div class="cetak-container">
    <h2 class="page-title">Cetak Dokumen Arsip</h2>

    <!-- Form Pencarian Khusus Modul Cetak -->
    <div class="search-box">
        <p style="margin-top: 0; margin-bottom: 15px; font-weight: 500; color: #444;">Cari data yang ingin dicetak (No. Arsip atau Nama)</p>
        <form action="<?= site_url('arsip/cetak_arsip') ?>" method="GET" class="search-form-centered">
            <input type="text" name="search_cetak" class="search-input-large" placeholder="Masukkan kata kunci..." value="<?= htmlspecialchars($search_cetak ?? '') ?>" required>
            <button type="submit" class="btn-search-large">
                <i class="fas fa-search"></i> Cari Data
            </button>
        </form>
    </div>

    <!-- Tabel Hasil Pencarian -->
    <?php if (!empty($search_cetak)): ?>
    <div class="results-table-wrapper">
        <?php if (count($results) > 0): ?>
        <table class="cetak-table">
            <thead>
                <tr>
                    <th>No. Arsip</th>
                    <th>Nama Suami</th>
                    <th>Nama Istri</th>
                    <th>Kelurahan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['no_arsip']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nama_suami']) ?></td>
                    <td><?= htmlspecialchars($row['nama_istri']) ?></td>
                    <td><?= htmlspecialchars($row['kelurahan']) ?></td>
                    <td>
                        <!-- Tombol arahkan ke fungsi cetak PDF di controller -->
                        <a href="<?= site_url('arsip/cetak/'.$row['id']) ?>" class="btn-action-cetak" target="_blank">
                            <i class="fas fa-print"></i> Cetak PDF
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="no-results">
            <i class="fas fa-search" style="font-size: 3rem; color: #ddd; margin-bottom: 15px; display: block;"></i>
            <p>Tidak ada data yang ditemukan untuk "<strong><?= htmlspecialchars($search_cetak) ?></strong>"</p>
        </div>
        <?php endif; ?>
    </div>
    <?php else: ?>
        <p style="color: #856404; font-size: 1.1rem; margin: 0;">Silakan cari data terlebih dahulu untuk mencetak dokumen PDF.</p>
    <?php endif; ?>
</div>
