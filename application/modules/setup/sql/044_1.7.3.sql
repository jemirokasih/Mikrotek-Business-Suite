CREATE TABLE IF NOT EXISTS `ip_roles` (
  `role_id` INT(11) NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(100) NOT NULL,
  `role_description` TEXT NULL,
  `role_permissions` TEXT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `ip_users` ADD COLUMN `user_role_id` INT(11) NULL DEFAULT NULL AFTER `user_type`;
