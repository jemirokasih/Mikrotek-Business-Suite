CREATE TABLE IF NOT EXISTS `ip_companies` (
  `company_id` INT(11) NOT NULL AUTO_INCREMENT,
  `company_name` VARCHAR(100) NOT NULL,
  `company_address_1` TEXT NULL,
  `company_address_2` TEXT NULL,
  `company_city` VARCHAR(50) NULL,
  `company_state` VARCHAR(50) NULL,
  `company_zip` VARCHAR(20) NULL,
  `company_country` VARCHAR(50) NULL,
  `company_phone` VARCHAR(50) NULL,
  `company_email` VARCHAR(100) NULL,
  `company_active` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `ip_users` ADD COLUMN `company_id` INT(11) NULL DEFAULT NULL AFTER `user_company`;
