<?php

if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * Mikrotek Business Suite
 * Module: Leaves Controller (Pengajuan Cuti)
 */

#[AllowDynamicProperties]
class Leaves extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leaves/mdl_leaves');
        $this->load->model('employees/mdl_employees');
    }

    public function index($page = 0)
    {
        check_permission('leaves', 'view');

        $status = $this->input->get('status') ?: 'pending';

        $this->mdl_leaves->by_status($status);
        $this->mdl_leaves->paginate((int) $page);

        $leaves = $this->mdl_leaves->get()->result();

        $this->layout->set([
            'leaves'         => $leaves,
            'current_status' => $status,
            'filter_display' => true,
        ]);

        $this->layout->buffer('content', 'leaves/index');
        $this->layout->render();
    }

    public function my_leaves($page = 0)
    {
        $user_id  = $this->session->userdata('user_id');
        $employee = $this->db->where('user_id', $user_id)->get('ip_employees')->row();

        if ( ! $employee) {
            $this->session->set_flashdata('alert_error', 'No employee profile linked to your account.');
            redirect('dashboard');
        }

        $this->mdl_leaves->by_employee($employee->employee_id);
        $this->mdl_leaves->paginate((int) $page);

        $leaves = $this->mdl_leaves->get()->result();

        $this->layout->set([
            'employee' => $employee,
            'leaves'   => $leaves,
        ]);

        $this->layout->buffer('content', 'leaves/my_leaves');
        $this->layout->render();
    }

    public function modal_form()
    {
        $user_id  = $this->session->userdata('user_id');
        $employee = $this->db->where('user_id', $user_id)->get('ip_employees')->row();

        if ( ! $employee) {
            $this->output->set_status_header(400);
            echo json_encode(['success' => 0, 'message' => 'No employee profile found.']);

            return;
        }

        $data = [
            'employee'    => $employee,
            'leave_types' => [
                'annual'    => trans('leave_type_annual'),
                'sick'      => trans('leave_type_sick'),
                'emergency' => trans('leave_type_emergency'),
                'maternity' => trans('leave_type_maternity'),
                'unpaid'    => trans('leave_type_unpaid'),
            ],
        ];

        $this->load->view('leaves/modal_form', $data);
    }

    public function save()
    {
        $user_id  = $this->session->userdata('user_id');
        $employee = $this->db->where('user_id', $user_id)->get('ip_employees')->row();

        if ( ! $employee) {
            echo json_encode(['success' => 0, 'validation_errors' => ['employee' => 'Employee profile not found.']]);

            return;
        }

        if ($this->mdl_leaves->run_validation()) {
            $start_date = date_to_mysql($this->input->post('start_date'));
            $end_date   = date_to_mysql($this->input->post('end_date'));
            $total_days = $this->mdl_leaves->calculate_days($start_date, $end_date);

            if ($total_days <= 0) {
                echo json_encode([
                    'success'           => 0,
                    'validation_errors' => ['end_date' => 'End date must be on or after start date.'],
                ]);

                return;
            }

            $db_array = [
                'company_id'    => $employee->company_id ?: 1,
                'employee_id'   => $employee->employee_id,
                'leave_type'    => $this->input->post('leave_type'),
                'start_date'    => $start_date,
                'end_date'      => $end_date,
                'total_days'    => $total_days,
                'reason'        => $this->input->post('reason'),
                'status'        => 'pending',
                'date_created'  => date('Y-m-d H:i:s'),
                'date_modified' => date('Y-m-d H:i:s'),
            ];

            $this->db->insert('ip_leave_requests', $db_array);

            echo json_encode(['success' => 1, 'message' => trans('leave_request_submitted_successfully')]);
        } else {
            $this->load->helper('json_error');
            echo json_encode([
                'success'           => 0,
                'validation_errors' => $this->mdl_leaves->validation_errors,
            ]);
        }
    }

    public function modal_approve_reject($id)
    {
        check_permission('leaves', 'edit');

        $leave = $this->mdl_leaves->get_by_id($id);

        if ( ! $leave) {
            return;
        }

        $data = [
            'leave' => $leave,
        ];

        $this->load->view('leaves/modal_approve_reject', $data);
    }

    public function save_approval()
    {
        check_permission('leaves', 'edit');

        $leave_request_id = $this->input->post('leave_request_id');
        $status           = $this->input->post('status'); // approved or rejected
        $admin_notes      = $this->input->post('admin_notes');

        if ( ! in_array($status, ['approved', 'rejected'])) {
            echo json_encode(['success' => 0, 'message' => 'Invalid status option.']);

            return;
        }

        $leave = $this->mdl_leaves->get_by_id($leave_request_id);

        if ( ! $leave) {
            echo json_encode(['success' => 0, 'message' => 'Leave request not found.']);

            return;
        }

        $update_data = [
            'status'              => $status,
            'approved_by_user_id' => $this->session->userdata('user_id'),
            'approved_at'         => date('Y-m-d H:i:s'),
            'admin_notes'         => $admin_notes,
            'date_modified'       => date('Y-m-d H:i:s'),
        ];

        $this->db->where('leave_request_id', $leave_request_id)->update('ip_leave_requests', $update_data);

        echo json_encode(['success' => 1, 'message' => trans('leave_request_updated_successfully')]);
    }

    public function cancel($id)
    {
        $user_id  = $this->session->userdata('user_id');
        $employee = $this->db->where('user_id', $user_id)->get('ip_employees')->row();

        if ( ! $employee) {
            echo json_encode(['success' => 0, 'message' => 'Unauthorized']);

            return;
        }

        $leave = $this->mdl_leaves->get_by_id($id);

        if ( ! $leave || $leave->employee_id != $employee->employee_id) {
            echo json_encode(['success' => 0, 'message' => 'Leave request not found.']);

            return;
        }

        if ($leave->status !== 'pending') {
            echo json_encode(['success' => 0, 'message' => 'Only pending requests can be cancelled.']);

            return;
        }

        $this->db->where('leave_request_id', $id)->update('ip_leave_requests', [
            'status'        => 'cancelled',
            'date_modified' => date('Y-m-d H:i:s'),
        ]);

        echo json_encode(['success' => 1, 'message' => trans('leave_request_cancelled_successfully')]);
    }
}
