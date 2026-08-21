<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mdl_Companies extends Response_Model
{
    public $table = 'ip_companies';
    public $primary_key = 'ip_companies.company_id';

    public function default_select(): void
    {
        $this->db->select('ip_companies.*');
    }

    public function default_order_by(): void
    {
        $this->db->order_by('ip_companies.company_name');
    }

    public function validation_rules(): array
    {
        return [
            'company_name' => [
                'field' => 'company_name',
                'label' => trans('company_name'),
                'rules' => 'required',
            ],
            'company_email' => [
                'field' => 'company_email',
                'label' => trans('email'),
                'rules' => 'valid_email',
            ],
            'company_phone' => [
                'field' => 'company_phone',
                'label' => trans('phone'),
            ],
            'company_address_1' => [
                'field' => 'company_address_1',
            ],
            'company_address_2' => [
                'field' => 'company_address_2',
            ],
            'company_city' => [
                'field' => 'company_city',
            ],
            'company_state' => [
                'field' => 'company_state',
            ],
            'company_zip' => [
                'field' => 'company_zip',
            ],
            'company_country' => [
                'field' => 'company_country',
            ],
        ];
    }
}
