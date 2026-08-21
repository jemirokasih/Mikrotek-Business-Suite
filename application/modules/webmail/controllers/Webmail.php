<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * InvoicePlane / Mikrotek Business Suite
 * Webmail Module (Roundcube Integration)
 */

class Webmail extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings/mdl_settings');
        $this->load->library('Cryptor');
    }

    public function index()
    {
        $webmail_url = get_setting('webmail_url');
        $webmail_email = get_setting('webmail_email');

        $data = [
            'webmail_url'   => $webmail_url,
            'webmail_email' => $webmail_email,
            'is_configured' => !empty($webmail_url),
        ];

        $this->layout->buffer('content', 'webmail/index', $data);
        $this->layout->render();
    }

    public function settings()
    {
        $webmail_url    = get_setting('webmail_url');
        $webmail_email  = get_setting('webmail_email');
        $encrypted_pass = get_setting('webmail_password');
        $webmail_password = '';

        if (!empty($encrypted_pass)) {
            try {
                $encryption_key   = config_item('encryption_key') ?: 'MikrotekWebmailKey';
                $webmail_password = Cryptor::Decrypt($encrypted_pass, $encryption_key);
            } catch (Exception $e) {
                $webmail_password = '';
            }
        }

        $data = [
            'webmail_url'      => $webmail_url,
            'webmail_email'    => $webmail_email,
            'webmail_password' => $webmail_password,
        ];

        $this->layout->buffer('content', 'webmail/settings', $data);
        $this->layout->render();
    }

    public function save_settings()
    {
        $webmail_url      = trim((string) $this->input->post('webmail_url', true));
        $webmail_email    = trim((string) $this->input->post('webmail_email', true));
        $webmail_password = (string) $this->input->post('webmail_password');

        if (!empty($webmail_url) && !preg_match('#^https?://#i', $webmail_url)) {
            $webmail_url = 'https://' . $webmail_url;
        }

        $this->mdl_settings->save('webmail_url', $webmail_url);
        $this->mdl_settings->save('webmail_email', $webmail_email);

        if ($webmail_password !== '') {
            $encryption_key = config_item('encryption_key') ?: 'MikrotekWebmailKey';
            $encrypted_pass = Cryptor::Encrypt($webmail_password, $encryption_key);
            $this->mdl_settings->save('webmail_password', $encrypted_pass);
        }

        $this->session->set_flashdata('alert_success', 'Pengaturan Roundcube Webmail berhasil disimpan.');
        redirect('webmail');
    }
}
