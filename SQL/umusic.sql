-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 02-12-2012 a las 21:53:49
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `umusic`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Album`
--

DROP TABLE IF EXISTS `Album`;
CREATE TABLE IF NOT EXISTS `Album` (
  `idAlbum` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `Artista_idArtista` int(11) NOT NULL,
  PRIMARY KEY (`idAlbum`),
  KEY `fk_Album_Artista1_idx` (`Artista_idArtista`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Artista`
--

DROP TABLE IF EXISTS `Artista`;
CREATE TABLE IF NOT EXISTS `Artista` (
  `idArtista` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idArtista`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Cancion`
--

DROP TABLE IF EXISTS `Cancion`;
CREATE TABLE IF NOT EXISTS `Cancion` (
  `idCancion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `duracion` int(11) NOT NULL,
  `contenido_binario` mediumblob NOT NULL,
  `mime` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `tamaño_bytes` int(11) NOT NULL,
  `Album_idAlbum` int(11) NOT NULL,
  `Genero_idGenero` int(11) NOT NULL,
  `Usuario_idUsuario` int(11) NOT NULL,
  PRIMARY KEY (`idCancion`),
  KEY `fk_Cancion_Album_idx` (`Album_idAlbum`),
  KEY `fk_Cancion_Genero1_idx` (`Genero_idGenero`),
  KEY `fk_Cancion_Usuario1_idx` (`Usuario_idUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Genero`
--

DROP TABLE IF EXISTS `Genero`;
CREATE TABLE IF NOT EXISTS `Genero` (
  `idGenero` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idGenero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuario`
--

DROP TABLE IF EXISTS `Usuario`;
CREATE TABLE IF NOT EXISTS `Usuario` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `contraseña` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=cuenta activa, 0=cuenta deshabilitada',
  `sesion_activa` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idUsuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `Usuario`
--

INSERT INTO `Usuario` (`idUsuario`, `nombre`, `apellido`, `email`, `contraseña`, `estado`, `sesion_activa`) VALUES
(1, 'jose', 'nobile', 'jose.nobile@gmail.com', 'c80cd33965afff788030ec2935fb9960', 1, 0),
(2, 'darlin', 'cordoba', 'dalatinrofrau@gmail.com', '08f499a2ae950f47c617ca35ee29a500', 1, 0);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Album`
--
ALTER TABLE `Album`
  ADD CONSTRAINT `fk_Album_Artista1` FOREIGN KEY (`Artista_idArtista`) REFERENCES `Artista` (`idArtista`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `Cancion`
--
ALTER TABLE `Cancion`
  ADD CONSTRAINT `fk_Cancion_Album` FOREIGN KEY (`Album_idAlbum`) REFERENCES `Album` (`idAlbum`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Cancion_Genero1` FOREIGN KEY (`Genero_idGenero`) REFERENCES `Genero` (`idGenero`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Cancion_Usuario1` FOREIGN KEY (`Usuario_idUsuario`) REFERENCES `Usuario` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
