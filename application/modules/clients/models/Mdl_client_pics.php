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
class Mdl_Client_Pics extends Response_Model
{
    public $table = 'ip_client_pics';

    public $primary_key = 'ip_client_pics.client_pic_id';

    public function default_order_by()
    {
        $this->db->order_by('ip_client_pics.client_pic_id ASC');
    }

    public function by_client($client_id)
    {
        $this->filter_where('ip_client_pics.client_id', $client_id);
        return $this;
    }

    public function validation_rules()
    {
        return [
            'client_id' => [
                'field' => 'client_id',
                'label' => trans('client'),
                'rules' => 'required|numeric',
            ],
            'pic_name' => [
                'field' => 'pic_name',
                'label' => trans('pic_name'),
                'rules' => 'required|trim|max_length[100]',
            ],
            'pic_position' => [
                'field' => 'pic_position',
                'label' => trans('pic_position'),
                'rules' => 'trim|max_length[100]',
            ],
            'pic_email' => [
                'field' => 'pic_email',
                'label' => trans('pic_email'),
                'rules' => 'trim|valid_email|max_length[100]',
            ],
            'pic_phone' => [
                'field' => 'pic_phone',
                'label' => trans('pic_phone'),
                'rules' => 'trim|max_length[50]',
            ],
            'pic_notes' => [
                'field' => 'pic_notes',
                'label' => trans('pic_notes'),
                'rules' => 'trim',
            ],
        ];
    }

    public function db_array()
    {
        $db_array = parent::db_array();

        if (empty($db_array['pic_date_created'])) {
            $db_array['pic_date_created'] = date('Y-m-d H:i:s');
        }

        return $db_array;
    }

    /**
     * @param int $id
     */
    public function delete($id): bool
    {
        parent::delete($id);

        return true;
    }
}
