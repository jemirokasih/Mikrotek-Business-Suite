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
    }

    public function modal_create_reimbursement()
    {
        check_permission('reimbursements', 'create');

        $data = [
            'categories' => $this->mdl_reimbursements->get_categories(),
        ];

        $this->load->view('reimbursements/modal_create_reimbursement', $data);
    }

    public function create_reimbursement()
    {
        check_permission('reimbursements', 'create');

        if (!$this->mdl_reimbursements->run_validation()) {
            $response = [
                'success' => 0,
                'validation_errors' => $this->mdl_reimbursements->validation_errors,
            ];
            echo json_encode($response);
            return;
        }

        $user_id = (int) $this->session->userdata('user_id');
        
        // Find linked employee ID if available
        $this->load->model('employees/mdl_employees');
        $employee = $this->db->get_where('ip_employees', ['user_id' => $user_id])->row();
        $employee_id = $employee ? $employee->employee_id : null;
        $company_id = $employee ? $employee->company_id : 1;

        $attachment_name = null;

        // Handle attachment file upload if provided
        if (isset($_FILES['attachment']) && !empty($_FILES['attachment']['name'])) {
            $upload_path = './uploads/reimbursements/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config = [
                'upload_path' => $upload_path,
                'allowed_types' => 'jpg|jpeg|png|webp|pdf',
                'max_size' => 5120, // 5MB
                'encrypt_name' => TRUE,
            ];

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('attachment')) {
                $response = [
                    'success' => 0,
                    'validation_errors' => '<div class="alert alert-danger">' . $this->upload->display_errors('', '') . '</div>',
                ];
                echo json_encode($response);
                return;
            }

            $upload_data = $this->upload->data();
            $attachment_name = $upload_data['file_name'];
        }

        $amount_raw = $this->input->post('amount');
        $amount = (float) str_replace(['.', ','], ['', '.'], $amount_raw);

        $db_array = [
            'reimbursement_number' => $this->mdl_reimbursements->generate_number(),
            'company_id' => $company_id,
            'user_id' => $user_id,
            'employee_id' => $employee_id,
            'reimbursement_title' => $this->input->post('reimbursement_title', true),
            'reimbursement_date' => date_to_mysql($this->input->post('reimbursement_date')),
            'category' => $this->input->post('category', true),
            'amount' => $amount,
            'description' => $this->input->post('description', true),
            'attachment' => $attachment_name,
            'status' => 'pending',
            'date_created' => date('Y-m-d H:i:s'),
            'date_modified' => date('Y-m-d H:i:s'),
        ];

        $this->mdl_reimbursements->save(null, $db_array);

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
        $payment_date = date_to_mysql($this->input->post('payment_date'));
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
