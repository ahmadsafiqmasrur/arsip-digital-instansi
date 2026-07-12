<div class="header-action"
    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Manajemen Petugas</h2>
    <a href="index.php?page=tambah_petugas"
        style="background-color: #0c7a5c; color: white; padding: 12px; text-decoration: none; border-radius: 6px; font-size: 16px; display: inline-block; white-space: nowrap;">+
        Tambah Petugas</a>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"
        style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
        <?= htmlspecialchars($_SESSION['success']);
        unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"
        style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
        <?= htmlspecialchars($_SESSION['error']);
        unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

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
            $stmt = $pdo->query("SELECT * FROM petugas ORDER BY nama ASC");
            $no = 1;
            while ($row = $stmt->fetch()):
                ?>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 12px;"><?= $no++ ?></td>
                    <td style="padding: 12px;"><?= htmlspecialchars($row['nama']) ?></td>
                    <td style="padding: 12px;"><?= htmlspecialchars($row['username']) ?></td>
                    <td style="padding: 12px;">
                        <?php if ($row['role'] == 'koor'): ?>
                            <span
                                style="background-color: #cce5ff; color: #004085; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">Koordinator</span>
                        <?php else: ?>
                            <span
                                style="background-color: #e2e3e5; color: #383d41; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">Petugas</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="index.php?page=data_petugas&id_petugas=<?= $row['id'] ?>"
                            style="color: #0c7a5c; text-decoration: none; margin-right: 15px; font-weight: 600;">Lihat
                            Data</a>
                        <a href="index.php?page=edit_petugas&id=<?= $row['id'] ?>"
                            style="color: #007bff; text-decoration: none; margin-right: 10px;">Edit</a>
                        <?php if ($row['id'] != $_SESSION['user_id']): ?>
                            <a href="proses/petugas.php?action=hapus&id=<?= $row['id'] ?>"
                                style="color: #dc3545; text-decoration: none;"
                                onclick="return confirm('Yakin ingin menghapus petugas ini?');">Hapus</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>