ALTER TABLE `ip_invoices` ADD COLUMN `is_proforma` TINYINT(1) NOT NULL DEFAULT 0 AFTER `invoice_status_id`;
