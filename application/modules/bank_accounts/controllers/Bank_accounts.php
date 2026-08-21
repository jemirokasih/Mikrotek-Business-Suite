<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

#[AllowDynamicProperties]
class Bank_Accounts extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_bank_accounts');
    }

    public function index($page = 0): void
    {
        $this->mdl_bank_accounts->paginate(site_url('bank_accounts/index'), $page);
        $bank_accounts = $this->mdl_bank_accounts->result();

        $this->layout->set([
            'bank_accounts' => $bank_accounts,
        ]);
        $this->layout->buffer('content', 'bank_accounts/index');
        $this->layout->render();
    }

    public function form($id = null): void
    {
        if ($this->input->post('btn_cancel')) {
            redirect('bank_accounts');
        }

        if ($this->mdl_bank_accounts->run_validation()) {
            $this->mdl_bank_accounts->save($id);
            redirect('bank_accounts');
        }

        if ($id && !$this->input->post('btn_submit')) {
            if (!$this->mdl_bank_accounts->prep_form($id)) {
                show_404();
            }
            $this->mdl_bank_accounts->set_form_value('is_update', true);
        } elseif (!$id && !$this->input->post('btn_submit')) {
            $this->mdl_bank_accounts->set_form_value('bank_active', 1);
        }

        $this->load->model('payment_methods/mdl_payment_methods');

        $this->layout->set([
            'payment_methods' => $this->mdl_payment_methods->get()->result(),
        ]);
        $this->layout->buffer('content', 'bank_accounts/form');
        $this->layout->render();
    }

    public function delete($id): void
    {
        $this->mdl_bank_accounts->delete($id);
        redirect('bank_accounts');
    }
}
