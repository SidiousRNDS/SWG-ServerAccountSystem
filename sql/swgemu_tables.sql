use swgemu;

-- Alter Accounts table
ALTER TABLE accounts
	ADD COLUMN email varchar(255) not null,
	ADD COLUMN create_ip varchar(45) not null,
	ADD COLUMN admin_auth tinyint(1) not null default 0;
