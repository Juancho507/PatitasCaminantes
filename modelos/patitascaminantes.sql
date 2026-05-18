-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-05-2026 a las 00:37:03
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
(11, '16:00:00', '17:00:00', 1, 3);

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
  `Activo` tinyint(4) NOT NULL,
  `Direccion` varchar(15) NOT NULL,
  `Foto` varchar(50) DEFAULT NULL,
  `Localidad_idLocalidad` int(11) NOT NULL,
  `admin_idAdmin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `dueño`
--

INSERT INTO `dueño` (`idDueño`, `NroDocumento`, `Nombre`, `Apellido`, `Correo`, `Clave`, `Contacto`, `Activo`, `Direccion`, `Foto`, `Localidad_idLocalidad`, `admin_idAdmin`) VALUES
(1, '1001001001', 'Carlos', 'Perez', 'carlos@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 300111111, 1, 'Calle 1', 'imagenes/1779141855.png', 1, 1),
(2, '1001001002', 'Laura', 'Gomez', 'laura@gmail.com', '1234', 300111112, 1, 'Calle 2', 'laura.jpg', 2, 1),
(3, '1001001003', 'Mateo', 'Ruiz', 'mateo@gmail.com', '1234', 300111113, 1, 'Calle 3', 'mateo.jpg', 3, 1),
(4, '1001001004', 'Valentina', 'Diaz', 'valentina@gmail.com', '1234', 300111114, 1, 'Calle 4', 'vale.jpg', 4, 1),
(5, '1001001005', 'Andres', 'Torres', 'andres@gmail.com', '1234', 300111115, 1, 'Calle 5', 'andres.jpg', 5, 2),
(6, '1001001006', 'Camila', 'Rojas', 'camila@gmail.com', '1234', 300111116, 1, 'Calle 6', 'camila.jpg', 6, 2),
(7, '1001001007', 'Sebastian', 'Moreno', 'sebastian@gmail.com', '1234', 300111117, 1, 'Calle 7', 'sebas.jpg', 7, 2),
(8, '1001001008', 'Daniela', 'Vargas', 'daniela@gmail.com', '1234', 300111118, 1, 'Calle 8', 'daniela.jpg', 8, 2),
(9, '1001001009', 'Juan', 'Castro', 'juan@gmail.com', '1234', 300111119, 1, 'Calle 9', 'juan.jpg', 9, 1),
(10, '1001001010', 'Sara', 'Mendez', 'sara@gmail.com', '1234', 300111120, 1, 'Calle 10', 'sara.jpg', 10, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `idEstado` int(11) NOT NULL,
  `Nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`idEstado`, `Nombre`) VALUES
(1, 'pendiente'),
(2, 'aprobado'),
(3, 'rechazado'),
(4, 'bloqueado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadopaseo`
--

CREATE TABLE `estadopaseo` (
  `idEstadoPaseo` int(11) NOT NULL,
  `Estado` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estadopaseo`
--

INSERT INTO `estadopaseo` (`idEstadoPaseo`, `Estado`) VALUES
(1, 'En espera'),
(2, 'Aceptado'),
(3, 'En curso'),
(4, 'Completado'),
(5, 'Cancelado dueño'),
(6, 'Cancelado paseador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadosolicitud`
--

CREATE TABLE `estadosolicitud` (
  `idEstadoSolicitud` int(11) NOT NULL,
  `Estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `estadosolicitud`
--

INSERT INTO `estadosolicitud` (`idEstadoSolicitud`, `Estado`) VALUES
(1, 'pendiente'),
(2, 'aprobado'),
(3, 'rechazado');

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
-- Estructura de tabla para la tabla `paseador`
--

CREATE TABLE `paseador` (
  `idPaseador` int(11) NOT NULL,
  `Nombre` varchar(15) NOT NULL,
  `Apellido` varchar(15) NOT NULL,
  `NroDocumento` varchar(12) DEFAULT NULL,
  `FechaNacimiento` date DEFAULT NULL,
  `Correo` varchar(25) NOT NULL,
  `Clave` varchar(45) NOT NULL,
  `Contacto` int(11) NOT NULL,
  `Estado_idEstado` int(11) NOT NULL DEFAULT 1,
  `Informacion` varchar(500) NOT NULL,
  `Foto` varchar(50) DEFAULT NULL,
  `HojaDeVida` varchar(50) NOT NULL,
  `Certificados` varchar(50) DEFAULT NULL,
  `Admin_idAdmin` int(11) NOT NULL,
  `Localidad_idLocalidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `paseador`
--

INSERT INTO `paseador` (`idPaseador`, `Nombre`, `Apellido`, `NroDocumento`, `FechaNacimiento`, `Correo`, `Clave`, `Contacto`, `Estado_idEstado`, `Informacion`, `Foto`, `HojaDeVida`, `Certificados`, `Admin_idAdmin`, `Localidad_idLocalidad`) VALUES
(1, 'Kevin', 'Lopez', '1020301001', '1995-03-15', 'kevin@gmail.com', '1234', 311111111, 2, 'Experiencia 3 años paseando perros', 'kevin.jpg', 'hv_kevin.pdf', 'cert1.pdf', 1, 1),
(2, 'Natalia', 'Garcia', '1020301002', '1997-07-22', 'natalia@gmail.com', '1234', 311111112, 2, 'Ama los animales y tiene experiencia veterinaria', 'nata.jpg', 'hv_nata.pdf', 'cert2.pdf', 1, 2),
(3, 'Felipe', 'Martinez', '1020301003', '1993-11-08', 'felipe@gmail.com', '1234', 311111113, 2, 'Especialista en perros grandes', 'felipe.jpg', 'hv_felipe.pdf', 'cert3.pdf', 2, 3),
(4, 'Paula', 'Herrera', '1020301004', '1996-05-30', 'paula@gmail.com', '1234', 311111114, 2, 'Paseadora profesional certificada', 'paula.jpg', 'hv_paula.pdf', 'cert4.pdf', 2, 4),
(5, 'David', 'Jimenez', '1020301005', '1994-09-12', 'david@gmail.com', '1234', 311111115, 2, 'Cuidador y entrenador canino', 'david.jpg', 'hv_david.pdf', 'cert5.pdf', 1, 5),
(16, 'Pedro', 'Ramirez', '1020301016', '1999-01-25', 'pedro@aspirante.com', '81dc9bdb52d04dc20036dbd8313ed055', 311111116, 3, 'Aspirante en revision', NULL, 'hv_pedro.pdf', 'cert_pedro.pdf', 1, 1),
(17, 'Maria', 'Lopez', '1020301017', '1998-04-14', 'maria@rechazado.com', '81dc9bdb52d04dc20036dbd8313ed055', 311111117, 2, 'Especialista en perros grandes', NULL, 'hv_maria.pdf', 'cert_maria.pdf', 1, 2);

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
  `Paseador_idPaseador` int(11) NOT NULL,
  `EstadoPaseo_idEstadoPaseo` int(11) NOT NULL,
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

INSERT INTO `paseo` (`idPaseo`, `FechaInicio`, `FechaFin`, `Bozal`, `Observaciones`, `MotivoCancelacion`, `Paseador_idPaseador`, `EstadoPaseo_idEstadoPaseo`, `perro_idPerro`, `perro_idPerro2`, `perro_idPerro3`, `perro_idPerro4`, `perro_idPerro5`, `perro_idPerro6`) VALUES
(1, '2026-05-01 08:00:00', '2026-05-01 09:00:00', 0, 'Todo bien', NULL, 1, 4, 1, NULL, NULL, NULL, NULL, NULL),
(2, '2026-05-02 10:00:00', '2026-05-02 11:00:00', 0, 'Muy tranquilo', NULL, 2, 4, 2, NULL, NULL, NULL, NULL, NULL),
(3, '2026-05-03 09:00:00', '2026-05-03 10:00:00', 1, 'Necesito bozal', NULL, 3, 3, 3, NULL, NULL, NULL, NULL, NULL),
(4, '2026-05-04 07:00:00', '2026-05-04 08:00:00', 0, 'Activo', NULL, 4, 2, 4, NULL, NULL, NULL, NULL, NULL),
(5, '2026-05-05 12:00:00', '2026-05-05 13:00:00', 1, 'Muy fuerte', NULL, 5, 1, 5, NULL, NULL, NULL, NULL, NULL),
(6, '2026-05-06 14:00:00', '2026-05-06 15:00:00', 0, NULL, 'El dueño cancelo', 1, 5, 6, NULL, NULL, NULL, NULL, NULL),
(7, '2026-05-07 15:00:00', '2026-05-07 16:00:00', 1, NULL, 'El paseador cancelo', 2, 6, 7, NULL, NULL, NULL, NULL, NULL),
(8, '2026-05-08 16:00:00', '2026-05-08 17:00:00', 0, 'Excelente paseo', NULL, 3, 4, 8, NULL, NULL, NULL, NULL, NULL),
(9, '2026-05-09 17:00:00', '2026-05-09 18:00:00', 1, 'Perro inquieto', NULL, 4, 3, 9, NULL, NULL, NULL, NULL, NULL),
(10, '2026-05-10 18:00:00', '2026-05-10 19:00:00', 1, 'Sin problemas', NULL, 5, 2, 10, NULL, NULL, NULL, NULL, NULL),
(15, '2026-05-20 16:00:00', '2026-05-20 17:00:00', 0, '', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, NULL),
(16, '2026-05-25 08:00:00', '2026-05-25 09:00:00', 0, '', NULL, 1, 4, 1, NULL, NULL, NULL, NULL, NULL),
(18, '2026-05-27 16:00:00', '2026-05-27 17:00:00', 0, '', NULL, 1, 4, 1, NULL, NULL, NULL, NULL, NULL);

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
  `Activo` tinyint(4) NOT NULL,
  `Foto` varchar(50) DEFAULT NULL,
  `Raza_idRaza` int(11) NOT NULL,
  `Dueño_idDueño` int(11) NOT NULL,
  `Peligrosidad_idPeligrosidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `perro`
--

INSERT INTO `perro` (`idPerro`, `Nombre`, `Peso`, `Recomendacion`, `Activo`, `Foto`, `Raza_idRaza`, `Dueño_idDueño`, `Peligrosidad_idPeligrosidad`) VALUES
(1, 'Max', 12.50, 'No acercarlo a gatos y perros mas grandes', 1, 'max.jpg', 1, 1, 2),
(2, 'Luna', 8.00, 'Muy amigable', 1, 'luna.jpg', 3, 2, 1),
(3, 'Rocky', 25.00, 'Usar bozal', 1, 'rocky.jpg', 14, 3, 4),
(4, 'Toby', 18.00, 'Le gusta correr', 1, 'toby.jpg', 8, 4, 2),
(5, 'Nala', 30.00, 'Evitar otros machos', 1, 'nala.jpg', 12, 5, 3),
(6, 'Simba', 5.00, 'Muy pequeño', 1, 'simba.jpg', 6, 6, 1),
(7, 'Zeus', 40.00, 'Perro protector', 1, 'zeus.jpg', 16, 7, 4),
(8, 'Milo', 10.00, 'Jugueton', 1, 'milo.jpg', 22, 8, 1),
(9, 'Kira', 50.00, 'Necesita espacio', 1, 'kira.jpg', 18, 9, 3),
(10, 'Bruno', 65.00, 'Muy fuerte', 1, 'bruno.jpg', 20, 10, 4);

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
  `EstadoSolicitud_idEstadoSolicitud` int(11) NOT NULL DEFAULT 1,
  `FechaSolicitud` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `solicitudraza`
--

INSERT INTO `solicitudraza` (`idSolicitud`, `NombreRaza`, `idDueño`, `EstadoSolicitud_idEstadoSolicitud`, `FechaSolicitud`) VALUES
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
(190, 15000, '2026-05-18', 0, 1, 1),
(191, 16000, '2026-05-18', 1, 1, 1),
(192, 17000, '2026-05-18', 1, 1, 2);

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
  ADD KEY `fk_dueño_Localidad1_idx` (`Localidad_idLocalidad`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`idEstado`);

--
-- Indices de la tabla `estadopaseo`
--
ALTER TABLE `estadopaseo`
  ADD PRIMARY KEY (`idEstadoPaseo`);

--
-- Indices de la tabla `estadosolicitud`
--
ALTER TABLE `estadosolicitud`
  ADD PRIMARY KEY (`idEstadoSolicitud`);

--
-- Indices de la tabla `localidad`
--
ALTER TABLE `localidad`
  ADD PRIMARY KEY (`idLocalidad`),
  ADD KEY `fk_Localidad_Ciudad1_idx` (`Ciudad_idCiudad`);

--
-- Indices de la tabla `paseador`
--
ALTER TABLE `paseador`
  ADD PRIMARY KEY (`idPaseador`),
  ADD KEY `fk_Paseador_Admin1_idx` (`Admin_idAdmin`),
  ADD KEY `fk_Paseador_Estado1_idx` (`Estado_idEstado`),
  ADD KEY `fk_Paseador_Localidad1_idx` (`Localidad_idLocalidad`);

--
-- Indices de la tabla `paseo`
--
ALTER TABLE `paseo`
  ADD PRIMARY KEY (`idPaseo`),
  ADD KEY `fk_Paseo_Paseador1_idx` (`Paseador_idPaseador`),
  ADD KEY `fk_Paseo_EstadoPaseo1_idx` (`EstadoPaseo_idEstadoPaseo`),
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
  ADD KEY `fk_Perro_Raza1_idx` (`Raza_idRaza`),
  ADD KEY `fk_Perro_Dueño1_idx` (`Dueño_idDueño`),
  ADD KEY `fk_perro_Peligrosidad1_idx` (`Peligrosidad_idPeligrosidad`);

--
-- Indices de la tabla `raza`
--
ALTER TABLE `raza`
  ADD PRIMARY KEY (`idRaza`),
  ADD KEY `fk_Raza_Tamaño_idx` (`Tamaño_idTamaño`);

--
-- Indices de la tabla `solicitudraza`
--
ALTER TABLE `solicitudraza`
  ADD PRIMARY KEY (`idSolicitud`),
  ADD KEY `fk_solicitud_Dueño1_idx` (`idDueño`),
  ADD KEY `fk_solicitud_Estado1_idx` (`EstadoSolicitud_idEstadoSolicitud`);

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
  ADD KEY `fk_Tarifa_Paseador1_idx` (`Paseador_idPaseador`),
  ADD KEY `fk_tarifa_Peligrosidad1_idx` (`Peligrosidad_idPeligrosidad`);

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
  MODIFY `idDisponibilidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `dueño`
--
ALTER TABLE `dueño`
  MODIFY `idDueño` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `idEstado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estadopaseo`
--
ALTER TABLE `estadopaseo`
  MODIFY `idEstadoPaseo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estadosolicitud`
--
ALTER TABLE `estadosolicitud`
  MODIFY `idEstadoSolicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `localidad`
--
ALTER TABLE `localidad`
  MODIFY `idLocalidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `paseador`
--
ALTER TABLE `paseador`
  MODIFY `idPaseador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `paseo`
--
ALTER TABLE `paseo`
  MODIFY `idPaseo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `peligrosidad`
--
ALTER TABLE `peligrosidad`
  MODIFY `idPeligrosidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `perro`
--
ALTER TABLE `perro`
  MODIFY `idPerro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `raza`
--
ALTER TABLE `raza`
  MODIFY `idRaza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=348;

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
  ADD CONSTRAINT `fk_Disponibilidad_DiaSemana1` FOREIGN KEY (`DiaSemana_idDiaSemana`) REFERENCES `diasemana` (`idDiaSemana`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Disponibilidad_paseador1` FOREIGN KEY (`paseador_idPaseador`) REFERENCES `paseador` (`idPaseador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `dueño`
--
ALTER TABLE `dueño`
  ADD CONSTRAINT `fk_dueño_Localidad1` FOREIGN KEY (`Localidad_idLocalidad`) REFERENCES `localidad` (`idLocalidad`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_dueño_admin1` FOREIGN KEY (`admin_idAdmin`) REFERENCES `admin` (`idAdmin`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `localidad`
--
ALTER TABLE `localidad`
  ADD CONSTRAINT `fk_Localidad_Ciudad1` FOREIGN KEY (`Ciudad_idCiudad`) REFERENCES `ciudad` (`idCiudad`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `paseador`
--
ALTER TABLE `paseador`
  ADD CONSTRAINT `fk_Paseador_Admin1` FOREIGN KEY (`Admin_idAdmin`) REFERENCES `admin` (`idAdmin`),
  ADD CONSTRAINT `fk_Paseador_Estado1` FOREIGN KEY (`Estado_idEstado`) REFERENCES `estado` (`idEstado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Paseador_Localidad1` FOREIGN KEY (`Localidad_idLocalidad`) REFERENCES `localidad` (`idLocalidad`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `paseo`
--
ALTER TABLE `paseo`
  ADD CONSTRAINT `fk_Paseo_EstadoPaseo1` FOREIGN KEY (`EstadoPaseo_idEstadoPaseo`) REFERENCES `estadopaseo` (`idEstadoPaseo`),
  ADD CONSTRAINT `fk_Paseo_Paseador1` FOREIGN KEY (`Paseador_idPaseador`) REFERENCES `paseador` (`idPaseador`),
  ADD CONSTRAINT `fk_paseo_perro1` FOREIGN KEY (`perro_idPerro`) REFERENCES `perro` (`idPerro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_paseo_perro2` FOREIGN KEY (`perro_idPerro2`) REFERENCES `perro` (`idPerro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_paseo_perro3` FOREIGN KEY (`perro_idPerro3`) REFERENCES `perro` (`idPerro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_paseo_perro4` FOREIGN KEY (`perro_idPerro4`) REFERENCES `perro` (`idPerro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_paseo_perro5` FOREIGN KEY (`perro_idPerro5`) REFERENCES `perro` (`idPerro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_paseo_perro6` FOREIGN KEY (`perro_idPerro6`) REFERENCES `perro` (`idPerro`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `perro`
--
ALTER TABLE `perro`
  ADD CONSTRAINT `fk_Perro_Dueño1` FOREIGN KEY (`Dueño_idDueño`) REFERENCES `dueño` (`idDueño`),
  ADD CONSTRAINT `fk_Perro_Raza1` FOREIGN KEY (`Raza_idRaza`) REFERENCES `raza` (`idRaza`),
  ADD CONSTRAINT `fk_perro_Peligrosidad1` FOREIGN KEY (`Peligrosidad_idPeligrosidad`) REFERENCES `peligrosidad` (`idPeligrosidad`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `raza`
--
ALTER TABLE `raza`
  ADD CONSTRAINT `fk_Raza_Tamaño` FOREIGN KEY (`Tamaño_idTamaño`) REFERENCES `tamaño` (`idTamaño`);

--
-- Filtros para la tabla `solicitudraza`
--
ALTER TABLE `solicitudraza`
  ADD CONSTRAINT `fk_solicitud_Dueño1` FOREIGN KEY (`idDueño`) REFERENCES `dueño` (`idDueño`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_solicitud_Estado1` FOREIGN KEY (`EstadoSolicitud_idEstadoSolicitud`) REFERENCES `estadosolicitud` (`idEstadoSolicitud`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tarifa`
--
ALTER TABLE `tarifa`
  ADD CONSTRAINT `fk_Tarifa_Paseador1` FOREIGN KEY (`Paseador_idPaseador`) REFERENCES `paseador` (`idPaseador`),
  ADD CONSTRAINT `fk_tarifa_Peligrosidad1` FOREIGN KEY (`Peligrosidad_idPeligrosidad`) REFERENCES `peligrosidad` (`idPeligrosidad`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
