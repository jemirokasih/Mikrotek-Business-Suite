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
class Ajax extends Admin_Controller
{
    public $ajax_controller = true;

    public function get_cron_key()
    {
        $this->load->helper('ip_security');
        // 8 bytes -> 16 hexadecimal characters.
        echo generate_secure_token(8);
    }

    public function switch_layout()
    {
        $layout_mode = $this->input->post('layout_mode');
        if (in_array($layout_mode, ['sidebar', 'top'])) {
            $this->load->model('settings/mdl_settings');
            $this->mdl_settings->save('layout_mode', $layout_mode);
            echo json_encode(['success' => 1, 'layout_mode' => $layout_mode]);
        } else {
            echo json_encode(['success' => 0, 'message' => 'Invalid layout mode']);
        }
    }
}
