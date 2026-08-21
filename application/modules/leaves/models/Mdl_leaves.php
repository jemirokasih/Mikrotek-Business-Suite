<?php

if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * Mikrotek Business Suite
 * Module: Leaves (Pengajuan Cuti)
 */

class Mdl_leaves extends MY_Model
{
    public $table = 'ip_leave_requests';

    public $primary_key = 'ip_leave_requests.leave_request_id';

    public function default_select()
    {
        $this->db->select('ip_leave_requests.*, 
            ip_employees.first_name, 
            ip_employees.last_name, 
            ip_employees.employee_number, 
            ip_employees.department, 
            ip_employees.position,
            ip_employees.user_id AS employee_user_id,
            approver.user_name AS approver_name');
    }

    public function default_join()
    {
        $this->db->join('ip_employees', 'ip_employees.employee_id = ip_leave_requests.employee_id', 'left');
        $this->db->join('ip_users AS approver', 'approver.user_id = ip_leave_requests.approved_by_user_id', 'left');
    }

    public function default_order_by()
    {
        $this->db->order_by('ip_leave_requests.leave_request_id', 'DESC');
    }

    public function validation_rules()
    {
        return [
            'leave_type' => [
                'field' => 'leave_type',
                'label' => trans('leave_type'),
                'rules' => 'required',
            ],
            'start_date' => [
                'field' => 'start_date',
                'label' => trans('start_date'),
                'rules' => 'required',
            ],
            'end_date' => [
                'field' => 'end_date',
                'label' => trans('end_date'),
                'rules' => 'required',
            ],
            'reason' => [
                'field' => 'reason',
                'label' => trans('reason'),
                'rules' => 'trim',
            ],
        ];
    }

    public function by_employee($employee_id)
    {
        $this->db->where('ip_leave_requests.employee_id', $employee_id);

        return $this;
    }

    public function by_status($status)
    {
        if ($status && $status !== 'all') {
            $this->db->where('ip_leave_requests.status', $status);
        }

        return $this;
    }

    public function calculate_days($start_date, $end_date)
    {
        $start = new DateTime($start_date);
        $end   = new DateTime($end_date);

        if ($start > $end) {
            return 0;
        }

        // Calculate difference in days inclusive (+1)
        $diff = $start->diff($end);

        return $diff->days + 1;
    }

    public function db_array()
    {
        $db_array = parent::db_array();

        if (isset($db_array['start_date'])) {
            $db_array['start_date'] = date_to_mysql($db_array['start_date']);
        }

        if (isset($db_array['end_date'])) {
            $db_array['end_date'] = date_to_mysql($db_array['end_date']);
        }

        if (isset($db_array['start_date'], $db_array['end_date'])) {
            $db_array['total_days'] = $this->calculate_days($db_array['start_date'], $db_array['end_date']);
        }

        $db_array['date_modified'] = date('Y-m-d H:i:s');

        return $db_array;
    }
}
