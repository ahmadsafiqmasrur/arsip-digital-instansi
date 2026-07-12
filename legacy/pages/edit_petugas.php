<?php
if (!isset($_GET['id'])) {
    header("Location: index.php?page=petugas");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM petugas WHERE id = ?");
$stmt->execute([$id]);
$petugas = $stmt->fetch();

if (!$petugas) {
    header("Location: index.php?page=petugas");
    exit;
}
?>

<div class="header-action" style="margin-bottom: 20px;">
    <h2>Edit Petugas</h2>
</div>

<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <div class="card" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); flex: 1; min-width: 300px;">
        <h3 style="margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Data Profil</h3>
        <form action="proses/petugas.php?action=edit" method="POST">
            <input type="hidden" name="id" value="<?= $petugas['id'] ?>">
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($petugas['nama']) ?>" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Username</label>
                <input type="text" value="<?= htmlspecialchars($petugas['username']) ?>" disabled style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box; background-color: #e9ecef;">
                <small style="color: #6c757d;">Username tidak dapat diubah.</small>
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Role</label>
                <select name="role" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box;" <?= ($petugas['id'] == $_SESSION['user_id']) ? 'disabled' : '' ?>>
                    <option value="petugas" <?= $petugas['role'] == 'petugas' ? 'selected' : '' ?>>Petugas</option>
                    <option value="koor" <?= $petugas['role'] == 'koor' ? 'selected' : '' ?>>Koordinator</option>
                </select>
                <?php if ($petugas['id'] == $_SESSION['user_id']): ?>
                    <input type="hidden" name="role" value="<?= $petugas['role'] ?>">
                    <small style="color: #6c757d;">Anda tidak dapat mengubah role Anda sendiri.</small>
                <?php endif; ?>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background-color: #0c7a5c; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">Simpan Perubahan</button>
                <a href="index.php?page=petugas" style="background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Kembali</a>
            </div>
        </form>
    </div>

    <div class="card" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); flex: 1; min-width: 300px;">
        <h3 style="margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Reset Password</h3>
        <form action="proses/petugas.php?action=reset_password" method="POST">
            <input type="hidden" name="id" value="<?= $petugas['id'] ?>">
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Password Baru</label>
                <input type="password" name="password" required minlength="6" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box;">
                <small style="color: #6c757d;">Minimal 6 karakter.</small>
            </div>
            
            <button type="submit" onclick="return confirm('Yakin ingin mereset password pengguna ini?');" style="background-color: #ffc107; color: #212529; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: 500;">Reset Password</button>
        </form>
    </div>
</div>
