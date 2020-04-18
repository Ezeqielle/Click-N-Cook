-- MySQL Script generated by MySQL Workbench
-- Sat Apr  4 19:19:45 2020
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`Franchisee`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Franchisee` (
  `id` INT NOT NULL,
  `social security number` VARCHAR(15) NULL,
  `driver's licence reference` VARCHAR(12) NULL,
  `active` TINYINT NULL,
  `note` INT NULL,
  `last name` VARCHAR(50) NULL,
  `first name` VARCHAR(50) NULL,
  `date of birth` DATETIME NULL,
  `email` VARCHAR(384) NULL,
  `contact number` VARCHAR(15) NULL,
  `admin` TINYINT NULL,
  `entrance fee` TINYINT NULL,
  `password` VARCHAR(500) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Purchase`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Purchase` (
  `id` INT NOT NULL,
  `date` DATETIME NULL,
  `price` FLOAT NULL,
  `#idFranchisee` INT NOT NULL,
  PRIMARY KEY (`id`, `#idFranchisee`),
  CONSTRAINT `fk_Purchase_Franchisee`
    FOREIGN KEY (`#idFranchisee`)
    REFERENCES `mydb`.`Franchisee` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Item` (
  `id` INT NOT NULL,
  `name` VARCHAR(255) NULL,
  `price` FLOAT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`ContainsIn`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`ContainsIn` (
  `#idPurchase` INT NOT NULL,
  `#iditem` INT NOT NULL,
  `quantity` INT NULL,
  PRIMARY KEY (`#iditem`, `#idPurchase`),
  CONSTRAINT `fk_Contains_Purchase1`
    FOREIGN KEY (`#idPurchase`)
    REFERENCES `mydb`.`Purchase` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Contains_Item1`
    FOREIGN KEY (`#iditem`)
    REFERENCES `mydb`.`Item` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Warehouse`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Warehouse` (
  `id` INT NOT NULL,
  `latitude` FLOAT NULL,
  `lontitude` FLOAT NULL,
  `address` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`BelongIn`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`BelongIn` (
  `#idWarehouse` INT NOT NULL,
  `#idItem` INT NOT NULL,
  `quantity` INT NULL,
  PRIMARY KEY (`#idWarehouse`, `#idItem`),
  CONSTRAINT `fk_table3_Warehouse1`
    FOREIGN KEY (`#idWarehouse`)
    REFERENCES `mydb`.`Warehouse` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table3_Item1`
    FOREIGN KEY (`#idItem`)
    REFERENCES `mydb`.`Item` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Franchise`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Franchise` (
  `number` INT NOT NULL,
  `date` DATETIME NULL,
  `#idFranchisee` INT NOT NULL,
  PRIMARY KEY (`number`, `#idFranchisee`),
  CONSTRAINT `fk_Franchise_Franchisee1`
    FOREIGN KEY (`#idFranchisee`)
    REFERENCES `mydb`.`Franchisee` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Truck`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Truck` (
  `id` INT NOT NULL,
  `location` VARCHAR(255) NULL,
  `car registration document` VARCHAR(15) NULL,
  `#idFranchisee` INT NOT NULL,
  PRIMARY KEY (`id`, `#idFranchisee`),
  CONSTRAINT `fk_Truck_Franchisee1`
    FOREIGN KEY (`#idFranchisee`)
    REFERENCES `mydb`.`Franchisee` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Maintenance`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Maintenance` (
  `id` INT NOT NULL,
  `date` DATETIME NULL,
  `description` TEXT NULL,
  `garage name` VARCHAR(255) NULL,
  `age` INT NULL,
  `mileage` FLOAT NULL,
  `price` FLOAT NULL,
  `#idTruck` INT NOT NULL,
  PRIMARY KEY (`id`, `#idTruck`),
  CONSTRAINT `fk_Maintenance_Truck1`
    FOREIGN KEY (`#idTruck`)
    REFERENCES `mydb`.`Truck` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Item of truck`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Item of truck` (
  `id` INT NOT NULL,
  `name` VARCHAR(255) NULL,
  `quantity` INT NULL,
  `#idTruck` INT NOT NULL,
  PRIMARY KEY (`id`, `#idTruck`),
  CONSTRAINT `fk_Item of truck_Truck1`
    FOREIGN KEY (`#idTruck`)
    REFERENCES `mydb`.`Truck` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Breakdown`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Breakdown` (
  `id` INT NOT NULL,
  `date` DATETIME NULL,
  `description` TEXT NULL,
  `repaired` TINYINT NULL,
  `price` FLOAT NULL,
  `#idTruck` INT NOT NULL,
  PRIMARY KEY (`id`, `#idTruck`),
  CONSTRAINT `fk_Breakdown_Truck1`
    FOREIGN KEY (`#idTruck`)
    REFERENCES `mydb`.`Truck` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Purchase out of warehouse`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Purchase out of warehouse` (
  `id` INT NOT NULL,
  `date` DATETIME NULL,
  `price` FLOAT NULL,
  `#idFranchisee` INT NOT NULL,
  PRIMARY KEY (`id`, `#idFranchisee`),
  CONSTRAINT `fk_Purchase out of warehouse_Franchisee1`
    FOREIGN KEY (`#idFranchisee`)
    REFERENCES `mydb`.`Franchisee` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Item out of warehouse`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Item out of warehouse` (
  `id` INT NOT NULL,
  `name` VARCHAR(255) NULL,
  `price` FLOAT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`ContainsOut`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`ContainsOut` (
  `#idPurchase` INT NOT NULL,
  `#idItem` INT NOT NULL,
  `quantity` INT NULL,
  PRIMARY KEY (`#idPurchase`, `#idItem`),
  CONSTRAINT `fk_table2_Purchase out of warehouse1`
    FOREIGN KEY (`#idPurchase`)
    REFERENCES `mydb`.`Purchase out of warehouse` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Contains_Item out of warehouse1`
    FOREIGN KEY (`#idItem`)
    REFERENCES `mydb`.`Item out of warehouse` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Out of warehouse`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Out of warehouse` (
  `id` INT NOT NULL,
  `latitude` FLOAT NULL,
  `longitude` FLOAT NULL,
  `address` VARCHAR(255) NULL,
  `name` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`BelongOut`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`BelongOut` (
  `#idItem` INT NOT NULL,
  `#idOut` INT NOT NULL,
  PRIMARY KEY (`#idItem`, `#idOut`),
  CONSTRAINT `fk_table4_Out of warehouse1`
    FOREIGN KEY (`#idItem`)
    REFERENCES `mydb`.`Out of warehouse` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table4_Item out of warehouse1`
    FOREIGN KEY (`#idOut`)
    REFERENCES `mydb`.`Item out of warehouse` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Sale`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Sale` (
  `id` INT NOT NULL,
  `date` DATETIME NULL,
  `price` FLOAT NULL,
  `#idFranchisee` INT NOT NULL,
  PRIMARY KEY (`id`, `#idFranchisee`),
  CONSTRAINT `fk_Sale_Franchisee1`
    FOREIGN KEY (`#idFranchisee`)
    REFERENCES `mydb`.`Franchisee` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Menu`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Menu` (
  `id` INT NOT NULL,
  `name` VARCHAR(255) NULL,
  `price` FLOAT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`ContainsMenuSale`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`ContainsMenuSale` (
  `#idMenu` INT NOT NULL,
  `#idSale` INT NOT NULL,
  `quantity` INT NULL,
  PRIMARY KEY (`#idMenu`, `#idSale`),
  CONSTRAINT `fk_Contains_Menu2`
    FOREIGN KEY (`#idMenu`)
    REFERENCES `mydb`.`Menu` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Contains_Sale2`
    FOREIGN KEY (`#idSale`)
    REFERENCES `mydb`.`Sale` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Dish`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Dish` (
  `id` INT NOT NULL,
  `name` VARCHAR(255) NULL,
  `price` FLOAT NULL,
  `type` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`ContainsDishMenu`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`ContainsDishMenu` (
  `#idDish` INT NOT NULL,
  `#idMenu` INT NOT NULL,
  `quantity` INT NULL,
  PRIMARY KEY (`#idDish`, `#idMenu`),
  CONSTRAINT `fk_Contains_Dish2`
    FOREIGN KEY (`#idDish`)
    REFERENCES `mydb`.`Dish` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Contains_Menu1`
    FOREIGN KEY (`#idMenu`)
    REFERENCES `mydb`.`Menu` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Ingredient`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Ingredient` (
  `id` INT NOT NULL,
  `name` VARCHAR(255) NULL,
  `quantity` FLOAT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`ContainsIngredientsDish`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`ContainsIngredientsDish` (
  `#idIngredient` INT NOT NULL,
  `#idDish` INT NOT NULL,
  `quantity` INT NULL,
  PRIMARY KEY (`#idIngredient`, `#idDish`),
  CONSTRAINT `fk_Contains_Ingredient1`
    FOREIGN KEY (`#idIngredient`)
    REFERENCES `mydb`.`Ingredient` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Contains_Dish1`
    FOREIGN KEY (`#idDish`)
    REFERENCES `mydb`.`Dish` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`ContainsDishSale`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`ContainsDishSale` (
  `#idDish` INT NOT NULL,
  `#idSale` INT NOT NULL,
  `quantity` INT NULL,
  PRIMARY KEY (`#idDish`, `#idSale`),
  CONSTRAINT `fk_Contains_Dish3`
    FOREIGN KEY (`#idDish`)
    REFERENCES `mydb`.`Dish` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Contains_Sale1`
    FOREIGN KEY (`#idSale`)
    REFERENCES `mydb`.`Sale` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
