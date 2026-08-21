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
class Projects extends Admin_Controller
{
    /**
     * Projects constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('mdl_projects');
    }

    /**
     * @param int $page
     */
    public function index($page = 0)
    {
        check_permission('projects', 'view');
        $this->mdl_projects->paginate(site_url('projects/index'), $page);
        $projects = $this->mdl_projects->result();

        $this->layout->set(
            [
                'filter_display'     => true,
                'filter_placeholder' => trans('filter_projects'),
                'filter_method'      => 'filter_projects',
                'projects'           => $projects,
            ]
        );
        $this->layout->buffer('content', 'projects/index');
        $this->layout->render();
    }

    public function form($id = null)
    {
        if ($id) {
            check_permission('projects', 'edit');
        } else {
            check_permission('projects', 'create');
        }

        if ($this->input->post('btn_cancel')) {
            redirect('projects');
        }

        if ($this->mdl_projects->run_validation()) {
            $this->mdl_projects->save($id);
            redirect('projects');
        }

        if ($id && ! $this->input->post('btn_submit') && ! $this->mdl_projects->prep_form($id)) {
            show_404();
        }

        $this->load->model('clients/mdl_clients');

        $this->layout->set(
            [
                'project' => $this->mdl_projects->get_by_id($id),
                'clients' => $this->mdl_clients->where('client_active', 1)->get()->result(),
            ]
        );

        $this->layout->buffer('content', 'projects/form');
        $this->layout->render();
    }

    public function view($project_id, $activeTab = 'tasks', $page = 0)
    {
        check_permission('projects', 'view');
        if ($this->input->post('btn_cancel')) {
            redirect('projects');
        }

        if ( ! $this->mdl_projects->can_user_access($project_id)) {
            show_error(trans('access_denied'), 403);
        }

        $project = $this->mdl_projects->get_by_id($project_id);

        if ( ! $project) {
            show_404();
        }

        $this->load->model([
            'tasks/mdl_tasks',
            'quotes/mdl_quotes',
            'invoices/mdl_invoices',
            'payments/mdl_payments',
            'receipts/mdl_receipts',
        ]);

        $quotes   = $this->mdl_projects->get_quotes($project->project_id);
        $invoices = $this->mdl_projects->get_invoices($project->project_id);
        $payments = $this->mdl_projects->get_payments($project->project_id);
        $receipts = $this->mdl_projects->get_receipts($project->project_id);

        $this->layout->set([
            'project'          => $project,
            'tasks'            => $this->mdl_projects->get_tasks($project->project_id),
            'quotes'           => $quotes,
            'invoices'         => $invoices,
            'payments'         => $payments,
            'receipts'         => $receipts,
            'task_statuses'    => $this->mdl_tasks->statuses(),
            'quote_statuses'   => $this->mdl_quotes->statuses(),
            'invoice_statuses' => $this->mdl_invoices->statuses(),
            'activeTab'        => $activeTab,
        ]);

        $this->layout->buffer([
            ['task_table', 'tasks/partial_tasks_table'],
            ['quote_table', 'quotes/partial_quote_table'],
            ['invoice_table', 'invoices/partial_invoice_table'],
            ['payment_table', 'payments/partial_payments_table'],
            ['receipt_table', 'receipts/partial_receipts_table'],
            ['content', 'projects/view'],
        ]);
        $this->layout->render();
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        check_permission('projects', 'delete');
        if ( ! $this->ensure_valid_post_request('projects/index')) {
            return;
        }

        $this->load->model('tasks/mdl_tasks');
        $this->mdl_tasks->update_on_project_delete($id);

        $this->mdl_projects->delete($id);
        redirect('projects');
    }
}
