# Penjelasan Use Case Diagram Sistem Arsip Pernikahan (Versi Lanjutan)

Use Case Diagram ini telah dilengkapi dengan relasi `<<include>>` dan `<<extend>>` untuk menggambarkan ketergantungan antar fungsi secara lebih detail dan profesional.

## Visualisasi Use Case (Mermaid)

```mermaid
useCaseDiagram
    actor Petugas
    actor Koordinator
    
    package "Sistem Arsip Akta Nikah KUA Jetis" {
        usecase "Login" as UC1
        usecase "Melihat Statistik Wilayah" as UC2
        usecase "Mengelola Data Arsip Pernikahan" as UC3
        usecase "Mengelola Arsip Scan PDF" as UC4
        usecase "Manajemen Petugas" as UC5
        usecase "Validasi Data" as UC6
        usecase "Mencetak Laporan PDF" as UC7
        usecase "Logout" as UC8
    }
    
    Petugas --> UC2
    Petugas --> UC3
    Petugas --> UC4
    Petugas --> UC8
    
    Koordinator --> UC2
    Koordinator --> UC3
    Koordinator --> UC4
    Koordinator --> UC5
    Koordinator --> UC8
    
    UC2 ..> UC1 : <<include>>
    UC3 ..> UC1 : <<include>>
    UC4 ..> UC1 : <<include>>
    UC5 ..> UC1 : <<include>>
    
    UC3 ..> UC6 : <<include>>
    UC5 ..> UC6 : <<include>>
    
    UC7 ..> UC3 : <<extend>>
```

## Narasi Penjelasan Use Case (Untuk Skripsi)

**Gambar 3.4 Use Case Diagram Sistem Arsip Pernikahan**

"Berdasarkan Gambar 3.4 Use Case Diagram di atas, sistem digambarkan memiliki alur yang lebih kompleks dengan adanya relasi ketergantungan antar use case. Berikut adalah penjelasan detailnya:

1.  **Relasi `<<include>>` Login**: Semua fitur utama sistem seperti Melihat Statistik, Mengelola Arsip, dan Manajemen Petugas menyertakan (*include*) proses **Login**. Hal ini menunjukkan bahwa sistem memiliki mekanisme keamanan di mana pengguna diwajibkan untuk melewati proses autentikasi terlebih dahulu sebelum dapat mengakses fungsionalitas di dalamnya.
2.  **Relasi `<<include>>` Validasi Data**: Use case **Mengelola Data Arsip** dan **Manajemen Petugas** menyertakan (*include*) proses **Validasi Data**. Ini menggambarkan bahwa setiap kali pengguna melakukan input atau perubahan data, sistem akan secara otomatis menjalankan sub-proses validasi untuk memastikan integritas dan kesesuaian data sebelum disimpan ke database.
3.  **Relasi `<<extend>>` Mencetak Laporan PDF**: Use case **Mencetak Laporan PDF** merupakan perluasan (*extend*) dari use case **Mengelola Data Arsip Pernikahan**. Hal ini berarti fitur cetak bersifat opsional dan merupakan fungsionalitas tambahan yang dapat dipicu oleh aktor setelah berhasil mengelola atau menampilkan data arsip.
4.  **Aktor Koordinator**: Sebagai aktor dengan level otoritas tertinggi (Administrator), Koordinator memiliki hak akses khusus ke use case **Manajemen Petugas** untuk mengelola akun pengguna lain.
5.  **Aktor Petugas**: Berfokus pada operasional data harian yang meliputi pengelolaan arsip akta nikah dan arsip scan PDF.
6.  **Logout**: Menandai akhir dari interaksi aktor dengan sistem yang bertujuan untuk menutup sesi akses secara aman."

---

**Catatan**: Penambahan relasi `include` dan `extend` ini akan memberikan nilai tambah pada kualitas penulisan skripsi Anda karena menunjukkan pemodelan sistem yang mendalam.
