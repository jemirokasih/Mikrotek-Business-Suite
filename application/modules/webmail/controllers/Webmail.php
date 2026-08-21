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
        $webmail_mode  = get_setting('webmail_mode') ?: 'internal';
        $webmail_url   = get_setting('webmail_url');
        $webmail_email = get_setting('webmail_email');

        if ($webmail_mode === 'external' && !empty($webmail_url)) {
            $target_url = $webmail_url;
        } else {
            $target_url = site_url('webmail/roundcube');
        }

        $data = [
            'webmail_url'   => $target_url,
            'webmail_email' => $webmail_email,
            'is_configured' => true,
            'is_admin'      => has_permission('settings'),
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
        if (!has_permission('settings')) {
            redirect('webmail');
        }

        $webmail_mode           = get_setting('webmail_mode') ?: 'internal';
        $webmail_url            = get_setting('webmail_url');
        $webmail_imap_host      = get_setting('webmail_imap_host') ?: 'ssl://mail.mzi.co.id:993';
        $webmail_smtp_host      = get_setting('webmail_smtp_host') ?: 'ssl://mail.mzi.co.id:465';
        $webmail_default_domain = get_setting('webmail_default_domain') ?: 'mzi.co.id';

        $data = [
            'webmail_mode'           => $webmail_mode,
            'webmail_url'            => $webmail_url,
            'webmail_imap_host'      => $webmail_imap_host,
            'webmail_smtp_host'      => $webmail_smtp_host,
            'webmail_default_domain' => $webmail_default_domain,
        ];

        $this->layout->buffer('content', 'webmail/settings', $data);
        $this->layout->render();
    }

    public function save_settings()
    {
        if (!has_permission('settings')) {
            redirect('webmail');
        }
        $webmail_mode           = trim((string) $this->input->post('webmail_mode', true));
        $webmail_url            = trim((string) $this->input->post('webmail_url', true));
        $webmail_imap_host      = trim((string) $this->input->post('webmail_imap_host', true));
        $webmail_smtp_host      = trim((string) $this->input->post('webmail_smtp_host', true));
        $webmail_default_domain = trim((string) $this->input->post('webmail_default_domain', true));

        if (!empty($webmail_url) && !preg_match('#^https?://#i', $webmail_url) && strpos($webmail_url, '/') !== 0) {
            $webmail_url = 'https://' . $webmail_url;
        }

        $this->mdl_settings->save('webmail_mode', $webmail_mode);
        $this->mdl_settings->save('webmail_url', $webmail_url);
        $this->mdl_settings->save('webmail_imap_host', $webmail_imap_host);
        $this->mdl_settings->save('webmail_smtp_host', $webmail_smtp_host);
        $this->mdl_settings->save('webmail_default_domain', $webmail_default_domain);

        $this->session->set_flashdata('alert_success', 'Konfigurasi Webmail berhasil disimpan.');
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
