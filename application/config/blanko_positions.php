<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['blanko_positions'] = [
    // page number (1-based) => fields with CSS-like top/left
    1 => [
        'no_arsip' => ['top' => '6.5%', 'left' => '80%',],
        'no_akta' => ['top' => '35.5%', 'left' => '35%'],
        'kelurahan' => ['top' => '42%', 'left' => '35%'],
        'kabupaten' => ['top' => '45%', 'left' => '35%', 'value' => 'Bantul'],
        'provinsi' => ['top' => '48.5%', 'left' => '35%', 'value' => 'D.I. Yogyakarta'],
        'republik' => ['top' => '52%', 'left' => '35%', 'value' => 'Republik Indonesia'],
    
        'foto_suami' => ['top' => '60%', 'left' => '30.5%', 'width' => '130px', 'height' => '170px'],
        'foto_istri' => ['top' => '60%', 'left' => '52%', 'width' => '130px', 'height' => '170px'],
    ],
    2 => [
         'tglDaftar_weekday' => ['top' => '2%', 'left' => '25%'],
        'tglDaftar_day' => ['top' => '2%', 'left' => '42%'],
        'tglDaftar_month' => ['top' => '2%', 'left' => '52%'],
        'tglDaftar_year' => ['top' => '2%', 'left' => '75%'],
        'tglNikah_day' => ['top' => '5.5%', 'left' => '34%'],
        'tglNikah_month' => ['top' => '5.5%', 'left' => '48%'],
        'tglNikah_year' => ['top' => '5.5%', 'left' => '66%'],
        'nama_suami' => ['top' => '10.5%', 'left' => '45.5%'],
        'nik_suami' => ['top' => '13.5%', 'left' => '45.5%'],
        'kewarganegaraan' => ['top' => '14.5%', 'left' => '45.5%', 'value' => 'Warga Negara Indonesia'],
        'agama' => ['top' => '15.5%', 'left' => '45.5%', 'value' => 'Islam'],
        'alamat_suami' => ['top' => '17.5%', 'left' => '45.5%'],
        'status_pernikahan' => ['top' => '37%', 'left' => '45.5%'],
    ],
    3 => [
        'nama_istri' => ['top' => '3.5%', 'left' => '45.5%'],
        'nik_istri' => ['top' => '6.5%', 'left' => '45.5%'],
        'kewarganegaraan' => ['top' => '7.5%', 'left' => '45.5%', 'value' => 'Warga Negara Indonesia'],
        'agama' => ['top' => '8.5%', 'left' => '45.5%', 'value' => 'Islam'],
        'alamat_istri' => ['top' => '11%', 'left' => '45.5%'],
    ],
    4 => [
        'nama_saksi' => ['top' => '37.5%', 'left' => '45.5%'],
        'nama_penghulu' => ['top' => '59.5%', 'left' => '45.5%'],
        'nama_petugas' => ['top' => '82.5%', 'left' => '68.5%'],
    ],
];

/*
Positions are percent-based (top/left) so they scale with page width/height.
Adjust values if alignment needs fine-tuning.
*/

return $config;
