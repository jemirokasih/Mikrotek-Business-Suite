<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Reimbursements extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_reimbursements');
        $this->load->helper('date');
        $this->load->helper('number_helper');
        $this->load->helper('file_security');
    }

    public function index($status = 'all')
    {
        check_permission('reimbursements', 'view');

        $is_admin = has_permission('reimbursements', 'edit');
        $current_user_id = (int) $this->session->userdata('user_id');

        // Apply filtering
        if (!$is_admin) {
            $this->mdl_reimbursements->where('ip_reimbursements.user_id', $current_user_id);
        }

        if ($status !== 'all' && in_array($status, ['pending', 'approved', 'rejected', 'paid'], true)) {
            $this->mdl_reimbursements->where('ip_reimbursements.status', $status);
        }

        $reimbursements = $this->mdl_reimbursements->get()->result();
        $kpi_stats = $this->mdl_reimbursements->get_kpi_stats(!$is_admin ? $current_user_id : null);

        $this->layout->set([
            'reimbursements' => $reimbursements,
            'kpi_stats' => $kpi_stats,
            'status' => $status,
            'is_admin' => $is_admin,
            'categories' => $this->mdl_reimbursements->get_categories(),
        ]);

        $this->layout->buffer('content', 'reimbursements/index');
        $this->layout->render();
    }

    public function download_attachment($reimbursement_id = null)
    {
        check_permission('reimbursements', 'view');

        if (!$reimbursement_id) {
            redirect('reimbursements');
        }

        $reimbursement = $this->mdl_reimbursements->get_by_id($reimbursement_id);
        if (!$reimbursement || empty($reimbursement->attachment)) {
            $this->session->set_flashdata('alert_error', 'File lampiran tidak ditemukan.');
            redirect('reimbursements');
        }

        $is_admin = has_permission('reimbursements', 'edit');
        if (!$is_admin && (int) $reimbursement->user_id !== (int) $this->session->userdata('user_id')) {
            $this->session->set_flashdata('alert_error', 'Anda tidak memiliki hak akses mendownload lampiran ini.');
            redirect('reimbursements');
        }

        $file_path = FCPATH . 'uploads/reimbursements/' . $reimbursement->attachment;
        if (!file_exists($file_path)) {
            $this->session->set_flashdata('alert_error', 'File lampiran tidak ada di server.');
            redirect('reimbursements');
        }

        $this->load->helper('download');
        force_download($file_path, NULL);
    }
}
