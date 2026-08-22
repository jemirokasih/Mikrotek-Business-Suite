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
        $this->load->helper('number_helper');
    }

    public function modal_create_reimbursement()
    {
        check_permission('reimbursements', 'create');

        $employee_id = $this->input->post('employee_id');

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

        // Read all POST fields directly — no XSS filter so we don't lose values
        $title       = isset($_POST['reimbursement_title']) ? trim($_POST['reimbursement_title']) : '';
        $amount_raw  = isset($_POST['amount']) ? trim($_POST['amount']) : '';
        $category    = isset($_POST['category']) ? trim($_POST['category']) : 'Lain-lain';
        $date_input  = isset($_POST['reimbursement_date']) ? trim($_POST['reimbursement_date']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $post_emp_id = isset($_POST['employee_id']) ? trim($_POST['employee_id']) : '';

        // Defaults for empty fields
        if (empty($title)) {
            $title = 'Pengajuan Reimburse ' . date('d/m/Y H:i');
        }
        if (empty($category)) {
            $category = 'Lain-lain';
        }

        // Parse amount — strip everything except digits
        $amount = 0.0;
        if (!empty($amount_raw)) {
            // Remove all non-numeric except comma and dot
            $clean = preg_replace('/[^0-9.,]/', '', $amount_raw);
            // If it looks like a thousands format (has dot/comma but last group is 3 digits), treat all as integer
            if (preg_match('/^[\d]+([.,]\d{3})+$/', $clean)) {
                // e.g. 10.000 or 10,000 → 10000
                $amount = (float) preg_replace('/[.,]/', '', $clean);
            } elseif (preg_match('/^[\d]+[.,]\d{1,2}$/', $clean)) {
                // e.g. 10000,50 or 10000.50 → decimal
                $amount = (float) str_replace(',', '.', $clean);
            } else {
                // Plain number: just strip non-digits
                $amount = (float) preg_replace('/[^0-9]/', '', $clean);
            }
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
                'allowed_types' => '*',
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

        $reimbursement_id = (int) ($this->input->post('reimbursement_id') ?: ($_POST['reimbursement_id'] ?? 0));
        $status           = $this->input->post('status') ?: ($_POST['status'] ?? '');
        $admin_notes      = $this->input->post('admin_notes') ?: ($_POST['admin_notes'] ?? '');

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

        $reimbursement_id = (int) ($this->input->post('reimbursement_id') ?: ($_POST['reimbursement_id'] ?? 0));
        $payment_date     = date_to_mysql($this->input->post('payment_date') ?: ($_POST['payment_date'] ?? '')) ?: date('Y-m-d');
        $payment_method   = $this->input->post('payment_method') ?: ($_POST['payment_method'] ?? 'Transfer Bank');

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
