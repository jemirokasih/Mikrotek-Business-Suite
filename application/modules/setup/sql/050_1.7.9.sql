CREATE TABLE IF NOT EXISTS `ip_bank_accounts` (
  `bank_id` INT(11) NOT NULL AUTO_INCREMENT,
  `company_id` INT(11) NOT NULL DEFAULT 1,
  `payment_method_id` INT(11) NULL DEFAULT NULL,
  `bank_name` VARCHAR(100) NOT NULL,
  `account_number` VARCHAR(100) NOT NULL,
  `account_name` VARCHAR(100) NOT NULL,
  `bank_notes` TEXT NULL DEFAULT NULL,
  `bank_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`bank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
