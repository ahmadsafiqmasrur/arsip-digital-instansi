<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$action = $_GET['action'] ?? '';

if ($action == 'get_no_arsip') {
    $kelurahan = $_GET['kelurahan'] ?? '';
    
    $prefix = '';
    if ($kelurahan == 'Canden') $prefix = 'CDN-SCAN';
    elseif ($kelurahan == 'Patalan') $prefix = 'PTL-SCAN';
    elseif ($kelurahan == 'Sumberagung') $prefix = 'SBR-SCAN';
    elseif ($kelurahan == 'Trimulyo') $prefix = 'TRM-SCAN';
    
    if (!$prefix) {
        echo "";
        exit;
    }

    $stmt = $pdo->prepare("SELECT no_arsip FROM arsip_scan WHERE kelurahan = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$kelurahan]);
    $last = $stmt->fetch();

    if ($last) {
        $last_no = (int) str_replace($prefix, '', $last['no_arsip']);
        $new_no = $last_no + 1;
    } else {
        $new_no = 1;
    }

    echo $prefix . $new_no;
    exit;
}

if ($action == 'tambah') {
    $kelurahan = $_POST['kelurahan'];
    $no_arsip = $_POST['no_arsip'];
    $tgl_arsip = $_POST['tgl_arsip'];
    $nama_suami = $_POST['nama_suami'];
    $nama_istri = $_POST['nama_istri'];
    $nama_petugas = $_SESSION['nama_petugas'];
    $id_petugas = $_SESSION['user_id'];

    $uploaded_files = [];
    if (isset($_FILES['file_pdf']) && is_array($_FILES['file_pdf']['name'])) {
        $count = count($_FILES['file_pdf']['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['file_pdf']['error'][$i] == 0) {
                $allowed = ['pdf'];
                $filename = $_FILES['file_pdf']['name'][$i];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (in_array($ext, $allowed)) {
                    $new_filename = 'SCAN_' . time() . '_' . $i . '_' . str_replace(['/', '\\'], '-', $no_arsip) . '.' . $ext;
                    $dest = '../uploads/' . $new_filename;
                    if (move_uploaded_file($_FILES['file_pdf']['tmp_name'][$i], $dest)) {
                        $uploaded_files[] = $new_filename;
                    }
                }
            }
        }
    }

    if (empty($uploaded_files)) {
        $_SESSION['error'] = "Minimal 1 file PDF wajib diupload!";
        header("Location: ../index.php?page=arsip_scan");
        exit;
    }

    $files_json = json_encode($uploaded_files);

    try {
        $sql = "INSERT INTO arsip_scan (no_arsip, kelurahan, tgl_arsip, nama_suami, nama_istri, files_pdf, nama_petugas, id_petugas) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$no_arsip, $kelurahan, $tgl_arsip, $nama_suami, $nama_istri, $files_json, $nama_petugas, $id_petugas]);
        
        $_SESSION['success'] = "Arsip scan berhasil ditambahkan!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal menyimpan data: " . $e->getMessage();
    }

    header("Location: ../index.php?page=arsip_scan");
    exit;
}

if ($action == 'edit') {
    $id = $_POST['id'];
    $tgl_arsip = $_POST['tgl_arsip'];
    $nama_suami = $_POST['nama_suami'];
    $nama_istri = $_POST['nama_istri'];

    $stmt = $pdo->prepare("SELECT * FROM arsip_scan WHERE id = ?");
    $stmt->execute([$id]);
    $old_data = $stmt->fetch();

    if (!$old_data) {
        $_SESSION['error'] = "Data tidak ditemukan!";
        header("Location: ../index.php?page=arsip_scan");
        exit;
    }

    $deleted_files = $_POST['deleted_files'] ?? [];
    $existing_files = json_decode($old_data['files_pdf'], true) ?: [];

    // Filter out deleted files
    $remaining_files = array_filter($existing_files, function($file) use ($deleted_files) {
        if (in_array($file, $deleted_files)) {
            if (file_exists('../uploads/' . $file)) {
                unlink('../uploads/' . $file);
            }
            return false;
        }
        return true;
    });

    // Check if new files are uploaded
    if (isset($_FILES['file_pdf']) && is_array($_FILES['file_pdf']['name']) && $_FILES['file_pdf']['error'][0] == 0) {
        $new_uploads = [];
        $count = count($_FILES['file_pdf']['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['file_pdf']['error'][$i] == 0) {
                $allowed = ['pdf'];
                $filename = $_FILES['file_pdf']['name'][$i];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (in_array($ext, $allowed)) {
                    $new_filename = 'SCAN_' . time() . '_' . $i . '_' . str_replace(['/', '\\'], '-', $old_data['no_arsip']) . '.' . $ext;
                    $dest = '../uploads/' . $new_filename;
                    if (move_uploaded_file($_FILES['file_pdf']['tmp_name'][$i], $dest)) {
                        $new_uploads[] = $new_filename;
                    }
                }
            }
        }
        $remaining_files = array_merge($remaining_files, $new_uploads);
    }

    $files_json = json_encode(array_values($remaining_files));

    try {
        $sql = "UPDATE arsip_scan SET tgl_arsip = ?, nama_suami = ?, nama_istri = ?, files_pdf = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$tgl_arsip, $nama_suami, $nama_istri, $files_json, $id]);
        
        $_SESSION['success'] = "Arsip scan berhasil diupdate!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal mengupdate data: " . $e->getMessage();
    }

    header("Location: ../index.php?page=arsip_scan");
    exit;
}

if ($action == 'hapus') {
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("SELECT * FROM arsip_scan WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();

    if ($data) {
        $files = json_decode($data['files_pdf'], true);
        if ($files && is_array($files)) {
            foreach ($files as $file) {
                if (file_exists('../uploads/' . $file)) {
                    unlink('../uploads/' . $file);
                }
            }
        }
        $stmt = $pdo->prepare("DELETE FROM arsip_scan WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "Data scan berhasil dihapus!";
    }
    
    header("Location: ../index.php?page=arsip_scan");
    exit;
}
?>
