<?php

if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mdl_Roles extends Response_Model
{
    public $table = 'ip_roles';

    public $primary_key = 'ip_roles.role_id';

    public function default_select(): void
    {
        $this->db->select('ip_roles.*');
    }

    public function default_order_by(): void
    {
        $this->db->order_by('ip_roles.role_name');
    }

    public function validation_rules(): array
    {
        return [
            'role_name' => [
                'field' => 'role_name',
                'label' => trans('role_name'),
                'rules' => 'required',
            ],
            'role_description' => [
                'field' => 'role_description',
                'label' => trans('description'),
            ],
        ];
    }

    public function db_array(): array
    {
        $db_array                     = parent::db_array();
        $permissions                  = $this->input->post('permissions');
        $db_array['role_permissions'] = json_encode($permissions ?: []);

        return $db_array;
    }

    public function get_permissions_matrix(): array
    {
        return [
            'invoices' => [
                'label'   => trans('invoices'),
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'quotes' => [
                'label'   => trans('quotes'),
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'clients' => [
                'label'   => trans('clients'),
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'payments' => [
                'label'   => trans('payments'),
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'products' => [
                'label'   => trans('products'),
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'projects' => [
                'label'   => trans('projects'),
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'reports' => [
                'label'   => trans('reports'),
                'actions' => ['view'],
            ],
            'settings' => [
                'label'   => trans('settings'),
                'actions' => ['view', 'edit'],
            ],
            'users' => [
                'label'   => trans('users'),
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'receipts' => [
                'label'   => trans('receipts'),
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'roles' => [
                'label'   => trans('user_roles'),
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'bank_accounts' => [
                'label'   => 'Rekening Bank',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'employees' => [
                'label'   => trans('employees'),
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'attendance' => [
                'label'   => trans('attendance'),
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'leaves' => [
                'label'   => trans('leave_requests'),
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'reimbursements' => [
                'label'   => 'Klaim Reimburse',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
        ];
    }
}
