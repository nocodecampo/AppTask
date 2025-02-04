-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema apptask
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema apptask
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `apptask` DEFAULT CHARACTER SET utf8 ;
USE `apptask` ;

-- -----------------------------------------------------
-- Table `apptask`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `apptask`.`users` (
  `users_id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NULL,
  `password` VARCHAR(255) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `apellidos` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`users_id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `apptask`.`tasks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `apptask`.`tasks` (
  `tasks_id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` VARCHAR(255) NULL,
  `fecha_creacion` TIMESTAMP NULL,
  `estado` ENUM("En proceso", "Terminada") NULL,
  `users_id` INT NOT NULL,
  PRIMARY KEY (`tasks_id`),
  INDEX `fk_tasks_users_idx` (`users_id` ASC),
  CONSTRAINT `fk_tasks_users`
    FOREIGN KEY (`users_id`)
    REFERENCES `apptask`.`users` (`users_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
