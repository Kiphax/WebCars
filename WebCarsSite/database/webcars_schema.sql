-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema webcars
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema webcars
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `webcars` DEFAULT CHARACTER SET utf8mb4 ;
USE `webcars` ;

-- -----------------------------------------------------
-- Table `webcars`.`car_images`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `webcars`.`car_images` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `car_id` INT NOT NULL,
  `filename` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `uploaded_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `car_id` (`car_id` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 23
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `webcars`.`cars`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `webcars`.`cars` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `brand` VARCHAR(50) NOT NULL,
  `model` VARCHAR(50) NOT NULL,
  `body_type` ENUM('mini', 'hatchback', 'sedan', 'SUV') NULL DEFAULT NULL,
  `engine_cc` INT NULL DEFAULT NULL,
  `fuel_type` VARCHAR(30) NULL DEFAULT NULL,
  `kilometers` INT NULL DEFAULT NULL,
  `first_registration` DATE NULL DEFAULT NULL,
  `has_turbo` TINYINT(1) NULL DEFAULT '0',
  `is_hybrid` TINYINT(1) NULL DEFAULT '0',
  `needs_repair` TINYINT(1) NULL DEFAULT '0',
  `price` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_id` (`user_id` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `webcars`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `webcars`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(30) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `first_name` VARCHAR(100) NULL DEFAULT NULL,
  `last_name` VARCHAR(100) NULL DEFAULT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NULL DEFAULT NULL,
  `activation_code` VARCHAR(10) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NULL DEFAULT '0',
  `style_pref` VARCHAR(10) NULL DEFAULT 'light',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username` (`username` ASC) ,
  UNIQUE INDEX `email` (`email` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8mb4;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
