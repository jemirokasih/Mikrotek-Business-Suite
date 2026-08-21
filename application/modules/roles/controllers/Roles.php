<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Roles extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_roles');
    }

    public function index(int $page = 0): void
    {
        check_permission('roles', 'view');

        $this->mdl_roles->paginate(site_url('roles/index'), $page);
        $roles = $this->mdl_roles->get()->result();

        $this->layout->set('roles', $roles);
        $this->layout->buffer('content', 'roles/index');
        $this->layout->render();
    }

    public function form(?int $id = null): void
    {
        if ($id) {
            check_permission('roles', 'edit');
        } else {
            check_permission('roles', 'create');
        }

        if ($this->input->post('btn_cancel')) {
            redirect('roles');
        }

        if ($this->input->post('btn_submit')) {
            if ($this->mdl_roles->run_validation()) {
                $this->mdl_roles->save($id);
                $this->session->set_flashdata('alert_success', trans('record_successfully_saved'));
                redirect('roles');
            }
        }

        if ($id && !$this->input->post('btn_submit')) {
            if (!$this->mdl_roles->prep_form($id)) {
                show_404();
            }
        }

        $role_permissions = [];
        if ($id) {
            $role = $this->mdl_roles->get_by_id($id);
            if ($role && $role->role_permissions) {
                $role_permissions = json_decode($role->role_permissions, true) ?: [];
            }
        } elseif ($this->input->post('permissions')) {
            $role_permissions = $this->input->post('permissions');
        }

        $this->layout->set([
            'matrix' => $this->mdl_roles->get_permissions_matrix(),
            'role_permissions' => $role_permissions,
        ]);
        $this->layout->buffer('content', 'roles/form');
        $this->layout->render();
    }

    public function delete(int $id): void
    {
        check_permission('roles', 'delete');

        $this->mdl_roles->delete($id);
        $this->session->set_flashdata('alert_success', trans('record_successfully_deleted'));
        redirect('roles');
    }
}
