<?php
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM arsip_scan WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan!</div>";
    return;
}
?>

<style>
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
        font-family: 'Inter', sans-serif;
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
        font-family: 'Inter', sans-serif;
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
        font-family: 'Inter', sans-serif;
        transition: background-color 0.2s;
    }

    .btn-batal:hover {
        background-color: #e04e46;
    }

    .existing-files {
        margin-top: 10px;
        font-size: 0.85rem;
        color: #333;
    }

    .file-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        padding: 5px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 5px;
    }
</style>

<div class="tambah-container">
    <h2 class="tambah-title">Edit Arsip Scan Dokumen</h2>

    <form action="proses/arsip_scan.php?action=edit" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">
        <div class="tambah-card">
            <div class="form-grid">
                <div class="form-group-tambah">
                    <label>Kelurahan</label>
                    <input type="text" class="form-control-tambah" value="<?= htmlspecialchars($data['kelurahan']) ?>" readonly>
                    <input type="hidden" name="kelurahan" value="<?= $data['kelurahan'] ?>">
                </div>
                <div class="form-group-tambah">
                    <label>No. Arsip</label>
                    <input type="text" name="no_arsip" class="form-control-tambah" value="<?= htmlspecialchars($data['no_arsip']) ?>" readonly>
                </div>

                <div class="form-group-tambah">
                    <label>Tanggal Pengarsipan</label>
                    <input type="date" name="tgl_arsip" class="form-control-tambah" value="<?= $data['tgl_arsip'] ?>" required>
                </div>
                <div class="form-group-tambah">
                    <label>Tambah File PDF Scan Baru</label>
                    <div class="file-upload-wrapper">
                        <label for="file_pdf" class="btn-pilih-file">Pilih PDF</label>
                        <span class="file-text" id="file-name">Tidak ada file yang dipilih</span>
                        <input type="file" id="file_pdf" name="file_pdf[]" accept=".pdf" multiple
                            onchange="updateFileName(this)">
                    </div>
                    <div id="selected-files-list" style="margin-top: 10px; max-height: 150px; overflow-y: auto;"></div>
                    
                    <div class="existing-files" style="margin-top: 20px;">
                        <p><strong>File saat ini:</strong></p>
                        <div id="existing-files-list">
                            <?php 
                            $files = json_decode($data['files_pdf'], true);
                            if ($files && is_array($files)):
                                foreach ($files as $index => $file):
                            ?>
                                <div class="file-item" id="file-row-<?= $index ?>">
                                    <span><?= htmlspecialchars($file) ?></span>
                                    <div>
                                        <a href="uploads/<?= $file ?>" target="_blank" style="color: #007bff; text-decoration: none; margin-right: 10px;">Lihat</a>
                                        <button type="button" onclick="markForDeletion('<?= $file ?>', 'file-row-<?= $index ?>')" style="background: #ff5f56; color: white; border: none; padding: 2px 8px; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">Hapus</button>
                                    </div>
                                </div>
                            <?php 
                                endforeach;
                            endif;
                            ?>
                        </div>
                        <div id="deletion-inputs"></div>
                        <p style="font-size: 0.8rem; color: #666; margin-top: 10px;">* File baru yang diupload akan ditambahkan ke daftar file yang sudah ada.</p>
                    </div>
                </div>

                <div class="form-group-tambah">
                    <label>Nama Suami</label>
                    <input type="text" name="nama_suami" class="form-control-tambah" value="<?= htmlspecialchars($data['nama_suami']) ?>" required>
                </div>
                <div class="form-group-tambah">
                    <label>Nama Istri</label>
                    <input type="text" name="nama_istri" class="form-control-tambah" value="<?= htmlspecialchars($data['nama_istri']) ?>" required>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button type="submit" class="btn-simpan">Update Arsip Scan</button>
            <a href="index.php?page=arsip_scan" class="btn-batal">Batal</a>
        </div>
    </form>
</div>

<script>
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

    function markForDeletion(filename, rowId) {
        if (confirm('Yakin ingin menghapus file ini?')) {
            const row = document.getElementById(rowId);
            row.style.display = 'none';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'deleted_files[]';
            input.value = filename;
            document.getElementById('deletion-inputs').appendChild(input);
        }
    }
</script>
