<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mdl_Reimbursements extends Response_Model
{
    public $table = 'ip_reimbursements';
    public $primary_key = 'ip_reimbursements.reimbursement_id';

    public function __construct()
    {
        parent::__construct();
        $this->ensure_table_exists();
    }

    private function ensure_table_exists()
    {
        if (!$this->db->table_exists('ip_reimbursements')) {
            $sql = "CREATE TABLE IF NOT EXISTS `ip_reimbursements` (
              `reimbursement_id` INT(11) NOT NULL AUTO_INCREMENT,
              `reimbursement_number` VARCHAR(50) NOT NULL,
              `company_id` INT(11) DEFAULT 1,
              `user_id` INT(11) NOT NULL,
              `employee_id` INT(11) DEFAULT NULL,
              `reimbursement_title` VARCHAR(255) NOT NULL,
              `reimbursement_date` DATE NOT NULL,
              `category` VARCHAR(100) NOT NULL DEFAULT 'Lain-lain',
              `amount` DECIMAL(20,2) NOT NULL DEFAULT 0.00,
              `description` TEXT DEFAULT NULL,
              `attachment` VARCHAR(255) DEFAULT NULL,
              `status` VARCHAR(25) NOT NULL DEFAULT 'pending',
              `approved_by_user_id` INT(11) DEFAULT NULL,
              `approved_at` DATETIME DEFAULT NULL,
              `admin_notes` TEXT DEFAULT NULL,
              `payment_date` DATE DEFAULT NULL,
              `payment_method` VARCHAR(100) DEFAULT NULL,
              `date_created` DATETIME NOT NULL,
              `date_modified` DATETIME NOT NULL,
              PRIMARY KEY (`reimbursement_id`),
              UNIQUE KEY `reimbursement_number` (`reimbursement_number`),
              KEY `company_id` (`company_id`),
              KEY `user_id` (`user_id`),
              KEY `employee_id` (`employee_id`),
              KEY `status` (`status`),
              KEY `reimbursement_date` (`reimbursement_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->db->query($sql);
        }
    }

    public function default_select()
    {
        $this->db->select('ip_reimbursements.*, ip_users.user_name, ip_users.user_email, approver.user_name as approver_name');
    }

    public function default_join()
    {
        $this->db->join('ip_users', 'ip_users.user_id = ip_reimbursements.user_id', 'left');
        $this->db->join('ip_users as approver', 'approver.user_id = ip_reimbursements.approved_by_user_id', 'left');
    }

    public function default_order_by()
    {
        $this->db->order_by('ip_reimbursements.reimbursement_id', 'DESC');
    }

    public function validation_rules()
    {
        return [
            'reimbursement_title' => [
                'field' => 'reimbursement_title',
                'label' => 'Judul Reimburse',
                'rules' => 'required|trim|max_length[255]',
            ],
            'reimbursement_date' => [
                'field' => 'reimbursement_date',
                'label' => 'Tanggal Pengeluaran',
                'rules' => 'required|trim',
            ],
            'category' => [
                'field' => 'category',
                'label' => 'Kategori',
                'rules' => 'required|trim',
            ],
            'amount' => [
                'field' => 'amount',
                'label' => 'Nominal (Rp)',
                'rules' => 'required|trim',
            ],
        ];
    }

    public function generate_number()
    {
        $prefix = 'RMB-' . date('Ym') . '-';
        $this->db->select('reimbursement_number');
        $this->db->like('reimbursement_number', $prefix, 'after');
        $this->db->order_by('reimbursement_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('ip_reimbursements');
        
        if ($query && $query->num_rows() > 0) {
            $last_number = $query->row()->reimbursement_number;
            $seq = (int) substr($last_number, -4) + 1;
        } else {
            $seq = 1;
        }

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function get_categories()
    {
        return [
            'Transportasi' => 'Transportasi & Bensin',
            'Konsumsi' => 'Konsumsi & Jamuan Klien',
            'Peralatan' => 'Peralatan & Kantor',
            'Akomodasi' => 'Akomodasi & Perjalanan Dinas',
            'Kesehatan' => 'Kesehatan & Pengobatan',
            'Lain-lain' => 'Lain-lain',
        ];
    }

    public function get_kpi_stats($user_id = null)
    {
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }
        $query = $this->db->select('
            COUNT(reimbursement_id) as total_count,
            SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_count,
            SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_count,
            SUM(CASE WHEN status = "paid" THEN 1 ELSE 0 END) as paid_count,
            SUM(CASE WHEN status IN ("approved", "paid") THEN amount ELSE 0 END) as total_approved_amount,
            SUM(CASE WHEN status = "pending" THEN amount ELSE 0 END) as total_pending_amount
        ')->get('ip_reimbursements');

        return $query->row();
    }
}
