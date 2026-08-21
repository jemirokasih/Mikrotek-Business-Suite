ALTER TABLE `ip_payment_methods` 
  ADD COLUMN `payment_method_bank_name` VARCHAR(100) NULL DEFAULT NULL AFTER `payment_method_name`,
  ADD COLUMN `payment_method_account_number` VARCHAR(100) NULL DEFAULT NULL AFTER `payment_method_bank_name`,
  ADD COLUMN `payment_method_account_name` VARCHAR(100) NULL DEFAULT NULL AFTER `payment_method_account_number`,
  ADD COLUMN `payment_method_notes` TEXT NULL DEFAULT NULL AFTER `payment_method_account_name`;
