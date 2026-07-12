<?php
defined('BASEPATH') OR exit('No direct script access allowed');



/**
 * Controller Pengguna
 * Mengatur manajemen akun pengguna (hanya dapat diakses oleh Koordinator)
 */
class Pengguna extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Cek apakah user yang login adalah Koordinator, jika bukan lempar ke dashboard
        if ($this->session->userdata('role') !== 'koor') {
            redirect('dashboard');
        }
        // Memuat model Pengguna_model untuk manipulasi data pengguna
        $this->load->model('Pengguna_model');
    }

    /**
     * Menampilkan daftar seluruh pengguna
     */
    public function index() {
        $data['title'] = 'Manajemen Pengguna';
        $data['pengguna'] = $this->Pengguna_model->get_all();
        $this->render('pengguna/index', $data);
    }

    /**
     * Menampilkan form tambah pengguna baru
     */
    public function tambah() {
        $data['title'] = 'Tambah Pengguna';
        $this->render('pengguna/tambah', $data);
    }

    /**
     * Memproses penyimpanan akun pengguna baru ke database
     */
    public function do_tambah() {
        // Enkripsi password menggunakan password_hash sebelum disimpan
        $data = [
            'nama' => $this->input->post('nama'),
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'role' => $this->input->post('role')
        ];

        // Validasi agar username tidak duplikat
        if ($this->Pengguna_model->get_by_username($data['username'])) {
            $this->session->set_flashdata('error', 'Username sudah digunakan!');
            redirect('pengguna/tambah');
        }

        $this->Pengguna_model->insert($data);
        $this->session->set_flashdata('success', 'Pengguna berhasil ditambahkan!');
        redirect('pengguna');
    }

    /**
     * Menampilkan form edit data pengguna
     */
    public function edit($id) {
        $data['title'] = 'Edit Pengguna';
        $data['user'] = $this->Pengguna_model->get_by_id($id);
        if (!$data['user']) show_404();
        $this->render('pengguna/edit', $data);
    }

    /**
     * Memproses update data pengguna
     */
    public function do_edit() {
        $id = $this->input->post('id');
        $data = [
            'nama' => $this->input->post('nama'),
            'username' => $this->input->post('username'),
            'role' => $this->input->post('role')
        ];

        // Update password hanya jika input password diisi oleh user
        if ($this->input->post('password')) {
            $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        }

        $this->Pengguna_model->update($id, $data);
        $this->session->set_flashdata('success', 'Pengguna berhasil diupdate!');
        redirect('pengguna');
    }

    /**
     * Menghapus akun pengguna
     */
    public function hapus($id) {
        // Mencegah penghapusan diri sendiri saat sedang login
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Tidak bisa menghapus diri sendiri!');
        } else {
            $this->Pengguna_model->delete($id);
            $this->session->set_flashdata('success', 'Pengguna berhasil dihapus!');
        }
        redirect('pengguna');
    }
    
    /**
     * Menampilkan daftar arsip yang pernah diinputkan oleh pengguna tertentu
     */
    public function view_data($id) {
        $this->load->model('Arsip_model');
        $data['title'] = 'Data Input Pengguna';
        $data['user'] = $this->Pengguna_model->get_by_id($id);
        $data['arsip'] = $this->Arsip_model->get_by_pengguna($id);
        $this->render('pengguna/view_data', $data);
    }
}
