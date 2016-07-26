DROP TABLE record_activity;
CREATE TABLE IF NOT EXISTS `levelup76`.`record_activity` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `schedule_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `activity_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `activitydate` DATE NULL DEFAULT NULL,
  `starttime` TIME NULL DEFAULT NULL,
  `recordcomment` TEXT NULL DEFAULT NULL,
  `name` TEXT NULL DEFAULT NULL,
  `phone` BIGINT(20) UNSIGNED NOT NULL,
  `deleted` TINYINT,
  PRIMARY KEY (`id`),
  INDEX `fk_record_activity_users_idx` (`user_id` ASC),
  INDEX `fk_record_activity_schedule_activity_idx` (`schedule_id` ASC),
  INDEX `fk_record_activity_activity_activity_idx` (`activity_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;