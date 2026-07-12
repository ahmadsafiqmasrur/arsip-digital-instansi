<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Arsip_scan_model
 * Menangani query database untuk tabel 'arsip_scan' (dokumen PDF)
 */
class Arsip_scan_model extends CI_Model {

    /**
     * Mengambil seluruh data scan dokumen
     */
    public function get_all() {
        return $this->db->get('arsip_scan')->result_array();
    }

    /**
     * Mengambil satu data scan berdasarkan ID
     */
    public function get_by_id($id) {
        return $this->db->get_where('arsip_scan', ['id' => $id])->row_array();
    }

    /**
     * Mengambil record terakhir untuk kelurahan tertentu (untuk penomoran)
     */
    public function get_last_by_kelurahan($kelurahan) {
        $this->db->where('kelurahan', $kelurahan);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        return $this->db->get('arsip_scan')->row_array();
    }

    /**
     * Menambahkan data scan baru
     */
    public function insert($data) {
        return $this->db->insert('arsip_scan', $data);
    }

    /**
     * Mengupdate data scan
     */
    public function update($id, $data) {
        return $this->db->update('arsip_scan', $data, ['id' => $id]);
    }

    /**
     * Menghapus data scan
     */
    public function delete($id) {
        return $this->db->delete('arsip_scan', ['id' => $id]);
    }

    /**
     * Mencari data scan berdasarkan nama suami, istri, atau nomor arsip
     */
    public function search($query) {
        $this->db->like('nama_suami', $query);
        $this->db->or_like('nama_istri', $query);
        $this->db->or_like('no_arsip', $query);
        $this->db->order_by('id', 'DESC');
        return $this->db->get('arsip_scan')->result_array();
    }
}
