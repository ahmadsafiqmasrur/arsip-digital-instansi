# Sistem Informasi Arsip Digital Pernikahan

Aplikasi berbasis web untuk manajemen dan pengarsipan digital dokumen pernikahan. Membantu petugas dalam mengelola data pendaftaran, pencatatan sipil, riwayat berkas digital (scan PDF), serta alur pengajuan perubahan data (edit requests) bagi petugas lapangan.

---

## Fitur Utama

- **Dashboard Statistik**: Pemantauan cepat data arsip per wilayah/kelurahan.
- **Kelola Arsip Pernikahan**: CRUD data arsip secara detail (nomor akta, identitas suami/istri, tanggal daftar, alamat, saksi, dan penghulu).
- **Arsip Digital (Scan PDF)**: Pencatatan & pengunggahan dokumen berkas fisik asli ke format digital (PDF).
- **Sistem Pengajuan Edit (Edit Request Workflow)**: Petugas dapat mengirim pengajuan edit arsip lama kepada koordinator. Edit data hanya diizinkan jika status pengajuan disetujui (`approved`).
- **Cetak Laporan**: Cetak data rekapitulasi arsip digital dalam format PDF (menggunakan library `dompdf`).

---

## Spesifikasi Teknis

- **Framework**: CodeIgniter 3
- **Bahasa**: PHP
- **Database**: MySQL / MariaDB
- **Desain**: Bootstrap / Vanilla CSS
- **Library Tambahan**: Dompdf (Cetak PDF)

---

## Langkah Instalasi

Ikuti petunjuk di bawah ini untuk menjalankan aplikasi ini di lingkungan lokal Anda (Localhost):

### 1. Prasyarat (Prerequisites)
Pastikan komputer Anda sudah terpasang:
- **XAMPP** (dengan versi PHP minimal 7.4 ke atas)
- **Composer** (untuk instalasi dependensi)

### 2. Unduh Proyek
Clone repository ini atau unduh file zip-nya dan letakkan ke dalam folder `htdocs` Anda (misal: `C:/xampp/htdocs/arsip_pernikahan`).

### 3. Konfigurasi Database
1. Buka phpMyAdmin di browser (`http://localhost/phpmyadmin`).
2. Buat database baru bernama **`db_arsip_pernikahan`**.
3. Impor file **`db_schema.sql`** yang ada di folder root proyek ini ke database baru Anda.
4. Salin/rename file konfigurasi database:
   * Cari file `application/config/database.php.example`.
   * Rename/salin menjadi **`database.php`** di direktori yang sama.
   * Sesuaikan `hostname`, `username`, `password`, dan `database` dengan server lokal Anda:
     ```php
     $db['default'] = array(
         'hostname' => 'localhost',
         'username' => 'root',     // sesuaikan username database Anda
         'password' => '',         // sesuaikan password database Anda
         'database' => 'db_arsip_pernikahan',
         // ...
     );
     ```

### 4. Aktifkan Ekstensi PHP GD (Penting untuk Cetak PDF)
Agar fitur ekspor PDF (Dompdf) dapat berjalan lancar dengan pemrosesan gambar, aktifkan ekstensi PHP GD di server Anda:
1. Buka XAMPP Control Panel.
2. Klik tombol **Config** di baris **Apache**, pilih **PHP (php.ini)**.
3. Cari kata `extension=gd` (gunakan Ctrl+F).
4. Hapus tanda titik koma (`;`) di depan `;extension=gd` menjadi `extension=gd`.
5. Simpan file `php.ini` lalu **Restart** service Apache Anda.

### 5. Install Dependensi (Composer)
Buka Terminal/Command Prompt di dalam folder proyek Anda, lalu jalankan perintah:
```bash
composer install
```

### 6. Jalankan Aplikasi
Buka browser dan akses aplikasi melalui URL berikut:
👉 **`http://localhost/arsip_pernikahan/`**

---

## Akun Demo Default

Gunakan kredensial berikut untuk masuk sebagai peran (role) yang berbeda:

* **Akun Koordinator (Koor)**
  * **Username**: `admin_koor`
  * **Password**: `password`

* **Akun Petugas**
  * **Username**: `petugas_demo`
  * **Password**: `password`
