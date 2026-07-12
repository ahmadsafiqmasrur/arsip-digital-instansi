<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Model Editrequest_model
 * Handles edit request workflow for arsip records.
 */
class Editrequest_model extends CI_Model {
    public function request_edit($arsip_id, $petugas_id, $note = null) {
        $data = [
            'arsip_id'   => $arsip_id,
            'petugas_id' => $petugas_id,
            'status'     => 'pending',
            'comment'    => $note,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->insert('edit_requests', $data);
    }
    public function get_requests($status = null) {
        if ($status) $this->db->where('er.status', $status);
        $this->db->select('er.*, a.no_arsip, a.kelurahan, p.username as petugas_name, er.comment as note');
        $this->db->from('edit_requests er');
        $this->db->join('arsip a', 'er.arsip_id = a.id', 'left');
        $this->db->join('pengguna p', 'er.petugas_id = p.id', 'left');
        $this->db->order_by('er.created_at', 'DESC');
        return $this->db->get()->result_array();
    }
    public function get_requests_by_petugas($petugas_id, $status = null) {
        $this->db->where('er.petugas_id', $petugas_id);
        if ($status) {
            $this->db->where('er.status', $status);
        } else {
            $this->db->where_in('er.status', ['pending', 'approved']);
        }
        $this->db->select('er.*, a.no_arsip, a.kelurahan, er.comment as note');
        $this->db->from('edit_requests er');
        $this->db->join('arsip a', 'er.arsip_id = a.id', 'left');
        $this->db->order_by('er.created_at', 'DESC');
        return $this->db->get()->result_array();
    }
    public function get_latest_request($arsip_id, $petugas_id) {
        $this->db->where('arsip_id', $arsip_id);
        $this->db->where('petugas_id', $petugas_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);
        return $this->db->get('edit_requests')->row_array();
    }
    public function approve($id, $note = null) {
        $data = ['status'=>'approved','comment'=>$note,'updated_at'=>date('Y-m-d H:i:s')];
        return $this->db->where('id',$id)->update('edit_requests',$data);
    }
    public function reject($id, $note = null) {
        $data = ['status'=>'rejected','comment'=>$note,'updated_at'=>date('Y-m-d H:i:s')];
        return $this->db->where('id',$id)->update('edit_requests',$data);
    }
    public function can_edit($arsip_id,$petugas_id) {
        $this->db->where(['arsip_id'=>$arsip_id,'petugas_id'=>$petugas_id,'status'=>'approved']);
        return $this->db->count_all_results('edit_requests')>0;
    }

    public function complete_request($arsip_id, $petugas_id) {
        $data = ['status' => 'completed', 'updated_at' => date('Y-m-d H:i:s')];
        return $this->db->where(['arsip_id' => $arsip_id, 'petugas_id' => $petugas_id, 'status' => 'approved'])
            ->update('edit_requests', $data);
    }

    public function expire_pending($hours=48){
        $threshold = date('Y-m-d H:i:s', strtotime("-{$hours} hours"));
        $this->db->where('status','pending');
        $this->db->where('created_at <=',$threshold);
        return $this->db->update('edit_requests',[
            'status'=>'rejected','updated_at'=>date('Y-m-d H:i:s'),'note'=>'Auto‑expired after timeout'
        ]);
    }
    public function get_pending_count() {
        $role = $this->session->userdata('role');
        if ($role === 'koor') {
            $this->db->where('status', 'pending');
            return $this->db->count_all_results('edit_requests');
        }
        $petugas_id = $this->session->userdata('user_id');
        $this->db->where('petugas_id', $petugas_id);
        $this->db->where_in('status', ['pending', 'approved']);
        return $this->db->count_all_results('edit_requests');
    }
}
?>

