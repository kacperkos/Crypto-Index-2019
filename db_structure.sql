-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- MySQL version: 5.7.36
-- PHP version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Structure for table `crypto`
--

DROP TABLE IF EXISTS `crypto`;
CREATE TABLE IF NOT EXISTS `crypto` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cmc_id` int(10) NOT NULL,
  `name` char(255) COLLATE utf8_polish_ci NOT NULL,
  `symbol` char(255) COLLATE utf8_polish_ci NOT NULL,
  `price` double NOT NULL,
  `percent_change_1h` double DEFAULT NULL,
  `percent_change_24h` double DEFAULT NULL,
  `percent_change_7d` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`cmc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Structure for table `invest`
--

DROP TABLE IF EXISTS `invest`;
CREATE TABLE IF NOT EXISTS `invest` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `crypto_id` int(10) NOT NULL,
  `quantity` double NOT NULL,
  `market` char(255) COLLATE utf8_polish_ci NOT NULL,
  `exchange_pln` double NOT NULL,
  `value_pln` double NOT NULL,
  `exchange_usdpln` double NOT NULL,
  `exchange_usd` double NOT NULL,
  `value_usd` double NOT NULL,
  `comments` char(255) COLLATE utf8_polish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
