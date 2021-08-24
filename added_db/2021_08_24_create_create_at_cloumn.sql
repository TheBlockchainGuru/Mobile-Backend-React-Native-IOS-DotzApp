ALTER TABLE `dotz_dev`.`user_medias`   
	ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP() NULL AFTER `share_cnt`;
