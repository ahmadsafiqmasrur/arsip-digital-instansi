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
    if ($kelurahan == 'Canden') $prefix = 'CDN';
    elseif ($kelurahan == 'Patalan') $prefix = 'PTL';
    elseif ($kelurahan == 'Sumberagung') $prefix = 'SBR';
    elseif ($kelurahan == 'Trimulyo') $prefix = 'TRM';
    
    if (!$prefix) {
        echo "";
        exit;
    }

    $stmt = $pdo->prepare("SELECT no_arsip FROM arsip WHERE kelurahan = ? ORDER BY id DESC LIMIT 1");
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
    $tgl_daftar = $_POST['tgl_daftar'];
    $no_akta = $_POST['no_akta'];
    $nama_suami = $_POST['nama_suami'];
    $nama_istri = $_POST['nama_istri'];
    $status_pernikahan = $_POST['status_pernikahan'];
    $nama_saksi = $_POST['nama_saksi'];
    $nik_suami = $_POST['nik_suami'] ?? '';
    $nik_istri = $_POST['nik_istri'] ?? '';
    $tgl_nikah = $_POST['tgl_nikah'] ?? '';
    $alamat_suami = $_POST['alamat_suami'] ?? '';
    $alamat_istri = $_POST['alamat_istri'] ?? '';
    $nama_penghulu = $_POST['nama_penghulu'] ?? '';
    $nama_petugas = $_SESSION['nama_petugas']; 
    $id_petugas = $_SESSION['user_id'];

    
    $prefix = '';
    if ($kelurahan == 'Canden') $prefix = 'CDN';
    elseif ($kelurahan == 'Patalan') $prefix = 'PTL';
    elseif ($kelurahan == 'Sumberagung') $prefix = 'SBR';
    elseif ($kelurahan == 'Trimulyo') $prefix = 'TRM';
    
    $stmt = $pdo->prepare("SELECT no_arsip FROM arsip WHERE kelurahan = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$kelurahan]);
    $last = $stmt->fetch();
    $new_no = $last ? ((int) str_replace($prefix, '', $last['no_arsip'])) + 1 : 1;
    $no_arsip = $prefix . $new_no;

   
    // Upload handler for 2 photos
    $photos = ['foto_suami', 'foto_istri'];
    $uploaded_files = [];

    foreach ($photos as $photo_type) {
        $file_name_db = '';
        if (isset($_FILES[$photo_type]) && $_FILES[$photo_type]['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $filename = $_FILES[$photo_type]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $new_filename = time() . '_' . $photo_type . '_' . $no_arsip . '.' . $ext;
                $dest = '../uploads/' . $new_filename;
                if (move_uploaded_file($_FILES[$photo_type]['tmp_name'], $dest)) {
                    $file_name_db = $new_filename;
                }
            } else {
                $_SESSION['error'] = "File $photo_type harus JPG atau PNG!";
                header("Location: ../index.php?page=tambah");
                exit;
            }
        }

        if (empty($file_name_db)) {
            $_SESSION['error'] = "Semua foto (Foto Suami & Foto Istri) wajib diupload!";
            header("Location: ../index.php?page=tambah");
            exit;
        }
        $uploaded_files[$photo_type] = $file_name_db;
    }

    $sql = "INSERT INTO arsip (no_arsip, kelurahan, tgl_daftar, no_akta, nama_suami, nama_istri, status_pernikahan, nama_saksi, nik_suami, nik_istri, tgl_nikah, alamat_suami, alamat_istri, nama_penghulu, nama_petugas, id_petugas, foto_suami, foto_istri) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $no_arsip, $kelurahan, $tgl_daftar, $no_akta, $nama_suami, $nama_istri, 
        $status_pernikahan, $nama_saksi, $nik_suami, $nik_istri, $tgl_nikah, 
        $alamat_suami, $alamat_istri, $nama_penghulu, $nama_petugas, $id_petugas, 
        $uploaded_files['foto_suami'], $uploaded_files['foto_istri']
    ]);

    $_SESSION['success'] = "Data berhasil ditambahkan!";
    header("Location: ../index.php?page=data_kelurahan&kelurahan=" . urlencode($kelurahan));
    exit;
}

if ($action == 'edit') {
    $id = $_POST['id'];
    $tgl_daftar = $_POST['tgl_daftar'];
    $no_akta = $_POST['no_akta'];
    $nama_suami = $_POST['nama_suami'];
    $nama_istri = $_POST['nama_istri'];
    $status_pernikahan = $_POST['status_pernikahan'];
    $nama_saksi = $_POST['nama_saksi'];
    $nik_suami = $_POST['nik_suami'] ?? '';
    $nik_istri = $_POST['nik_istri'] ?? '';
    $tgl_nikah = $_POST['tgl_nikah'] ?? '';
    $alamat_suami = $_POST['alamat_suami'] ?? '';
    $alamat_istri = $_POST['alamat_istri'] ?? '';
    $nama_penghulu = $_POST['nama_penghulu'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM arsip WHERE id = ?");
    $stmt->execute([$id]);
    $old_data = $stmt->fetch();

    $file_foto_suami = $old_data['foto_suami'];
    $file_foto_istri = $old_data['foto_istri'];

    $photos = ['foto_suami', 'foto_istri'];
    foreach ($photos as $photo_type) {
        if (isset($_FILES[$photo_type]) && $_FILES[$photo_type]['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $filename = $_FILES[$photo_type]['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $new_filename = time() . '_' . $photo_type . '_' . $old_data['no_arsip'] . '.' . $ext;
                $dest = '../uploads/' . $new_filename;
                if (move_uploaded_file($_FILES[$photo_type]['tmp_name'], $dest)) {
                    // Hapus foto lama
                    if (file_exists('../uploads/' . ${"file_" . $photo_type}) && ${"file_" . $photo_type} != '') {
                        unlink('../uploads/' . ${"file_" . $photo_type});
                    }
                    ${"file_" . $photo_type} = $new_filename;
                }
            }
        }
    }

    $sql = "UPDATE arsip SET tgl_daftar=?, no_akta=?, nama_suami=?, nama_istri=?, status_pernikahan=?, nama_saksi=?, nik_suami=?, nik_istri=?, tgl_nikah=?, alamat_suami=?, alamat_istri=?, nama_penghulu=?, foto_suami=?, foto_istri=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$tgl_daftar, $no_akta, $nama_suami, $nama_istri, $status_pernikahan, $nama_saksi, $nik_suami, $nik_istri, $tgl_nikah, $alamat_suami, $alamat_istri, $nama_penghulu, $file_foto_suami, $file_foto_istri, $id]);

    $_SESSION['success'] = "Data berhasil diupdate!";
    header("Location: ../index.php?page=data_kelurahan&kelurahan=" . urlencode($old_data['kelurahan']));
    exit;
}

if ($action == 'hapus') {
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("SELECT * FROM arsip WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();

    if ($data) {
        $photos = ['foto_suami', 'foto_istri'];
        foreach ($photos as $photo) {
            if (!empty($data[$photo]) && file_exists('../uploads/' . $data[$photo])) {
                unlink('../uploads/' . $data[$photo]);
            }
        }
        $stmt = $pdo->prepare("DELETE FROM arsip WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "Data berhasil dihapus!";
    }
    
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
