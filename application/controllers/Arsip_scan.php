<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Arsip_scan
 * Mengatur pengelolaan data scan dokumen arsip (PDF)
 */
class Arsip_scan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Memuat model Arsip_scan_model untuk interaksi database
        $this->load->model('Arsip_scan_model');
    }

    /**
     * Menampilkan daftar data scan dokumen
     */
    public function index() {
        $search = $this->input->get('search_scan');
        $data['title'] = 'Arsip Scan Dokumen';
        $data['search_scan'] = $search;
        
        // Cari data jika ada input pencarian, jika tidak ambil semua
        if ($search) {
            $data['scans'] = $this->Arsip_scan_model->search($search);
        } else {
            $data['scans'] = $this->Arsip_scan_model->get_all();
        }
        
        $this->render('arsip_scan/index', $data);
    }

    /**
     * Memproses penambahan data scan baru beserta upload file PDF
     */
    public function do_tambah() {
        $kelurahan = $this->input->post('kelurahan');
        
        // Penentuan prefix nomor arsip
        $prefix = '';
        if ($kelurahan == 'Canden') $prefix = 'CDN';
        elseif ($kelurahan == 'Patalan') $prefix = 'PTL';
        elseif ($kelurahan == 'Sumberagung') $prefix = 'SBR';
        elseif ($kelurahan == 'Trimulyo') $prefix = 'TRM';
        
        // Ambil nomor urut terakhir
        $last = $this->Arsip_scan_model->get_last_by_kelurahan($kelurahan);
        $new_no = $last ? ((int) str_replace($prefix, '', $last['no_arsip'])) + 1 : 1;
        $no_arsip = $prefix . $new_no;

        $files_pdf = [];
        $this->load->library('upload');

        // Proses upload multiple file PDF
        if (!empty($_FILES['file_pdf']['name'][0])) {
            $filesCount = count($_FILES['file_pdf']['name']);
            for ($i = 0; $i < $filesCount; $i++) {
                // Reformat array $_FILES agar bisa dibaca library upload CodeIgniter
                $_FILES['userfile']['name'] = $_FILES['file_pdf']['name'][$i];
                $_FILES['userfile']['type'] = $_FILES['file_pdf']['type'][$i];
                $_FILES['userfile']['tmp_name'] = $_FILES['file_pdf']['tmp_name'][$i];
                $_FILES['userfile']['error'] = $_FILES['file_pdf']['error'][$i];
                $_FILES['userfile']['size'] = $_FILES['file_pdf']['size'][$i];

                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'pdf';
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);

                if ($this->upload->do_upload('userfile')) {
                    $fileData = $this->upload->data();
                    $files_pdf[] = $fileData['file_name'];
                }
            }
        }

        // Simpan data ke database, file PDF disimpan dalam format JSON string
        $insert_data = [
            'no_arsip' => $no_arsip,
            'kelurahan' => $kelurahan,
            'tgl_arsip' => $this->input->post('tgl_arsip'),
            'nama_suami' => $this->input->post('nama_suami'),
            'nama_istri' => $this->input->post('nama_istri'),
            'files_pdf' => json_encode($files_pdf),
            'nama_petugas' => $this->session->userdata('nama_petugas'),
            'id_pengguna' => $this->session->userdata('user_id')
        ];

        $this->Arsip_scan_model->insert($insert_data);
        $this->session->set_flashdata('success', 'Data scan berhasil ditambahkan!');
        redirect('arsip_scan');
    }

    /**
     * Menampilkan form edit data scan
     */
    public function edit($id) {
        $data['title'] = 'Edit Arsip Scan';
        $data['scan'] = $this->Arsip_scan_model->get_by_id($id);
        if (!$data['scan']) show_404();
        $this->render('arsip_scan/edit', $data);
    }

    /**
     * Memproses update data scan, termasuk hapus/tambah file PDF
     */
    public function do_edit() {
        $id = $this->input->post('id');
        $old_data = $this->Arsip_scan_model->get_by_id($id);
        // Decode JSON untuk mendapatkan daftar file lama
        $files_pdf = json_decode($old_data['files_pdf'], true) ?? [];

        // Hapus file yang dipilih untuk dibuang oleh user
        $delete_files = $this->input->post('delete_files') ?? [];
        foreach ($delete_files as $file_to_delete) {
            if (($key = array_search($file_to_delete, $files_pdf)) !== false) {
                unset($files_pdf[$key]);
                if (file_exists('./uploads/' . $file_to_delete)) {
                    unlink('./uploads/' . $file_to_delete);
                }
            }
        }
        $files_pdf = array_values($files_pdf); // Re-index array

        // Upload file PDF baru jika ada
        $this->load->library('upload');
        if (!empty($_FILES['file_pdf']['name'][0])) {
            $filesCount = count($_FILES['file_pdf']['name']);
            for ($i = 0; $i < $filesCount; $i++) {
                $_FILES['userfile']['name'] = $_FILES['file_pdf']['name'][$i];
                $_FILES['userfile']['type'] = $_FILES['file_pdf']['type'][$i];
                $_FILES['userfile']['tmp_name'] = $_FILES['file_pdf']['tmp_name'][$i];
                $_FILES['userfile']['error'] = $_FILES['file_pdf']['error'][$i];
                $_FILES['userfile']['size'] = $_FILES['file_pdf']['size'][$i];

                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'pdf';
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);

                if ($this->upload->do_upload('userfile')) {
                    $fileData = $this->upload->data();
                    $files_pdf[] = $fileData['file_name'];
                }
            }
        }

        $update_data = [
            'tgl_arsip' => $this->input->post('tgl_arsip'),
            'nama_suami' => $this->input->post('nama_suami'),
            'nama_istri' => $this->input->post('nama_istri'),
            'files_pdf' => json_encode($files_pdf)
        ];

        $this->Arsip_scan_model->update($id, $update_data);
        $this->session->set_flashdata('success', 'Data scan berhasil diupdate!');
        redirect('arsip_scan');
    }

    /**
     * Menghapus data scan beserta seluruh file PDF terkait
     */
    public function hapus($id) {
        $data = $this->Arsip_scan_model->get_by_id($id);
        if ($data) {
            $files = json_decode($data['files_pdf'], true);
            if ($files) {
                foreach ($files as $file) {
                    if (file_exists('./uploads/' . $file)) unlink('./uploads/' . $file);
                }
            }
            $this->Arsip_scan_model->delete($id);
            $this->session->set_flashdata('success', 'Data scan berhasil dihapus!');
        }
        redirect('arsip_scan');
    }

    /**
     * Endpoint API untuk mendapatkan nomor arsip scan terbaru
     */
    public function get_no_arsip() {
        $kelurahan = $this->input->get('kelurahan');
        $prefix = '';
        if ($kelurahan == 'Canden') $prefix = 'CDN';
        elseif ($kelurahan == 'Patalan') $prefix = 'PTL';
        elseif ($kelurahan == 'Sumberagung') $prefix = 'SBR';
        elseif ($kelurahan == 'Trimulyo') $prefix = 'TRM';
        
        $last = $this->Arsip_scan_model->get_last_by_kelurahan($kelurahan);
        $new_no = $last ? ((int) str_replace($prefix, '', $last['no_arsip'])) + 1 : 1;
        echo $prefix . $new_no;
    }
}
