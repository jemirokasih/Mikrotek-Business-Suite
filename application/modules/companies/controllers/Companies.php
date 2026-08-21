<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Companies extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_companies');
    }

    public function index(int $page = 0): void
    {
        check_permission('settings', 'view');

        $this->mdl_companies->paginate(site_url('companies/index'), $page);
        $companies = $this->mdl_companies->get()->result();

        $this->layout->set('companies', $companies);
        $this->layout->buffer('content', 'companies/index');
        $this->layout->render();
    }

    public function form(?int $id = null): void
    {
        check_permission('settings', 'edit');

        if ($this->input->post('btn_cancel')) {
            redirect('companies');
        }

        if ($this->input->post('btn_submit')) {
            if ($this->mdl_companies->run_validation()) {
                $this->mdl_companies->save($id);
                $this->session->set_flashdata('alert_success', trans('record_successfully_saved'));
                redirect('companies');
            }
        }

        if ($id && !$this->input->post('btn_submit')) {
            if (!$this->mdl_companies->prep_form($id)) {
                show_404();
            }
        }

        $this->layout->set([
            'countries' => get_country_list(trans('cldr')),
            'selected_country' => $this->mdl_companies->form_value('company_country') ?: get_setting('default_country'),
        ]);
        $this->layout->buffer('content', 'companies/form');
        $this->layout->render();
    }

    public function delete(int $id): void
    {
        check_permission('settings', 'edit');

        $this->mdl_companies->delete($id);
        $this->session->set_flashdata('alert_success', trans('record_successfully_deleted'));
        redirect('companies');
    }
}
