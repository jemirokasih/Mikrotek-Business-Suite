<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Receipts extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_receipts');
    }

    public function index(int $page = 0): void
    {
        check_permission('receipts', 'view');

        $this->mdl_receipts->paginate(site_url('receipts/index'), $page);
        $receipts = $this->mdl_receipts->get()->result();

        $this->layout->set('receipts', $receipts);
        $this->layout->buffer('content', 'receipts/index');
        $this->layout->render();
    }

    public function form(?int $id = null): void
    {
        if ($id) {
            check_permission('receipts', 'edit');
        } else {
            check_permission('receipts', 'create');
        }

        if ($this->input->post('btn_cancel')) {
            redirect('receipts');
        }

        if ($this->input->post('btn_submit')) {
            if ($this->mdl_receipts->run_validation()) {
                $db_array = [
                    'client_id'                 => (int) $this->input->post('client_id'),
                    'company_id'                => $this->input->post('company_id') ? (int) $this->input->post('company_id') : $this->session->userdata('company_id'),
                    'invoice_id'                => $this->input->post('invoice_id') ? (int) $this->input->post('invoice_id') : null,
                    'payment_id'                => $this->input->post('payment_id') ? (int) $this->input->post('payment_id') : null,
                    'receipt_date'              => date_to_mysql($this->input->post('receipt_date')),
                    'receipt_amount'            => standardize_amount($this->input->post('receipt_amount')),
                    'receipt_payment_method_id' => $this->input->post('receipt_payment_method_id') ? (int) $this->input->post('receipt_payment_method_id') : null,
                    'receipt_notes'             => $this->input->post('receipt_notes'),
                    'receipt_status'            => $this->input->post('receipt_status') ? (int) $this->input->post('receipt_status') : 1,
                ];

                if (!$id) {
                    $db_array['user_id']        = $this->session->userdata('user_id');
                    $db_array['receipt_number'] = $this->mdl_receipts->generate_receipt_number();
                    $db_array['receipt_url_key'] = $this->mdl_receipts->generate_url_key();
                }

                $receipt_id = $this->mdl_receipts->save($id, $db_array);
                $this->session->set_flashdata('alert_success', trans('record_successfully_saved'));
                redirect('receipts/view/' . $receipt_id);
            }
        }

        if ($id && !$this->input->post('btn_submit')) {
            if (!$this->mdl_receipts->prep_form($id)) {
                show_404();
            }
        }

        $this->load->model('clients/mdl_clients');
        $this->load->model('invoices/mdl_invoices');
        $this->load->model('companies/mdl_companies');
        $this->load->model('payment_methods/mdl_payment_methods');

        $this->layout->set([
            'clients'         => $this->mdl_clients->where('client_active', 1)->get()->result(),
            'invoices'        => $this->mdl_invoices->get()->result(),
            'companies'       => $this->mdl_companies->get()->result(),
            'payment_methods' => $this->mdl_payment_methods->get()->result(),
        ]);
        $this->layout->buffer('content', 'receipts/form');
        $this->layout->render();
    }

    public function create_from_invoice(int $invoice_id): void
    {
        check_permission('receipts', 'create');

        $this->load->model('invoices/mdl_invoices');
        $invoice = $this->mdl_invoices->get_by_id($invoice_id);

        if (!$invoice) {
            show_404();
        }

        $db_array = [
            'user_id'                   => $this->session->userdata('user_id'),
            'company_id'                => $this->session->userdata('company_id') ?: $invoice->company_id ?? null,
            'client_id'                 => $invoice->client_id,
            'invoice_id'                => $invoice->invoice_id,
            'receipt_number'            => $this->mdl_receipts->generate_receipt_number(),
            'receipt_date'              => date('Y-m-d'),
            'receipt_amount'            => $invoice->invoice_balance > 0 ? $invoice->invoice_balance : $invoice->invoice_total,
            'receipt_notes'             => 'Pembayaran untuk Faktur #' . $invoice->invoice_number,
            'receipt_url_key'           => $this->mdl_receipts->generate_url_key(),
            'receipt_status'            => 2,
            'receipt_date_created'      => date('Y-m-d H:i:s'),
            'receipt_date_modified'     => date('Y-m-d H:i:s'),
        ];

        $receipt_id = $this->mdl_receipts->save(null, $db_array);
        $this->session->set_flashdata('alert_success', trans('record_successfully_saved'));
        redirect('receipts/view/' . $receipt_id);
    }

    public function create_from_payment(int $payment_id): void
    {
        check_permission('receipts', 'create');

        $this->load->model('payments/mdl_payments');
        $payment = $this->mdl_payments->get_by_id($payment_id);

        if (!$payment) {
            show_404();
        }

        $db_array = [
            'user_id'                   => $this->session->userdata('user_id'),
            'company_id'                => $this->session->userdata('company_id'),
            'client_id'                 => $payment->client_id,
            'invoice_id'                => $payment->invoice_id,
            'payment_id'                => $payment->payment_id,
            'receipt_number'            => $this->mdl_receipts->generate_receipt_number(),
            'receipt_date'              => $payment->payment_date,
            'receipt_amount'            => $payment->payment_amount,
            'receipt_payment_method_id' => $payment->payment_method_id,
            'receipt_notes'             => 'Pembayaran untuk Faktur #' . ($payment->invoice_number ?? '') . ' - ' . $payment->payment_note,
            'receipt_url_key'           => $this->mdl_receipts->generate_url_key(),
            'receipt_status'            => 2,
            'receipt_date_created'      => date('Y-m-d H:i:s'),
            'receipt_date_modified'     => date('Y-m-d H:i:s'),
        ];

        $receipt_id = $this->mdl_receipts->save(null, $db_array);
        $this->session->set_flashdata('alert_success', trans('record_successfully_saved'));
        redirect('receipts/view/' . $receipt_id);
    }

    public function view(int $id): void
    {
        check_permission('receipts', 'view');

        $receipt = $this->mdl_receipts->get_by_id($id);
        if (!$receipt) {
            show_404();
        }

        $terbilang = Mdl_Receipts::terbilang((float)$receipt->receipt_amount);

        $this->layout->set([
            'receipt'   => $receipt,
            'terbilang' => $terbilang,
        ]);
        $this->layout->buffer('content', 'receipts/view');
        $this->layout->render();
    }

    public function generate_pdf(int $id, bool $stream = true): void
    {
        check_permission('receipts', 'view');

        $receipt = $this->mdl_receipts->get_by_id($id);
        if (!$receipt) {
            show_404();
        }

        $terbilang = Mdl_Receipts::terbilang((float)$receipt->receipt_amount);

        $html = $this->load->view('receipt_templates/pdf/Kwitansi', [
            'receipt'   => $receipt,
            'terbilang' => $terbilang,
        ], true);

        $this->load->helper('pdf');
        $filename = 'Kwitansi_' . $receipt->receipt_number;

        if (function_exists('pdf_create')) {
            pdf_create($html, $filename, $stream);
        } else {
            echo $html;
        }
    }

    public function delete(int $id): void
    {
        check_permission('receipts', 'delete');

        $this->mdl_receipts->delete($id);
        $this->session->set_flashdata('alert_success', trans('record_successfully_deleted'));
        redirect('receipts');
    }
}
