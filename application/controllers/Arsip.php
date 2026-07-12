<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Arsip
 * Mengatur pengelolaan data arsip pernikahan utama
 */
class Arsip extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Memuat model Arsip_model untuk interaksi database
        $this->load->model('Arsip_model');
    }

    /**
     * Menampilkan daftar arsip berdasarkan kelurahan atau pencarian
     */
    public function index()
    {
        $kelurahan = $this->input->get('kelurahan');
        $search = $this->input->get('search');

        $data['title'] = 'Data Arsip';
        $data['kelurahan_filter'] = $kelurahan;
        $data['search'] = $search;

        // Logika pengambilan data: jika ada pencarian, gunakan fungsi search
        if ($search) {
            $data['data'] = $this->Arsip_model->search($search, $kelurahan);
        } else {
            // Jika ada filter kelurahan, ambil per kelurahan, jika tidak ambil semua
            if ($kelurahan) {
                $data['data'] = $this->Arsip_model->get_by_kelurahan($kelurahan);
            } else {
                $data['data'] = $this->Arsip_model->get_all();
            }
        }

        // Render ke view arsip/index
        $this->render('arsip/index', $data);
    }

    /**
     * Menampilkan halaman pencarian arsip yang akan diedit
     */
    public function edit_list()
    {
        $search_edit = $this->input->get('search_edit');
        $data['title'] = 'Pencarian Data untuk Diedit';
        $data['search_edit'] = $search_edit;

        $this->load->model('Editrequest_model');
        $petugas_id = $this->session->userdata('user_id');

        if (!empty($search_edit)) {
            $data['results'] = $this->Arsip_model->search($search_edit);
        } else {
            $data['results'] = [];
        }

        foreach ($data['results'] as &$row) {
            $request = $this->Editrequest_model->get_latest_request($row['id'], $petugas_id);
            $row['edit_request_status'] = $request['status'] ?? null;
        }
        unset($row);

        $data['user_id'] = $petugas_id;
        $this->render('arsip/edit_list', $data);
    }

    /**
     * Menampilkan form tambah arsip baru
     */
    public function tambah()
    {
        $data['title'] = 'Tambah Arsip';
        $this->render('arsip/tambah', $data);
    }

    /**
     * Memproses penyimpanan data arsip baru ke database
     */
    public function do_tambah()
    {
        $kelurahan = $this->input->post('kelurahan');

        // Menentukan prefix nomor arsip berdasarkan kelurahan
        $prefix = '';
        if ($kelurahan == 'Canden')
            $prefix = 'CDN';
        elseif ($kelurahan == 'Patalan')
            $prefix = 'PTL';
        elseif ($kelurahan == 'Sumberagung')
            $prefix = 'SBR';
        elseif ($kelurahan == 'Trimulyo')
            $prefix = 'TRM';

        // Mengambil nomor arsip terakhir untuk increment otomatis
        $last = $this->Arsip_model->get_last_by_kelurahan($kelurahan);
        $new_no = $last ? ((int) str_replace($prefix, '', $last['no_arsip'])) + 1 : 1;
        $no_arsip = $prefix . $new_no;

        // Konfigurasi upload foto
        $uploaded_files = [];
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);

        // Upload foto suami dan istri
        foreach (['foto_suami', 'foto_istri'] as $field) {
            if (!$this->upload->do_upload($field)) {
                // Jika gagal, set pesan error dan kembali ke halaman tambah
                $this->session->set_flashdata('error', "Gagal upload $field: " . $this->upload->display_errors('', ''));
                redirect('arsip/tambah');
            } else {
                $fileData = $this->upload->data();
                $uploaded_files[$field] = $fileData['file_name'];
            }
        }

        // Data yang akan disimpan ke database
        $insert_data = [
            'no_arsip' => $no_arsip,
            'kelurahan' => $kelurahan,
            'tgl_daftar' => $this->input->post('tgl_daftar'),
            'no_akta' => $this->input->post('no_akta'),
            'nama_suami' => $this->input->post('nama_suami'),
            'nama_istri' => $this->input->post('nama_istri'),
            'nik_suami' => $this->input->post('nik_suami'),
            'nik_istri' => $this->input->post('nik_istri'),
            'alamat_suami' => $this->input->post('alamat_suami'),
            'alamat_istri' => $this->input->post('alamat_istri'),
            'status_pernikahan' => $this->input->post('status_pernikahan'),
            'nama_saksi' => $this->input->post('nama_saksi'),
            'tgl_nikah' => $this->input->post('tgl_nikah'),
            'nama_penghulu' => $this->input->post('nama_penghulu'),
            'nama_petugas' => $this->session->userdata('nama_petugas'), // Diambil dari session login
            'id_pengguna' => $this->session->userdata('user_id'),
            'foto_suami' => $uploaded_files['foto_suami'],
            'foto_istri' => $uploaded_files['foto_istri']
        ];

        // Simpan ke database melalui model
        $this->Arsip_model->insert($insert_data);
        $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
        redirect('arsip?kelurahan=' . urlencode($kelurahan));
    }

    /**
     * Menampilkan form edit data arsip berdasarkan ID
     */
    public function edit($id)
    {
        $data['title'] = 'Edit Arsip';
        $data['arsip'] = $this->Arsip_model->get_by_id($id);
        if (!$data['arsip'])
            show_404(); // Tampilkan 404 jika data tidak ditemukan
        // Load edit request model and verify permission
        $this->load->model('Editrequest_model');
        $petugas_id = $this->session->userdata('user_id');
        if (!$this->Editrequest_model->can_edit($id, $petugas_id)) {
            $this->session->set_flashdata('error', 'Anda belum mendapatkan persetujuan koordinator untuk mengedit arsip ini.');
            redirect('arsip');
            return;
        }
        $this->render('arsip/edit', $data);
    }

    /**
     * Memproses update data arsip ke database
     */
    public function do_edit()
    {
        $id = $this->input->post('id');
        $old_data = $this->Arsip_model->get_by_id($id);

        $update_data = [
            'tgl_daftar' => $this->input->post('tgl_daftar'),
            'no_akta' => $this->input->post('no_akta'),
            'nama_suami' => $this->input->post('nama_suami'),
            'nama_istri' => $this->input->post('nama_istri'),
            'nik_suami' => $this->input->post('nik_suami'),
            'nik_istri' => $this->input->post('nik_istri'),
            'alamat_suami' => $this->input->post('alamat_suami'),
            'alamat_istri' => $this->input->post('alamat_istri'),
            'status_pernikahan' => $this->input->post('status_pernikahan'),
            'nama_saksi' => $this->input->post('nama_saksi'),
            'tgl_nikah' => $this->input->post('tgl_nikah'),
            'nama_penghulu' => $this->input->post('nama_penghulu'),
        ];

        // Konfigurasi upload jika ada penggantian foto
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);

        // Proses ganti foto jika file diunggah
        foreach (['foto_suami', 'foto_istri'] as $field) {
            if (!empty($_FILES[$field]['name'])) {
                if ($this->upload->do_upload($field)) {
                    $fileData = $this->upload->data();
                    $update_data[$field] = $fileData['file_name'];
                    // Hapus foto lama dari server untuk menghemat ruang
                    if (!empty($old_data[$field]) && file_exists('./uploads/' . $old_data[$field])) {
                        unlink('./uploads/' . $old_data[$field]);
                    }
                } else {
                    $error = $this->upload->display_errors('', '');
                    if ($this->input->is_ajax_request()) {
                        header('Content-Type: application/json; charset=utf-8');
                        echo json_encode(['status' => 'error', 'message' => 'Upload foto gagal: ' . trim($error)]);
                        return;
                    }
                    $this->session->set_flashdata('error', 'Upload foto gagal: ' . trim($error));
                    redirect('arsip/edit/' . $id);
                    return;
                }
            }
        }

        // Update data di database
        $this->Arsip_model->update($id, $update_data);

        // Tandai permintaan edit sebagai selesai jika sudah disetujui
        $this->load->model('Editrequest_model');
        $petugas_id = $this->session->userdata('user_id');
        $this->Editrequest_model->complete_request($id, $petugas_id);

        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate!']);
            return;
        }

        $this->session->set_flashdata('success', 'Data berhasil diupdate!');
        redirect('arsip?kelurahan=' . urlencode($old_data['kelurahan']));
    }

    /**
     * Menghapus data arsip beserta file fotonya
     */
    public function hapus($id)
    {
        $data = $this->Arsip_model->get_by_id($id);
        if ($data) {
            // Hapus file fisik foto dari folder uploads
            if (file_exists('./uploads/' . $data['foto_suami']))
                unlink('./uploads/' . $data['foto_suami']);
            if (file_exists('./uploads/' . $data['foto_istri']))
                unlink('./uploads/' . $data['foto_istri']);

            // Hapus record dari database
            $this->Arsip_model->delete($id);
            $this->session->set_flashdata('success', 'Data berhasil dihapus!');
        }
        redirect($_SERVER['HTTP_REFERER'] ?? 'arsip');
    }

    /**
     * Menampilkan halaman pencarian data arsip untuk dicetak
     */
    public function cetak_arsip()
    {
        $search_cetak = $this->input->get('search_cetak');
        $data['title'] = 'Cetak Dokumen Arsip';
        $data['search_cetak'] = $search_cetak;

        if (!empty($search_cetak)) {
            $data['results'] = $this->Arsip_model->search($search_cetak);
        } else {
            $data['results'] = [];
        }

        $this->render('arsip/cetak_arsip', $data);
    }

    /**
     * Menghasilkan file PDF untuk detail arsip menggunakan Dompdf
     */
    /**
     * Menghasilkan file PDF untuk detail arsip menggunakan Dompdf
     */
    public function cetak($id)
    {
        $data = $this->Arsip_model->get_by_id($id);
        if (!$data)
            show_404();

        // Inisialisasi Dompdf dengan opsi
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);

        // Menyiapkan data foto dalam format base64 agar bisa dirender Dompdf
        $fotoSuamiBase64 = '';
        $fotoIstriBase64 = '';

        if (!empty($data['foto_suami'])) {
            $imagePath = './uploads/' . $data['foto_suami'];
            if (file_exists($imagePath)) {
                $type = pathinfo($imagePath, PATHINFO_EXTENSION);
                $img_data = file_get_contents($imagePath);
                $fotoSuamiBase64 = 'data:image/' . $type . ';base64,' . base64_encode($img_data);
            }
        }

        if (!empty($data['foto_istri'])) {
            $imagePath = './uploads/' . $data['foto_istri'];
            if (file_exists($imagePath)) {
                $type = pathinfo($imagePath, PATHINFO_EXTENSION);
                $img_data = file_get_contents($imagePath);
                $fotoIstriBase64 = 'data:image/' . $type . ';base64,' . base64_encode($img_data);
            }
        }

        $blankoPages = [];
        // Build a page-number => file path map.
        // Prefer files that contain 'ori' (the real blanko), fallback to marker files that contain 'tanda'.
        $ori_map = [];
        $marker_map = [];
        $allFiles = glob('./assets/blanko/*page*.png');
        foreach ($allFiles as $f) {
            $bn = basename($f);
            if (preg_match('/page[_\-]?(\d+)\.png$/i', $bn, $m)) {
                $n = (int) $m[1];
                $lower = strtolower($bn);
                if (strpos($lower, 'ori') !== false) {
                    $ori_map[$n] = $f;
                } elseif (strpos($lower, 'tanda') !== false || strpos($lower, 'tanda_merah') !== false) {
                    $marker_map[$n] = $f;
                } else {
                    // unknown page file: treat as marker fallback
                    $marker_map[$n] = $f;
                }
            }
        }

        $page_nums = array_keys($ori_map + $marker_map);
        sort($page_nums, SORT_NUMERIC);
        foreach ($page_nums as $num) {
            $path = $ori_map[$num] ?? $marker_map[$num] ?? null;
            if ($path && file_exists($path)) {
                $blankoType = pathinfo($path, PATHINFO_EXTENSION);
                $blankoData = file_get_contents($path);
                $blankoPages[] = 'data:image/' . $blankoType . ';base64,' . base64_encode($blankoData);
            }
        }

        // Helper function untuk format tanggal Indonesia
        $bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tglDaftar = '';
        $tglDaftarDay = '';
        $tglDaftarMonth = '';
        $tglDaftarYear = '';
        $tglDaftarWeekday = '';
        if (!empty($data['tgl_daftar']) && $data['tgl_daftar'] !== '0000-00-00') {
            $tglDaftarDate = strtotime($data['tgl_daftar']);
            $tglDaftar = date('j', $tglDaftarDate) . ' ' . $bulanIndo[date('n', $tglDaftarDate)] . ' ' . date('Y', $tglDaftarDate);
            $tglDaftarDay = date('j', $tglDaftarDate);
            $tglDaftarMonth = $bulanIndo[date('n', $tglDaftarDate)];
            $tglDaftarYear = date('Y', $tglDaftarDate);
            $hariIndo = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            $tglDaftarWeekday = $hariIndo[date('w', $tglDaftarDate)];
        }
        $tglNikah = '-';
        $tglNikahDay = '';
        $tglNikahMonth = '';
        $tglNikahYear = '';
        if (!empty($data['tgl_nikah']) && $data['tgl_nikah'] !== '0000-00-00') {
            $tglNikahDate = strtotime($data['tgl_nikah']);
            $tglNikah = date('j', $tglNikahDate) . ' ' . $bulanIndo[date('n', $tglNikahDate)] . ' ' . date('Y', $tglNikahDate);
            $tglNikahDay = date('j', $tglNikahDate);
            $tglNikahMonth = $bulanIndo[date('n', $tglNikahDate)];
            $tglNikahYear = date('Y', $tglNikahDate);
        }
        $tglCetak = date('j') . ' ' . $bulanIndo[date('n')] . ' ' . date('Y');

        // Mengambil logo Kemenag dan konversi ke base64
        $logoPath = './assets/images/kemenag.png';
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }

        // Struktur data untuk template cetak
        // load blanko positions config (per-page)
        $this->config->load('blanko_positions', true, true);
        $blanko_positions = $this->config->item('blanko_positions', 'blanko_positions');
        if ($blanko_positions === null) {
            $this->config->load('blanko_positions');
            $blanko_positions = $this->config->item('blanko_positions');
        }

        $templateData = [
            'no_arsip' => $data['no_arsip'],
            'no_akta' => $data['no_akta'],
            'kelurahan' => $data['kelurahan'],
            'kecamatan' => $data['kecamatan'] ?? 'Jetis',
            'tglDaftar' => $tglDaftar,
            'tglDaftar_day' => $tglDaftarDay,
            'tglDaftar_month' => $tglDaftarMonth,
            'tglDaftar_year' => $tglDaftarYear,
            'tglDaftar_weekday' => $tglDaftarWeekday,
            'tglNikah' => $tglNikah,
            'tglNikah_day' => $tglNikahDay,
            'tglNikah_month' => $tglNikahMonth,
            'tglNikah_year' => $tglNikahYear,
            'nama_suami' => $data['nama_suami'],
            'nik_suami' => $data['nik_suami'] ?? '-',
            'alamat_suami' => $data['alamat_suami'] ?? '-',
            'nama_istri' => $data['nama_istri'],
            'nik_istri' => $data['nik_istri'] ?? '-',
            'alamat_istri' => $data['alamat_istri'] ?? '-',
            'status_pernikahan' => $data['status_pernikahan'],
            'nama_saksi' => $data['nama_saksi'] ?? '-',
            'nama_penghulu' => $data['nama_penghulu'] ?? '-',
            'nama_petugas' => $data['nama_petugas'],
            'tglCetak' => $tglCetak,
            'logoBase64' => $logoBase64,
            'blanko_pages' => $blankoPages,
            'blanko_positions' => $blanko_positions,
            'foto_suami_base64' => $fotoSuamiBase64,
            'foto_istri_base64' => $fotoIstriBase64
        ];

        // Build a flat map of field values for all fields referenced in blanko_positions
        $field_values = [];
        foreach ($blanko_positions as $pnum => $fields) {
            foreach ($fields as $fname => $meta) {
                if (array_key_exists($fname, $field_values)) continue; // already set

                // fixed text values from blanko_positions config
                if (!empty($meta['value'])) {
                    $field_values[$fname] = $meta['value'];
                    continue;
                }

                // photo fields
                if ($fname === 'foto_suami') {
                    $field_values[$fname] = $fotoSuamiBase64;
                    continue;
                }
                if ($fname === 'foto_istri') {
                    $field_values[$fname] = $fotoIstriBase64;
                    continue;
                }
                if ($fname === 'kelurahan') {
                    $value = isset($data['kelurahan']) ? trim($data['kelurahan']) : ($templateData['kelurahan'] ?? '');
                    if ($value === '') {
                        $field_values[$fname] = 'Jetis / ';
                    } elseif (stripos($value, 'jetis') === 0) {
                        $field_values[$fname] = $value;
                    } else {
                        $field_values[$fname] = 'Jetis / ' . $value;
                    }
                    continue;
                }

                // exact match in templateData
                if (array_key_exists($fname, $templateData)) {
                    $field_values[$fname] = $templateData[$fname];
                    continue;
                }

                // try from raw db row
                if (array_key_exists($fname, $data)) {
                    $field_values[$fname] = $data[$fname];
                    continue;
                }

                // handle simple suffix mapping like status_pernikahan_istri -> status_pernikahan
                if (preg_match('/^(.*)_(istri|suami)$/', $fname, $m)) {
                    $base = $m[1];
                    if (array_key_exists($base, $templateData)) {
                        $field_values[$fname] = $templateData[$base];
                        continue;
                    }
                    if (array_key_exists($base, $data)) {
                        $field_values[$fname] = $data[$base];
                        continue;
                    }
                }

                // fallback empty string
                $field_values[$fname] = '';
            }
        }

        // expose as part of template data for simpler lookup in view
        $templateData['field_values'] = $field_values;

        ob_start();
        $this->load->view('arsip/cetak_template', $templateData);
        $html = ob_get_clean();
        
        // --- PERBAIKAN DI SINI ---
        $dompdf->loadHtml($html); // Baris ini yang sebelumnya hilang!
        // -------------------------

        $dompdf->setPaper('F4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Arsip_' . $data['no_arsip'] . '.pdf', ["Attachment" => false]);
    }
    /**
     * Endpoint API untuk mendapatkan nomor arsip terbaru (digunakan oleh AJAX)
     */
    public function get_no_arsip()
    {
        $kelurahan = $this->input->get('kelurahan');
        $prefix = '';
        if ($kelurahan == 'Canden')
            $prefix = 'CDN';
        elseif ($kelurahan == 'Patalan')
            $prefix = 'PTL';
        elseif ($kelurahan == 'Sumberagung')
            $prefix = 'SBR';
        elseif ($kelurahan == 'Trimulyo')
            $prefix = 'TRM';

        $last = $this->Arsip_model->get_last_by_kelurahan($kelurahan);
        $new_no = $last ? ((int) str_replace($prefix, '', $last['no_arsip'])) + 1 : 1;
        echo $prefix . $new_no;
    }
}
