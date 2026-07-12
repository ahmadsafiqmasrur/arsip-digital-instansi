<style>
    /* CSS Internal untuk halaman Edit Pengguna */
    .tambah-container {
        max-width: 600px;
        margin: 0 auto;
    }
    .tambah-card {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 30px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }
    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #dcdcdc;
        border-radius: 6px;
        box-sizing: border-box;
    }
    .btn-simpan {
        background-color: #0c7a5c;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
    }
</style>

<div class="tambah-container">
    <h2>Edit Pengguna</h2>
    <div class="tambah-card">
        <!-- Form dikirim ke controller pengguna/do_edit -->
        <form action="<?= site_url('pengguna/do_edit') ?>" method="POST">
            <!-- ID tersembunyi untuk mengetahui record mana yang diedit -->
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            <!-- Password opsional: jika dikosongkan, password lama tetap digunakan -->
            <div class="form-group">
                <label>Password (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <!-- Dropdown role dengan nilai yang sudah terpilih sesuai data pengguna -->
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="petugas" <?= $user['role'] == 'petugas' ? 'selected' : '' ?>>Petugas</option>
                    <option value="koor" <?= $user['role'] == 'koor' ? 'selected' : '' ?>>Koordinator</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn-simpan">Update</button>
                <a href="<?= site_url('pengguna') ?>" style="padding: 12px 25px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Batal</a>
            </div>
        </form>
    </div>
</div>
