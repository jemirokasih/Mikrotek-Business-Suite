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
        $session_user_id = (int) $this->session->userdata('user_id') ?: 1;

        $title = trim((string) ($this->input->post('reimbursement_title', true) ?: ($_POST['reimbursement_title'] ?? '')));
        if (empty($title)) {
            $title = 'Pengajuan Reimburse ' . date('d/m/Y H:i');
        }

        $amount_raw = trim((string) ($this->input->post('amount', true) ?: ($_POST['amount'] ?? '')));
        $cleaned_amount = preg_replace('/[^0-9\.,]/', '', $amount_raw);
        if (strpos($cleaned_amount, '.') !== false && strpos($cleaned_amount, ',') === false) {
            $parts = explode('.', $cleaned_amount);
            if (count($parts) == 2 && strlen($parts[1]) == 3) {
                $cleaned_amount = implode('', $parts);
            } else if (count($parts) > 2) {
                $cleaned_amount = implode('', $parts);
            }
        }
        $amount = (float) (is_numeric($cleaned_amount) ? $cleaned_amount : standardize_amount($cleaned_amount));
        if ($amount <= 0 && !empty($cleaned_amount)) {
            $amount = (float) $cleaned_amount;
        }

        $category = trim((string) ($this->input->post('category', true) ?: ($_POST['category'] ?? ''))) ?: 'Lain-lain';
        $date_input = trim((string) ($this->input->post('reimbursement_date', true) ?: ($_POST['reimbursement_date'] ?? '')));
        $description = trim((string) ($this->input->post('description', true) ?: ($_POST['description'] ?? '')));

        $post_emp_id = $this->input->post('employee_id') ?: ($_POST['employee_id'] ?? null);

        if ($post_emp_id) {
            $employee = $this->db->get_where('ip_employees', ['employee_id' => (int) $post_emp_id])->row();
            $employee_id = $employee ? $employee->employee_id : (int) $post_emp_id;
            $user_id = ($employee && $employee->user_id) ? $employee->user_id : $session_user_id;
            $company_id = ($employee && $employee->company_id) ? $employee->company_id : 1;
        } else {
            $user_id = $session_user_id;
            $employee = $this->db->get_where('ip_employees', ['user_id' => $user_id])->row();
            $employee_id = $employee ? $employee->employee_id : null;
            $company_id = $employee ? $employee->company_id : 1;
        }

        $attachment_name = null;

        // Handle attachment file upload if provided
        if (isset($_FILES['attachment']) && !empty($_FILES['attachment']['name'])) {
            $upload_path = FCPATH . 'uploads/reimbursements/';
            if (!is_dir($upload_path)) {
                @mkdir($upload_path, 0777, true);
            }

            $config = [
                'upload_path' => $upload_path,
                'allowed_types' => '*',
                'encrypt_name' => TRUE,
            ];

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload('attachment')) {
                $upload_data = $this->upload->data();
                $attachment_name = $upload_data['file_name'];
            }
        }

        $reimbursement_date = date_to_mysql($date_input);
        if (empty($reimbursement_date)) {
            $reimbursement_date = date('Y-m-d');
        }

        $db_array = [
            'reimbursement_number' => $this->mdl_reimbursements->generate_number(),
            'company_id' => $company_id,
            'user_id' => $user_id,
            'employee_id' => $employee_id,
            'reimbursement_title' => $title,
            'reimbursement_date' => $reimbursement_date,
            'category' => $category,
            'amount' => $amount,
            'description' => $description,
            'attachment' => $attachment_name,
            'status' => 'pending',
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified' => date('Y-m-d H:i:s'),
        ];

        $this->db->insert('ip_reimbursements', $db_array);
        echo json_encode(['success' => 1]);
    }

    public function modal_view_reimbursement()
    {
        check_permission('reimbursements', 'view');

        $reimbursement_id = $this->input->post('reimbursement_id');
        $reimbursement = $this->mdl_reimbursements->get_by_id($reimbursement_id);

        if (!$reimbursement) {
            return;
        }

        $is_admin = has_permission('reimbursements', 'edit');
        if (!$is_admin && (int) $reimbursement->user_id !== (int) $this->session->userdata('user_id')) {
            return;
        }

        $data = [
            'reimbursement' => $reimbursement,
            'is_admin' => $is_admin,
        ];

        $this->load->view('reimbursements/modal_view_reimbursement', $data);
    }

    public function modal_approve_reimbursement()
    {
        check_permission('reimbursements', 'edit');

        $reimbursement_id = $this->input->post('reimbursement_id');
        $reimbursement = $this->mdl_reimbursements->get_by_id($reimbursement_id);

        if (!$reimbursement) {
            return;
        }

        $data = [
            'reimbursement' => $reimbursement,
        ];

        $this->load->view('reimbursements/modal_approve_reimbursement', $data);
    }

    public function approve_reimbursement()
    {
        check_permission('reimbursements', 'edit');

        $reimbursement_id = (int) $this->input->post('reimbursement_id');
        $status = $this->input->post('status');
        $admin_notes = $this->input->post('admin_notes', true);

        if (!in_array($status, ['approved', 'rejected'], true)) {
            echo json_encode(['success' => 0, 'error' => 'Status tidak valid']);
            return;
        }

        $db_array = [
            'status' => $status,
            'approved_by_user_id' => $this->session->userdata('user_id'),
            'approved_at' => date('Y-m-d H:i:s'),
            'admin_notes' => $admin_notes,
            'date_modified' => date('Y-m-d H:i:s'),
        ];

        $this->mdl_reimbursements->save($reimbursement_id, $db_array);

        echo json_encode(['success' => 1]);
    }

    public function modal_pay_reimbursement()
    {
        check_permission('reimbursements', 'edit');

        $reimbursement_id = $this->input->post('reimbursement_id');
        $reimbursement = $this->mdl_reimbursements->get_by_id($reimbursement_id);

        if (!$reimbursement) {
            return;
        }

        $data = [
            'reimbursement' => $reimbursement,
        ];

        $this->load->view('reimbursements/modal_pay_reimbursement', $data);
    }

    public function pay_reimbursement()
    {
        check_permission('reimbursements', 'edit');

        $reimbursement_id = (int) $this->input->post('reimbursement_id');
        $payment_date = date_to_mysql($this->input->post('payment_date')) ?: date('Y-m-d');
        $payment_method = $this->input->post('payment_method', true);

        $db_array = [
            'status' => 'paid',
            'payment_date' => $payment_date,
            'payment_method' => $payment_method,
            'date_modified' => date('Y-m-d H:i:s'),
        ];

        $this->mdl_reimbursements->save($reimbursement_id, $db_array);

        echo json_encode(['success' => 1]);
    }
}
