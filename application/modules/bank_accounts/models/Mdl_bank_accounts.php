<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

#[AllowDynamicProperties]
class Mdl_Bank_Accounts extends Response_Model
{
    public $table = 'ip_bank_accounts';
    public $primary_key = 'ip_bank_accounts.bank_id';

    public function default_select()
    {
        $this->db->select('SQL_CALC_FOUND_ROWS ip_bank_accounts.*, ip_payment_methods.payment_method_name', false);
        $this->db->join('ip_payment_methods', 'ip_payment_methods.payment_method_id = ip_bank_accounts.payment_method_id', 'left');
    }

    public function order_by()
    {
        $this->db->order_by('ip_bank_accounts.bank_name');
    }

    public function validation_rules()
    {
        return [
            'bank_name' => [
                'field' => 'bank_name',
                'label' => 'Nama Bank',
                'rules' => 'required|trim',
            ],
            'account_number' => [
                'field' => 'account_number',
                'label' => 'No. Rekening',
                'rules' => 'required|trim',
            ],
            'account_name' => [
                'field' => 'account_name',
                'label' => 'Atas Nama',
                'rules' => 'required|trim',
            ],
        ];
    }

    public function db_array()
    {
        $db_array = parent::db_array();

        $db_array['company_id']        = $this->session->userdata('company_id') ?: 1;
        $db_array['payment_method_id'] = $this->input->post('payment_method_id') ? (int)$this->input->post('payment_method_id') : null;
        $db_array['bank_active']       = $this->input->post('bank_active') !== null ? (int)$this->input->post('bank_active') : 1;

        return $db_array;
    }

    public function get_active_bank_accounts()
    {
        return $this->where('bank_active', 1)->get()->result();
    }
}
