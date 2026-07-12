<style>
    /* CSS Internal untuk halaman Tambah Arsip */
    .tambah-container {
        max-width: 900px;
        margin: 0 auto 40px 0;
    }

    .tambah-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 20px;
    }

    /* Card untuk membungkus form */
    .tambah-card {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 30px;
        margin-bottom: 20px;
    }

    /* Grid layout untuk form agar rapi (2 kolom) */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 30px;
        row-gap: 20px;
    }

    .form-group-tambah {
        display: flex;
        flex-direction: column;
    }

    .form-group-tambah label {
        font-size: 1.05rem;
        font-weight: 600;
        color: #000;
        margin-bottom: 10px;
    }

    /* Styling input field */
    .form-control-tambah {
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

    .form-control-tambah:read-only {
        background-color: #f4f5f7;
        color: #555;
    }

    /* Styling preview foto yang dipilih */
    .img-preview {
        width: 100%;
        height: 300px;
        background-color: #f4f5f7;
        border-radius: 8px;
        object-fit: cover;
        margin-bottom: 10px;
        display: none;
    }

    /* Wrapper custom untuk input file agar lebih estetik */
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

    .btn-simpan {
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

    .btn-simpan:hover {
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

    /* Responsivitas untuk layar kecil (hp) */
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="tambah-container">
    <h2 class="tambah-title">Tambah Arsip</h2>

    <!-- Form Tambah Arsip, menggunakan multipart untuk upload file -->
    <form action="<?= site_url('arsip/do_tambah') ?>" method="POST" enctype="multipart/form-data">
        <div class="tambah-card">
            <div class="form-grid">
                <!-- Data Kewilayahan -->
                <div class="form-group-tambah">
                    <label>Kelurahan</label>
                    <select name="kelurahan" id="kelurahan" class="form-control-tambah" required
                        onchange="getNoArsip()">
                        <option value="">Pilih Kelurahan</option>
                        <option value="Canden">Canden</option>
                        <option value="Patalan">Patalan</option>
                        <option value="Sumberagung">Sumberagung</option>
                        <option value="Trimulyo">Trimulyo</option>
                    </select>
                </div>
                <div class="form-group-tambah">
                    <label>No. Arsip</label>
                    <input type="text" id="no_arsip" class="form-control-tambah" readonly
                        placeholder="Pilih kelurahan dulu">
                </div>

                <!-- Data Administrasi Akta -->
                <div class="form-group-tambah">
                    <label>Tanggal Daftar</label>
                    <input type="date" name="tgl_daftar" class="form-control-tambah" required>
                </div>
                <div class="form-group-tambah">
                    <label>Nomor Akta</label>
                    <input type="text" name="no_akta" class="form-control-tambah" required>
                </div>

                <!-- Identitas Suami & Istri -->
                <div class="form-group-tambah">
                    <label>Nama Suami</label>
                    <input type="text" name="nama_suami" class="form-control-tambah" required>
                </div>
                <div class="form-group-tambah">
                    <label>Nama Istri</label>
                    <input type="text" name="nama_istri" class="form-control-tambah" required>
                </div>

                <div class="form-group-tambah">
                    <label>NIK Suami</label>
                    <input type="text" name="nik_suami" class="form-control-tambah" required>
                </div>
                <div class="form-group-tambah">
                    <label>NIK Istri</label>
                    <input type="text" name="nik_istri" class="form-control-tambah" required>
                </div>

                <div class="form-group-tambah">
                    <label>Alamat Suami</label>
                    <textarea name="alamat_suami" class="form-control-tambah" rows="3" required></textarea>
                </div>
                <div class="form-group-tambah">
                    <label>Alamat Istri</label>
                    <textarea name="alamat_istri" class="form-control-tambah" rows="3" required></textarea>
                </div>

                <!-- Detail Pernikahan -->
                <div class="form-group-tambah">
                    <label>Status Pernikahan</label>
                    <select name="status_pernikahan" class="form-control-tambah" required>
                        <option value="">Pilih Status</option>
                        <option value="Sah">Sah</option>
                        <option value="Siri">Siri</option>
                    </select>
                </div>
                <div class="form-group-tambah">
                    <label>Nama Saksi</label>
                    <input type="text" name="nama_saksi" class="form-control-tambah" required>
                </div>

                <div class="form-group-tambah">
                    <label>Tanggal & Tahun Nikah</label>
                    <input type="date" name="tgl_nikah" class="form-control-tambah" required>
                </div>
                <div class="form-group-tambah">
                    <label>Nama Penghulu</label>
                    <input type="text" name="nama_penghulu" class="form-control-tambah" required>
                </div>

                <!-- Bagian Upload Pas Foto -->
                <div class="form-group-tambah">
                    <label>Upload Pas Foto Suami 3x4</label>
                    <img id="preview-foto_suami" class="img-preview" src="" alt="Preview">
                    <div class="file-upload-wrapper">
                        <label for="foto_suami" class="btn-pilih-foto">Pilih foto</label>
                        <span class="file-text" id="name-foto_suami">Tidak ada foto yang dipilih</span>
                        <input type="file" id="foto_suami" name="foto_suami" accept=".jpg,.jpeg,.png" required
                            onchange="updatePreview(this, 'foto_suami')">
                    </div>
                </div>
                <div class="form-group-tambah">
                    <label>Upload Pas Foto Istri 3x4</label>
                    <img id="preview-foto_istri" class="img-preview" src="" alt="Preview">
                    <div class="file-upload-wrapper">
                        <label for="foto_istri" class="btn-pilih-foto">Pilih foto</label>
                        <span class="file-text" id="name-foto_istri">Tidak ada foto yang dipilih</span>
                        <input type="file" id="foto_istri" name="foto_istri" accept=".jpg,.jpeg,.png" required
                            onchange="updatePreview(this, 'foto_istri')">
                    </div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button type="submit" class="btn-simpan">Simpan Arsip</button>
            <a href="<?= site_url('dashboard') ?>" class="btn-batal">Batal</a>
        </div>
    </form>
</div>

<script>
    /**
     * Mengambil nomor arsip otomatis dari server saat kelurahan berubah
     */
    function getNoArsip() {
        const kel = document.getElementById('kelurahan').value;
        const noArsipInput = document.getElementById('no_arsip');

        if (kel) {
            fetch(`<?= site_url('arsip/get_no_arsip') ?>?kelurahan=${kel}`)
                .then(res => res.text())
                .then(text => {
                    noArsipInput.value = text;
                });
        } else {
            noArsipInput.value = '';
        }
    }

    /**
     * Memperbarui preview gambar dan nama file setelah user memilih foto
     */
    function updatePreview(input, type) {
        const fileName = input.files[0] ? input.files[0].name : "Tidak ada foto yang dipilih";
        document.getElementById('name-' + type).textContent = fileName;

        const preview = document.getElementById('preview-' + type);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
</script>