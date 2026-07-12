<div class="header-action" style="margin-bottom: 20px;">
    <h2>Tambah Petugas</h2>
</div>

<div class="card" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px;">
    <form action="proses/petugas.php?action=tambah" method="POST">
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Nama Lengkap</label>
            <input type="text" name="nama" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Username</label>
            <input type="text" name="username" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Password</label>
            <input type="password" name="password" required minlength="6" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box;">
            <small style="color: #6c757d;">Minimal 6 karakter.</small>
        </div>
        
        <div style="margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Role</label>
            <select name="role" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box;">
                <option value="petugas">Petugas</option>
                <option value="koor">Koordinator</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background-color: #0c7a5c; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">Simpan</button>
            <a href="index.php?page=petugas" style="background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Batal</a>
        </div>
    </form>
</div>
