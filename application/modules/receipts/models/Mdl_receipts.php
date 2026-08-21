<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mdl_Receipts extends Response_Model
{
    public $table = 'ip_receipts';
    public $primary_key = 'ip_receipts.receipt_id';
    public $date_created_field = 'receipt_date_created';
    public $date_modified_field = 'receipt_date_modified';

    public function default_select(): void
    {
        $this->db->select('SQL_CALC_FOUND_ROWS ip_receipts.*, 
            ip_clients.client_name, ip_clients.client_address_1, ip_clients.client_city,
            ip_invoices.invoice_number,
            ip_companies.company_name, ip_companies.company_address_1 AS company_address,
            ip_payment_methods.payment_method_name,
            ip_users.user_name', false);
    }

    public function default_join(): void
    {
        $this->db->join('ip_clients', 'ip_clients.client_id = ip_receipts.client_id', 'left');
        $this->db->join('ip_invoices', 'ip_invoices.invoice_id = ip_receipts.invoice_id', 'left');
        $this->db->join('ip_companies', 'ip_companies.company_id = ip_receipts.company_id', 'left');
        $this->db->join('ip_payment_methods', 'ip_payment_methods.payment_method_id = ip_receipts.receipt_payment_method_id', 'left');
        $this->db->join('ip_users', 'ip_users.user_id = ip_receipts.user_id', 'left');
    }

    public function default_order_by(): void
    {
        $this->db->order_by('ip_receipts.receipt_id', 'DESC');
    }

    public function validation_rules(): array
    {
        return [
            'client_id' => [
                'field' => 'client_id',
                'label' => trans('client'),
                'rules' => 'required',
            ],
            'receipt_date' => [
                'field' => 'receipt_date',
                'label' => trans('date'),
                'rules' => 'required',
            ],
            'receipt_amount' => [
                'field' => 'receipt_amount',
                'label' => trans('amount'),
                'rules' => 'required',
            ],
        ];
    }

    public function generate_receipt_number(): string
    {
        $this->load->model('invoice_groups/mdl_invoice_groups');
        $group = $this->mdl_invoice_groups->where('invoice_group_name', 'Kwitansi')->get()->row();
        if ($group) {
            return $this->mdl_invoice_groups->generate_invoice_number($group->invoice_group_id);
        }
        return 'KWT-' . date('Ym') . '-' . sprintf('%04d', rand(1, 9999));
    }

    public function generate_url_key(): string
    {
        $this->load->helper('string');
        return random_string('alnum', 32);
    }

    public static function terbilang(float $number): string
    {
        $number = abs($number);
        $words = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        $temp = "";

        if ($number < 12) {
            $temp = " " . $words[(int)$number];
        } else if ($number < 20) {
            $temp = self::terbilang($number - 10) . " Belas";
        } else if ($number < 100) {
            $temp = self::terbilang($number / 10) . " Puluh" . self::terbilang($number % 10);
        } else if ($number < 200) {
            $temp = " Seratus" . self::terbilang($number - 100);
        } else if ($number < 1000) {
            $temp = self::terbilang($number / 100) . " Ratus" . self::terbilang($number % 100);
        } else if ($number < 2000) {
            $temp = " Seribu" . self::terbilang($number - 1000);
        } else if ($number < 1000000) {
            $temp = self::terbilang($number / 1000) . " Ribu" . self::terbilang($number % 1000);
        } else if ($number < 1000000000) {
            $temp = self::terbilang($number / 1000000) . " Juta" . self::terbilang($number % 1000000);
        } else if ($number < 1000000000000) {
            $temp = self::terbilang($number / 1000000000) . " Milyar" . self::terbilang(fmod($number, 1000000000));
        } else if ($number < 1000000000000000) {
            $temp = self::terbilang($number / 1000000000000) . " Trilyun" . self::terbilang(fmod($number, 1000000000000));
        }

        return trim($temp) . " Rupiah";
    }
}
