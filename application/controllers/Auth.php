<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Auth
 * Mengatur proses autentikasi (login/logout) untuk Petugas dan Koordinator
 */
class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Memuat model Pengguna_model untuk verifikasi user
        $this->load->model('Pengguna_model');
    }

    /**
     * Halaman landing awal yang sekaligus menjadi halaman Login
     */
    public function index()
    {
        // Jika sudah login, arahkan ke dashboard
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }
        $this->load->view('auth/landing');
    }

    /**
     * Memproses percobaan login
     */
    public function do_login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Cari user berdasarkan username
        $user = $this->Pengguna_model->get_by_username($username);

        // Verifikasi user dan password (hash)
        if ($user && password_verify($password, $user['password'])) {
            // Set data session jika login berhasil
            $this->session->set_userdata([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'nama_petugas' => $user['nama'],
                'role' => $user['role']
            ]);
            redirect('dashboard');
        } else {
            // Jika gagal, tampilkan pesan error
            $this->session->set_flashdata('error', 'Username atau Password salah!');
            redirect('auth');
        }
    }

    /**
     * Proses logout dan hancurkan session
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
