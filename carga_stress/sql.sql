-- phpMyAdmin SQL Dump
-- version 3.5.0-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 30, 2012 at 01:28 AM
-- Server version: 5.5.16
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pruebas_rendimiento`
--
DROP DATABASE IF EXISTS `pruebas_rendimiento`;
CREATE DATABASE `pruebas_rendimiento` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `pruebas_rendimiento`;

-- --------------------------------------------------------

--
-- Table structure for table `registro_viajes`
--

DROP TABLE IF EXISTS `registro_viajes`;
CREATE TABLE IF NOT EXISTS `registro_viajes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `documento_pasajero` decimal(12,0) NOT NULL,
  `fecha_entrada` datetime NOT NULL,
  `minutos_entrada_estacion` tinyint(2) unsigned NOT NULL,
  `nombre_estacion_origen` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_estacion_destino` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=15001 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
