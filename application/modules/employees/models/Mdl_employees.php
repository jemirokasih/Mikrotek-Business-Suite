<?php

if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mdl_Employees extends Response_Model
{
    public $table = 'ip_employees';

    public $primary_key = 'ip_employees.employee_id';

    public function default_select(): void
    {
        $this->db->select('ip_employees.*, ip_companies.company_name, ip_users.user_name, ip_users.user_email, ip_users.user_type, ip_users.user_active as linked_user_active');
    }

    public function default_join(): void
    {
        $this->db->join('ip_companies', 'ip_companies.company_id = ip_employees.company_id', 'left');
        $this->db->join('ip_users', 'ip_users.user_id = ip_employees.user_id', 'left');
    }

    public function default_order_by(): void
    {
        $this->db->order_by('ip_employees.first_name, ip_employees.last_name');
    }

    public function validation_rules(): array
    {
        return [
            'employee_number' => [
                'field' => 'employee_number',
                'label' => trans('employee_number'),
                'rules' => 'required|trim',
            ],
            'first_name' => [
                'field' => 'first_name',
                'label' => trans('first_name'),
                'rules' => 'required|trim',
            ],
            'last_name' => [
                'field' => 'last_name',
                'label' => trans('last_name'),
                'rules' => 'trim',
            ],
            'gender' => [
                'field' => 'gender',
                'label' => trans('gender'),
                'rules' => 'trim',
            ],
            'birth_date' => [
                'field' => 'birth_date',
                'label' => trans('birth_date'),
                'rules' => 'trim',
            ],
            'birth_place' => [
                'field' => 'birth_place',
                'label' => trans('birth_place'),
                'rules' => 'trim',
            ],
            'national_id' => [
                'field' => 'national_id',
                'label' => trans('national_id'),
                'rules' => 'trim',
            ],
            'email' => [
                'field' => 'email',
                'label' => trans('email'),
                'rules' => 'required|valid_email|trim',
            ],
            'phone' => [
                'field' => 'phone',
                'label' => trans('phone'),
                'rules' => 'trim',
            ],
            'mobile' => [
                'field' => 'mobile',
                'label' => trans('mobile'),
                'rules' => 'trim',
            ],
            'address_1' => [
                'field' => 'address_1',
                'label' => trans('street_address'),
                'rules' => 'trim',
            ],
            'address_2' => [
                'field' => 'address_2',
                'label' => trans('street_address_2'),
                'rules' => 'trim',
            ],
            'city' => [
                'field' => 'city',
                'label' => trans('city'),
                'rules' => 'trim',
            ],
            'state' => [
                'field' => 'state',
                'label' => trans('state'),
                'rules' => 'trim',
            ],
            'zip_code' => [
                'field' => 'zip_code',
                'label' => trans('zip_code'),
                'rules' => 'trim',
            ],
            'country' => [
                'field' => 'country',
                'label' => trans('country'),
                'rules' => 'trim',
            ],
            'company_id' => [
                'field' => 'company_id',
                'label' => trans('company'),
                'rules' => 'numeric',
            ],
            'department' => [
                'field' => 'department',
                'label' => trans('department'),
                'rules' => 'trim',
            ],
            'job_title' => [
                'field' => 'job_title',
                'label' => trans('job_title'),
                'rules' => 'trim',
            ],
            'employment_status' => [
                'field' => 'employment_status',
                'label' => trans('employment_status'),
                'rules' => 'trim',
            ],
            'join_date' => [
                'field' => 'join_date',
                'label' => trans('join_date'),
                'rules' => 'trim',
            ],
            'active' => [
                'field' => 'active',
                'label' => trans('active'),
                'rules' => 'trim',
            ],
            'bank_name' => [
                'field' => 'bank_name',
                'label' => trans('bank_name'),
                'rules' => 'trim',
            ],
            'bank_account_number' => [
                'field' => 'bank_account_number',
                'label' => trans('account_number'),
                'rules' => 'trim',
            ],
            'bank_account_holder' => [
                'field' => 'bank_account_holder',
                'label' => trans('bank_account_holder'),
                'rules' => 'trim',
            ],
            'tax_id' => [
                'field' => 'tax_id',
                'label' => trans('tax_id'),
                'rules' => 'trim',
            ],
            'notes' => [
                'field' => 'notes',
                'label' => trans('notes'),
                'rules' => 'trim',
            ],
        ];
    }

    public function db_array(): array
    {
        $db_array = parent::db_array();

        if (empty($db_array['company_id'])) {
            $db_array['company_id'] = $this->session->userdata('company_id') ?: 1;
        }

        if ( ! empty($db_array['birth_date'])) {
            $db_array['birth_date'] = date_to_mysql($db_array['birth_date']);
        } else {
            $db_array['birth_date'] = null;
        }

        if ( ! empty($db_array['join_date'])) {
            $db_array['join_date'] = date_to_mysql($db_array['join_date']);
        } else {
            $db_array['join_date'] = null;
        }

        $db_array['active']        = isset($_POST['active']) ? (int) $_POST['active'] : 0;
        $db_array['date_modified'] = date('Y-m-d H:i:s');

        return $db_array;
    }

    public function generate_employee_number(): string
    {
        $this->db->select_max('employee_id');
        $query   = $this->db->get('ip_employees');
        $row     = $query->row();
        $next_id = ($row && $row->employee_id) ? ($row->employee_id + 1) : 1;

        return 'EMP-' . str_pad((string) $next_id, 4, '0', STR_PAD_LEFT);
    }

    public function by_company(int $company_id): self
    {
        $this->filter_where('ip_employees.company_id', $company_id);

        return $this;
    }

    public function is_active(int $active = 1): self
    {
        $this->filter_where('ip_employees.active', $active);

        return $this;
    }

    public function has_user_account(): self
    {
        $this->filter_where('ip_employees.user_id IS NOT NULL', null, false);

        return $this;
    }

    public function no_user_account(): self
    {
        $this->filter_where('ip_employees.user_id IS NULL', null, false);

        return $this;
    }
}
