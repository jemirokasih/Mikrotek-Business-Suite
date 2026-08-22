<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ajax extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_reimbursements');
        $this->load->helper('file_security');
        $this->load->helper('date');
    }

    public function modal_create_reimbursement()
    {
        check_permission('reimbursements', 'create');

        $employee_id = $this->input->get('employee_id');

        $data = [
            'categories'  => $this->mdl_reimbursements->get_categories(),
            'employee_id' => $employee_id,
        ];

        $this->load->view('reimbursements/modal_create_reimbursement', $data);
    }

    public function create_reimbursement()
    {
        header('Content-Type: application/json');

        $session_user_id = (int) $this->session->userdata('user_id');
        if (!$session_user_id) {
            echo json_encode(['success' => 0, 'error' => 'Sesi login telah berakhir.']);
            return;
        }

        $title       = trim((string) $this->input->post('reimbursement_title'));
        $amount_raw  = trim((string) $this->input->post('amount'));
        $category    = trim((string) $this->input->post('category'));
        $date_input  = trim((string) $this->input->post('reimbursement_date'));
        $description = trim((string) $this->input->post('description'));
        $post_emp_id = trim((string) $this->input->post('employee_id'));

        // Defaults for empty fields
        if (empty($title)) {
            $title = 'Pengajuan Reimburse ' . date('d/m/Y H:i');
        }
        if (empty($category)) {
            $category = 'Lain-lain';
        }

        // Parse amount — strip everything except digits, dots, commas
        $amount = 0.0;
        if (!empty($amount_raw)) {
            // Remove all non-numeric except comma and dot
            $clean = preg_replace('/[^0-9.,]/', '', $amount_raw);
            // Detect thousands separator format: all dots (or commas) are thousands separators
            // e.g. 1.500.000 or 1,500,000 → strip all separators
            if (preg_match('/^\d{1,3}([.,]\d{3})+$/', $clean)) {
                // e.g. 10.000 or 1.500.000 or 10,000 → strip separators entirely
                $amount = (float) preg_replace('/[.,]/', '', $clean);
            } elseif (preg_match('/^\d+[.,]\d{1,2}$/', $clean)) {
                // e.g. 10000,50 or 10000.50 → decimal
                $amount = (float) str_replace(',', '.', $clean);
            } else {
                // Plain number or mixed: strip all non-digits
                $amount = (float) preg_replace('/[^0-9]/', '', $clean);
            }
        }

        if ($amount <= 0) {
            echo json_encode(['success' => 0, 'error' => 'Nominal harus lebih dari 0.']);
            return;
        }

        // Resolve employee / user / company
        if (!empty($post_emp_id)) {
            $employee   = $this->db->get_where('ip_employees', ['employee_id' => (int) $post_emp_id])->row();
            $employee_id = $employee ? $employee->employee_id : (int) $post_emp_id;
            $user_id    = ($employee && $employee->user_id) ? $employee->user_id : $session_user_id;
            $company_id = ($employee && $employee->company_id) ? $employee->company_id : 1;
        } else {
            $user_id    = $session_user_id;
            $employee   = $this->db->get_where('ip_employees', ['user_id' => $user_id])->row();
            $employee_id = $employee ? $employee->employee_id : null;
            $company_id = $employee ? $employee->company_id : 1;
        }

        // Handle file upload
        $attachment_name = null;
        if (!empty($_FILES['attachment']['name'])) {
            $upload_path = FCPATH . 'uploads/reimbursements/';
            if (!is_dir($upload_path)) {
                @mkdir($upload_path, 0755, true);
            }
            $this->load->library('upload');
            $this->upload->initialize([
                'upload_path'   => $upload_path,
                'allowed_types' => 'jpg|jpeg|png|gif|webp|pdf',
                'encrypt_name'  => true,
            ]);
            if ($this->upload->do_upload('attachment')) {
                $attachment_name = $this->upload->data('file_name');
            }
        }

        // Parse date
        $reimbursement_date = date_to_mysql($date_input);
        if (empty($reimbursement_date)) {
            $reimbursement_date = date('Y-m-d');
        }

        // Generate unique number
        $reimb_number = $this->mdl_reimbursements->generate_number();
        if (empty($reimb_number)) {
            $reimb_number = 'RMB-' . date('Ym') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        $db_array = [
            'reimbursement_number' => $reimb_number,
            'company_id'           => $company_id,
            'user_id'              => $user_id,
            'employee_id'          => $employee_id,
            'reimbursement_title'  => $title,
            'reimbursement_date'   => $reimbursement_date,
            'category'             => $category,
            'amount'               => $amount,
            'description'          => $description,
            'attachment'           => $attachment_name,
            'status'               => 'pending',
            'date_created'         => date('Y-m-d H:i:s'),
            'date_modified'        => date('Y-m-d H:i:s'),
        ];

        $this->db->insert('ip_reimbursements', $db_array);

        $db_err = $this->db->error();
        if (!empty($db_err['code'])) {
            log_message('error', 'Reimbursement insert error: ' . $db_err['message']);
            echo json_encode(['success' => 0, 'error' => 'DB error: ' . $db_err['message']]);
            return;
        }

        echo json_encode(['success' => 1, 'id' => $this->db->insert_id()]);
    }

    public function modal_view_reimbursement()
    {
        check_permission('reimbursements', 'view');

        $reimbursement_id = $this->input->post('reimbursement_id');
        $reimbursement    = $this->mdl_reimbursements->get_by_id($reimbursement_id);

        if (!$reimbursement) {
            return;
        }

        $is_admin = has_permission('reimbursements', 'edit');
        if (!$is_admin && (int) $reimbursement->user_id !== (int) $this->session->userdata('user_id')) {
            return;
        }

        $data = [
            'reimbursement' => $reimbursement,
            'is_admin'      => $is_admin,
        ];

        $this->load->view('reimbursements/modal_view_reimbursement', $data);
    }

    public function modal_approve_reimbursement()
    {
        check_permission('reimbursements', 'edit');

        $reimbursement_id = $this->input->post('reimbursement_id');
        $reimbursement    = $this->mdl_reimbursements->get_by_id($reimbursement_id);

        if (!$reimbursement) {
            return;
        }

        $data = ['reimbursement' => $reimbursement];
        $this->load->view('reimbursements/modal_approve_reimbursement', $data);
    }

    public function approve_reimbursement()
    {
        header('Content-Type: application/json');
        check_permission('reimbursements', 'edit');

        $reimbursement_id = (int) $this->input->post('reimbursement_id');
        $status           = (string) $this->input->post('status');
        $admin_notes      = (string) $this->input->post('admin_notes');

        if (!in_array($status, ['approved', 'rejected'], true)) {
            echo json_encode(['success' => 0, 'error' => 'Status tidak valid']);
            return;
        }

        $db_array = [
            'status'              => $status,
            'approved_by_user_id' => $this->session->userdata('user_id'),
            'approved_at'         => date('Y-m-d H:i:s'),
            'admin_notes'         => $admin_notes,
            'date_modified'       => date('Y-m-d H:i:s'),
        ];

        $this->db->where('reimbursement_id', $reimbursement_id)->update('ip_reimbursements', $db_array);
        echo json_encode(['success' => 1]);
    }

    public function modal_pay_reimbursement()
    {
        check_permission('reimbursements', 'edit');

        $reimbursement_id = $this->input->post('reimbursement_id');
        $reimbursement    = $this->mdl_reimbursements->get_by_id($reimbursement_id);

        if (!$reimbursement) {
            return;
        }

        $data = ['reimbursement' => $reimbursement];
        $this->load->view('reimbursements/modal_pay_reimbursement', $data);
    }

    public function pay_reimbursement()
    {
        header('Content-Type: application/json');
        check_permission('reimbursements', 'edit');

        $reimbursement_id = (int) $this->input->post('reimbursement_id');
        $payment_date     = date_to_mysql((string) $this->input->post('payment_date')) ?: date('Y-m-d');
        $payment_method   = (string) $this->input->post('payment_method') ?: 'Transfer Bank';

        $db_array = [
            'status'         => 'paid',
            'payment_date'   => $payment_date,
            'payment_method' => $payment_method,
            'date_modified'  => date('Y-m-d H:i:s'),
        ];

        $this->db->where('reimbursement_id', $reimbursement_id)->update('ip_reimbursements', $db_array);
        echo json_encode(['success' => 1]);
    }
}
