-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

SET TIME_ZONE='-03:00';

-- Volcando estructura de base de datos para sistema
CREATE DATABASE IF NOT EXISTS `sistema` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sistema`;

-- Volcando estructura para tabla sistema.alumnos
CREATE TABLE IF NOT EXISTS `alumnos` (
  `id_alumno` int NOT NULL AUTO_INCREMENT,
  `dni_alumno` int NOT NULL,
  `nombre` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `apellido` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `nacimiento` date NOT NULL,
  PRIMARY KEY (`id_alumno`) USING BTREE,
  UNIQUE KEY `unique_dni` (`dni_alumno`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla sistema.asistencias
CREATE TABLE IF NOT EXISTS `asistencias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dni` int NOT NULL,
  `fecha` timestamp NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla sistema.parametros
CREATE TABLE IF NOT EXISTS `parametros` (
  `cant_dias` int DEFAULT '1',
  `porcentaje_promocion` int DEFAULT '70',
  `porcentaje_regular` int DEFAULT '50'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO parametros (cant_dias,porcentaje_promocion,porcentaje_regular)VALUES (1,70,50);

INSERT INTO `alumnos` (`id_alumno`, `dni_alumno`, `nombre`, `apellido`, `nacimiento`) VALUES
	(1, 40790201, 'Esteban', 'Copello', '1998-02-13'),
	(2, 42850626, 'Lucas', 'Barreiro', '2000-10-04'),
  (3, 45847922, 'Franco', 'Cabrera', '1999-11-12'),
  (4, 43149316, 'Franco Agustin', 'Chappe', '1997-09-14'),
  (5, 43632750, 'Roman', 'Coletti', '1995-01-23'),
  (6, 40790545, 'Daian Exequiel', 'Fernandez', '1996-10-10'),
  (7, 44980999, 'Nicolas Osvaldo', 'Fernandez', '2000-11-25'),
  (8, 44623314, 'Facundo Geronimo', 'Figun', '1999-05-15'),
  (9, 45389325, 'Lucas Jeremias', 'Fiorotto', '1991-06-12'),
  (10, 45048325, 'Felipe', 'Franco', '2000-05-05'),
  (11, 43631803, 'Bruno', 'Godoy', '1989-11-20'),
  (12, 42069298, 'Marcos Damian', 'Godoy', '1997-01-01'),
  (13, 45385675, 'Teo', 'Hildt', '1990-02-21'),
  (14, 41872676, 'Facundo Ariel', 'Janusa', '2001-10-21'),
  (15, 45048950, 'Facundo Martin', 'Jara', '2000-05-04'),
  (16, 45387761, 'Santiago Nicolas', 'Martinez Bender', '1999-09-19'),
  (17, 45741185, 'Pablo Federico', 'Martinez', '1995-08-14'),
  (18, 44981059, 'Federico Jose', 'Martinolich', '1999-04-22'),
  (19, 42070085, 'Maria Pia', 'Melgarejo', '1994-05-06'),
  (20, 43631710, 'Thiago Jeremias', 'Meseguer', '1996-12-21'),
  (21, 44644523, 'Ignacio Agustin', 'Piter', '1992-06-05'),
  (22, 44282007, 'Bianca Ariana', 'Quiroga', '1991-06-08'),
  (23, 40018598, 'Kevin Gustavo', 'Quiroga', '1997-02-20'),
  (24, 38570361, 'Marcos', 'Reynoso', '1993-03-02'),
  (25, 39255959, 'Franco Antonio', 'Robles', '1998-04-21'),
  (26, 43414566, 'Maximiliano', 'Weyler', '1995-05-20');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
