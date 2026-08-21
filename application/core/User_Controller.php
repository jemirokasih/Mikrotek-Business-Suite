<?php

if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * InvoicePlane
 *
 * @author      InvoicePlane Developers & Contributors
 * @copyright   Copyright (c) 2012 - 2018 InvoicePlane.com
 * @license     https://invoiceplane.com/license.txt
 * @link        https://invoiceplane.com
 */

#[AllowDynamicProperties]
class User_Controller extends Base_Controller
{
    /**
     * User_Controller constructor.
     *
     * @param string $required_key
     * @param int    $required_val
     */
    public function __construct($required_key, $required_val)
    {
        parent::__construct();

        $user_val = (string) $this->session->userdata($required_key);
        $allowed = is_array($required_val)
            ? array_map('strval', $required_val)
            : [(string) $required_val];

        if (!in_array($user_val, $allowed, true)) {
            session_destroy();
            redirect('sessions/login');
        }
    }
}
