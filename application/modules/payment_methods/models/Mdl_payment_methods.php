<?php

if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * InvoicePlane
 *
 * @author		InvoicePlane Developers & Contributors
 * @copyright	Copyright (c) 2012 - 2018 InvoicePlane.com
 * @license		https://invoiceplane.com/license.txt
 * @link		https://invoiceplane.com
 */

#[AllowDynamicProperties]
class Mdl_Payment_Methods extends Response_Model
{
    public $table = 'ip_payment_methods';

    public $primary_key = 'ip_payment_methods.payment_method_id';

    public function default_select()
    {
        $this->db->select('SQL_CALC_FOUND_ROWS *', false);
    }

    public function order_by()
    {
        $this->db->order_by('ip_payment_methods.payment_method_name');
    }

    /**
     * @return array
     */
    public function validation_rules()
    {
        return [
            'payment_method_name' => [
                'field' => 'payment_method_name',
                'label' => trans('payment_method'),
                'rules' => 'required',
            ],
            'payment_method_bank_name' => [
                'field' => 'payment_method_bank_name',
                'label' => trans('bank_name'),
                'rules' => 'trim',
            ],
            'payment_method_account_number' => [
                'field' => 'payment_method_account_number',
                'label' => trans('account_number'),
                'rules' => 'trim',
            ],
            'payment_method_account_name' => [
                'field' => 'payment_method_account_name',
                'label' => trans('account_name'),
                'rules' => 'trim',
            ],
            'payment_method_notes' => [
                'field' => 'payment_method_notes',
                'label' => trans('notes'),
                'rules' => 'trim',
            ],
        ];
    }

    public function db_array()
    {
        $db_array = parent::db_array();

        $db_array['payment_method_bank_name']      = $this->input->post('payment_method_bank_name');
        $db_array['payment_method_account_number'] = $this->input->post('payment_method_account_number');
        $db_array['payment_method_account_name']   = $this->input->post('payment_method_account_name');
        $db_array['payment_method_notes']          = $this->input->post('payment_method_notes');

        return $db_array;
    }
}
