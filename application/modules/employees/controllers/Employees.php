<?php

if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Employees extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_employees');
        $this->load->model('companies/mdl_companies');
        $this->load->model('roles/mdl_roles');
        $this->load->model('users/mdl_users');
    }

    public function index(string $status = 'active', int $page = 0): void
    {
        check_permission('employees');

        switch ($status) {
            case 'inactive':
                $this->mdl_employees->is_active(0);
                break;
            case 'all':
                break;
            case 'linked':
                $this->mdl_employees->has_user_account();
                break;
            case 'active':
            default:
                $this->mdl_employees->is_active(1);
                break;
        }

        $employees = $this->mdl_employees->paginate()->get()->result();

        $this->layout->set([
            'employees'          => $employees,
            'status'             => $status,
            'filter_display'     => true,
            'filter_placeholder' => trans('filter_employees'),
            'filter_method'      => 'filter_employees',
        ]);

        $this->layout->buffer('content', 'employees/index');
        $this->layout->render();
    }

    public function form(?int $id = null): void
    {
        check_permission('employees', $id ? 'edit' : 'create');

        if ($this->input->post('btn_cancel')) {
            redirect('employees');
        }

        if ($this->input->post('btn_submit')) {
            if ($this->mdl_employees->run_validation()) {
                $id = $this->mdl_employees->save($id);
                $this->session->set_flashdata('alert_success', trans('employee_saved_successfully'));
                redirect('employees/view/' . $id);
            }
        }

        if ($id) {
            if ( ! $this->mdl_employees->prep_form($id)) {
                show_404();
            }
        } else {
            $this->mdl_employees->prep_form();
            $this->mdl_employees->set_form_value('employee_number', $this->mdl_employees->generate_employee_number());
            $this->mdl_employees->set_form_value('active', 1);
            $this->mdl_employees->set_form_value('company_id', $this->session->userdata('company_id') ?: 1);
        }

        $companies = $this->mdl_companies->where('company_active', 1)->get()->result();

        $this->layout->set([
            'companies' => $companies,
            'id'        => $id,
        ]);

        $this->layout->buffer('content', 'employees/form');
        $this->layout->render();
    }

    public function view(int $id): void
    {
        check_permission('employees');

        $employee = $this->mdl_employees->get_by_id($id);

        if ( ! $employee) {
            show_404();
        }

        $this->layout->set([
            'employee' => $employee,
        ]);

        $this->layout->buffer('content', 'employees/view');
        $this->layout->render();
    }

    public function delete(int $id): void
    {
        check_permission('employees', 'delete');

        $this->mdl_employees->delete($id);
        $this->session->set_flashdata('alert_success', trans('employee_deleted_successfully'));
        redirect('employees');
    }

    public function toggle_status(int $id): void
    {
        check_permission('employees', 'edit');

        $employee = $this->mdl_employees->get_by_id($id);
        if ($employee) {
            $new_status = $employee->active ? 0 : 1;
            $this->db->where('employee_id', $id);
            $this->db->update('ip_employees', ['active' => $new_status, 'date_modified' => date('Y-m-d H:i:s')]);
        }

        redirect('employees/view/' . $id);
    }

    public function modal_create_user_account(): void
    {
        check_permission('employees', 'edit');

        $employee_id = $this->input->post('employee_id');
        $employee    = $this->mdl_employees->get_by_id($employee_id);

        if ( ! $employee) {
            echo json_encode(['success' => 0, 'error' => 'Employee not found']);

            return;
        }

        $roles = $this->mdl_roles->get()->result();

        $this->layout->load_view('employees/modal_create_user_account', [
            'employee' => $employee,
            'roles'    => $roles,
        ]);
    }

    public function create_user_account(): void
    {
        check_permission('employees', 'edit');

        $employee_id = $this->input->post('employee_id');
        $employee    = $this->mdl_employees->get_by_id($employee_id);

        if ( ! $employee) {
            echo json_encode(['success' => 0, 'error' => 'Employee record not found.']);

            return;
        }

        if ($employee->user_id) {
            echo json_encode(['success' => 0, 'error' => 'Employee already has a linked user account.']);

            return;
        }

        $user_name             = trim($this->input->post('user_name'));
        $user_email            = trim($this->input->post('user_email'));
        $user_password         = $this->input->post('user_password');
        $user_password_confirm = $this->input->post('user_password_confirm');
        $user_type             = (int) $this->input->post('user_type');
        $user_role_id          = $this->input->post('user_role_id') ? (int) $this->input->post('user_role_id') : null;

        if (empty($user_name) || empty($user_email) || empty($user_password)) {
            echo json_encode(['success' => 0, 'error' => 'Name, email, and password are required.']);

            return;
        }

        if ($user_password !== $user_password_confirm) {
            echo json_encode(['success' => 0, 'error' => 'Passwords do not match.']);

            return;
        }

        // Check if email already exists in ip_users
        $existing_user = $this->db->where('user_email', $user_email)->get('ip_users')->row();
        if ($existing_user) {
            echo json_encode(['success' => 0, 'error' => 'A user account with this email already exists.']);

            return;
        }

        $this->load->library('cryptor');
        $salt          = $this->cryptor->genSalt();
        $password_hash = $this->cryptor->generate_password_hash($user_password, $salt);

        $user_data = [
            'user_type'           => $user_type,
            'user_active'         => 1,
            'user_date_created'   => date('Y-m-d H:i:s'),
            'user_date_modified'  => date('Y-m-d H:i:s'),
            'user_name'           => $user_name,
            'user_email'          => $user_email,
            'user_password'       => $password_hash,
            'user_password_reset' => $salt,
            'user_role_id'        => $user_role_id,
            'company_id'          => $employee->company_id ?: 1,
        ];

        $this->db->insert('ip_users', $user_data);
        $new_user_id = $this->db->insert_id();

        // Update employee with linked user_id
        $this->db->where('employee_id', $employee_id);
        $this->db->update('ip_employees', [
            'user_id'       => $new_user_id,
            'date_modified' => date('Y-m-d H:i:s'),
        ]);

        $this->session->set_flashdata('alert_success', trans('user_account_created_successfully'));

        echo json_encode([
            'success' => 1,
            'user_id' => $new_user_id,
            'message' => trans('user_account_created_successfully'),
        ]);
    }
}
