<!-- Header Halaman Manajemen Pengguna -->
<div class="header-action"
    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Manajemen Pengguna</h2>
    <!-- Tombol untuk menambah akun pengguna baru -->
    <a href="<?= site_url('pengguna/tambah') ?>"
        style="background-color: #0c7a5c; color: white; padding: 12px; text-decoration: none; border-radius: 6px; font-size: 16px; display: inline-block; white-space: nowrap;">+
        Tambah Pengguna</a>
</div>

<!-- Kartu Konten Tabel Pengguna -->
<div class="card" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                <th style="padding: 12px; text-align: left;">No</th>
                <th style="padding: 12px; text-align: left;">Nama</th>
                <th style="padding: 12px; text-align: left;">Username</th>
                <th style="padding: 12px; text-align: left;">Role</th>
                <th style="padding: 12px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($pengguna as $row):
                ?>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 12px;"><?= $no++ ?></td>
                    <td style="padding: 12px;"><?= htmlspecialchars($row['nama']) ?></td>
                    <td style="padding: 12px;"><?= htmlspecialchars($row['username']) ?></td>
                    <!-- Menampilkan Badge Role (Koordinator/Petugas) -->
                    <td style="padding: 12px;">
                        <?php if ($row['role'] == 'koor'): ?>
                            <span
                                style="background-color: #cce5ff; color: #004085; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">Koordinator</span>
                        <?php else: ?>
                            <span
                                style="background-color: #e2e3e5; color: #383d41; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">Petugas</span>
                        <?php endif; ?>
                    </td>
                    <!-- Kolom Aksi (Edit, Hapus, Lihat) -->
                    <td style="padding: 12px; text-align: center;">
                        <a href="<?= site_url('pengguna/view_data/'.$row['id']) ?>"
                            style="color: #0c7a5c; text-decoration: none; margin-right: 15px; font-weight: 600;">Lihat
                            Data</a>
                        <a href="<?= site_url('pengguna/edit/'.$row['id']) ?>"
                            style="color: #007bff; text-decoration: none; margin-right: 10px;">Edit</a>
                        <!-- Petugas tidak bisa menghapus dirinya sendiri -->
                        <?php if ($row['id'] != $this->session->userdata('user_id')): ?>
                            <a href="<?= site_url('pengguna/hapus/'.$row['id']) ?>"
                                style="color: #dc3545; text-decoration: none;"
                                onclick="return confirm('Yakin ingin menghapus pengguna ini?');">Hapus</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
