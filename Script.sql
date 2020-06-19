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
-- Database: `clickncook`
--

-- --------------------------------------------------------
	
--
-- Table structure for table `BELONGIN`
--

DROP TABLE IF EXISTS `BELONGIN`;
CREATE TABLE IF NOT EXISTS `BELONGIN` (
  `idWarehouse` int(11) NOT NULL,
  `idItem` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idWarehouse`,`idItem`),
  KEY `fk_table3_ITEM1` (`idItem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `BELONGIN`
--

INSERT INTO `BELONGIN` (`idWarehouse`, `idItem`, `quantity`) VALUES
(1, 22, 55),
(1, 23, 5),
(1, 24, 5);

-- --------------------------------------------------------

--
-- Table structure for table `BELONGOUT`
--

DROP TABLE IF EXISTS `BELONGOUT`;
CREATE TABLE IF NOT EXISTS `BELONGOUT` (
  `idItem` int(11) NOT NULL,
  `idOut` int(11) NOT NULL,
  PRIMARY KEY (`idItem`,`idOut`),
  KEY `fk_table4_ITEMOUTOFWAREHOUSE1` (`idOut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `BREAKDOWN`
--

DROP TABLE IF EXISTS `BREAKDOWN`;
CREATE TABLE IF NOT EXISTS `BREAKDOWN` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `description` text,
  `repaired` tinyint(4) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `idTruck` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idTruck`),
  KEY `fk_Breakdown_TRUCK1` (`idTruck`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CONTAINSDISHMENU`
--

DROP TABLE IF EXISTS `CONTAINSDISHMENU`;
CREATE TABLE IF NOT EXISTS `CONTAINSDISHMENU` (
  `idDish` int(11) NOT NULL,
  `idMenu` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDish`,`idMenu`),
  KEY `fk_Contains_MENU1` (`idMenu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CONTAINSDISHSALE`
--

DROP TABLE IF EXISTS `CONTAINSDISHSALE`;
CREATE TABLE IF NOT EXISTS `CONTAINSDISHSALE` (
  `idDish` int(11) NOT NULL,
  `idSale` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDish`,`idSale`),
  KEY `fk_Contains_SALE1` (`idSale`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CONTAINSIN`
--

DROP TABLE IF EXISTS `CONTAINSIN`;
CREATE TABLE IF NOT EXISTS `CONTAINSIN` (
  `idPurchase` int(11) NOT NULL,
  `iditem` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `idWarehouse` int(11) NOT NULL,
  PRIMARY KEY (`iditem`,`idPurchase`),
  KEY `fk_Contains_PURCHASE1` (`idPurchase`) USING BTREE,
  KEY `fk_Contains_ITEM1` (`iditem`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `CONTAINSIN`
--

INSERT INTO `CONTAINSIN` (`idPurchase`, `iditem`, `quantity`, `idWarehouse`) VALUES
(90, 23, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `CONTAINSINGREDIENTSDISH`
--

DROP TABLE IF EXISTS `CONTAINSINGREDIENTSDISH`;
CREATE TABLE IF NOT EXISTS `CONTAINSINGREDIENTSDISH` (
  `idIngredient` int(11) NOT NULL,
  `idDish` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idIngredient`,`idDish`),
  KEY `fk_Contains_DISH1` (`idDish`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CONTAINSMENUSALE`
--

DROP TABLE IF EXISTS `CONTAINSMENUSALE`;
CREATE TABLE IF NOT EXISTS `CONTAINSMENUSALE` (
  `idMenu` int(11) NOT NULL,
  `idSale` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idMenu`,`idSale`),
  KEY `fk_Contains_SALE2` (`idSale`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CONTAINSOUT`
--

DROP TABLE IF EXISTS `CONTAINSOUT`;
CREATE TABLE IF NOT EXISTS `CONTAINSOUT` (
  `idPurchase` int(11) NOT NULL,
  `idItem` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPurchase`,`idItem`),
  KEY `fk_Contains_ITEMOUTOFWAREHOUSE1` (`idItem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `DISH`
--

DROP TABLE IF EXISTS `DISH`;
CREATE TABLE IF NOT EXISTS `DISH` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `FRANCHISE`
--

DROP TABLE IF EXISTS `FRANCHISE`;
CREATE TABLE IF NOT EXISTS `FRANCHISE` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `idFranchisee` int(11) NOT NULL,
  PRIMARY KEY (`number`,`idFranchisee`),
  KEY `fk_Franchise_FRANCHISEE1` (`idFranchisee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `FRANCHISEE`
--

DROP TABLE IF EXISTS `FRANCHISEE`;
CREATE TABLE IF NOT EXISTS `FRANCHISEE` (
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
-- Dumping data for table `FRANCHISEE`
--

INSERT INTO `FRANCHISEE` (`id`, `social security number`, `driver's licence reference`, `active`, `note`, `last name`, `first name`, `date of birth`, `email`, `contact number`, `admin`, `entrance fee`, `password`) VALUES
(10, '012345678912345', '012345678912', 123, NULL, 'geoffrey', 'lavigne', '2000-10-02 00:00:00', 'lavigne.geoff@gmail.com', '0673386786', 0, 1, 'coucou');

-- --------------------------------------------------------

--
-- Table structure for table `INGREDIENT`
--

DROP TABLE IF EXISTS `INGREDIENT`;
CREATE TABLE IF NOT EXISTS `INGREDIENT` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ITEM`
--

DROP TABLE IF EXISTS `ITEM`;
CREATE TABLE IF NOT EXISTS `ITEM` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `product_status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ITEM`
--

INSERT INTO `ITEM` (`id`, `name`, `price`, `product_status`) VALUES
(22, 'coca', 0.000001, 0),
(23, 'ice tea', 5, 1),
(24, 'the', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ITEMOFTRUCK`
--

DROP TABLE IF EXISTS `ITEMOFTRUCK`;
CREATE TABLE IF NOT EXISTS `ITEMOFTRUCK` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `idTruck` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idTruck`),
  KEY `fk_ITEMOFTRUCK_TRUCK1` (`idTruck`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ITEMOUTOFWAREHOUSE`
--

DROP TABLE IF EXISTS `ITEMOUTOFWAREHOUSE`;
CREATE TABLE IF NOT EXISTS `ITEMOUTOFWAREHOUSE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `MAINTENANCE`
--

DROP TABLE IF EXISTS `MAINTENANCE`;
CREATE TABLE IF NOT EXISTS `MAINTENANCE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `description` text,
  `garage name` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `mileage` float DEFAULT NULL,
  `price` float DEFAULT NULL,
  `idTruck` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idTruck`),
  KEY `fk_MAINTENANCE_TRUCK1` (`idTruck`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `MENU`
--

DROP TABLE IF EXISTS `MENU`;
CREATE TABLE IF NOT EXISTS `MENU` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `OUTOFWAREHOUSE`
--

DROP TABLE IF EXISTS `OUTOFWAREHOUSE`;
CREATE TABLE IF NOT EXISTS `OUTOFWAREHOUSE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `PURCHASE`
--

DROP TABLE IF EXISTS `PURCHASE`;
CREATE TABLE IF NOT EXISTS `PURCHASE` (
  `bill_number` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `price` float DEFAULT NULL,
  `idFranchisee` int(11) NOT NULL,
  PRIMARY KEY (`bill_number`) USING BTREE,
  KEY `fk_PURCHASE_FRANCHISEE` (`idFranchisee`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PURCHASE`
--

INSERT INTO `PURCHASE` (`bill_number`, `date`, `price`, `idFranchisee`) VALUES
(90, NULL, NULL, 10);

-- --------------------------------------------------------

--
-- Table structure for table `PURCHASEOUTOFWAREHOUSE`
--

DROP TABLE IF EXISTS `PURCHASEOUTOFWAREHOUSE`;
CREATE TABLE IF NOT EXISTS `PURCHASEOUTOFWAREHOUSE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `price` float DEFAULT NULL,
  `idFranchisee` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idFranchisee`),
  KEY `fk_PURCHASEOUTOFWAREHOUSE_FRANCHISEE1` (`idFranchisee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

DROP TABLE IF EXISTS `SALE`;
CREATE TABLE IF NOT EXISTS `SALE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `price` float DEFAULT NULL,
  `idFranchisee` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idFranchisee`),
  KEY `fk_Sale_FRANCHISEE1` (`idFranchisee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TRUCK`
--

DROP TABLE IF EXISTS `TRUCK`;
CREATE TABLE IF NOT EXISTS `TRUCK` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(255) DEFAULT NULL,
  `car registration document` varchar(15) DEFAULT NULL,
  `idFranchisee` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idFranchisee`),
  KEY `fk_Truck_FRANCHISEE1` (`idFranchisee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `WAREHOUSE`
--

DROP TABLE IF EXISTS `WAREHOUSE`;
CREATE TABLE IF NOT EXISTS `WAREHOUSE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `latitude` float DEFAULT NULL,
  `lontitude` float DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `WAREHOUSE`
--

INSERT INTO `WAREHOUSE` (`id`, `latitude`, `lontitude`, `address`) VALUES
(1, 52, 68, '2485 gutvdshjq');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `CLIENT`;
CREATE TABLE IF NOT EXISTS `CLIENT` (
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
-- Table structure for table `EVENT`
--

DROP TABLE IF EXISTS `EVENT`;
CREATE TABLE IF NOT EXISTS `EVENT` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `idFranchisee` int(11) NOT NULL,
  `idClient` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idFranchisee`,`idClient`),
  KEY `fk_event_FRANCHISEE1` (`idFranchisee`),
  KEY `fk_event_CLIENT1` (`idClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- --------------------------------------------------------

--
-- Table structure for table `PURCHASECLIENT`
--

DROP TABLE IF EXISTS `PURCHASECLIENT`;
CREATE TABLE IF NOT EXISTS `PURCHASECLIENT` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `price` float DEFAULT NULL,
  `idClient` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idClient`),
  KEY `fk_PURCHASECLIENT_CLIENT1` (`idClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `DISHCLIENT`
--

DROP TABLE IF EXISTS `DISHCLIENT`;
CREATE TABLE IF NOT EXISTS `DISHCLIENT` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CONTAINSDISHMENUCLIENT`
--

DROP TABLE IF EXISTS `CONTAINSDISHMENUCLIENT`;
CREATE TABLE IF NOT EXISTS `CONTAINSDISHMENUCLIENT` (
  `idDishClient` int(11) NOT NULL,
  `idMenuClient` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDishClient`,`idMenuClient`),
  KEY `fk_CONTAINS_MENUCLIENT1` (`idMenuClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CONTAINSINGREDIENTSDISHCLIENT`
--

DROP TABLE IF EXISTS `CONTAINSINGREDIENTSDISHCLIENT`;
CREATE TABLE IF NOT EXISTS `CONTAINSINGREDIENTSDISHCLIENT` (
  `idIngredientClient` int(11) NOT NULL,
  `idDishClient` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idIngredientClient`,`idDishClient`),
  KEY `fk_CONTAINS_DISHCLIENT1` (`idDishClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CONTAINSDISHSALECLIENT`
--

DROP TABLE IF EXISTS `CONTAINSDISHSALECLIENT`;
CREATE TABLE IF NOT EXISTS `CONTAINSDISHSALECLIENT` (
  `idDishClient` int(11) NOT NULL,
  `idPurchaseClient` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDishClient`,`idPurchaseClient`),
  KEY `fk_CONTAINS_SALECLIENT1` (`idPurchaseClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CONTAINSMENUSALECLIENT`
--

DROP TABLE IF EXISTS `CONTAINSMENUSALECLIENT`;
CREATE TABLE IF NOT EXISTS `CONTAINSMENUSALECLIENT` (
  `idMenuClient` int(11) NOT NULL,
  `idPurchaseClient` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`idMenuClient`,`idPurchaseClient`),
  KEY `fk_CONTAINS_SALECLIENT1` (`idPurchaseClient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `MENUCLIENT`
--

DROP TABLE IF EXISTS `MENUCLIENT`;
CREATE TABLE IF NOT EXISTS `MENUCLIENT` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `INGREDIENTCLIENT`
--

DROP TABLE IF EXISTS `INGREDIENTCLIENT`;
CREATE TABLE IF NOT EXISTS `INGREDIENTCLIENT` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `BELONGIN`
--
ALTER TABLE `BELONGIN`
  ADD CONSTRAINT `fk_TABLE3_ITEM1` FOREIGN KEY (`idItem`) REFERENCES `ITEM` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TABLE3_WAREHOUSE1` FOREIGN KEY (`idWarehouse`) REFERENCES `WAREHOUSE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `BELONGOUT`
--
ALTER TABLE `BELONGOUT`
  ADD CONSTRAINT `fk_TABLE4_ITEMOUTOFWAREHOUSE1` FOREIGN KEY (`idOut`) REFERENCES `ITEMOUTOFWAREHOUSE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TABLE4_OUTOFWAREHOUSE1` FOREIGN KEY (`idItem`) REFERENCES `OUTOFWAREHOUSE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `BREAKDOWN`
--
ALTER TABLE `BREAKDOWN`
  ADD CONSTRAINT `fk_BREAKDOWN_TRUCK1` FOREIGN KEY (`idTruck`) REFERENCES `TRUCK` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `CONTAINSDISHMENU`
--
ALTER TABLE `CONTAINSDISHMENU`
  ADD CONSTRAINT `fk_CONTAINS_DISH2` FOREIGN KEY (`idDish`) REFERENCES `DISH` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CONTAINS_MENU1` FOREIGN KEY (`idMenu`) REFERENCES `MENU` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `CONTAINSDISHMENUCLIENT`
--
ALTER TABLE `CONTAINSDISHMENUCLIENT`
  ADD CONSTRAINT `fk_CONTAINS_DISHCLIENT2` FOREIGN KEY (`idDishClient`) REFERENCES `DISHCLIENT` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CONTAINS_MENUCLIENT1` FOREIGN KEY (`idMenuClient`) REFERENCES `MENUCLIENT` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `CONTAINSDISHSALE`
--
ALTER TABLE `CONTAINSDISHSALE`
  ADD CONSTRAINT `fk_CONTAINS_DISH3` FOREIGN KEY (`idDish`) REFERENCES `DISH` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CONTAINS_SALE1` FOREIGN KEY (`idSale`) REFERENCES `SALE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `CONTAINSDISHSALECLIENT`
--
ALTER TABLE `CONTAINSDISHSALECLIENT`
  ADD CONSTRAINT `fk_CONTAINS_DISHCLIENT3` FOREIGN KEY (`idDishClient`) REFERENCES `DISHCLIENT` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CONTAINS_PURCHASECLIENT1` FOREIGN KEY (`idPurchaseClient`) REFERENCES `PURCHASECLIENT` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `CONTAINSIN`
--
ALTER TABLE `CONTAINSIN`
  ADD CONSTRAINT `fk_CONTAINS_ITEM1` FOREIGN KEY (`iditem`) REFERENCES `ITEM` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CONTAINS_PURCHASE1` FOREIGN KEY (`idPurchase`) REFERENCES `PURCHASE` (`bill_number`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `containsingredientsdish`
--
ALTER TABLE `CONTAINSINGREDIENTSDISH`
  ADD CONSTRAINT `fk_CONTAINS_DISH1` FOREIGN KEY (`idDish`) REFERENCES `DISH` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CONTAINS_INGREDIENT1` FOREIGN KEY (`idIngredient`) REFERENCES `INGREDIENT` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `CONTAINSINGREDIENTSDISHCLIENT`
--
ALTER TABLE `CONTAINSINGREDIENTSDISHCLIENT`
  ADD CONSTRAINT `fk_CONTAINS_DISHCLIENT1` FOREIGN KEY (`idDishClient`) REFERENCES `DISHCLIENT` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CONTAINS_INGREDIENTCLIENT1` FOREIGN KEY (`idIngredientClient`) REFERENCES `INGREDIENTCLIENT` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `CONTAINSMENUSALECLIENT`
--
ALTER TABLE `CONTAINSMENUSALECLIENT`
  ADD CONSTRAINT `fk_CONTAINS_MENUCLIENT2` FOREIGN KEY (`idMenuClient`) REFERENCES `MENUCLIENT` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CONTAINS_PURCHASECLIENT2` FOREIGN KEY (`idPurchaseClient`) REFERENCES `PURCHASECLIENT` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `CONTAINSMENUSALE`
--
ALTER TABLE `CONTAINSMENUSALE`
  ADD CONSTRAINT `fk_CONTAINS_MENU2` FOREIGN KEY (`idMenu`) REFERENCES `MENU` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_CONTAINS_SALE2` FOREIGN KEY (`idSale`) REFERENCES `SALE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `CONTAINSOUT`
--
ALTER TABLE `CONTAINSOUT`
  ADD CONSTRAINT `fk_CONTAINS_ITEMOUTOFWAREHOUSE1` FOREIGN KEY (`idItem`) REFERENCES `ITEMOUTOFWAREHOUSE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TABLE2_PURCHASEOUTOFWAREHOUSE1` FOREIGN KEY (`idPurchase`) REFERENCES `PURCHASEOUTOFWAREHOUSE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `FRANCHISE`
--
ALTER TABLE `FRANCHISE`
  ADD CONSTRAINT `fk_FRANCHISE_FRANCHISEE1` FOREIGN KEY (`idFranchisee`) REFERENCES `FRANCHISEE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ITEMOFTRUCK`
--
ALTER TABLE `ITEMOFTRUCK`
  ADD CONSTRAINT `fk_ITEMOFTRUCK_TRUCK1` FOREIGN KEY (`idTruck`) REFERENCES `TRUCK` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `MAINTENANCE`
--
ALTER TABLE `MAINTENANCE`
  ADD CONSTRAINT `fk_MAINTENANCE_TRUCK1` FOREIGN KEY (`idTruck`) REFERENCES `TRUCK` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `PURCHASE`
--
ALTER TABLE `PURCHASE`
  ADD CONSTRAINT `fk_PURCHASE_FRANCHISEE` FOREIGN KEY (`idFranchisee`) REFERENCES `FRANCHISEE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `PURCHASEOUTOFWAREHOUSE`
--
ALTER TABLE `PURCHASEOUTOFWAREHOUSE`
  ADD CONSTRAINT `fk_PURCHASEOUTOFWAREHOUSE_FRANCHISEE1` FOREIGN KEY (`idFranchisee`) REFERENCES `FRANCHISEE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `SALE`
--
ALTER TABLE `SALE`
  ADD CONSTRAINT `fk_SALE_FRANCHISEE1` FOREIGN KEY (`idFranchisee`) REFERENCES `FRANCHISEE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `PURCHASECLIENT`
--
ALTER TABLE `PURCHASECLIENT`
  ADD CONSTRAINT `fk_PURCHASECLIENT_CLIENT1` FOREIGN KEY (`idClient`) REFERENCES `CLIENT` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `TRUCK`
--
ALTER TABLE `TRUCK`
  ADD CONSTRAINT `fk_TRUCK_FRANCHISEE1` FOREIGN KEY (`idFranchisee`) REFERENCES `FRANCHISEE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;