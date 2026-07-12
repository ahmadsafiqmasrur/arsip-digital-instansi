<style>
/* CSS Internal untuk halaman Lihat Data Input Pengguna */
.data-container {
    max-width: 100%;
}
.data-header {
    display: flex;
    justify-content: space-between;
    align-items: center; 
    margin-bottom: 25px;
}
.data-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #000;
    margin: 0;
}
.table-wrapper {
    border: 1px solid #dcdcdc;
    border-radius: 8px;
    padding: 15px;
    background-color: #fff;
    overflow-x: auto;
    margin-bottom: 20px;
}
/* Styling tabel daftar arsip milik pengguna */
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
.data-table td {
    padding: 12px;
    vertical-align: top;
    color: #000;
    border-bottom: 1px solid #f0f0f0;
}
.btn-kembali {
    background-color: #ff5f56; 
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    display: inline-block;
}
</style>

<!-- Header dengan Nama Pengguna dan Tombol Kembali -->
<div class="data-container">
    <div class="data-header">
        <h2 class="data-title">Data yang diinput oleh: <?= htmlspecialchars($user['nama']) ?></h2>
        <a href="<?= site_url('pengguna') ?>" class="btn-kembali">Kembali</a>
    </div>

    <!-- Tabel Daftar Arsip yang Diinput oleh Pengguna Bersangkutan -->
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($arsip) > 0): ?>
                    <?php $no = 1; foreach($arsip as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['no_arsip']) ?></td>
                        <td><?= htmlspecialchars($row['kelurahan']) ?></td>
                        <td><?= date('d/m/Y', strtotime($row['tgl_daftar'])) ?></td>
                        <td><?= htmlspecialchars($row['nama_suami']) ?></td>
                        <td><?= htmlspecialchars($row['nama_istri']) ?></td>
                        <td>
                            <!-- Koordinator bisa mengedit data yang diinput pengguna -->
                            <a href="<?= site_url('arsip/edit/'.$row['id']) ?>" style="color: #007bff; text-decoration: none;">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px;">Belum ada data yang diinput oleh pengguna ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
