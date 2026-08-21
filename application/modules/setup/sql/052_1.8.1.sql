CREATE TABLE IF NOT EXISTS `ip_client_pics` (
  `client_pic_id` INT(11) NOT NULL AUTO_INCREMENT,
  `client_id` INT(11) NOT NULL,
  `pic_name` VARCHAR(100) NOT NULL,
  `pic_position` VARCHAR(100) DEFAULT '',
  `pic_email` VARCHAR(100) DEFAULT '',
  `pic_phone` VARCHAR(50) DEFAULT '',
  `pic_notes` TEXT DEFAULT NULL,
  `pic_date_created` DATETIME NOT NULL,
  PRIMARY KEY (`client_pic_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `ip_invoices`
  ADD COLUMN `invoice_reference_number` VARCHAR(100) NULL DEFAULT NULL,
  ADD COLUMN `project_id` INT(11) NULL DEFAULT NULL;

ALTER TABLE `ip_quotes`
  ADD COLUMN `project_id` INT(11) NULL DEFAULT NULL;
