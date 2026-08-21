<?php

if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Attendance extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_attendance');
        $this->load->model('employees/mdl_employees');
        $this->load->model('companies/mdl_companies');
        $this->load->helper('date');
    }

    public function index(?string $date = null): void
    {
        check_permission('attendance', 'view');

        $date       = $date ?: date('Y-m-d');
        $company_id = $this->session->userdata('company_id') ?: 1;

        // Fetch all active employees for company
        $employees = $this->mdl_employees
            ->by_company($company_id)
            ->is_active(1)
            ->get()
            ->result();

        // Fetch attendance records for selected date
        $attendance_query = $this->db
            ->where('company_id', $company_id)
            ->where('attendance_date', $date)
            ->get('ip_attendance')
            ->result();

        $attendance_map = [];
        foreach ($attendance_query as $att) {
            $attendance_map[$att->employee_id] = $att;
        }

        // Calculate KPI summaries
        $total_employees  = count($employees);
        $present_count    = 0;
        $late_count       = 0;
        $absent_count     = 0;
        $leave_sick_count = 0;

        foreach ($employees as $emp) {
            if (isset($attendance_map[$emp->employee_id])) {
                $st = $attendance_map[$emp->employee_id]->status;
                if ($st == 'present') {
                    $present_count++;
                } elseif ($st == 'late') {
                    $late_count++;
                } elseif ($st == 'leave' || $st == 'sick') {
                    $leave_sick_count++;
                } else {
                    $absent_count++;
                }
            } else {
                $absent_count++;
            }
        }

        $this->layout->set([
            'date'             => $date,
            'employees'        => $employees,
            'attendance_map'   => $attendance_map,
            'total_employees'  => $total_employees,
            'present_count'    => $present_count,
            'late_count'       => $late_count,
            'absent_count'     => $absent_count,
            'leave_sick_count' => $leave_sick_count,
        ]);

        $this->layout->buffer('content', 'attendance/index');
        $this->layout->render();
    }

    public function clock(): void
    {
        $user_id  = $this->session->userdata('user_id');
        $employee = $this->db->where('user_id', $user_id)->get('ip_employees')->row();

        if ( ! $employee) {
            $this->session->set_flashdata('alert_error', 'No linked Employee profile found for your user account.');
        }

        $today_attendance = $employee ? $this->mdl_attendance->get_today_attendance($employee->employee_id) : null;

        // Fetch monthly attendance history for employee
        $first_day = date('Y-m-01');
        $last_day  = date('Y-m-t');

        $monthly_history = [];
        if ($employee) {
            $monthly_history = $this->db
                ->where('employee_id', $employee->employee_id)
                ->where('attendance_date >=', $first_day)
                ->where('attendance_date <=', $last_day)
                ->order_by('attendance_date', 'DESC')
                ->get('ip_attendance')
                ->result();
        }

        $this->layout->set([
            'employee'         => $employee,
            'today_attendance' => $today_attendance,
            'monthly_history'  => $monthly_history,
        ]);

        $this->layout->buffer('content', 'attendance/clock');
        $this->layout->render();
    }

    public function save_clock_in(): void
    {
        $user_id  = $this->session->userdata('user_id');
        $employee = $this->db->where('user_id', $user_id)->get('ip_employees')->row();

        if ( ! $employee) {
            echo json_encode(['success' => 0, 'error' => 'No linked Employee profile found.']);

            return;
        }

        $ip       = $this->input->ip_address();
        $location = trim($this->input->post('location'));
        $notes    = trim($this->input->post('notes'));

        $company_id = $employee->company_id ?: 1;

        $result = $this->mdl_attendance->clock_in(
            $employee->employee_id,
            $company_id,
            $ip,
            $location,
            $notes
        );

        echo json_encode($result);
    }

    public function save_clock_out(): void
    {
        $user_id  = $this->session->userdata('user_id');
        $employee = $this->db->where('user_id', $user_id)->get('ip_employees')->row();

        if ( ! $employee) {
            echo json_encode(['success' => 0, 'error' => 'No linked Employee profile found.']);

            return;
        }

        $ip       = $this->input->ip_address();
        $location = trim($this->input->post('location'));
        $notes    = trim($this->input->post('notes'));

        $result = $this->mdl_attendance->clock_out(
            $employee->employee_id,
            $ip,
            $location,
            $notes
        );

        echo json_encode($result);
    }

    public function modal_manual_attendance(): void
    {
        check_permission('attendance', 'edit');

        $employee_id = $this->input->post('employee_id');
        $date        = $this->input->post('date') ?: date('Y-m-d');

        $employee = $this->mdl_employees->get_by_id($employee_id);
        if ( ! $employee) {
            return;
        }

        $attendance = $this->mdl_attendance->get_today_attendance($employee_id, $date);

        $this->layout->load_view('attendance/modal_manual_attendance', [
            'employee'   => $employee,
            'date'       => $date,
            'attendance' => $attendance,
        ]);
    }

    public function save_manual_attendance(): void
    {
        check_permission('attendance', 'edit');

        $employee_id    = (int) $this->input->post('employee_id');
        $date           = $this->input->post('attendance_date') ?: date('Y-m-d');
        $status         = $this->input->post('status') ?: 'present';
        $clock_in_time  = $this->input->post('clock_in_time');
        $clock_out_time = $this->input->post('clock_out_time');
        $notes          = trim($this->input->post('notes'));

        $employee = $this->mdl_employees->get_by_id($employee_id);
        if ( ! $employee) {
            echo json_encode(['success' => 0, 'error' => 'Employee not found.']);

            return;
        }

        $existing = $this->mdl_attendance->get_today_attendance($employee_id, $date);

        $clock_in  = ( ! empty($clock_in_time)) ? $date . ' ' . $clock_in_time . ':00' : null;
        $clock_out = ( ! empty($clock_out_time)) ? $date . ' ' . $clock_out_time . ':00' : null;

        $now  = date('Y-m-d H:i:s');
        $data = [
            'company_id'         => $employee->company_id ?: 1,
            'employee_id'        => $employee_id,
            'attendance_date'    => $date,
            'clock_in'           => $clock_in,
            'clock_out'          => $clock_out,
            'status'             => $status,
            'notes'              => $notes,
            'is_manual'          => 1,
            'created_by_user_id' => $this->session->userdata('user_id'),
            'date_modified'      => $now,
        ];

        if ($existing) {
            $this->db->where('attendance_id', $existing->attendance_id);
            $this->db->update('ip_attendance', $data);
        } else {
            $data['date_created']      = $now;
            $data['clock_in_ip']       = $this->input->ip_address();
            $data['clock_in_location'] = 'Manual (Admin)';
            $this->db->insert('ip_attendance', $data);
        }

        echo json_encode(['success' => 1, 'message' => trans('attendance_recorded_successfully')]);
    }

    public function report(?string $month = null, ?string $year = null): void
    {
        check_permission('attendance', 'view');

        $month      = $month ?: date('m');
        $year       = $year ?: date('Y');
        $company_id = $this->session->userdata('company_id') ?: 1;

        $first_day = "{$year}-{$month}-01";
        $last_day  = date('Y-m-t', strtotime($first_day));

        $employees = $this->mdl_employees
            ->by_company($company_id)
            ->is_active(1)
            ->get()
            ->result();

        $raw_attendance = $this->db
            ->where('company_id', $company_id)
            ->where('attendance_date >=', $first_day)
            ->where('attendance_date <=', $last_day)
            ->get('ip_attendance')
            ->result();

        $employee_summary = [];
        foreach ($employees as $emp) {
            $employee_summary[$emp->employee_id] = [
                'employee'      => $emp,
                'present'       => 0,
                'late'          => 0,
                'leave_sick'    => 0,
                'absent'        => 0,
                'total_seconds' => 0,
            ];
        }

        foreach ($raw_attendance as $att) {
            if (isset($employee_summary[$att->employee_id])) {
                $st = $att->status;
                if ($st == 'present') {
                    $employee_summary[$att->employee_id]['present']++;
                } elseif ($st == 'late') {
                    $employee_summary[$att->employee_id]['late']++;
                } elseif ($st == 'leave' || $st == 'sick') {
                    $employee_summary[$att->employee_id]['leave_sick']++;
                } else {
                    $employee_summary[$att->employee_id]['absent']++;
                }

                if ($att->clock_in && $att->clock_out) {
                    $sec = strtotime($att->clock_out) - strtotime($att->clock_in);
                    if ($sec > 0) {
                        $employee_summary[$att->employee_id]['total_seconds'] += $sec;
                    }
                }
            }
        }

        $this->layout->set([
            'month'            => $month,
            'year'             => $year,
            'employee_summary' => $employee_summary,
        ]);

        $this->layout->buffer('content', 'attendance/report');
        $this->layout->render();
    }
}
