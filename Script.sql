-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 29, 2020 at 12:47 PM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `belongin`
--

DROP TABLE IF EXISTS `belongin`;
CREATE TABLE IF NOT EXISTS `belongin` (
  `idWarehouse` int(11) NOT NULL,
  `idItem` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idWarehouse`,`idItem`),
  KEY `fk_table3_Item1` (`idItem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `belongin`
--

INSERT INTO `belongin` (`idWarehouse`, `idItem`, `quantity`) VALUES
(1, 22, 55),
(1, 23, 5),
(1, 24, 5);

-- --------------------------------------------------------

--
-- Table structure for table `belongout`
--

DROP TABLE IF EXISTS `belongout`;
CREATE TABLE IF NOT EXISTS `belongout` (
  `idItem` int(11) NOT NULL,
  `idOut` int(11) NOT NULL,
  PRIMARY KEY (`idItem`,`idOut`),
  KEY `fk_table4_ItemOutOfWarehouse1` (`idOut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `breakdown`
--

DROP TABLE IF EXISTS `breakdown`;
CREATE TABLE IF NOT EXISTS `breakdown` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `description` text,
  `repaired` tinyint(4) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `idTruck` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idTruck`),
  KEY `fk_Breakdown_Truck1` (`idTruck`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `containsdishmenu`
--

DROP TABLE IF EXISTS `containsdishmenu`;
CREATE TABLE IF NOT EXISTS `containsdishmenu` (
  `idDish` int(11) NOT NULL,
  `idMenu` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDish`,`idMenu`),
  KEY `fk_Contains_Menu1` (`idMenu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `containsdishsale`
--

DROP TABLE IF EXISTS `containsdishsale`;
CREATE TABLE IF NOT EXISTS `containsdishsale` (
  `idDish` int(11) NOT NULL,
  `idSale` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDish`,`idSale`),
  KEY `fk_Contains_Sale1` (`idSale`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `containsin`
--

DROP TABLE IF EXISTS `containsin`;
CREATE TABLE IF NOT EXISTS `containsin` (
  `idPurchase` int(11) NOT NULL,
  `iditem` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `idWarehouse` int(11) NOT NULL,
  PRIMARY KEY (`iditem`,`idPurchase`),
  KEY `fk_Contains_Purchase1` (`idPurchase`) USING BTREE,
  KEY `fk_Contains_Item1` (`iditem`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `containsin`
--

INSERT INTO `containsin` (`idPurchase`, `iditem`, `quantity`, `idWarehouse`) VALUES
(90, 23, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `containsingredientsdish`
--

DROP TABLE IF EXISTS `containsingredientsdish`;
CREATE TABLE IF NOT EXISTS `containsingredientsdish` (
  `idIngredient` int(11) NOT NULL,
  `idDish` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idIngredient`,`idDish`),
  KEY `fk_Contains_Dish1` (`idDish`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `containsmenusale`
--

DROP TABLE IF EXISTS `containsmenusale`;
CREATE TABLE IF NOT EXISTS `containsmenusale` (
  `idMenu` int(11) NOT NULL,
  `idSale` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idMenu`,`idSale`),
  KEY `fk_Contains_Sale2` (`idSale`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `containsout`
--

DROP TABLE IF EXISTS `containsout`;
CREATE TABLE IF NOT EXISTS `containsout` (
  `idPurchase` int(11) NOT NULL,
  `idItem` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPurchase`,`idItem`),
  KEY `fk_Contains_ItemOutOfWarehouse1` (`idItem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dish`
--

DROP TABLE IF EXISTS `dish`;
CREATE TABLE IF NOT EXISTS `dish` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `franchise`
--

DROP TABLE IF EXISTS `franchise`;
CREATE TABLE IF NOT EXISTS `franchise` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `idFranchisee` int(11) NOT NULL,
  PRIMARY KEY (`number`,`idFranchisee`),
  KEY `fk_Franchise_Franchisee1` (`idFranchisee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `franchisee`
--

DROP TABLE IF EXISTS `franchisee`;
CREATE TABLE IF NOT EXISTS `franchisee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `social security number` varchar(15) DEFAULT NULL,
  `driver's licence reference` varchar(12) DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL,
  `note` int(11) DEFAULT NULL,
  `last name` varchar(50) DEFAULT NULL,
  `first name` varchar(50) DEFAULT NULL,
  `date of birth` datetime DEFAULT NULL,
  `email` varchar(384) DEFAULT NULL,
  `contact number` varchar(15) DEFAULT NULL,
  `admin` tinyint(4) DEFAULT NULL,
  `entrance fee` tinyint(4) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `franchisee`
--

INSERT INTO `franchisee` (`id`, `social security number`, `driver's licence reference`, `active`, `note`, `last name`, `first name`, `date of birth`, `email`, `contact number`, `admin`, `entrance fee`, `password`) VALUES
(10, '012345678912345', '012345678912', 123, NULL, 'geoffrey', 'lavigne', '2000-10-02 00:00:00', 'lavigne.geoff@gmail.com', '0673386786', 0, 1, 'coucou');

-- --------------------------------------------------------

--
-- Table structure for table `ingredient`
--

DROP TABLE IF EXISTS `ingredient`;
CREATE TABLE IF NOT EXISTS `ingredient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `product_status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `name`, `price`, `product_status`) VALUES
(22, 'coca', 0.000001, 0),
(23, 'ice tea', 5, 1),
(24, 'the', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `itemOfTruck`
--

DROP TABLE IF EXISTS `itemOfTruck`;
CREATE TABLE IF NOT EXISTS `itemOfTruck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `idTruck` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idTruck`),
  KEY `fk_ItemOfTruck_Truck1` (`idTruck`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `itemOutOfWarehouse`
--

DROP TABLE IF EXISTS `itemOutOfWarehouse`;
CREATE TABLE IF NOT EXISTS `itemOutOfWarehouse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

DROP TABLE IF EXISTS `maintenance`;
CREATE TABLE IF NOT EXISTS `maintenance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `description` text,
  `garage name` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `mileage` float DEFAULT NULL,
  `price` float DEFAULT NULL,
  `idTruck` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idTruck`),
  KEY `fk_Maintenance_Truck1` (`idTruck`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `outOfWarehouse`
--

DROP TABLE IF EXISTS `outOfWarehouse`;
CREATE TABLE IF NOT EXISTS `outOfWarehouse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

DROP TABLE IF EXISTS `purchase`;
CREATE TABLE IF NOT EXISTS `purchase` (
  `bill_number` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `price` float DEFAULT NULL,
  `idFranchisee` int(11) NOT NULL,
  PRIMARY KEY (`bill_number`) USING BTREE,
  KEY `fk_Purchase_Franchisee` (`idFranchisee`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`bill_number`, `date`, `price`, `idFranchisee`) VALUES
(90, NULL, NULL, 10);

-- --------------------------------------------------------

--
-- Table structure for table `purchaseOutOfWarehouse`
--

DROP TABLE IF EXISTS `purchaseOutOfWarehouse`;
CREATE TABLE IF NOT EXISTS `purchaseOutOfWarehouse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `price` float DEFAULT NULL,
  `idFranchisee` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idFranchisee`),
  KEY `fk_PurchaseOutOfWarehouse_Franchisee1` (`idFranchisee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

DROP TABLE IF EXISTS `sale`;
CREATE TABLE IF NOT EXISTS `sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `price` float DEFAULT NULL,
  `idFranchisee` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idFranchisee`),
  KEY `fk_Sale_Franchisee1` (`idFranchisee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `truck`
--

DROP TABLE IF EXISTS `truck`;
CREATE TABLE IF NOT EXISTS `truck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(255) DEFAULT NULL,
  `car registration document` varchar(15) DEFAULT NULL,
  `idFranchisee` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idFranchisee`),
  KEY `fk_Truck_Franchisee1` (`idFranchisee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

DROP TABLE IF EXISTS `warehouse`;
CREATE TABLE IF NOT EXISTS `warehouse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `latitude` float DEFAULT NULL,
  `lontitude` float DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`id`, `latitude`, `lontitude`, `address`) VALUES
(1, 52, 68, '2485 gutvdshjq');

-- --------------------------------------------------------

--
-- Table structure for table `creditCard`
--

DROP TABLE IF EXISTS `creditCard`;
CREATE TABLE IF NOT EXISTS `creditCard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(16) DEFAULT NULL,
  `date` varchar(5) DEFAULT NULL,
  `cvc` varchar(3) DEFAULT NULL,
  `holder` varchar(50) DEFAULT NULL,
  `idFranchisee` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idFranchisee`),
  KEY `fk_creditCard_Franchisee1` (`idFranchisee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastName` varchar(50) DEFAULT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `email` varchar(384) DEFAULT NULL,
  `pwd` varchar(500) DEFAULT NULL,
  `contactNumber` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL,
  `advantage` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `creditCardClient`
--

DROP TABLE IF EXISTS `creditCardClient`;
CREATE TABLE IF NOT EXISTS `creditCardClient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(16) DEFAULT NULL,
  `date` varchar(5) DEFAULT NULL,
  `cvc` varchar(3) DEFAULT NULL,
  `holder` varchar(50) DEFAULT NULL,
  `idClient` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idClient`),
  KEY `fk_creditCardClient_Client1` (`idClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `idFranchisee` int(11) NOT NULL,
  `idClient` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idFranchisee`,`idClient`),
  KEY `fk_event_Franchisee1` (`idFranchisee`),
  KEY `fk_event_Client1` (`idClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- --------------------------------------------------------

--
-- Table structure for table `purchaseClient`
--

DROP TABLE IF EXISTS `purchaseClient`;
CREATE TABLE IF NOT EXISTS `purchaseClient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `price` float DEFAULT NULL,
  `idClient` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idClient`),
  KEY `fk_purchaseClient_Client1` (`idClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dishClient`
--

DROP TABLE IF EXISTS `dishClient`;
CREATE TABLE IF NOT EXISTS `dishClient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `containsdishmenuClient`
--

DROP TABLE IF EXISTS `containsdishmenuClient`;
CREATE TABLE IF NOT EXISTS `containsdishmenuClient` (
  `idDishClient` int(11) NOT NULL,
  `idMenuClient` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDishClient`,`idMenuClient`),
  KEY `fk_Contains_MenuClient1` (`idMenuClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `containsingredientsdishClient`
--

DROP TABLE IF EXISTS `containsingredientsdishClient`;
CREATE TABLE IF NOT EXISTS `containsingredientsdishClient` (
  `idIngredientClient` int(11) NOT NULL,
  `idDishClient` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idIngredientClient`,`idDishClient`),
  KEY `fk_Contains_DishClient1` (`idDishClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `containsdishsaleClient`
--

DROP TABLE IF EXISTS `containsdishsaleClient`;
CREATE TABLE IF NOT EXISTS `containsdishsaleClient` (
  `idDishClient` int(11) NOT NULL,
  `idPurchaseClient` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDishClient`,`idPurchaseClient`),
  KEY `fk_Contains_SaleClient1` (`idPurchaseClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `containsmenusaleClient`
--

DROP TABLE IF EXISTS `containsmenusaleClient`;
CREATE TABLE IF NOT EXISTS `containsmenusaleClient` (
  `idMenuClient` int(11) NOT NULL,
  `idPurchaseClient` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idMenuClient`,`idPurchaseClient`),
  KEY `fk_Contains_SaleClient1` (`idPurchaseClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menuClient`
--

DROP TABLE IF EXISTS `menuClient`;
CREATE TABLE IF NOT EXISTS `menuClient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ingredientClient`
--

DROP TABLE IF EXISTS `ingredientClient`;
CREATE TABLE IF NOT EXISTS `ingredientClient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `belongin`
--
ALTER TABLE `belongin`
  ADD CONSTRAINT `fk_table3_Item1` FOREIGN KEY (`idItem`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_table3_Warehouse1` FOREIGN KEY (`idWarehouse`) REFERENCES `warehouse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `belongout`
--
ALTER TABLE `belongout`
  ADD CONSTRAINT `fk_table4_ItemOutOfWarehouse1` FOREIGN KEY (`idOut`) REFERENCES `itemOutOfWarehouse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_table4_OutOfWarehouse1` FOREIGN KEY (`idItem`) REFERENCES `outOfWarehouse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `breakdown`
--
ALTER TABLE `breakdown`
  ADD CONSTRAINT `fk_Breakdown_Truck1` FOREIGN KEY (`idTruck`) REFERENCES `truck` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `containsdishmenu`
--
ALTER TABLE `containsdishmenu`
  ADD CONSTRAINT `fk_Contains_Dish2` FOREIGN KEY (`idDish`) REFERENCES `dish` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Contains_Menu1` FOREIGN KEY (`idMenu`) REFERENCES `menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `containsdishmenuClient`
--
ALTER TABLE `containsdishmenuClient`
  ADD CONSTRAINT `fk_Contains_DishClient2` FOREIGN KEY (`idDishClient`) REFERENCES `dishClient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Contains_MenuClient1` FOREIGN KEY (`idMenuClient`) REFERENCES `menuClient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `containsdishsale`
--
ALTER TABLE `containsdishsale`
  ADD CONSTRAINT `fk_Contains_Dish3` FOREIGN KEY (`idDish`) REFERENCES `dish` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Contains_Sale1` FOREIGN KEY (`idSale`) REFERENCES `sale` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `containsdishsaleClient`
--
ALTER TABLE `containsdishsaleClient`
  ADD CONSTRAINT `fk_Contains_DishClient3` FOREIGN KEY (`idDishClient`) REFERENCES `dishClient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Contains_PurchaseClient1` FOREIGN KEY (`idPurchaseClient`) REFERENCES `purchaseClient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `containsin`
--
ALTER TABLE `containsin`
  ADD CONSTRAINT `fk_Contains_Item1` FOREIGN KEY (`iditem`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Contains_Purchase1` FOREIGN KEY (`idPurchase`) REFERENCES `purchase` (`bill_number`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `containsingredientsdish`
--
ALTER TABLE `containsingredientsdish`
  ADD CONSTRAINT `fk_Contains_Dish1` FOREIGN KEY (`idDish`) REFERENCES `dish` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Contains_Ingredient1` FOREIGN KEY (`idIngredient`) REFERENCES `ingredient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `containsingredientsdishClient`
--
ALTER TABLE `containsingredientsdishClient`
  ADD CONSTRAINT `fk_Contains_DishClient1` FOREIGN KEY (`idDishClient`) REFERENCES `dishClient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Contains_IngredientClient1` FOREIGN KEY (`idIngredientClient`) REFERENCES `ingredientClient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `containsmenusaleClient`
--
ALTER TABLE `containsmenusaleClient`
  ADD CONSTRAINT `fk_Contains_MenuClient2` FOREIGN KEY (`idMenuClient`) REFERENCES `menuClient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Contains_PurchaseClient2` FOREIGN KEY (`idPurchaseClient`) REFERENCES `purchaseClient` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `containsmenusale`
--
ALTER TABLE `containsmenusale`
  ADD CONSTRAINT `fk_Contains_Menu2` FOREIGN KEY (`idMenu`) REFERENCES `menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Contains_Sale2` FOREIGN KEY (`idSale`) REFERENCES `sale` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `containsout`
--
ALTER TABLE `containsout`
  ADD CONSTRAINT `fk_Contains_ItemOutOfWarehouse1` FOREIGN KEY (`idItem`) REFERENCES `itemOutOfWarehouse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_table2_PurchaseOutOfWarehouse1` FOREIGN KEY (`idPurchase`) REFERENCES `purchaseOutOfWarehouse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `franchise`
--
ALTER TABLE `franchise`
  ADD CONSTRAINT `fk_Franchise_Franchisee1` FOREIGN KEY (`idFranchisee`) REFERENCES `franchisee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `itemOfTruck`
--
ALTER TABLE `itemOfTruck`
  ADD CONSTRAINT `fk_ItemOfTruck_Truck1` FOREIGN KEY (`idTruck`) REFERENCES `truck` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD CONSTRAINT `fk_Maintenance_Truck1` FOREIGN KEY (`idTruck`) REFERENCES `truck` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `fk_Purchase_Franchisee` FOREIGN KEY (`idFranchisee`) REFERENCES `franchisee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `purchaseOutOfWarehouse`
--
ALTER TABLE `purchaseOutOfWarehouse`
  ADD CONSTRAINT `fk_PurchaseOutOfWarehouse_Franchisee1` FOREIGN KEY (`idFranchisee`) REFERENCES `franchisee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `sale`
--
ALTER TABLE `sale`
  ADD CONSTRAINT `fk_Sale_Franchisee1` FOREIGN KEY (`idFranchisee`) REFERENCES `franchisee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `purchaseClient`
--
ALTER TABLE `purchaseClient`
  ADD CONSTRAINT `fk_PurchaseClient_Client1` FOREIGN KEY (`idClient`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `truck`
--
ALTER TABLE `truck`
  ADD CONSTRAINT `fk_Truck_Franchisee1` FOREIGN KEY (`idFranchisee`) REFERENCES `franchisee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;