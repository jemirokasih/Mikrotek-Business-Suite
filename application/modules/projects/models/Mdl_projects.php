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
class Mdl_Projects extends Response_Model
{
    public $table = 'ip_projects';

    public $primary_key = 'ip_projects.project_id';

    public function default_select()
    {
        $this->db->select('SQL_CALC_FOUND_ROWS *', false);
    }

    public function default_order_by()
    {
        $this->db->order_by('ip_projects.project_id');
    }

    public function default_join()
    {
        $this->db->join('ip_clients', 'ip_clients.client_id = ip_projects.client_id', 'left');
    }

    public function get_latest()
    {
        $this->db->order_by('ip_projects.project_id', 'DESC');

        return $this;
    }

    /**
     * @return array
     */
    public function validation_rules()
    {
        return [
            'project_name' => [
                'field' => 'project_name',
                'label' => trans('project_name'),
                'rules' => 'required',
            ],
            'client_id' => [
                'field' => 'client_id',
                'label' => trans('client'),
            ],
        ];
    }

    public function get_tasks($project_id)
    {
        $result = [];

        if ( ! $project_id) {
            return $result;
        }

        $this->load->model('tasks/mdl_tasks');
        $query = $this->mdl_tasks->where('ip_tasks.project_id', $project_id)->get();

        foreach ($query->result() as $row) {
            $result[] = $row;
        }

        return $result;
    }

    public function get_invoices($project_id)
    {
        if ( ! $project_id) {
            return [];
        }

        $this->load->model('invoices/mdl_invoices');

        // Find invoice IDs linked through task items
        $task_invoice_ids = [];
        $task_items = $this->db->select('DISTINCT(invoice_id) as invoice_id', false)
            ->from('ip_invoice_items')
            ->join('ip_tasks', 'ip_tasks.task_id = ip_invoice_items.item_task_id')
            ->where('ip_tasks.project_id', $project_id)
            ->get()->result();

        foreach ($task_items as $item) {
            if (!empty($item->invoice_id)) {
                $task_invoice_ids[] = $item->invoice_id;
            }
        }

        $this->mdl_invoices->group_start()
            ->where('ip_invoices.project_id', $project_id);

        if (!empty($task_invoice_ids)) {
            $this->mdl_invoices->or_where_in('ip_invoices.invoice_id', $task_invoice_ids);
        }

        $this->mdl_invoices->group_end();

        return $this->mdl_invoices->get()->result();
    }

    public function get_quotes($project_id)
    {
        if ( ! $project_id) {
            return [];
        }

        $this->load->model('quotes/mdl_quotes');

        // Find quotes linked to invoices of this project
        $invoices = $this->get_invoices($project_id);
        $invoice_ids = array_column($invoices, 'invoice_id');

        $this->mdl_quotes->group_start()
            ->where('ip_quotes.project_id', $project_id);

        if (!empty($invoice_ids)) {
            $this->mdl_quotes->or_where_in('ip_quotes.invoice_id', $invoice_ids);
        }

        $this->mdl_quotes->group_end();

        return $this->mdl_quotes->get()->result();
    }

    public function get_payments($project_id)
    {
        if ( ! $project_id) {
            return [];
        }

        $invoices = $this->get_invoices($project_id);
        $invoice_ids = array_column($invoices, 'invoice_id');

        if (empty($invoice_ids)) {
            return [];
        }

        $this->load->model('payments/mdl_payments');
        return $this->mdl_payments
            ->where_in('ip_payments.invoice_id', $invoice_ids)
            ->get()->result();
    }

    public function get_receipts($project_id)
    {
        if ( ! $project_id) {
            return [];
        }

        $invoices = $this->get_invoices($project_id);
        $invoice_ids = array_column($invoices, 'invoice_id');

        $payments = $this->get_payments($project_id);
        $payment_ids = array_column($payments, 'payment_id');

        if (empty($invoice_ids) && empty($payment_ids)) {
            return [];
        }

        $this->load->model('receipts/mdl_receipts');
        $this->mdl_receipts->group_start();
        if (!empty($invoice_ids)) {
            $this->mdl_receipts->where_in('ip_receipts.invoice_id', $invoice_ids);
        }
        if (!empty($payment_ids)) {
            $this->mdl_receipts->or_where_in('ip_receipts.payment_id', $payment_ids);
        }
        $this->mdl_receipts->group_end();

        return $this->mdl_receipts->get()->result();
    }

    /**
     * Check if the current user has access to this project.
     *
     * Security: Prevents IDOR vulnerabilities for project access by verifying
     * the user can access the project's associated client.
     *
     * @param int $project_id The project ID to check
     *
     * @return bool True if user has access, false otherwise
     */
    public function can_user_access($project_id)
    {
        $CI = & get_instance();

        // Normalize to integer to prevent type juggling
        $project_id = (int) $project_id;

        // Admin users (type 1) have access to all projects
        if ((int) $CI->session->userdata('user_type') === 1) {
            return true;
        }

        // For other user types, check if they have access to the project's client
        $project = $this->get_by_id($project_id);

        if ( ! $project) {
            return false;
        }

        // For user_type 3 (custom role)
        if ((int) $CI->session->userdata('user_type') === 3) {
            if (has_permission('projects', 'view')) {
                $this->load->model('clients/mdl_clients');
                return $this->mdl_clients->can_user_access((int) $project->client_id);
            }
            return false;
        }

        // Check if user has access to the project's client
        $this->load->model('clients/mdl_clients');

        return $this->mdl_clients->can_user_access((int) $project->client_id);
    }
}
