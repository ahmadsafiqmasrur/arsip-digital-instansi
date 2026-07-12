<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Editrequest
 * Handles edit request workflow for Koordinator and Petugas.
 */
class Editrequest extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Editrequest_model');
        // Ensure user is logged in
        if(!$this->session->userdata('user_id')){
            redirect('auth');
        }
    }

    /**
     * Petugas sends an edit request (AJAX)
     * Expected POST: arsip_id, note (optional)
     */
    public function request_edit(){
        header('Content-Type: application/json; charset=utf-8');
        $petugas_id = $this->session->userdata('user_id');
        $arsip_id   = $this->input->post('arsip_id');
        $note       = $this->input->post('note');
        if(!$arsip_id){
            echo json_encode(['status'=>'error','message'=>'Arsip ID missing']);
            return;
        }
        $this->Editrequest_model->request_edit($arsip_id, $petugas_id, $note);
        echo json_encode(['status'=>'pending']);
    }

    /**
     * Koordinator dashboard – list of all edit requests.
     */
    public function index(){
        // Only Koordinator can view this page
        if($this->session->userdata('role') !== 'koor'){
            show_error('Access denied', 403);
            return;
        }
        $data['requests'] = $this->Editrequest_model->get_requests();
        $this->load->view('pengguna/edit_requests', $data);
    }

    /** Reject a pending request */
    public function reject($request_id){
        if($this->session->userdata('role') !== 'koor') show_error('Access denied',403);
        $note = $this->input->post('note');
        $this->Editrequest_model->reject($request_id, $note);
        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'rejected']);
            return;
        }
        redirect('editrequest');
    }

    /** Approve a pending request */
    public function approve($request_id){
        if($this->session->userdata('role') !== 'koor') show_error('Access denied',403);
        $note = $this->input->post('note');
        $this->Editrequest_model->approve($request_id, $note);
        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'approved']);
            return;
        }
        redirect('editrequest');
    }

    /** Return pending requests for koordinator as JSON */
    public function pending_requests(){
        if($this->session->userdata('role') !== 'koor') {
            show_error('Access denied',403);
            return;
        }
        header('Content-Type: application/json; charset=utf-8');
        $requests = $this->Editrequest_model->get_requests('pending');
        echo json_encode(['requests' => $requests]);
    }

    /** Return current petugas requests as JSON */
    public function user_requests(){
        if($this->session->userdata('role') !== 'petugas') {
            show_error('Access denied',403);
            return;
        }
        header('Content-Type: application/json; charset=utf-8');
        $user_id = $this->session->userdata('user_id');
        $requests = $this->Editrequest_model->get_requests_by_petugas($user_id);
        echo json_encode(['requests' => $requests]);
    }

    /** Return notifications JSON based on current role */
    public function notifications(){
        header('Content-Type: application/json; charset=utf-8');
        $role = $this->session->userdata('role');
        if ($role === 'koor') {
            $requests = $this->Editrequest_model->get_requests('pending');
        } elseif ($role === 'petugas') {
            $user_id = $this->session->userdata('user_id');
            $requests = $this->Editrequest_model->get_requests_by_petugas($user_id);
        } else {
            $requests = [];
        }
        echo json_encode(['requests' => $requests]);
    }

    // Return pending request count as JSON for badge
    public function pending_count(){
        header('Content-Type: application/json; charset=utf-8');
        $count = $this->Editrequest_model->get_pending_count();
        echo json_encode(['count'=>$count]);
    }

}
?>
