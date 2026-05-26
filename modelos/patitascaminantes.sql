-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-05-2026 a las 23:27:59
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `patitascaminantes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `idAdmin` int(11) NOT NULL,
  `Nombre` varchar(15) NOT NULL,
  `Apellido` varchar(15) NOT NULL,
  `Correo` varchar(25) NOT NULL,
  `Clave` varchar(45) NOT NULL,
  `Contacto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`idAdmin`, `Nombre`, `Apellido`, `Correo`, `Clave`, `Contacto`) VALUES
(1, 'Juan', 'Ortiz', 'juan@patitas.com', 'f5737d25829e95b9c234b7fa06af8736', 300123456),
(2, 'Juliana', 'Cardenas', 'juliana@patitas.com', '70f5fb779be1312f0b2bcdcf922576c5', 301987654);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudad`
--

CREATE TABLE `ciudad` (
  `idCiudad` int(11) NOT NULL,
  `Ciudad` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ciudad`
--

INSERT INTO `ciudad` (`idCiudad`, `Ciudad`) VALUES
(1, 'Bogota'),
(2, 'Medellin'),
(3, 'Cali'),
(4, 'Cartagena'),
(5, 'Barranquilla'),
(6, 'Bucaramanga'),
(7, 'Pereira'),
(8, 'Manizales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diasemana`
--

CREATE TABLE `diasemana` (
  `idDiaSemana` int(11) NOT NULL,
  `Dia` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `diasemana`
--

INSERT INTO `diasemana` (`idDiaSemana`, `Dia`) VALUES
(1, 'Lunes'),
(2, 'Martes'),
(3, 'Miercoles'),
(4, 'Jueves'),
(5, 'Viernes'),
(6, 'Sabado'),
(7, 'Domingo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidad`
--

CREATE TABLE `disponibilidad` (
  `idDisponibilidad` int(11) NOT NULL,
  `HoraInicio` time NOT NULL,
  `HoraFin` time NOT NULL,
  `paseador_idPaseador` int(11) NOT NULL,
  `DiaSemana_idDiaSemana` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `disponibilidad`
--

INSERT INTO `disponibilidad` (`idDisponibilidad`, `HoraInicio`, `HoraFin`, `paseador_idPaseador`, `DiaSemana_idDiaSemana`) VALUES
(1, '08:00:00', '12:00:00', 1, 1),
(3, '09:00:00', '13:00:00', 2, 2),
(4, '15:00:00', '19:00:00', 2, 4),
(5, '07:00:00', '11:00:00', 3, 5),
(6, '13:00:00', '17:00:00', 3, 6),
(7, '10:00:00', '14:00:00', 4, 1),
(8, '16:00:00', '20:00:00', 4, 7),
(9, '06:00:00', '10:00:00', 5, 2),
(10, '12:00:00', '16:00:00', 5, 5),
(11, '16:00:00', '17:00:00', 1, 3),
(50, '08:00:00', '12:00:00', 50, 1),
(51, '14:00:00', '18:00:00', 50, 3),
(52, '08:00:00', '12:00:00', 50, 5),
(53, '08:00:00', '12:00:00', 51, 2),
(54, '14:00:00', '18:00:00', 51, 4),
(55, '08:00:00', '12:00:00', 51, 6),
(56, '08:00:00', '12:00:00', 52, 1),
(57, '14:00:00', '18:00:00', 52, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dueño`
--

CREATE TABLE `dueño` (
  `idDueño` int(11) NOT NULL,
  `NroDocumento` char(12) NOT NULL,
  `Nombre` varchar(15) NOT NULL,
  `Apellido` varchar(15) NOT NULL,
  `Correo` varchar(25) NOT NULL,
  `Clave` varchar(45) NOT NULL,
  `Contacto` int(11) NOT NULL,
  `Direccion` varchar(15) NOT NULL,
  `Foto` varchar(50) DEFAULT NULL,
  `Estado_idEstado` int(11) NOT NULL DEFAULT 2,
  `Localidad_idLocalidad` int(11) NOT NULL,
  `admin_idAdmin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `dueño`
--

INSERT INTO `dueño` (`idDueño`, `NroDocumento`, `Nombre`, `Apellido`, `Correo`, `Clave`, `Contacto`, `Direccion`, `Foto`, `Estado_idEstado`, `Localidad_idLocalidad`, `admin_idAdmin`) VALUES
(1, '1001001001', 'Carlos', 'Perez', 'carlos@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 300111111, 'Calle 1', 'imagenes/1779141855.png', 2, 1, 1),
(2, '1001001002', 'Laura', 'Gomez', 'laura@gmail.com', '1234', 300111112, 'Calle 2', 'laura.jpg', 2, 2, 1),
(3, '1001001003', 'Mateo', 'Ruiz', 'mateo@gmail.com', '1234', 300111113, 'Calle 3', 'mateo.jpg', 2, 3, 1),
(4, '1001001004', 'Valentina', 'Diaz', 'valentina@gmail.com', '1234', 300111114, 'Calle 4', 'vale.jpg', 2, 4, 1),
(5, '1001001005', 'Andres', 'Torres', 'andres@gmail.com', '1234', 300111115, 'Calle 5', 'andres.jpg', 2, 5, 2),
(6, '1001001006', 'Camila', 'Rojas', 'camila@gmail.com', '1234', 300111116, 'Calle 6', 'camila.jpg', 2, 6, 2),
(7, '1001001007', 'Sebastian', 'Moreno', 'sebastian@gmail.com', '1234', 300111117, 'Calle 7', 'sebas.jpg', 2, 7, 2),
(8, '1001001008', 'Daniela', 'Vargas', 'daniela@gmail.com', '1234', 300111118, 'Calle 8', 'daniela.jpg', 2, 8, 2),
(9, '1001001009', 'Juan', 'Castro', 'juan@gmail.com', '1234', 300111119, 'Calle 9', 'juan.jpg', 2, 9, 1),
(10, '1001001010', 'Sara', 'Mendez', 'sara@gmail.com', '1234', 300111120, 'Calle 10', 'sara.jpg', 2, 10, 1),
(50, '1001001011', 'Carlos', 'Mendoza', 'carlos@email.com', '81dc9bdb52d04dc20036dbd8313ed055', 300111101, 'Cra 1 #2-3', NULL, 2, 1, 1),
(51, '1001001012', 'Laura', 'Giraldo', 'laura@email.com', '81dc9bdb52d04dc20036dbd8313ed055', 300111102, 'Cra 2 #3-4', NULL, 2, 2, 1),
(52, '1001001013', 'Pedro', 'Ramirez', 'pedro@email.com', '81dc9bdb52d04dc20036dbd8313ed055', 300111103, 'Cra 3 #4-5', NULL, 4, 3, 1),
(53, '1001001014', 'Ana', 'Martinez', 'ana@email.com', '81dc9bdb52d04dc20036dbd8313ed055', 300111104, 'Cra 4 #5-6', NULL, 2, 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `idEstado` int(11) NOT NULL,
  `Nombre` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`idEstado`, `Nombre`) VALUES
(1, 'pendiente'),
(2, 'aprobado'),
(3, 'rechazado'),
(4, 'bloqueado'),
(5, 'en curso'),
(6, 'completado'),
(7, 'rechazado paseador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localidad`
--

CREATE TABLE `localidad` (
  `idLocalidad` int(11) NOT NULL,
  `Localidad` varchar(10) NOT NULL,
  `Ciudad_idCiudad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `localidad`
--

INSERT INTO `localidad` (`idLocalidad`, `Localidad`, `Ciudad_idCiudad`) VALUES
(1, 'Suba', 1),
(2, 'Kennedy', 1),
(3, 'Engativa', 1),
(4, 'Usaquen', 1),
(5, 'Chapinero', 1),
(6, 'Belen', 2),
(7, 'Poblado', 2),
(8, 'Laureles', 2),
(9, 'Aguablanca', 3),
(10, 'SanFernand', 3),
(11, 'Bocagrande', 4),
(12, 'Centro', 4),
(13, 'Riomar', 5),
(14, 'Cabecera', 6),
(15, 'Centro', 7),
(16, 'Palogrande', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_paseo`
--

CREATE TABLE `log_paseo` (
  `idLog` int(11) NOT NULL,
  `idPaseo` int(11) NOT NULL,
  `EstadoAnterior` int(11) DEFAULT NULL,
  `EstadoNuevo` int(11) DEFAULT NULL,
  `FechaCambio` timestamp NOT NULL DEFAULT current_timestamp(),
  `Motivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `log_paseo`
--

INSERT INTO `log_paseo` (`idLog`, `idPaseo`, `EstadoAnterior`, `EstadoNuevo`, `FechaCambio`, `Motivo`) VALUES
(1, 15, 1, 3, '2026-05-24 00:15:12', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paseador`
--

CREATE TABLE `paseador` (
  `idPaseador` int(11) NOT NULL,
  `Nombre` varchar(15) NOT NULL,
  `Apellido` varchar(15) NOT NULL,
  `NroDocumento` varchar(12) NOT NULL,
  `FechaNacimiento` date NOT NULL,
  `Correo` varchar(25) NOT NULL,
  `Clave` varchar(45) NOT NULL,
  `Contacto` int(11) NOT NULL,
  `Estado_idEstado` int(11) NOT NULL DEFAULT 1,
  `Informacion` varchar(500) NOT NULL,
  `Foto` varchar(50) DEFAULT NULL,
  `HojaDeVida` varchar(50) NOT NULL,
  `Certificados` varchar(50) DEFAULT NULL,
  `AprobadoPeligroso` tinyint(1) DEFAULT 0,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `Admin_idAdmin` int(11) NOT NULL,
  `Localidad_idLocalidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `paseador`
--

INSERT INTO `paseador` (`idPaseador`, `Nombre`, `Apellido`, `NroDocumento`, `FechaNacimiento`, `Correo`, `Clave`, `Contacto`, `Estado_idEstado`, `Informacion`, `Foto`, `HojaDeVida`, `Certificados`, `AprobadoPeligroso`, `FechaRegistro`, `Admin_idAdmin`, `Localidad_idLocalidad`) VALUES
(1, 'Kevin', 'Lopez', '1020301001', '1995-03-15', 'kevin@gmail.com', '1234', 311111111, 2, 'Experiencia 3 años paseando perros', 'kevin.jpg', 'hv_kevin.pdf', 'cert1.pdf', 1, '2026-05-23 23:56:44', 1, 1),
(2, 'Natalia', 'Garcia', '1020301002', '1997-07-22', 'natalia@gmail.com', '1234', 311111112, 2, 'Ama los animales y tiene experiencia veterinaria', 'nata.jpg', 'hv_nata.pdf', 'cert2.pdf', 1, '2026-05-23 23:56:44', 1, 2),
(3, 'Felipe', 'Martinez', '1020301003', '1993-11-08', 'felipe@gmail.com', '1234', 311111113, 2, 'Especialista en perros grandes', 'felipe.jpg', 'hv_felipe.pdf', 'cert3.pdf', 0, '2026-05-23 23:56:44', 2, 3),
(4, 'Paula', 'Herrera', '1020301004', '1996-05-30', 'paula@gmail.com', '1234', 311111114, 2, 'Paseadora profesional certificada', 'paula.jpg', 'hv_paula.pdf', 'cert4.pdf', 1, '2026-05-23 23:56:44', 2, 4),
(5, 'David', 'Jimenez', '1020301005', '1994-09-12', 'david@gmail.com', '1234', 311111115, 2, 'Cuidador y entrenador canino', 'david.jpg', 'hv_david.pdf', 'cert5.pdf', 1, '2026-05-23 23:56:44', 1, 5),
(16, 'Pedro', 'Ramirez', '1020301016', '1999-01-25', 'pedro@aspirante.com', '81dc9bdb52d04dc20036dbd8313ed055', 311111116, 3, 'Aspirante en revision', NULL, 'hv_pedro.pdf', 'cert_pedro.pdf', 0, '2026-05-23 23:56:44', 1, 1),
(17, 'Maria', 'Lopez', '1020301017', '1998-04-14', 'maria@rechazado.com', '81dc9bdb52d04dc20036dbd8313ed055', 311111117, 2, 'Especialista en perros grandes', NULL, 'hv_maria.pdf', 'cert_maria.pdf', 1, '2026-05-23 23:56:44', 2, 2),
(50, 'Andres', 'Torres', '1020301018', '1995-03-15', 'andres@email.com', '81dc9bdb52d04dc20036dbd8313ed055', 311111001, 2, 'Paseador con experiencia en perros peligrosos', NULL, 'hv_andres.pdf', 'cert_andres.pdf', 1, '2026-05-23 23:56:44', 1, 1),
(51, 'Sofia', 'Rivera', '1020301019', '1997-07-22', 'sofia@email.com', '81dc9bdb52d04dc20036dbd8313ed055', 311111002, 2, 'Paseadora responsable con referencias', NULL, 'hv_sofia.pdf', 'cert_sofia.pdf', 0, '2026-05-23 23:56:44', 1, 2),
(52, 'Diego', 'Herrera', '1020301022', '1993-11-08', 'diego@email.com', '81dc9bdb52d04dc20036dbd8313ed055', 311111003, 1, 'Aspirante en proceso de revisión', NULL, 'hv_diego.pdf', 'cert_diego.pdf', 0, '2026-05-23 23:56:44', 1, 3),
(53, 'Camila', 'Rojas', '1020301058', '1996-05-30', 'camila@email.com', '81dc9bdb52d04dc20036dbd8313ed055', 311111004, 2, 'Paseadora bloqueada por mal comportamiento', NULL, 'hv_camila.pdf', 'cert_camila.pdf', 0, '2026-05-23 23:56:44', 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paseo`
--

CREATE TABLE `paseo` (
  `idPaseo` int(11) NOT NULL,
  `FechaInicio` datetime NOT NULL,
  `FechaFin` datetime NOT NULL,
  `Bozal` tinyint(4) NOT NULL,
  `Observaciones` varchar(100) DEFAULT NULL,
  `MotivoCancelacion` varchar(100) DEFAULT NULL,
  `FechaSolicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `Paseador_idPaseador` int(11) NOT NULL,
  `Estado_idEstado` int(11) NOT NULL,
  `perro_idPerro` int(11) NOT NULL,
  `perro_idPerro2` int(11) DEFAULT NULL,
  `perro_idPerro3` int(11) DEFAULT NULL,
  `perro_idPerro4` int(11) DEFAULT NULL,
  `perro_idPerro5` int(11) DEFAULT NULL,
  `perro_idPerro6` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `paseo`
--

INSERT INTO `paseo` (`idPaseo`, `FechaInicio`, `FechaFin`, `Bozal`, `Observaciones`, `MotivoCancelacion`, `FechaSolicitud`, `Paseador_idPaseador`, `Estado_idEstado`, `perro_idPerro`, `perro_idPerro2`, `perro_idPerro3`, `perro_idPerro4`, `perro_idPerro5`, `perro_idPerro6`) VALUES
(1, '2026-05-01 08:00:00', '2026-05-01 09:00:00', 0, 'Todo bien', NULL, '2026-04-30 10:00:00', 1, 6, 1, NULL, NULL, NULL, NULL, NULL),
(2, '2026-05-02 10:00:00', '2026-05-02 11:00:00', 0, 'Muy tranquilo', NULL, '2026-05-01 10:00:00', 2, 6, 2, NULL, NULL, NULL, NULL, NULL),
(3, '2026-05-03 09:00:00', '2026-05-03 10:00:00', 1, 'Necesito bozal', NULL, '2026-05-02 10:00:00', 4, 5, 3, NULL, NULL, NULL, NULL, NULL),
(4, '2026-05-04 07:00:00', '2026-05-04 08:00:00', 0, 'Activo', NULL, '2026-05-03 10:00:00', 4, 2, 4, NULL, NULL, NULL, NULL, NULL),
(5, '2026-05-05 12:00:00', '2026-05-05 13:00:00', 1, 'Muy fuerte', NULL, '2026-05-04 10:00:00', 5, 1, 5, NULL, NULL, NULL, NULL, NULL),
(6, '2026-05-06 14:00:00', '2026-05-06 15:00:00', 0, NULL, 'El dueño cancelo', '2026-05-05 10:00:00', 1, 3, 6, NULL, NULL, NULL, NULL, NULL),
(7, '2026-05-07 15:00:00', '2026-05-07 16:00:00', 1, NULL, 'El paseador cancelo', '2026-05-06 10:00:00', 2, 7, 7, NULL, NULL, NULL, NULL, NULL),
(8, '2026-05-08 16:00:00', '2026-05-08 17:00:00', 0, 'Excelente paseo', NULL, '2026-05-07 10:00:00', 3, 6, 8, NULL, NULL, NULL, NULL, NULL),
(9, '2026-05-09 17:00:00', '2026-05-09 18:00:00', 1, 'Perro inquieto', NULL, '2026-05-08 10:00:00', 4, 5, 9, NULL, NULL, NULL, NULL, NULL),
(10, '2026-05-10 18:00:00', '2026-05-10 19:00:00', 1, 'Sin problemas', NULL, '2026-05-09 10:00:00', 5, 2, 10, NULL, NULL, NULL, NULL, NULL),
(15, '2026-05-20 16:00:00', '2026-05-20 17:00:00', 0, '', '', '2026-05-19 10:00:00', 1, 3, 1, NULL, NULL, NULL, NULL, NULL),
(16, '2026-05-25 08:00:00', '2026-05-25 09:00:00', 0, '', NULL, '2026-05-24 10:00:00', 1, 6, 1, NULL, NULL, NULL, NULL, NULL),
(18, '2026-05-27 16:00:00', '2026-05-27 17:00:00', 0, '', NULL, '2026-05-26 10:00:00', 1, 6, 1, NULL, NULL, NULL, NULL, NULL),
(50, '2026-06-15 08:00:00', '2026-06-15 09:00:00', 1, 'Paseo de Rocky (Peligroso) con paseador aprobado', NULL, '2026-06-14 10:00:00', 50, 2, 52, NULL, NULL, NULL, NULL, NULL),
(51, '2026-06-15 10:00:00', '2026-06-15 11:00:00', 0, 'Bruno (Alto) + Lola (Bajo) - capacidad Alto=2', NULL, '2026-06-14 10:00:00', 51, 2, 54, 60, NULL, NULL, NULL, NULL),
(52, '2026-06-16 09:00:00', '2026-06-16 10:00:00', 0, 'Max + Luna (Bajo) + Toby (Medio) - capacidad Medio=3', NULL, '2026-06-15 10:00:00', 50, 2, 50, 51, 55, NULL, NULL, NULL),
(53, '2026-06-14 14:00:00', '2026-06-14 15:00:00', 0, 'Nina (Medio) + Simba (Bajo) - capacidad Medio=3', NULL, '2026-06-13 10:00:00', 51, 6, 56, 59, NULL, NULL, NULL, NULL),
(54, '2026-06-17 08:00:00', '2026-06-17 09:00:00', 0, '5 perros nivel Bajo - capacidad Bajo=5', NULL, '2026-06-16 10:00:00', 51, 1, 50, 51, 61, 59, 60, NULL),
(55, '2026-06-17 10:00:00', '2026-06-17 11:00:00', 0, 'Thor (Alto) + Mia (Bajo) - capacidad Alto=2', NULL, '2026-06-16 10:00:00', 50, 1, 53, 61, NULL, NULL, NULL, NULL),
(56, '2026-06-14 08:00:00', '2026-06-14 09:00:00', 0, 'Rex + Zoe (Medio) - capacidad Medio=3', NULL, '2026-06-13 10:00:00', 51, 6, 57, 58, NULL, NULL, NULL, NULL),
(57, '2026-06-18 08:00:00', '2026-06-18 09:00:00', 1, 'Rocky (Peligroso) - segundo paseo con Andres', NULL, '2026-06-17 10:00:00', 50, 1, 52, NULL, NULL, NULL, NULL, NULL);

--
-- Disparadores `paseo`
--
DELIMITER $$
CREATE TRIGGER `trg_auditar_estado_paseo` AFTER UPDATE ON `paseo` FOR EACH ROW BEGIN
    IF OLD.Estado_idEstado != NEW.Estado_idEstado THEN
        INSERT INTO log_paseo (idPaseo, EstadoAnterior, EstadoNuevo, FechaCambio)
        VALUES (NEW.idPaseo, OLD.Estado_idEstado, NEW.Estado_idEstado, NOW());
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_bloquear_horario_peligroso` BEFORE INSERT ON `paseo` FOR EACH ROW BEGIN
    DECLARE v_total INT DEFAULT 0;

    IF (SELECT `Peligrosidad_idPeligrosidad` FROM `perro` WHERE `idPerro` = NEW.`Perro_idPerro`) = 4 THEN
        SELECT COUNT(*) INTO v_total FROM `paseo` p
        WHERE p.`Paseador_idPaseador` = NEW.`Paseador_idPaseador`
          AND p.`FechaInicio` = NEW.`FechaInicio`
          AND p.`Estado_idEstado` NOT IN (3, 5);
    ELSE
        SELECT COUNT(*) INTO v_total FROM `paseo` p
        WHERE p.`Paseador_idPaseador` = NEW.`Paseador_idPaseador`
          AND p.`FechaInicio` = NEW.`FechaInicio`
          AND p.`Estado_idEstado` NOT IN (3, 5)
          AND (
              (SELECT `Peligrosidad_idPeligrosidad` FROM `perro` WHERE `idPerro` = p.`Perro_idPerro`) = 4
              OR (p.`Perro_idPerro2` > 0 AND (SELECT `Peligrosidad_idPeligrosidad` FROM `perro` WHERE `idPerro` = p.`Perro_idPerro2`) = 4)
              OR (p.`Perro_idPerro3` > 0 AND (SELECT `Peligrosidad_idPeligrosidad` FROM `perro` WHERE `idPerro` = p.`Perro_idPerro3`) = 4)
              OR (p.`Perro_idPerro4` > 0 AND (SELECT `Peligrosidad_idPeligrosidad` FROM `perro` WHERE `idPerro` = p.`Perro_idPerro4`) = 4)
              OR (p.`Perro_idPerro5` > 0 AND (SELECT `Peligrosidad_idPeligrosidad` FROM `perro` WHERE `idPerro` = p.`Perro_idPerro5`) = 4)
              OR (p.`Perro_idPerro6` > 0 AND (SELECT `Peligrosidad_idPeligrosidad` FROM `perro` WHERE `idPerro` = p.`Perro_idPerro6`) = 4)
          );
    END IF;

    IF v_total > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El paseador ya tiene un paseo con perro peligroso en este horario.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_evitar_choque_horario` BEFORE INSERT ON `paseo` FOR EACH ROW BEGIN
    DECLARE existing INT;
    SELECT COUNT(*) INTO existing FROM `paseo`
    WHERE `Perro_idPerro` = NEW.`Perro_idPerro`
      AND `FechaInicio` = NEW.`FechaInicio`
      AND `Estado_idEstado` NOT IN (3, 5);
    IF existing > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El perro ya tiene un paseo programado en esta fecha y hora.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_validar_capacidad_riesgo` BEFORE INSERT ON `paseo` FOR EACH ROW BEGIN
    DECLARE total_perros INT;
    DECLARE max_risk INT DEFAULT 1;
    DECLARE curr_risk INT;

    SET total_perros = 1;
    IF NEW.`Perro_idPerro2` IS NOT NULL AND NEW.`Perro_idPerro2` > 0 THEN SET total_perros = total_perros + 1; END IF;
    IF NEW.`Perro_idPerro3` IS NOT NULL AND NEW.`Perro_idPerro3` > 0 THEN SET total_perros = total_perros + 1; END IF;
    IF NEW.`Perro_idPerro4` IS NOT NULL AND NEW.`Perro_idPerro4` > 0 THEN SET total_perros = total_perros + 1; END IF;
    IF NEW.`Perro_idPerro5` IS NOT NULL AND NEW.`Perro_idPerro5` > 0 THEN SET total_perros = total_perros + 1; END IF;
    IF NEW.`Perro_idPerro6` IS NOT NULL AND NEW.`Perro_idPerro6` > 0 THEN SET total_perros = total_perros + 1; END IF;

    SELECT `Peligrosidad_idPeligrosidad` INTO curr_risk FROM `perro` WHERE `idPerro` = NEW.`Perro_idPerro`;
    IF curr_risk > max_risk THEN SET max_risk = curr_risk; END IF;

    IF NEW.`Perro_idPerro2` IS NOT NULL AND NEW.`Perro_idPerro2` > 0 THEN
        SELECT `Peligrosidad_idPeligrosidad` INTO curr_risk FROM `perro` WHERE `idPerro` = NEW.`Perro_idPerro2`;
        IF curr_risk > max_risk THEN SET max_risk = curr_risk; END IF;
    END IF;
    IF NEW.`Perro_idPerro3` IS NOT NULL AND NEW.`Perro_idPerro3` > 0 THEN
        SELECT `Peligrosidad_idPeligrosidad` INTO curr_risk FROM `perro` WHERE `idPerro` = NEW.`Perro_idPerro3`;
        IF curr_risk > max_risk THEN SET max_risk = curr_risk; END IF;
    END IF;
    IF NEW.`Perro_idPerro4` IS NOT NULL AND NEW.`Perro_idPerro4` > 0 THEN
        SELECT `Peligrosidad_idPeligrosidad` INTO curr_risk FROM `perro` WHERE `idPerro` = NEW.`Perro_idPerro4`;
        IF curr_risk > max_risk THEN SET max_risk = curr_risk; END IF;
    END IF;
    IF NEW.`Perro_idPerro5` IS NOT NULL AND NEW.`Perro_idPerro5` > 0 THEN
        SELECT `Peligrosidad_idPeligrosidad` INTO curr_risk FROM `perro` WHERE `idPerro` = NEW.`Perro_idPerro5`;
        IF curr_risk > max_risk THEN SET max_risk = curr_risk; END IF;
    END IF;
    IF NEW.`Perro_idPerro6` IS NOT NULL AND NEW.`Perro_idPerro6` > 0 THEN
        SELECT `Peligrosidad_idPeligrosidad` INTO curr_risk FROM `perro` WHERE `idPerro` = NEW.`Perro_idPerro6`;
        IF curr_risk > max_risk THEN SET max_risk = curr_risk; END IF;
    END IF;

    IF total_perros > 5 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Límite máximo de 5 perros por paseo.';
    END IF;

    IF max_risk = 4 AND total_perros > 1 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Con perros Peligrosos solo se puede pasear 1 perro a la vez.';
    END IF;
    IF max_risk = 3 AND total_perros > 2 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Con perros de nivel Alto solo se pueden pasear máximo 2 perros.';
    END IF;
    IF max_risk = 2 AND total_perros > 3 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Con perros de nivel Medio solo se pueden pasear máximo 3 perros.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_validar_perro_peligroso` BEFORE INSERT ON `paseo` FOR EACH ROW BEGIN
    DECLARE peligrosidad INT;
    DECLARE aprobado INT;
    SELECT `Peligrosidad_idPeligrosidad` INTO peligrosidad FROM `perro` WHERE `idPerro` = NEW.`Perro_idPerro`;
    IF peligrosidad = 4 THEN
        SELECT `AprobadoPeligroso` INTO aprobado FROM `paseador` WHERE `idPaseador` = NEW.`Paseador_idPaseador`;
        IF aprobado IS NULL OR aprobado = 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Este paseador no está aprobado para pasear perros peligrosos.';
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peligrosidad`
--

CREATE TABLE `peligrosidad` (
  `idPeligrosidad` int(11) NOT NULL,
  `Nivel` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `peligrosidad`
--

INSERT INTO `peligrosidad` (`idPeligrosidad`, `Nivel`) VALUES
(1, 'Bajo'),
(2, 'Medio'),
(3, 'Alto'),
(4, 'Peligroso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perro`
--

CREATE TABLE `perro` (
  `idPerro` int(11) NOT NULL,
  `Nombre` varchar(15) NOT NULL,
  `Peso` decimal(5,2) NOT NULL,
  `Recomendacion` varchar(250) NOT NULL,
  `Foto` varchar(50) DEFAULT NULL,
  `Estado_idEstado` int(11) NOT NULL DEFAULT 2,
  `Raza_idRaza` int(11) NOT NULL,
  `Dueño_idDueño` int(11) NOT NULL,
  `Peligrosidad_idPeligrosidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `perro`
--

INSERT INTO `perro` (`idPerro`, `Nombre`, `Peso`, `Recomendacion`, `Foto`, `Estado_idEstado`, `Raza_idRaza`, `Dueño_idDueño`, `Peligrosidad_idPeligrosidad`) VALUES
(1, 'Max', 12.50, 'No acercarlo a gatos y perros mas grandes', 'max.jpg', 2, 1, 1, 2),
(2, 'Luna', 8.00, 'Muy amigable', 'luna.jpg', 2, 3, 2, 1),
(3, 'Rocky', 25.00, 'Usar bozal', 'rocky.jpg', 2, 14, 3, 4),
(4, 'Toby', 18.00, 'Le gusta correr', 'toby.jpg', 2, 8, 4, 2),
(5, 'Nala', 30.00, 'Evitar otros machos', 'nala.jpg', 2, 12, 5, 3),
(6, 'Simba', 5.00, 'Muy pequeño', 'simba.jpg', 2, 6, 6, 1),
(7, 'Zeus', 40.00, 'Perro protector', 'zeus.jpg', 2, 16, 7, 4),
(8, 'Milo', 10.00, 'Juguetón', 'milo.jpg', 2, 22, 8, 1),
(9, 'Kira', 50.00, 'Necesita espacio', 'kira.jpg', 2, 18, 9, 3),
(10, 'Bruno', 65.00, 'Muy fuerte', 'bruno.jpg', 2, 20, 10, 4),
(50, 'Max', 9.50, 'Jugueteón y tranquilo', NULL, 2, 3, 50, 1),
(51, 'Luna', 7.50, 'Muy amigable con otros perros', NULL, 2, 4, 51, 1),
(52, 'Rocky', 28.00, 'Requiere paseador certificado para perros peligrosos', NULL, 2, 14, 51, 4),
(53, 'Thor', 24.00, 'Perro energético, necesita espacio', NULL, 2, 16, 53, 3),
(54, 'Bruno', 35.00, 'Protector con extraños', NULL, 2, 12, 52, 3),
(55, 'Toby', 18.50, 'Le gusta correr junto a la bicicleta', NULL, 2, 8, 50, 2),
(56, 'Nina', 11.00, 'Dócil y cariñosa', NULL, 2, 22, 53, 2),
(57, 'Rex', 20.00, 'Juguetón pero territorial', NULL, 2, 2, 51, 2),
(58, 'Zoe', 10.50, 'Tranquila y obediente', NULL, 2, 21, 52, 2),
(59, 'Simba', 5.00, 'Muy pequeño, tener cuidado al caminar', NULL, 2, 6, 50, 1),
(60, 'Lola', 14.00, 'Amigable con niños', NULL, 2, 1, 53, 1),
(61, 'Mia', 6.50, 'Perfecta para principiantes', NULL, 2, 5, 51, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `raza`
--

CREATE TABLE `raza` (
  `idRaza` int(11) NOT NULL,
  `Raza` varchar(30) NOT NULL,
  `Tamaño_idTamaño` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `raza`
--

INSERT INTO `raza` (`idRaza`, `Raza`, `Tamaño_idTamaño`) VALUES
(1, 'Bulldog-Hembra', 2),
(2, 'Bulldog-Macho', 2),
(3, 'Pug-Hembra', 1),
(4, 'Pug-Macho', 1),
(5, 'Chihuahua-Hembr', 1),
(6, 'Chihuahua-Macho', 1),
(7, 'Labrador-Hembra', 3),
(8, 'Labrador-Macho', 3),
(9, 'Golden-Hembra', 3),
(10, 'Golden-Macho', 3),
(11, 'Pastor-Hembra', 3),
(12, 'Pastor-Macho', 3),
(13, 'Pitbull-Hembra', 3),
(14, 'Pitbull-Macho', 3),
(15, 'Husky-Hembra', 3),
(16, 'Husky-Macho', 3),
(17, 'GranDanes-Hembr', 4),
(18, 'GranDanes-Macho', 4),
(19, 'SanBernardo-Hem', 4),
(20, 'SanBernardo-Mac', 4),
(21, 'Cocker-Hembra', 2),
(22, 'Cocker-Macho', 2),
(23, 'Beagle-Hembra', 2),
(24, 'Beagle-Macho', 2),
(346, 'BUldog Sasaima Hembra', 2),
(347, 'BUldog Sasaima Masculino', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudraza`
--

CREATE TABLE `solicitudraza` (
  `idSolicitud` int(11) NOT NULL,
  `NombreRaza` varchar(50) NOT NULL,
  `idDueño` int(11) NOT NULL,
  `Estado_idEstado` int(11) NOT NULL DEFAULT 1,
  `FechaSolicitud` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `solicitudraza`
--

INSERT INTO `solicitudraza` (`idSolicitud`, `NombreRaza`, `idDueño`, `Estado_idEstado`, `FechaSolicitud`) VALUES
(1, 'Bulldog Frances', 1, 2, '2026-05-18 13:34:02'),
(2, 'Husky Siberiano', 2, 3, '2026-05-18 13:34:02'),
(3, 'Golden Retriever', 3, 2, '2026-05-18 13:34:02'),
(4, 'BUldog Sasaima', 1, 2, '2026-05-18 15:41:33'),
(5, 'BUldog Sasaima', 1, 2, '2026-05-18 15:59:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tamaño`
--

CREATE TABLE `tamaño` (
  `idTamaño` int(11) NOT NULL,
  `Tamaño` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tamaño`
--

INSERT INTO `tamaño` (`idTamaño`, `Tamaño`) VALUES
(1, 'Pequeño'),
(2, 'Mediano'),
(3, 'Grande'),
(4, 'Gigante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifa`
--

CREATE TABLE `tarifa` (
  `idTarifa` int(11) NOT NULL,
  `PrecioHora` decimal(10,0) NOT NULL,
  `FechaInicio` date NOT NULL,
  `Activa` tinyint(4) DEFAULT 1,
  `Paseador_idPaseador` int(11) NOT NULL,
  `Peligrosidad_idPeligrosidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tarifa`
--

INSERT INTO `tarifa` (`idTarifa`, `PrecioHora`, `FechaInicio`, `Activa`, `Paseador_idPaseador`, `Peligrosidad_idPeligrosidad`) VALUES
(1, 12000, '2026-01-01', 0, 1, 1),
(2, 15000, '2026-01-01', 0, 1, 2),
(3, 18000, '2026-01-01', 1, 1, 3),
(4, 22000, '2026-01-01', 1, 1, 4),
(5, 13000, '2026-01-01', 1, 2, 1),
(6, 16000, '2026-01-01', 1, 2, 2),
(7, 19000, '2026-01-01', 1, 2, 3),
(8, 24000, '2026-01-01', 1, 2, 4),
(9, 14000, '2026-01-01', 1, 3, 1),
(10, 17000, '2026-01-01', 1, 3, 2),
(11, 21000, '2026-01-01', 1, 3, 3),
(12, 26000, '2026-01-01', 1, 3, 4),
(13, 12500, '2026-01-01', 1, 4, 1),
(14, 15500, '2026-01-01', 1, 4, 2),
(15, 18500, '2026-01-01', 1, 4, 3),
(16, 23000, '2026-01-01', 1, 4, 4),
(17, 13500, '2026-01-01', 1, 5, 1),
(18, 16500, '2026-01-01', 1, 5, 2),
(19, 20000, '2026-01-01', 1, 5, 3),
(20, 25000, '2026-01-01', 1, 5, 4),
(50, 25000, '2026-01-01', 1, 50, 1),
(51, 30000, '2026-01-01', 1, 50, 2),
(52, 35000, '2026-01-01', 1, 50, 3),
(53, 45000, '2026-01-01', 1, 50, 4),
(54, 22000, '2026-01-01', 1, 51, 1),
(55, 27000, '2026-01-01', 1, 51, 2),
(56, 32000, '2026-01-01', 1, 51, 3),
(57, 28000, '2026-01-01', 1, 52, 1),
(58, 33000, '2026-01-01', 1, 52, 2),
(59, 25000, '2026-01-01', 1, 53, 1),
(190, 15000, '2026-05-18', 0, 1, 1),
(191, 16000, '2026-05-18', 1, 1, 1),
(192, 17000, '2026-05-18', 1, 1, 2);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_aspirantes_pendientes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_aspirantes_pendientes` (
`idPaseador` int(11)
,`Nombre` varchar(15)
,`Apellido` varchar(15)
,`Correo` varchar(25)
,`Contacto` int(11)
,`Informacion` varchar(500)
,`FechaRegistro` timestamp
,`DiasEspera` int(7)
,`EstadoRevision` varchar(8)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_estadisticas_admin`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_estadisticas_admin` (
`AspirantesPendientes` bigint(21)
,`PaseadoresActivos` bigint(21)
,`PaseadoresBloqueados` bigint(21)
,`PaseosPendientes` bigint(21)
,`PaseosAprobados` bigint(21)
,`PaseosEnCurso` bigint(21)
,`PaseosCompletados` bigint(21)
,`TotalPerros` bigint(21)
,`DuenosActivos` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_paseadores_activos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_paseadores_activos` (
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_paseos_detalle`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_paseos_detalle` (
`idPaseo` int(11)
,`FechaInicio` datetime
,`FechaFin` datetime
,`Bozal` tinyint(4)
,`Observaciones` varchar(100)
,`MotivoCancelacion` varchar(100)
,`FechaSolicitud` timestamp
,`idPaseador` int(11)
,`PaseadorNombre` varchar(31)
,`Estado_idEstado` int(11)
,`EstadoNombre` varchar(25)
,`perro_idPerro` int(11)
,`Perro1` varchar(48)
,`perro_idPerro2` int(11)
,`Perro2` varchar(48)
,`perro_idPerro3` int(11)
,`Perro3` varchar(48)
,`perro_idPerro4` int(11)
,`Perro4` varchar(48)
,`perro_idPerro5` int(11)
,`Perro5` varchar(48)
,`perro_idPerro6` int(11)
,`Perro6` varchar(48)
,`idDuenno` int(11)
,`DuenoNombre` varchar(31)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_paseos_pendientes_vencer`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_paseos_pendientes_vencer` (
`idPaseo` int(11)
,`FechaInicio` datetime
,`FechaFin` datetime
,`FechaSolicitud` timestamp
,`HorasRestantes` bigint(21)
,`HorasTranscurridas` bigint(21)
,`EstadoRespuesta` varchar(19)
,`Paseador_idPaseador` int(11)
,`PaseadorNombre` varchar(31)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_perros_con_dueno`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_perros_con_dueno` (
`idPerro` int(11)
,`PerroNombre` varchar(15)
,`Peso` decimal(5,2)
,`Recomendacion` varchar(250)
,`Peligrosidad` varchar(10)
,`Raza` varchar(30)
,`Tamanno` varchar(12)
,`idDuenno` int(11)
,`DuenoNombre` varchar(31)
,`DuenoCorreo` varchar(25)
,`DuenoContacto` int(11)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_aspirantes_pendientes`
--
DROP TABLE IF EXISTS `vw_aspirantes_pendientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_aspirantes_pendientes`  AS SELECT `p`.`idPaseador` AS `idPaseador`, `p`.`Nombre` AS `Nombre`, `p`.`Apellido` AS `Apellido`, `p`.`Correo` AS `Correo`, `p`.`Contacto` AS `Contacto`, `p`.`Informacion` AS `Informacion`, `p`.`FechaRegistro` AS `FechaRegistro`, to_days(current_timestamp()) - to_days(`p`.`FechaRegistro`) AS `DiasEspera`, CASE WHEN to_days(current_timestamp()) - to_days(`p`.`FechaRegistro`) > 7 THEN 'Vencido' ELSE 'En plazo' END AS `EstadoRevision` FROM `paseador` AS `p` WHERE `p`.`Estado_idEstado` = 1 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_estadisticas_admin`
--
DROP TABLE IF EXISTS `vw_estadisticas_admin`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_estadisticas_admin`  AS SELECT (select count(0) from `paseador` where `paseador`.`Estado_idEstado` = 1) AS `AspirantesPendientes`, (select count(0) from `paseador` where `paseador`.`Estado_idEstado` = 2) AS `PaseadoresActivos`, (select count(0) from `paseador` where `paseador`.`Estado_idEstado` = 4) AS `PaseadoresBloqueados`, (select count(0) from `paseo` where `paseo`.`Estado_idEstado` = 1) AS `PaseosPendientes`, (select count(0) from `paseo` where `paseo`.`Estado_idEstado` = 2) AS `PaseosAprobados`, (select count(0) from `paseo` where `paseo`.`Estado_idEstado` = 5) AS `PaseosEnCurso`, (select count(0) from `paseo` where `paseo`.`Estado_idEstado` = 6) AS `PaseosCompletados`, (select count(0) from `perro`) AS `TotalPerros`, (select count(0) from `dueño` where `dueño`.`Estado_idEstado` = 2) AS `DuenosActivos` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_paseadores_activos`
--
DROP TABLE IF EXISTS `vw_paseadores_activos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_paseadores_activos`  AS SELECT `p`.`idPaseador` AS `idPaseador`, `p`.`Nombre` AS `Nombre`, `p`.`Apellido` AS `Apellido`, `p`.`Correo` AS `Correo`, `p`.`Contacto` AS `Contacto`, `p`.`Informacion` AS `Informacion`, `p`.`AprobadoPeligroso` AS `AprobadoPeligroso`, `p`.`Multas` AS `Multas`, `p`.`FechaRegistro` AS `FechaRegistro`, `e`.`Nombre` AS `EstadoNombre`, `l`.`Localidad` AS `Localidad`, `c`.`Ciudad` AS `Ciudad` FROM (((`paseador` `p` join `estado` `e` on(`p`.`Estado_idEstado` = `e`.`idEstado`)) left join `localidad` `l` on(`p`.`Localidad_idLocalidad` = `l`.`idLocalidad`)) left join `ciudad` `c` on(`l`.`Ciudad_idCiudad` = `c`.`idCiudad`)) WHERE `p`.`Estado_idEstado` in (2,4) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_paseos_detalle`
--
DROP TABLE IF EXISTS `vw_paseos_detalle`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_paseos_detalle`  AS SELECT `p`.`idPaseo` AS `idPaseo`, `p`.`FechaInicio` AS `FechaInicio`, `p`.`FechaFin` AS `FechaFin`, `p`.`Bozal` AS `Bozal`, `p`.`Observaciones` AS `Observaciones`, `p`.`MotivoCancelacion` AS `MotivoCancelacion`, `p`.`FechaSolicitud` AS `FechaSolicitud`, `pas`.`idPaseador` AS `idPaseador`, concat(`pas`.`Nombre`,' ',`pas`.`Apellido`) AS `PaseadorNombre`, `p`.`Estado_idEstado` AS `Estado_idEstado`, `e`.`Nombre` AS `EstadoNombre`, `p`.`perro_idPerro` AS `perro_idPerro`, concat(`per1`.`Nombre`,' (',`r1`.`Raza`,')') AS `Perro1`, `p`.`perro_idPerro2` AS `perro_idPerro2`, concat(`per2`.`Nombre`,' (',`r2`.`Raza`,')') AS `Perro2`, `p`.`perro_idPerro3` AS `perro_idPerro3`, concat(`per3`.`Nombre`,' (',`r3`.`Raza`,')') AS `Perro3`, `p`.`perro_idPerro4` AS `perro_idPerro4`, concat(`per4`.`Nombre`,' (',`r4`.`Raza`,')') AS `Perro4`, `p`.`perro_idPerro5` AS `perro_idPerro5`, concat(`per5`.`Nombre`,' (',`r5`.`Raza`,')') AS `Perro5`, `p`.`perro_idPerro6` AS `perro_idPerro6`, concat(`per6`.`Nombre`,' (',`r6`.`Raza`,')') AS `Perro6`, `d`.`idDueño` AS `idDuenno`, concat(`d`.`Nombre`,' ',`d`.`Apellido`) AS `DuenoNombre` FROM (((((((((((((((`paseo` `p` join `paseador` `pas` on(`p`.`Paseador_idPaseador` = `pas`.`idPaseador`)) join `estado` `e` on(`p`.`Estado_idEstado` = `e`.`idEstado`)) left join `perro` `per1` on(`p`.`perro_idPerro` = `per1`.`idPerro`)) left join `raza` `r1` on(`per1`.`Raza_idRaza` = `r1`.`idRaza`)) left join `perro` `per2` on(`p`.`perro_idPerro2` = `per2`.`idPerro`)) left join `raza` `r2` on(`per2`.`Raza_idRaza` = `r2`.`idRaza`)) left join `perro` `per3` on(`p`.`perro_idPerro3` = `per3`.`idPerro`)) left join `raza` `r3` on(`per3`.`Raza_idRaza` = `r3`.`idRaza`)) left join `perro` `per4` on(`p`.`perro_idPerro4` = `per4`.`idPerro`)) left join `raza` `r4` on(`per4`.`Raza_idRaza` = `r4`.`idRaza`)) left join `perro` `per5` on(`p`.`perro_idPerro5` = `per5`.`idPerro`)) left join `raza` `r5` on(`per5`.`Raza_idRaza` = `r5`.`idRaza`)) left join `perro` `per6` on(`p`.`perro_idPerro6` = `per6`.`idPerro`)) left join `raza` `r6` on(`per6`.`Raza_idRaza` = `r6`.`idRaza`)) join `dueño` `d` on(`per1`.`Dueño_idDueño` = `d`.`idDueño`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_paseos_pendientes_vencer`
--
DROP TABLE IF EXISTS `vw_paseos_pendientes_vencer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_paseos_pendientes_vencer`  AS SELECT `p`.`idPaseo` AS `idPaseo`, `p`.`FechaInicio` AS `FechaInicio`, `p`.`FechaFin` AS `FechaFin`, `p`.`FechaSolicitud` AS `FechaSolicitud`, timestampdiff(HOUR,current_timestamp(),`p`.`FechaInicio`) AS `HorasRestantes`, timestampdiff(HOUR,`p`.`FechaSolicitud`,current_timestamp()) AS `HorasTranscurridas`, CASE WHEN timestampdiff(HOUR,`p`.`FechaSolicitud`,current_timestamp()) >= 5 THEN 'Vencido' ELSE 'Esperando respuesta' END AS `EstadoRespuesta`, `p`.`Paseador_idPaseador` AS `Paseador_idPaseador`, concat(`pas`.`Nombre`,' ',`pas`.`Apellido`) AS `PaseadorNombre` FROM (`paseo` `p` join `paseador` `pas` on(`p`.`Paseador_idPaseador` = `pas`.`idPaseador`)) WHERE `p`.`Estado_idEstado` = 1 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_perros_con_dueno`
--
DROP TABLE IF EXISTS `vw_perros_con_dueno`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_perros_con_dueno`  AS SELECT `per`.`idPerro` AS `idPerro`, `per`.`Nombre` AS `PerroNombre`, `per`.`Peso` AS `Peso`, `per`.`Recomendacion` AS `Recomendacion`, `pel`.`Nivel` AS `Peligrosidad`, `r`.`Raza` AS `Raza`, `t`.`Tamaño` AS `Tamanno`, `d`.`idDueño` AS `idDuenno`, concat(`d`.`Nombre`,' ',`d`.`Apellido`) AS `DuenoNombre`, `d`.`Correo` AS `DuenoCorreo`, `d`.`Contacto` AS `DuenoContacto` FROM ((((`perro` `per` join `peligrosidad` `pel` on(`per`.`Peligrosidad_idPeligrosidad` = `pel`.`idPeligrosidad`)) join `raza` `r` on(`per`.`Raza_idRaza` = `r`.`idRaza`)) join `tamaño` `t` on(`r`.`Tamaño_idTamaño` = `t`.`idTamaño`)) join `dueño` `d` on(`per`.`Dueño_idDueño` = `d`.`idDueño`)) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idAdmin`);

--
-- Indices de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD PRIMARY KEY (`idCiudad`);

--
-- Indices de la tabla `diasemana`
--
ALTER TABLE `diasemana`
  ADD PRIMARY KEY (`idDiaSemana`);

--
-- Indices de la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  ADD PRIMARY KEY (`idDisponibilidad`),
  ADD KEY `fk_Disponibilidad_paseador1_idx` (`paseador_idPaseador`),
  ADD KEY `fk_Disponibilidad_DiaSemana1_idx` (`DiaSemana_idDiaSemana`);

--
-- Indices de la tabla `dueño`
--
ALTER TABLE `dueño`
  ADD PRIMARY KEY (`idDueño`),
  ADD KEY `fk_dueño_admin1_idx` (`admin_idAdmin`),
  ADD KEY `fk_dueño_Localidad1_idx` (`Localidad_idLocalidad`),
  ADD KEY `fk_dueño_Estado1_idx` (`Estado_idEstado`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`idEstado`);

--
-- Indices de la tabla `localidad`
--
ALTER TABLE `localidad`
  ADD PRIMARY KEY (`idLocalidad`),
  ADD KEY `fk_localidad_ciudad1_idx` (`Ciudad_idCiudad`);

--
-- Indices de la tabla `log_paseo`
--
ALTER TABLE `log_paseo`
  ADD PRIMARY KEY (`idLog`);

--
-- Indices de la tabla `paseador`
--
ALTER TABLE `paseador`
  ADD PRIMARY KEY (`idPaseador`),
  ADD KEY `fk_paseador_estado1_idx` (`Estado_idEstado`),
  ADD KEY `fk_paseador_admin1_idx` (`Admin_idAdmin`),
  ADD KEY `fk_paseador_localidad1_idx` (`Localidad_idLocalidad`);

--
-- Indices de la tabla `paseo`
--
ALTER TABLE `paseo`
  ADD PRIMARY KEY (`idPaseo`),
  ADD KEY `fk_paseo_paseador1_idx` (`Paseador_idPaseador`),
  ADD KEY `fk_paseo_estado1_idx` (`Estado_idEstado`),
  ADD KEY `fk_paseo_perro1_idx` (`perro_idPerro`),
  ADD KEY `fk_paseo_perro2_idx` (`perro_idPerro2`),
  ADD KEY `fk_paseo_perro3_idx` (`perro_idPerro3`),
  ADD KEY `fk_paseo_perro4_idx` (`perro_idPerro4`),
  ADD KEY `fk_paseo_perro5_idx` (`perro_idPerro5`),
  ADD KEY `fk_paseo_perro6_idx` (`perro_idPerro6`);

--
-- Indices de la tabla `peligrosidad`
--
ALTER TABLE `peligrosidad`
  ADD PRIMARY KEY (`idPeligrosidad`);

--
-- Indices de la tabla `perro`
--
ALTER TABLE `perro`
  ADD PRIMARY KEY (`idPerro`),
  ADD KEY `fk_perro_estado1_idx` (`Estado_idEstado`),
  ADD KEY `fk_perro_raza1_idx` (`Raza_idRaza`),
  ADD KEY `fk_perro_due_idx` (`Dueño_idDueño`),
  ADD KEY `fk_perro_peligrosidad1_idx` (`Peligrosidad_idPeligrosidad`);

--
-- Indices de la tabla `raza`
--
ALTER TABLE `raza`
  ADD PRIMARY KEY (`idRaza`),
  ADD KEY `fk_raza_tam_idx` (`Tamaño_idTamaño`);

--
-- Indices de la tabla `solicitudraza`
--
ALTER TABLE `solicitudraza`
  ADD PRIMARY KEY (`idSolicitud`),
  ADD KEY `fk_solicitudraza_due_idx` (`idDueño`),
  ADD KEY `fk_solicitudraza_estado1_idx` (`Estado_idEstado`);

--
-- Indices de la tabla `tamaño`
--
ALTER TABLE `tamaño`
  ADD PRIMARY KEY (`idTamaño`);

--
-- Indices de la tabla `tarifa`
--
ALTER TABLE `tarifa`
  ADD PRIMARY KEY (`idTarifa`),
  ADD KEY `fk_tarifa_paseador1_idx` (`Paseador_idPaseador`),
  ADD KEY `fk_tarifa_peligrosidad1_idx` (`Peligrosidad_idPeligrosidad`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `idAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  MODIFY `idCiudad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `diasemana`
--
ALTER TABLE `diasemana`
  MODIFY `idDiaSemana` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  MODIFY `idDisponibilidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `dueño`
--
ALTER TABLE `dueño`
  MODIFY `idDueño` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `idEstado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `localidad`
--
ALTER TABLE `localidad`
  MODIFY `idLocalidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `log_paseo`
--
ALTER TABLE `log_paseo`
  MODIFY `idLog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `paseador`
--
ALTER TABLE `paseador`
  MODIFY `idPaseador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `paseo`
--
ALTER TABLE `paseo`
  MODIFY `idPaseo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `peligrosidad`
--
ALTER TABLE `peligrosidad`
  MODIFY `idPeligrosidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `perro`
--
ALTER TABLE `perro`
  MODIFY `idPerro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `raza`
--
ALTER TABLE `raza`
  MODIFY `idRaza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=376;

--
-- AUTO_INCREMENT de la tabla `solicitudraza`
--
ALTER TABLE `solicitudraza`
  MODIFY `idSolicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tamaño`
--
ALTER TABLE `tamaño`
  MODIFY `idTamaño` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tarifa`
--
ALTER TABLE `tarifa`
  MODIFY `idTarifa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  ADD CONSTRAINT `fk_Disponibilidad_diasemana1` FOREIGN KEY (`DiaSemana_idDiaSemana`) REFERENCES `diasemana` (`idDiaSemana`),
  ADD CONSTRAINT `fk_Disponibilidad_paseador1` FOREIGN KEY (`paseador_idPaseador`) REFERENCES `paseador` (`idPaseador`);

--
-- Filtros para la tabla `dueño`
--
ALTER TABLE `dueño`
  ADD CONSTRAINT `fk_dueno_admin1` FOREIGN KEY (`admin_idAdmin`) REFERENCES `admin` (`idAdmin`),
  ADD CONSTRAINT `fk_dueno_estado1` FOREIGN KEY (`Estado_idEstado`) REFERENCES `estado` (`idEstado`),
  ADD CONSTRAINT `fk_dueno_localidad1` FOREIGN KEY (`Localidad_idLocalidad`) REFERENCES `localidad` (`idLocalidad`);

--
-- Filtros para la tabla `localidad`
--
ALTER TABLE `localidad`
  ADD CONSTRAINT `fk_localidad_ciudad1` FOREIGN KEY (`Ciudad_idCiudad`) REFERENCES `ciudad` (`idCiudad`);

--
-- Filtros para la tabla `paseador`
--
ALTER TABLE `paseador`
  ADD CONSTRAINT `fk_paseador_admin1` FOREIGN KEY (`Admin_idAdmin`) REFERENCES `admin` (`idAdmin`),
  ADD CONSTRAINT `fk_paseador_estado1` FOREIGN KEY (`Estado_idEstado`) REFERENCES `estado` (`idEstado`),
  ADD CONSTRAINT `fk_paseador_localidad1` FOREIGN KEY (`Localidad_idLocalidad`) REFERENCES `localidad` (`idLocalidad`);

--
-- Filtros para la tabla `paseo`
--
ALTER TABLE `paseo`
  ADD CONSTRAINT `fk_paseo_estado1` FOREIGN KEY (`Estado_idEstado`) REFERENCES `estado` (`idEstado`),
  ADD CONSTRAINT `fk_paseo_paseador1` FOREIGN KEY (`Paseador_idPaseador`) REFERENCES `paseador` (`idPaseador`),
  ADD CONSTRAINT `fk_paseo_perro1` FOREIGN KEY (`perro_idPerro`) REFERENCES `perro` (`idPerro`),
  ADD CONSTRAINT `fk_paseo_perro2` FOREIGN KEY (`perro_idPerro2`) REFERENCES `perro` (`idPerro`),
  ADD CONSTRAINT `fk_paseo_perro3` FOREIGN KEY (`perro_idPerro3`) REFERENCES `perro` (`idPerro`),
  ADD CONSTRAINT `fk_paseo_perro4` FOREIGN KEY (`perro_idPerro4`) REFERENCES `perro` (`idPerro`),
  ADD CONSTRAINT `fk_paseo_perro5` FOREIGN KEY (`perro_idPerro5`) REFERENCES `perro` (`idPerro`),
  ADD CONSTRAINT `fk_paseo_perro6` FOREIGN KEY (`perro_idPerro6`) REFERENCES `perro` (`idPerro`);

--
-- Filtros para la tabla `perro`
--
ALTER TABLE `perro`
  ADD CONSTRAINT `fk_perro_dueno1` FOREIGN KEY (`Dueño_idDueño`) REFERENCES `dueño` (`idDueño`),
  ADD CONSTRAINT `fk_perro_estado1` FOREIGN KEY (`Estado_idEstado`) REFERENCES `estado` (`idEstado`),
  ADD CONSTRAINT `fk_perro_peligrosidad1` FOREIGN KEY (`Peligrosidad_idPeligrosidad`) REFERENCES `peligrosidad` (`idPeligrosidad`),
  ADD CONSTRAINT `fk_perro_raza1` FOREIGN KEY (`Raza_idRaza`) REFERENCES `raza` (`idRaza`);

--
-- Filtros para la tabla `raza`
--
ALTER TABLE `raza`
  ADD CONSTRAINT `fk_raza_tamano1` FOREIGN KEY (`Tamaño_idTamaño`) REFERENCES `tamaño` (`idTamaño`);

--
-- Filtros para la tabla `solicitudraza`
--
ALTER TABLE `solicitudraza`
  ADD CONSTRAINT `fk_solicitudraza_dueno` FOREIGN KEY (`idDueño`) REFERENCES `dueño` (`idDueño`),
  ADD CONSTRAINT `fk_solicitudraza_estado1` FOREIGN KEY (`Estado_idEstado`) REFERENCES `estado` (`idEstado`);

--
-- Filtros para la tabla `tarifa`
--
ALTER TABLE `tarifa`
  ADD CONSTRAINT `fk_tarifa_paseador1` FOREIGN KEY (`Paseador_idPaseador`) REFERENCES `paseador` (`idPaseador`),
  ADD CONSTRAINT `fk_tarifa_peligrosidad1` FOREIGN KEY (`Peligrosidad_idPeligrosidad`) REFERENCES `peligrosidad` (`idPeligrosidad`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
