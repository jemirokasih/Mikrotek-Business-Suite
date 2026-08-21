CREATE TABLE IF NOT EXISTS `ip_receipts` (
  `receipt_id` INT(11) NOT NULL AUTO_INCREMENT,
  `company_id` INT(11) NULL DEFAULT NULL,
  `user_id` INT(11) NOT NULL,
  `client_id` INT(11) NOT NULL,
  `invoice_id` INT(11) NULL DEFAULT NULL,
  `payment_id` INT(11) NULL DEFAULT NULL,
  `receipt_number` VARCHAR(50) NOT NULL,
  `receipt_date` DATE NOT NULL,
  `receipt_amount` DECIMAL(20,2) NOT NULL DEFAULT '0.00',
  `receipt_payment_method_id` INT(11) NULL DEFAULT NULL,
  `receipt_notes` TEXT NULL,
  `receipt_url_key` VARCHAR(32) NOT NULL,
  `receipt_status` TINYINT(1) DEFAULT 1,
  `receipt_date_created` DATETIME NOT NULL,
  `receipt_date_modified` DATETIME NOT NULL,
  PRIMARY KEY (`receipt_id`),
  UNIQUE KEY `receipt_number` (`receipt_number`),
  UNIQUE KEY `receipt_url_key` (`receipt_url_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `ip_invoice_groups` (`invoice_group_name`, `invoice_group_next_id`, `invoice_group_left_pad`, `invoice_group_identifier_format`) 
SELECT 'Kwitansi', 1, 4, 'KWT-{{{year}}}{{{month}}}-{{{id}}}'
FROM DUAL WHERE NOT EXISTS (SELECT * FROM `ip_invoice_groups` WHERE `invoice_group_name` = 'Kwitansi');
