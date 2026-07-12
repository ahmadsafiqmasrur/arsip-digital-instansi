<style>
    /* CSS Internal untuk halaman Tambah Pengguna Baru */
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
    <h2>Tambah Pengguna Baru</h2>
    <div class="tambah-card">
        <!-- Form dikirim ke controller pengguna/do_tambah -->
        <form action="<?= site_url('pengguna/do_tambah') ?>" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <!-- Dropdown pilihan role: Petugas atau Koordinator -->
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="petugas">Petugas</option>
                    <option value="koor">Koordinator</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn-simpan">Simpan</button>
                <a href="<?= site_url('pengguna') ?>" style="padding: 12px 25px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Batal</a>
            </div>
        </form>
    </div>
</div>
