use swgemu;

-- Add Table admin_auth_codes
CREATE TABLE IF NOT EXISTS `admin_auth_codes` (
	`aaid` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`auth_code` varchar(25) NOT NULL,
	`username` varchar(32) NOT NULL,
	`email` varchar(255) NOT NULL,
	`code_used` tinyint NOT NULL DEFAULT 0,
	`used_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Alter Accounts table
ALTER TABLE accounts
	ADD COLUMN email varchar(255) not null,
	ADD COLUMN create_ip varchar(45) not null,
	ADD COLUMN admin_auth tinyint(1) not null default 0;
