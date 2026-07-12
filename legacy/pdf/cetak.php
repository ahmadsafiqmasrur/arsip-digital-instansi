<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak");
}

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM arsip WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    die("Data tidak ditemukan");
}


if (!file_exists('../vendor/autoload.php')) {
    die("<h1>Dompdf tidak ditemukan!</h1><p>Silakan jalankan <code>composer require dompdf/dompdf</code> di folder root proyek ini untuk menginstall Dompdf, atau download manual dan include autoload-nya di file pdf/cetak.php.</p>");
}

require '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;


$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); 
$dompdf = new Dompdf($options);


$photos = [
    'foto_suami' => 'Pas Foto Suami 3x4',
    'foto_istri' => 'Pas Foto Istri 3x4'
];

$imagesHtml = '';
foreach ($photos as $key => $label) {
    $imagePath = '../uploads/' . $data[$key];
    if (file_exists($imagePath) && !empty($data[$key])) {
        $type = pathinfo($imagePath, PATHINFO_EXTENSION);
        $img_data = file_get_contents($imagePath);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($img_data);
        $imagesHtml .= "
            <div style='margin-bottom: 30px; text-align: center;'>
                <h3>{$label}</h3>
                <img src='{$base64}' style='max-width: 100%; max-height: 400px; border: 1px solid #ddd;' />
            </div>";
    }
}

$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cetak Arsip - ' . htmlspecialchars($data['no_arsip']) . '</title>
    <style>
        body { font-family: sans-serif; }
        h2 { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { width: 30%; background-color: #f2f2f2; }
        .page-break { page-break-after: always; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2>Detail Arsip Pernikahan</h2>
    <table>
        <tr><th>No Arsip</th><td>' . htmlspecialchars($data['no_arsip']) . '</td></tr>
        <tr><th>Kelurahan</th><td>' . htmlspecialchars($data['kelurahan']) . '</td></tr>
        <tr><th>Tanggal Daftar</th><td>' . date('d/m/Y', strtotime($data['tgl_daftar'])) . '</td></tr>
        <tr><th>Nomor Akta</th><td>' . htmlspecialchars($data['no_akta']) . '</td></tr>
        <tr><th>Nama Suami</th><td>' . htmlspecialchars($data['nama_suami']) . '</td></tr>
        <tr><th>Nama Istri</th><td>' . htmlspecialchars($data['nama_istri']) . '</td></tr>
        <tr><th>Status Pernikahan</th><td>' . htmlspecialchars($data['status_pernikahan']) . '</td></tr>
        <tr><th>Nama Saksi</th><td>' . htmlspecialchars($data['nama_saksi']) . '</td></tr>
        <tr><th>Nama Petugas</th><td>' . htmlspecialchars($data['nama_petugas']) . '</td></tr>
    </table>
    
    <div style="margin-top: 50px; text-align: right;">
        <p>Yogyakarta, ' . date('d M Y') . '</p>
        <br><br><br>
        <p><strong>' . htmlspecialchars($data['nama_petugas']) . '</strong></p>
    </div>

    <!-- Page 2 -->
    <div class="page-break"></div>
    
    <h2 class="text-center">Lampiran Berkas</h2>
    <div class="text-center">
        ' . $imagesHtml . '
    </div>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();


$dompdf->stream('Arsip_' . $data['no_arsip'] . '.pdf', ["Attachment" => false]);
?>