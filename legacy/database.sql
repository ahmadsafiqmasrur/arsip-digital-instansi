CREATE DATABASE IF NOT EXISTS db_arsip_pernikahan;
USE db_arsip_pernikahan;

CREATE TABLE IF NOT EXISTS petugas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    role ENUM('koor', 'petugas') NOT NULL DEFAULT 'petugas'
);

-- Insert a default petugas (password is 'admin' hashed)
-- password_hash('admin', PASSWORD_DEFAULT) => $2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm
INSERT INTO petugas (username, password, nama, role) VALUES 
('admin', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'Administrator', 'koor')
ON DUPLICATE KEY UPDATE id=id;

CREATE TABLE IF NOT EXISTS arsip (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_arsip VARCHAR(20) NOT NULL UNIQUE,
    kelurahan ENUM('Canden', 'Patalan', 'Sumberagung', 'Trimulyo') NOT NULL,
    tgl_daftar DATE NOT NULL,
    no_akta VARCHAR(50) NOT NULL,
    nama_suami VARCHAR(100) NOT NULL,
    nama_istri VARCHAR(100) NOT NULL,
    status_pernikahan VARCHAR(50) NOT NULL,
    nama_saksi VARCHAR(100) NOT NULL,
    nik_suami VARCHAR(20) NOT NULL,
    nik_istri VARCHAR(20) NOT NULL,
    tgl_nikah DATE NOT NULL,
    alamat_suami TEXT NOT NULL,
    alamat_istri TEXT NOT NULL,
    nama_penghulu VARCHAR(100) NOT NULL,
    nama_petugas VARCHAR(100) NOT NULL,
    id_petugas INT NOT NULL,
    foto_suami VARCHAR(255) NOT NULL,
    foto_istri VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS arsip_scan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_arsip VARCHAR(20) NOT NULL UNIQUE,
    kelurahan ENUM('Canden', 'Patalan', 'Sumberagung', 'Trimulyo') NOT NULL,
    tgl_arsip DATE NOT NULL,
    nama_suami VARCHAR(100) NOT NULL,
    nama_istri VARCHAR(100) NOT NULL,
    files_pdf TEXT NOT NULL,
    nama_petugas VARCHAR(100) NOT NULL,
    id_petugas INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
