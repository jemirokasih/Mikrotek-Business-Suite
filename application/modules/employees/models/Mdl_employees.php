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
            'company_id' => [
                'field' => 'company_id',
                'label' => trans('company'),
                'rules' => 'numeric',
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
