<style>
/* CSS Internal untuk halaman Edit Arsip */
.edit-container {
    max-width: 900px;
    margin: 0 auto 40px 0;
}
.edit-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #000;
    margin-bottom: 20px;
}
.edit-card {
    background-color: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 30px;
    margin-bottom: 20px;
}
/* Grid layout 2 kolom */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    column-gap: 30px;
    row-gap: 20px;
}
.form-group-edit {
    display: flex;
    flex-direction: column;
}
.form-group-edit label {
    font-size: 1.05rem;
    font-weight: 600;
    color: #000;
    margin-bottom: 10px;
}
.form-control-edit {
    background-color: #f4f5f7;
    border: none;
    padding: 14px 15px;
    border-radius: 6px;
    font-size: 0.95rem;
    color: #333;
    outline: none;
    width: 100%;
    box-sizing: border-box;
    font-family: 'DM Sans', sans-serif;
}
.form-control-edit:read-only {
    background-color: #f4f5f7;
    color: #555;
}
/* Preview foto lama */
.img-preview {
    width: 100%;
    height: 300px;
    background-color: #f4f5f7;
    border-radius: 8px;
    object-fit: cover;
    margin-bottom: 10px;
    display: block;
}
.file-upload-wrapper {
    display: flex;
    align-items: center;
    background-color: #f4f5f7;
    border-radius: 6px;
    padding: 0;
    overflow: hidden;
    width: 100%;
}
.btn-pilih-foto {
    background-color: #fff;
    border: 1px solid #dcdcdc;
    padding: 8px 15px;
    border-radius: 4px;
    font-size: 0.85rem;
    color: #333;
    cursor: pointer;
    margin: 4px;
}
.file-text {
    font-size: 0.85rem;
    color: #666;
    padding-left: 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
input[type="file"] {
    display: none;
}
.action-buttons {
    display: flex;
    gap: 15px;
}
.btn-update {
    background-color: #0c7a5c;
    color: white;
    padding: 12px 25px;
    border-radius: 6px;
    border: none;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: background-color 0.2s;
}
.btn-update:hover {
    background-color: #09634a;
}
.btn-batal {
    background-color: #ff5f56;
    color: white;
    padding: 12px 35px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-family: 'DM Sans', sans-serif;
    transition: background-color 0.2s;
}
.btn-batal:hover {
    background-color: #e04e46;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="edit-container">
    <h2 class="edit-title">Edit Arsip</h2>

    <!-- Form Edit Data Arsip -->
    <form id="arsipEditForm" action="<?= site_url('arsip/do_edit') ?>" method="POST" enctype="multipart/form-data">
        <!-- Hidden input ID untuk proses update di controller -->
        <input type="hidden" name="id" value="<?= $arsip['id'] ?>">
        
        <div class="edit-card">
            <div class="form-grid">
                <!-- Data Statis (Tidak bisa diubah) -->
                <div class="form-group-edit">
                    <label>Kelurahan</label>
                    <input type="text" class="form-control-edit" value="<?= htmlspecialchars($arsip['kelurahan']) ?>" readonly>
                </div>
                <div class="form-group-edit">
                    <label>No. Arsip</label>
                    <input type="text" class="form-control-edit" value="<?= htmlspecialchars($arsip['no_arsip']) ?>" readonly>
                </div>

                <!-- Data yang dapat diubah -->
                <div class="form-group-edit">
                    <label>Tanggal Daftar</label>
                    <input type="date" name="tgl_daftar" class="form-control-edit" value="<?= htmlspecialchars($arsip['tgl_daftar']) ?>" required>
                </div>
                <div class="form-group-edit">
                    <label>Nomor Akta</label>
                    <input type="text" name="no_akta" class="form-control-edit" value="<?= htmlspecialchars($arsip['no_akta']) ?>" required>
                </div>

                <div class="form-group-edit">
                    <label>Nama Suami</label>
                    <input type="text" name="nama_suami" class="form-control-edit" value="<?= htmlspecialchars($arsip['nama_suami']) ?>" required>
                </div>
                <div class="form-group-edit">
                    <label>Nama Istri</label>
                    <input type="text" name="nama_istri" class="form-control-edit" value="<?= htmlspecialchars($arsip['nama_istri']) ?>" required>
                </div>

                <div class="form-group-edit">
                    <label>NIK Suami</label>
                    <input type="text" name="nik_suami" class="form-control-edit" value="<?= htmlspecialchars($arsip['nik_suami']) ?>" required>
                </div>
                <div class="form-group-edit">
                    <label>NIK Istri</label>
                    <input type="text" name="nik_istri" class="form-control-edit" value="<?= htmlspecialchars($arsip['nik_istri']) ?>" required>
                </div>

                <div class="form-group-edit">
                    <label>Alamat Suami</label>
                    <textarea name="alamat_suami" class="form-control-edit" rows="3" required><?= htmlspecialchars($arsip['alamat_suami']) ?></textarea>
                </div>
                <div class="form-group-edit">
                    <label>Alamat Istri</label>
                    <textarea name="alamat_istri" class="form-control-edit" rows="3" required><?= htmlspecialchars($arsip['alamat_istri']) ?></textarea>
                </div>

                <div class="form-group-edit">
                    <label>Status Pernikahan</label>
                    <select name="status_pernikahan" class="form-control-edit" required>
                        <option value="Sah" <?= $arsip['status_pernikahan'] == 'Sah' ? 'selected' : '' ?>>Sah</option>
                        <option value="Siri" <?= $arsip['status_pernikahan'] == 'Siri' ? 'selected' : '' ?>>Siri</option>
                    </select>
                </div>
                <div class="form-group-edit">
                    <label>Nama Saksi</label>
                    <input type="text" name="nama_saksi" class="form-control-edit" value="<?= htmlspecialchars($arsip['nama_saksi']) ?>" required>
                </div>

                <div class="form-group-edit">
                    <label>Tanggal & Tahun Nikah</label>
                    <input type="date" name="tgl_nikah" class="form-control-edit" value="<?= htmlspecialchars($arsip['tgl_nikah']) ?>" required>
                </div>
                <div class="form-group-edit">
                    <label>Nama Penghulu</label>
                    <input type="text" name="nama_penghulu" class="form-control-edit" value="<?= htmlspecialchars($arsip['nama_penghulu']) ?>" required>
                </div>

                <!-- Bagian Ganti Foto -->
                <div class="form-group-edit">
                    <label>Upload Pas Foto Suami 3x4</label>
                    <?php if(!empty($arsip['foto_suami']) && file_exists('uploads/'.$arsip['foto_suami'])): ?>
                        <img src="<?= base_url('uploads/'.$arsip['foto_suami']) ?>" class="img-preview" id="preview-foto_suami">
                    <?php else: ?>
                        <img id="preview-foto_suami" class="img-preview" style="display: none;">
                    <?php endif; ?>
                    
                    <div class="file-upload-wrapper">
                        <label for="foto_suami" class="btn-pilih-foto">Pilih foto baru</label>
                        <span class="file-text" id="name-foto_suami">Tidak ada foto baru dipilih</span>
                        <input type="file" id="foto_suami" name="foto_suami" accept=".jpg,.jpeg,.png" onchange="updatePreview(this, 'foto_suami')">
                    </div>
                </div>

                <div class="form-group-edit">
                    <label>Upload Pas Foto Istri 3x4</label>
                    <?php if(!empty($arsip['foto_istri']) && file_exists('uploads/'.$arsip['foto_istri'])): ?>
                        <img src="<?= base_url('uploads/'.$arsip['foto_istri']) ?>" class="img-preview" id="preview-foto_istri">
                    <?php else: ?>
                        <img id="preview-foto_istri" class="img-preview" style="display: none;">
                    <?php endif; ?>
                    
                    <div class="file-upload-wrapper">
                        <label for="foto_istri" class="btn-pilih-foto">Pilih foto baru</label>
                        <span class="file-text" id="name-foto_istri">Tidak ada foto baru dipilih</span>
                        <input type="file" id="foto_istri" name="foto_istri" accept=".jpg,.jpeg,.png" onchange="updatePreview(this, 'foto_istri')">
                    </div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button type="submit" class="btn-update">Update Arsip</button>
            <a href="<?= site_url('arsip?kelurahan=' . urlencode($arsip['kelurahan'])) ?>" class="btn-batal">Batal</a>
        </div>
    </form>
</div>

<script>
/**
 * Update preview gambar saat user memilih file baru
 */
function updatePreview(input, type) {
    const fileName = input.files[0] ? input.files[0].name : "Tidak ada foto yang dipilih";
    document.getElementById('name-' + type).textContent = fileName;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview-' + type);
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('arsipEditForm');
  if (!form) return;

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
      submitButton.disabled = true;
    }

    const formData = new FormData(form);
    fetch(form.action, {
      method: 'POST',
      body: formData,
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('HTTP error ' + response.status);
        }
        return response.json();
      })
      .then(data => {
        if (data.status === 'success') {
          if (typeof showToast === 'function') {
            showToast(data.message || 'Data berhasil diupdate!', 'success');
          }
          if (typeof window.refreshPendingBadge === 'function') {
            window.refreshPendingBadge();
          }
        } else {
          throw new Error(data.message || 'Gagal mengupdate arsip');
        }
      })
      .catch(error => {
        console.error('Edit form submit error:', error);
        if (typeof showToast === 'function') {
          showToast('Gagal mengupdate arsip', 'danger');
        }
      })
      .finally(() => {
        if (submitButton) {
          submitButton.disabled = false;
        }
      });
  });
});
</script>
