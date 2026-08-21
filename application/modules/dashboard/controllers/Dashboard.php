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
class Dashboard extends Admin_Controller
{
    public function index()
    {
        $this->load->model('invoices/mdl_invoice_amounts');
        $this->load->model('quotes/mdl_quote_amounts');
        $this->load->model('invoices/mdl_invoices');
        $this->load->model('quotes/mdl_quotes');
        $this->load->model('projects/mdl_projects');
        $this->load->model('tasks/mdl_tasks');
        $this->load->model('attendance/mdl_attendance');
        $this->load->model('employees/mdl_employees');

        $quote_overview_period   = get_setting('quote_overview_period');
        $invoice_overview_period = get_setting('invoice_overview_period');

        $user_id              = $this->session->userdata('user_id');
        $employee             = $this->db->where('user_id', $user_id)->get('ip_employees')->row();
        $today_attendance     = $employee ? $this->mdl_attendance->get_today_attendance($employee->employee_id) : null;
        $employee_attendances = $employee ? $this->db->where('employee_id', $employee->employee_id)->order_by('attendance_date', 'DESC')->limit(7)->get('ip_attendance')->result() : [];

        $this->layout->set(
            [
                'employee'              => $employee,
                'today_attendance'      => $today_attendance,
                'employee_attendances'  => $employee_attendances,
                'invoice_status_totals' => $this->mdl_invoice_amounts->get_status_totals($invoice_overview_period),
                'quote_status_totals'   => $this->mdl_quote_amounts->get_status_totals($quote_overview_period),
                'invoice_status_period' => str_replace('-', '_', $invoice_overview_period),
                'quote_status_period'   => str_replace('-', '_', $quote_overview_period),
                'invoices'              => $this->mdl_invoices->limit(10)->get()->result(),
                'quotes'                => $this->mdl_quotes->limit(10)->get()->result(),
                'invoice_statuses'      => $this->mdl_invoices->statuses(),
                'quote_statuses'        => $this->mdl_quotes->statuses(),
                'overdue_invoices'      => $this->mdl_invoices->is_overdue()->get()->result(),
                'projects'              => $this->mdl_projects->get_latest()->get()->result(),
                'tasks'                 => $this->mdl_tasks->get_latest()->get()->result(),
                'task_statuses'         => $this->mdl_tasks->statuses(),
            ]
        );

        $this->layout->buffer('content', 'dashboard/index');
        $this->layout->render();
    }
}
