<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Core Controller Custom (MY_Controller)
 * Menjadi parent class untuk controller lain agar memiliki fitur login check dan render otomatis
 */
class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cek login secara global: jika belum login, tendang ke halaman auth
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    /**
     * Fungsi Helper untuk merender view dengan layout (header & footer)
     */
    protected function render($view, $data = []) {
        $this->load->view('layout/header', $data);
        $this->load->view($view, $data);
        $this->load->view('layout/footer', $data);
    }
}
