<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Arsip_model
 * Menangani seluruh query database untuk tabel 'arsip'
 */
class Arsip_model extends CI_Model {

    /**
     * Mengambil seluruh data arsip
     */
    public function get_all() {
        return $this->db->get('arsip')->result_array();
    }

    /**
     * Mengambil satu data arsip berdasarkan ID
     */
    public function get_by_id($id) {
        return $this->db->get_where('arsip', ['id' => $id])->row_array();
    }

    /**
     * Mengambil daftar arsip berdasarkan kelurahan tertentu
     */
    public function get_by_kelurahan($kelurahan) {
        $this->db->where('kelurahan', $kelurahan);
        $this->db->order_by('id', 'ASC');
        return $this->db->get('arsip')->result_array();
    }

    /**
     * Mengambil record terakhir dari suatu kelurahan (untuk penomoran otomatis)
     */
    public function get_last_by_kelurahan($kelurahan) {
        $this->db->where('kelurahan', $kelurahan);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        return $this->db->get('arsip')->row_array();
    }

    /**
     * Menambahkan data arsip baru
     */
    public function insert($data) {
        return $this->db->insert('arsip', $data);
    }

    /**
     * Mengupdate data arsip berdasarkan ID
     */
    public function update($id, $data) {
        return $this->db->update('arsip', $data, ['id' => $id]);
    }

    /**
     * Menghapus data arsip berdasarkan ID
     */
    public function delete($id) {
        return $this->db->delete('arsip', ['id' => $id]);
    }

    /**
     * Mencari data arsip berdasarkan nama suami, istri, atau nomor arsip
     */
    public function search($query, $kelurahan = null) {
        $this->db->group_start();
        $this->db->like('nama_suami', $query);
        $this->db->or_like('nama_istri', $query);
        $this->db->or_like('no_arsip', $query);
        $this->db->group_end();
        
        // Filter tambahan jika pencarian dibatasi per kelurahan
        if ($kelurahan) {
            $this->db->where('kelurahan', $kelurahan);
        }
        
        $this->db->order_by('id', 'DESC');
        return $this->db->get('arsip')->result_array();
    }

    /**
     * Menghitung total arsip di suatu kelurahan (untuk statistik dashboard)
     */
    public function get_count_by_kelurahan($kelurahan, $startDate = null, $endDate = null) {
        $this->db->where('kelurahan', $kelurahan);
        if (!empty($startDate)) {
            $this->db->where('created_at >=', $startDate);
        }
        if (!empty($endDate)) {
            $this->db->where('created_at <=', $endDate);
        }
        return $this->db->count_all_results('arsip');
    }

   
    public function get_time_series_counts($kelurahan = null, $startDate = null, $endDate = null, $group = 'day') {
        if ($kelurahan) {
            $this->db->where('kelurahan', $kelurahan);
        }
        if (!empty($startDate)) {
            $this->db->where('created_at >=', $startDate);
        }
        if (!empty($endDate)) {
            $this->db->where('created_at <', $endDate);
        }

        switch ($group) {
            case 'week':
                $periodSql = "CONCAT(YEAR(created_at),'-W',LPAD(WEEK(created_at,1),2,'0'))";
                break;
            case 'month':
                $periodSql = "DATE_FORMAT(created_at, '%Y-%m')";
                break;
            case 'year':
                $periodSql = "YEAR(created_at)";
                break;
            case 'day':
            default:
                $periodSql = "DATE(created_at)";
                break;
        }

        $this->db->select($periodSql . ' as period, COUNT(*) as cnt', false);
        $this->db->group_by('period');
        $this->db->order_by('period', 'ASC');
        $rows = $this->db->get('arsip')->result_array();

        $out = [];
        foreach ($rows as $r) {
            $out[$r['period']] = intval($r['cnt']);
        }
        return $out;
    }

    /**
     * Mengambil daftar arsip yang diinputkan oleh pengguna tertentu
     */
    public function get_by_pengguna($id_pengguna) {
        $this->db->where('id_pengguna', $id_pengguna);
        $this->db->order_by('id', 'DESC');
        return $this->db->get('arsip')->result_array();
    }
}
