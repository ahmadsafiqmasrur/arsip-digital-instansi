<style>
    /* CSS Internal untuk halaman Manajemen Arsip Scan */
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

    .tambah-card {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 30px;
        margin-bottom: 20px;
    }

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

    /* Styling Custom Upload File PDF */
    .file-upload-wrapper {
        display: flex;
        align-items: center;
        background-color: #f4f5f7;
        border-radius: 6px;
        padding: 0;
        overflow: hidden;
        width: 100%;
    }

    .btn-pilih-file {
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

    /* Styling Tabel Daftar Arsip Scan */
    .table-wrapper {
        margin-top: 40px;
        border: 1px solid #dcdcdc;
        border-radius: 8px;
        padding: 15px;
        background-color: #fff;
        overflow-x: auto;
    }
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
        border-bottom: 1px solid #f0f0f0;
    }
    .btn-download {
        background-color: #007bff;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.85rem;
    }
    .btn-hapus {
        background-color: #ff5f56;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.85rem;
        margin-left: 5px;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="tambah-container">
    <h2 class="tambah-title">Arsip Scan Dokumen</h2>

    <!-- Form Tambah Arsip Scan (Mendukung multiple upload PDF) -->
    <form action="<?= site_url('arsip_scan/do_tambah') ?>" method="POST" enctype="multipart/form-data">
        <div class="tambah-card">
            <div class="form-grid">
                <div class="form-group-tambah">
                    <label>Kelurahan</label>
                    <select name="kelurahan" id="kelurahan" class="form-control-tambah" required onchange="getNoArsipScan()">
                        <option value="">Pilih Kelurahan</option>
                        <option value="Canden">Canden</option>
                        <option value="Patalan">Patalan</option>
                        <option value="Sumberagung">Sumberagung</option>
                        <option value="Trimulyo">Trimulyo</option>
                    </select>
                </div>
                <div class="form-group-tambah">
                    <label>No. Arsip</label>
                    <input type="text" id="no_arsip_scan" name="no_arsip" class="form-control-tambah" readonly
                        placeholder="Pilih kelurahan dulu">
                </div>

                <div class="form-group-tambah">
                    <label>Tanggal Pengarsipan</label>
                    <input type="date" name="tgl_arsip" class="form-control-tambah" required>
                </div>
                <!-- Input File Multiple PDF -->
                <div class="form-group-tambah">
                    <label>Upload File PDF Scan (Bisa lebih dari 1)</label>
                    <div class="file-upload-wrapper">
                        <label for="file_pdf" class="btn-pilih-file">Pilih PDF</label>
                        <span class="file-text" id="file-name">Tidak ada file yang dipilih</span>
                        <input type="file" id="file_pdf" name="file_pdf[]" accept=".pdf" multiple required
                            onchange="updateFileName(this)">
                    </div>
                    <!-- Container untuk daftar nama file yang dipilih -->
                    <div id="selected-files-list" style="margin-top: 10px; max-height: 150px; overflow-y: auto;"></div>
                </div>

                <div class="form-group-tambah">
                    <label>Nama Suami</label>
                    <input type="text" name="nama_suami" class="form-control-tambah" required>
                </div>
                <div class="form-group-tambah">
                    <label>Nama Istri</label>
                    <input type="text" name="nama_istri" class="form-control-tambah" required>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button type="submit" class="btn-simpan">Simpan Arsip Scan</button>
            <a href="<?= site_url('dashboard') ?>" class="btn-batal">Batal</a>
        </div>
    </form>

    <!-- Tabel Daftar Data Arsip Scan -->
    <div class="table-wrapper">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0;">Daftar Arsip Scan</h3>
            <!-- Form Pencarian Nama -->
            <form action="<?= site_url('arsip_scan') ?>" method="GET" style="display: flex; gap: 5px;">
                <input type="text" name="search_scan" class="form-control-tambah" 
                    placeholder="Cari Nama..." style="padding: 8px 12px; font-size: 0.9rem;"
                    value="<?= htmlspecialchars($search_scan ?? '') ?>">
                <button type="submit" class="btn-simpan" style="padding: 8px 15px; font-size: 0.9rem;">Cari</button>
                <?php if (!empty($search_scan)): ?>
                    <a href="<?= site_url('arsip_scan') ?>" style="padding: 8px 12px; color: #ff5f56; text-decoration: none; font-size: 0.9rem;">Reset</a>
                <?php endif; ?>
            </form>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Arsip</th>
                    <th>Tanggal</th>
                    <th>Nama Suami/Istri</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($scans):
                    foreach ($scans as $row):
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['no_arsip']) ?></td>
                    <td><?= date('d/m/Y', strtotime($row['tgl_arsip'])) ?></td>
                    <td><?= htmlspecialchars($row['nama_suami']) ?> & <?= htmlspecialchars($row['nama_istri']) ?></td>
                    <td style="min-width: 300px;">
                        <div style="display: flex; align-items: flex-start; gap: 20px;">
                            <!-- List File PDF dalam bentuk tombol download -->
                            <div style="display: flex; flex-direction: column; gap: 5px; flex-grow: 1;">
                                <?php 
                                $files = json_decode($row['files_pdf'], true);
                                if ($files && is_array($files)):
                                    foreach ($files as $index => $file):
                                ?>
                                    <a href="<?= base_url('uploads/'.$file) ?>" target="_blank" class="btn-download" style="display: block; text-align: center;">File <?= $index + 1 ?></a>
                                <?php 
                                    endforeach;
                                else:
                                    echo "<span style='color: #666;'>Tidak ada file</span>";
                                endif;
                                ?>
                            </div>
                            <!-- Tombol Manajemen (Edit/Hapus) -->
                            <div style="display: flex; gap: 8px; flex-shrink: 0; padding-top: 2px;">
                                <a href="<?= site_url('arsip_scan/edit/'.$row['id']) ?>" class="btn-download" style="background-color: #2bd64b;">Edit</a>
                                <a href="<?= site_url('arsip_scan/hapus/'.$row['id']) ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Belum ada data scan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    /**
     * Generate Nomor Arsip Scan otomatis
     */
    function getNoArsipScan() {
        const kel = document.getElementById('kelurahan').value;
        const noArsipInput = document.getElementById('no_arsip_scan');

        if (kel) {
            fetch(`<?= site_url('arsip_scan/get_no_arsip') ?>?kelurahan=${kel}`)
                .then(res => res.text())
                .then(text => {
                    noArsipInput.value = text;
                });
        } else {
            noArsipInput.value = '';
        }
    }

    /**
     * Menampilkan daftar nama file PDF yang dipilih sebelum diupload
     */
    function updateFileName(input) {
        const fileList = document.getElementById('selected-files-list');
        fileList.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            document.getElementById('file-name').textContent = input.files.length + " file dipilih";
            for (let i = 0; i < input.files.length; i++) {
                const div = document.createElement('div');
                div.style.fontSize = '0.85rem';
                div.style.color = '#333';
                div.style.padding = '4px 8px';
                div.style.backgroundColor = '#fff';
                div.style.border = '1px solid #ddd';
                div.style.borderRadius = '4px';
                div.style.marginTop = '5px';
                div.textContent = input.files[i].name;
                fileList.appendChild(div);
            }
        } else {
            document.getElementById('file-name').textContent = "Tidak ada file yang dipilih";
        }
    }
</script>
