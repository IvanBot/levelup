SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema levelup76
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `levelup76` ;

-- -----------------------------------------------------
-- Schema levelup76
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `levelup76` DEFAULT CHARACTER SET utf8 ;
USE `levelup76` ;

-- -----------------------------------------------------
-- Table `levelup76`.`trainers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `levelup76`.`trainers` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `phone` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `trainername` TINYTEXT NULL DEFAULT NULL,
  `trainersurname` TINYTEXT NULL DEFAULT NULL,
  `experience` DATE NULL DEFAULT NULL,
  `email` TINYTEXT NULL DEFAULT NULL,
  `photo` TEXT NULL DEFAULT NULL,
  `userpassword` TINYTEXT NULL DEFAULT NULL,
  `permission` TINYINT(4) NULL DEFAULT NULL,
  `active` TINYINT(4) NULL DEFAULT NULL,
  `trainercomment` TEXT NULL DEFAULT NULL,
  `deleted` TINYINT,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `levelup76`.`activity`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `levelup76`.`activity` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `trainer_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `activityname` TEXT NULL DEFAULT NULL,
  `activitycomment` TEXT NULL DEFAULT NULL,
  `activityduration` INT(11) NULL DEFAULT NULL,
  `maxcount` INT(11) NULL DEFAULT NULL,
  `mincount` INT(11) NULL DEFAULT 0,
  `deleted` TINYINT,
  PRIMARY KEY (`id`),
  INDEX `fk_activity_trainers_idx` (`trainer_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `levelup76`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `levelup76`.`users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `phone` BIGINT(20) UNSIGNED NOT NULL,
  `username` TINYTEXT NULL DEFAULT NULL,
  `surname` TINYTEXT NULL DEFAULT NULL,
  `ip` TINYTEXT NULL DEFAULT NULL,
  `email` TINYTEXT NULL DEFAULT NULL,
  `userpassword` TINYTEXT NULL DEFAULT NULL,
  `permission` TINYINT(4) NULL DEFAULT NULL,
  `usercomment` TEXT NULL DEFAULT NULL,
  `deleted` TINYINT,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `levelup76`.`schedule_activity`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `levelup76`.`schedule_activity` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `activity_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `trainer_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `starttime` TIME NULL DEFAULT NULL,
  `endtime` TIME NULL DEFAULT NULL,
  `activitydate` DATE NULL DEFAULT NULL,
  `startdate` DATE NULL DEFAULT NULL,
  `activityduration` TINYINT,
  `maxcount` INT(11) NULL DEFAULT NULL,
  `mincount` INT(11) NULL DEFAULT 0,
  `cycleday` TINYINT,
  `deleted` TINYINT,
  PRIMARY KEY (`id`),
  INDEX `fk_schedule_activity_activity_idx` (`activity_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `levelup76`.`record_activity`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `levelup76`.`record_activity` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `schedule_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `activity_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `activitydate` DATE NULL DEFAULT NULL,
  `starttime` TIME NULL DEFAULT NULL,
  `recordcomment` TEXT NULL DEFAULT NULL,
  `deleted` TINYINT,
  PRIMARY KEY (`id`),
  INDEX `fk_record_activity_users_idx` (`user_id` ASC),
  INDEX `fk_record_activity_schedule_activity_idx` (`schedule_id` ASC),
  INDEX `fk_record_activity_activity_activity_idx` (`activity_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;