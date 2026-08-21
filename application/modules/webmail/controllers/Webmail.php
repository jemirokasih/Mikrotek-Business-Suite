<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * InvoicePlane / Mikrotek Business Suite
 * Webmail Module (Unified Integrated Webmail & Roundcube Suite)
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
        $webmail_url   = get_setting('webmail_url');
        $webmail_email = get_setting('webmail_email');

        // Default to internal Roundcube action route (bypasses direct file htaccess block)
        if (empty($webmail_url)) {
            $webmail_url = site_url('webmail/roundcube');
        }

        $data = [
            'webmail_url'   => $webmail_url,
            'webmail_email' => $webmail_email,
            'is_configured' => true,
        ];

        $this->layout->buffer('content', 'webmail/index', $data);
        $this->layout->render();
    }

    public function roundcube()
    {
        // Render built-in native Roundcube webmail application view
        $webmail_email = get_setting('webmail_email') ?: get_setting('smtp_user');

        $data = [
            'webmail_email' => $webmail_email,
            'smtp_host'     => get_setting('smtp_host'),
        ];

        $this->load->view('webmail/roundcube', $data);
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

        if (!empty($webmail_url) && !preg_match('#^https?://#i', $webmail_url) && strpos($webmail_url, '/') !== 0) {
            $webmail_url = 'https://' . $webmail_url;
        }

        $this->mdl_settings->save('webmail_url', $webmail_url);
        $this->mdl_settings->save('webmail_email', $webmail_email);

        if ($webmail_password !== '') {
            $encryption_key = config_item('encryption_key') ?: 'MikrotekWebmailKey';
            $encrypted_pass = Cryptor::Encrypt($webmail_password, $encryption_key);
            $this->mdl_settings->save('webmail_password', $encrypted_pass);
        }

        $this->session->set_flashdata('alert_success', 'Pengaturan Webmail berhasil disimpan.');
        redirect('webmail');
    }

    public function send_message()
    {
        $to_email = trim((string) $this->input->post('to_email', true));
        $subject  = trim((string) $this->input->post('subject', true));
        $message  = (string) $this->input->post('message');

        if (empty($to_email) || empty($subject) || empty($message)) {
            $this->session->set_flashdata('alert_error', 'Harap isi semua kolom email (Penerima, Subjek, dan Pesan).');
            redirect('webmail');
            return;
        }

        $this->load->helper('mailer');

        if (mailer_configured()) {
            $sent = email_invoice_template(null, $to_email, $subject, $message);
            if ($sent) {
                $this->session->set_flashdata('alert_success', 'Email berhasil dikirim ke ' . html_escape($to_email));
            } else {
                $this->session->set_flashdata('alert_error', 'Gagal mengirim email. Periksa pengaturan SMTP sistem.');
            }
        } else {
            $this->session->set_flashdata('alert_error', 'Sistem email (SMTP) belum dikonfigurasi di Pengaturan Sistem.');
        }

        redirect('webmail');
    }
}
