CREATE TABLE `dotz_dev`.`media_votes` (  
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `app_user_id` INT(11) NOT NULL,
  `user_media_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`) 
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `dotz_dev`.`user_medias`   
	DROP COLUMN `like_cnt`;
