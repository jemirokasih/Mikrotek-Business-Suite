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
        if (!has_permission('reimbursements', 'create')) {
            echo json_encode([
                'success' => 0,
                'validation_errors' => '<div class="alert alert-danger">Anda tidak memiliki hak akses untuk membuat pengajuan reimburse.</div>',
            ]);
            return;
        }

        $session_user_id = (int) $this->session->userdata('user_id');
        if (!$session_user_id) {
            echo json_encode([
                'success' => 0,
                'validation_errors' => '<div class="alert alert-danger">Sesi login Anda telah berakhir. Silakan login kembali.</div>',
            ]);
            return;
        }

        $title = trim((string) ($this->input->post('reimbursement_title', true) ?: ($_POST['reimbursement_title'] ?? '')));
        $amount_raw = trim((string) ($this->input->post('amount', true) ?: ($_POST['amount'] ?? '')));
        $category = trim((string) ($this->input->post('category', true) ?: ($_POST['category'] ?? ''))) ?: 'Lain-lain';
        $date_input = trim((string) ($this->input->post('reimbursement_date', true) ?: ($_POST['reimbursement_date'] ?? '')));
        $description = trim((string) ($this->input->post('description', true) ?: ($_POST['description'] ?? '')));

        $val_errors = [];
        if (empty($title)) {
            $val_errors[] = 'Judul Klaim / Pengeluaran wajib diisi.';
        }
        if (empty($amount_raw) || (float) str_replace(['.', ','], ['', '.'], $amount_raw) <= 0) {
            $val_errors[] = 'Nominal Pengeluaran wajib diisi dan harus lebih dari 0.';
        }

        if (!empty($val_errors)) {
            echo json_encode([
                'success' => 0,
                'validation_errors' => '<div class="alert alert-danger"><ul style="margin:0; padding-left:20px;"><li>' . implode('</li><li>', $val_errors) . '</li></ul></div>',
            ]);
            return;
        }

        $post_emp_id = $this->input->post('employee_id') ?: ($_POST['employee_id'] ?? null);
        $this->load->model('employees/mdl_employees');

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
                'allowed_types' => 'jpg|jpeg|png|webp|pdf',
                'max_size' => 5120, // 5MB
                'encrypt_name' => TRUE,
            ];

            $this->load->library('upload');
            $this->upload->initialize($config);

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

        $amount = (float) str_replace(['.', ','], ['', '.'], $amount_raw);

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

        try {
            $this->mdl_reimbursements->save(null, $db_array);
            echo json_encode(['success' => 1]);
        } catch (\Throwable $e) {
            log_message('error', 'Error saving reimbursement claim: ' . $e->getMessage());
            echo json_encode([
                'success' => 0,
                'validation_errors' => '<div class="alert alert-danger">Gagal menyimpan data klaim: ' . htmlsc($e->getMessage()) . '</div>',
            ]);
        }
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
