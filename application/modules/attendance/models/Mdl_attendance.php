<?php

if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mdl_Attendance extends Response_Model
{
    public $table = 'ip_attendance';

    public $primary_key = 'ip_attendance.attendance_id';

    public function default_select(): void
    {
        $this->db->select('ip_attendance.*, ip_employees.employee_number, ip_employees.first_name, ip_employees.last_name, ip_employees.department, ip_employees.job_title, ip_employees.user_id as employee_user_id, ip_companies.company_name');
    }

    public function default_join(): void
    {
        $this->db->join('ip_employees', 'ip_employees.employee_id = ip_attendance.employee_id', 'left');
        $this->db->join('ip_companies', 'ip_companies.company_id = ip_attendance.company_id', 'left');
    }

    public function default_order_by(): void
    {
        $this->db->order_by('ip_attendance.attendance_date DESC, ip_employees.first_name ASC');
    }

    public function validation_rules(): array
    {
        return [
            'employee_id' => [
                'field' => 'employee_id',
                'label' => trans('employee'),
                'rules' => 'required|numeric',
            ],
            'attendance_date' => [
                'field' => 'attendance_date',
                'label' => trans('date'),
                'rules' => 'required',
            ],
            'status' => [
                'field' => 'status',
                'label' => trans('status'),
                'rules' => 'required',
            ],
            'clock_in' => [
                'field' => 'clock_in',
                'label' => trans('clock_in'),
            ],
            'clock_out' => [
                'field' => 'clock_out',
                'label' => trans('clock_out'),
            ],
            'clock_in_ip' => [
                'field' => 'clock_in_ip',
            ],
            'clock_out_ip' => [
                'field' => 'clock_out_ip',
            ],
            'clock_in_location' => [
                'field' => 'clock_in_location',
            ],
            'clock_out_location' => [
                'field' => 'clock_out_location',
            ],
            'notes' => [
                'field' => 'notes',
            ],
            'company_id' => [
                'field' => 'company_id',
            ],
            'is_manual' => [
                'field' => 'is_manual',
            ],
        ];
    }

    public function get_today_attendance(int $employee_id, ?string $date = null)
    {
        $date = $date ?: date('Y-m-d');
        $this->db->where('ip_attendance.employee_id', $employee_id);
        $this->db->where('ip_attendance.attendance_date', $date);

        return $this->db->get('ip_attendance')->row();
    }

    public function clock_in(int $employee_id, int $company_id, string $ip, ?string $location = null, ?string $notes = null): array
    {
        $today    = date('Y-m-d');
        $existing = $this->get_today_attendance($employee_id, $today);

        if ($existing && $existing->clock_in) {
            return [
                'success' => 0,
                'error'   => trans('already_clocked_in'),
            ];
        }

        $now      = date('Y-m-d H:i:s');
        $time_str = date('H:i:s');
        $status   = ($time_str > '09:00:00') ? 'late' : 'present';

        if ($existing) {
            $this->db->where('attendance_id', $existing->attendance_id);
            $this->db->update('ip_attendance', [
                'clock_in'          => $now,
                'clock_in_ip'       => $ip,
                'clock_in_location' => $location ?: 'Location Access Denied',
                'status'            => $status,
                'notes'             => $notes ?: $existing->notes,
                'date_modified'     => $now,
            ]);
            $attendance_id = $existing->attendance_id;
        } else {
            $data = [
                'company_id'         => $company_id,
                'employee_id'        => $employee_id,
                'attendance_date'    => $today,
                'clock_in'           => $now,
                'clock_in_ip'        => $ip,
                'clock_in_location'  => $location ?: 'Location Access Denied',
                'status'             => $status,
                'notes'              => $notes,
                'is_manual'          => 0,
                'created_by_user_id' => $this->session->userdata('user_id'),
                'date_created'       => $now,
                'date_modified'      => $now,
            ];
            $this->db->insert('ip_attendance', $data);
            $attendance_id = $this->db->insert_id();
        }

        return [
            'success'       => 1,
            'attendance_id' => $attendance_id,
            'clock_in'      => date('H:i:s', strtotime($now)),
            'status'        => $status,
            'message'       => trans('attendance_recorded_successfully'),
        ];
    }

    public function clock_out(int $employee_id, string $ip, ?string $location = null, ?string $notes = null): array
    {
        $today    = date('Y-m-d');
        $existing = $this->get_today_attendance($employee_id, $today);

        if ( ! $existing || ! $existing->clock_in) {
            return [
                'success' => 0,
                'error'   => trans('clock_in_first'),
            ];
        }

        if ($existing->clock_out) {
            return [
                'success' => 0,
                'error'   => trans('already_clocked_out'),
            ];
        }

        $now = date('Y-m-d H:i:s');
        $this->db->where('attendance_id', $existing->attendance_id);
        $this->db->update('ip_attendance', [
            'clock_out'          => $now,
            'clock_out_ip'       => $ip,
            'clock_out_location' => $location ?: 'Location Access Denied',
            'date_modified'      => $now,
        ]);

        return [
            'success'   => 1,
            'clock_out' => date('H:i:s', strtotime($now)),
            'message'   => trans('attendance_recorded_successfully'),
        ];
    }

    public function by_company(int $company_id): self
    {
        $this->filter_where('ip_attendance.company_id', $company_id);

        return $this;
    }

    public function by_date(string $date): self
    {
        $this->filter_where('ip_attendance.attendance_date', $date);

        return $this;
    }
}
