<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'koor') {
    header("Location: ../index.php");
    exit;
}

$action = $_GET['action'] ?? '';

if ($action == 'tambah') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password minimal 6 karakter!";
        header("Location: ../index.php?page=tambah_petugas");
        exit;
    }

    // Cek username unik
    $stmt = $pdo->prepare("SELECT id FROM petugas WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Username sudah digunakan!";
        header("Location: ../index.php?page=tambah_petugas");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO petugas (nama, username, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nama, $username, $hashed_password, $role]);

    $_SESSION['success'] = "Petugas berhasil ditambahkan!";
    header("Location: ../index.php?page=petugas");
    exit;
}

if ($action == 'edit') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("UPDATE petugas SET nama = ?, role = ? WHERE id = ?");
    $stmt->execute([$nama, $role, $id]);

    $_SESSION['success'] = "Data petugas berhasil diupdate!";
    header("Location: ../index.php?page=petugas");
    exit;
}

if ($action == 'reset_password') {
    $id = $_POST['id'];
    $password = $_POST['password'];

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password minimal 6 karakter!";
        header("Location: ../index.php?page=edit_petugas&id=" . $id);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE petugas SET password = ? WHERE id = ?");
    $stmt->execute([$hashed_password, $id]);

    $_SESSION['success'] = "Password petugas berhasil direset!";
    header("Location: ../index.php?page=petugas");
    exit;
}

if ($action == 'hapus') {
    $id = $_GET['id'];
    
    // Jangan izinkan hapus diri sendiri
    if ($id == $_SESSION['user_id']) {
        $_SESSION['error'] = "Anda tidak dapat menghapus akun Anda sendiri!";
        header("Location: ../index.php?page=petugas");
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM petugas WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['success'] = "Petugas berhasil dihapus!";
    header("Location: ../index.php?page=petugas");
    exit;
}

header("Location: ../index.php?page=petugas");
exit;
?>
