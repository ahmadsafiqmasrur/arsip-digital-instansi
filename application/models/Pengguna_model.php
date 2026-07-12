<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Pengguna_model
 * Menangani query database untuk tabel 'pengguna'
 */
class Pengguna_model extends CI_Model {

    /**
     * Mengambil daftar seluruh pengguna
     */
    public function get_all() {
        return $this->db->get('pengguna')->result_array();
    }

    /**
     * Mengambil data pengguna berdasarkan ID
     */
    public function get_by_id($id) {
        return $this->db->get_where('pengguna', ['id' => $id])->row_array();
    }

    /**
     * Mengambil data pengguna berdasarkan username (untuk login)
     */
    public function get_by_username($username) {
        return $this->db->get_where('pengguna', ['username' => $username])->row_array();
    }

    /**
     * Menambahkan akun pengguna baru
     */
    public function insert($data) {
        return $this->db->insert('pengguna', $data);
    }

    /**
     * Mengupdate data pengguna
     */
    public function update($id, $data) {
        return $this->db->update('pengguna', $data, ['id' => $id]);
    }

    /**
     * Menghapus akun pengguna
     */
    public function delete($id) {
        return $this->db->delete('pengguna', ['id' => $id]);
    }
}
