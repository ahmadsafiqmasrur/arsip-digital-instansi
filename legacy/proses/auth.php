<?php
session_start();
require_once '../config/database.php';

if (isset($_GET['action']) && $_GET['action'] == 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username dan Password wajib diisi!";
        header("Location: ../auth/login.php");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM petugas WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['role'] !== 'petugas') {
            $_SESSION['error'] = "Silakan masuk melalui halaman Login Koordinator!";
            header("Location: ../auth/login.php");
            exit;
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_petugas'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../index.php");
        exit;
    } else {
        $_SESSION['error'] = "Username atau Password salah!";
        header("Location: ../auth/login.php");
        exit;
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'login_koordinator') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username dan Password wajib diisi!";
        header("Location: ../auth/login_koordinator.php");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM petugas WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['role'] !== 'koor') {
            $_SESSION['error'] = "Silakan masuk melalui halaman Login Petugas!";
            header("Location: ../auth/login_koordinator.php");
            exit;
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_petugas'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../index.php");
        exit;
    } else {
        $_SESSION['error'] = "Username atau Password salah!";
        header("Location: ../auth/login_koordinator.php");
        exit;
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
} else {
    header("Location: ../auth/login.php");
    exit;
}
?>
