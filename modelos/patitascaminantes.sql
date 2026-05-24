
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `idAdmin` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(15) NOT NULL,
  `Apellido` varchar(15) NOT NULL,
  `Correo` varchar(25) NOT NULL,
  `Clave` varchar(45) NOT NULL,
  `Contacto` int(11) NOT NULL,
  PRIMARY KEY (`idAdmin`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'Juan','Ortiz','juan@patitas.com','f5737d25829e95b9c234b7fa06af8736',300123456),(2,'Juliana','Cardenas','juliana@patitas.com','70f5fb779be1312f0b2bcdcf922576c5',301987654);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
DROP TABLE IF EXISTS `ciudad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ciudad` (
  `idCiudad` int(11) NOT NULL AUTO_INCREMENT,
  `Ciudad` varchar(12) NOT NULL,
  PRIMARY KEY (`idCiudad`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `ciudad` DISABLE KEYS */;
INSERT INTO `ciudad` VALUES (1,'Bogota'),(2,'Medellin'),(3,'Cali'),(4,'Cartagena'),(5,'Barranquilla'),(6,'Bucaramanga'),(7,'Pereira'),(8,'Manizales');
/*!40000 ALTER TABLE `ciudad` ENABLE KEYS */;
DROP TABLE IF EXISTS `diasemana`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `diasemana` (
  `idDiaSemana` int(11) NOT NULL AUTO_INCREMENT,
  `Dia` varchar(11) NOT NULL,
  PRIMARY KEY (`idDiaSemana`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `diasemana` DISABLE KEYS */;
INSERT INTO `diasemana` VALUES (1,'Lunes'),(2,'Martes'),(3,'Miercoles'),(4,'Jueves'),(5,'Viernes'),(6,'Sabado'),(7,'Domingo');
/*!40000 ALTER TABLE `diasemana` ENABLE KEYS */;
DROP TABLE IF EXISTS `disponibilidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disponibilidad` (
  `idDisponibilidad` int(11) NOT NULL AUTO_INCREMENT,
  `HoraInicio` time NOT NULL,
  `HoraFin` time NOT NULL,
  `paseador_idPaseador` int(11) NOT NULL,
  `DiaSemana_idDiaSemana` int(11) NOT NULL,
  PRIMARY KEY (`idDisponibilidad`),
  KEY `fk_Disponibilidad_paseador1_idx` (`paseador_idPaseador`),
  KEY `fk_Disponibilidad_DiaSemana1_idx` (`DiaSemana_idDiaSemana`),
  CONSTRAINT `fk_Disponibilidad_diasemana1` FOREIGN KEY (`DiaSemana_idDiaSemana`) REFERENCES `diasemana` (`idDiaSemana`),
  CONSTRAINT `fk_Disponibilidad_paseador1` FOREIGN KEY (`paseador_idPaseador`) REFERENCES `paseador` (`idPaseador`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `disponibilidad` DISABLE KEYS */;
INSERT INTO `disponibilidad` VALUES (1,'08:00:00','12:00:00',1,1),(3,'09:00:00','13:00:00',2,2),(4,'15:00:00','19:00:00',2,4),(5,'07:00:00','11:00:00',3,5),(6,'13:00:00','17:00:00',3,6),(7,'10:00:00','14:00:00',4,1),(8,'16:00:00','20:00:00',4,7),(9,'06:00:00','10:00:00',5,2),(10,'12:00:00','16:00:00',5,5),(11,'16:00:00','17:00:00',1,3),(50,'08:00:00','12:00:00',50,1),(51,'14:00:00','18:00:00',50,3),(52,'08:00:00','12:00:00',50,5),(53,'08:00:00','12:00:00',51,2),(54,'14:00:00','18:00:00',51,4),(55,'08:00:00','12:00:00',51,6),(56,'08:00:00','12:00:00',52,1),(57,'14:00:00','18:00:00',52,5);
/*!40000 ALTER TABLE `disponibilidad` ENABLE KEYS */;
DROP TABLE IF EXISTS `dueño`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dueño` (
  `idDueño` int(11) NOT NULL AUTO_INCREMENT,
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
  `admin_idAdmin` int(11) NOT NULL,
  PRIMARY KEY (`idDueño`),
  KEY `fk_dueño_admin1_idx` (`admin_idAdmin`),
  KEY `fk_dueño_Localidad1_idx` (`Localidad_idLocalidad`),
  KEY `fk_dueño_Estado1_idx` (`Estado_idEstado`),
  CONSTRAINT `fk_dueno_admin1` FOREIGN KEY (`admin_idAdmin`) REFERENCES `admin` (`idAdmin`),
  CONSTRAINT `fk_dueno_estado1` FOREIGN KEY (`Estado_idEstado`) REFERENCES `estado` (`idEstado`),
  CONSTRAINT `fk_dueno_localidad1` FOREIGN KEY (`Localidad_idLocalidad`) REFERENCES `localidad` (`idLocalidad`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `dueño` DISABLE KEYS */;
INSERT INTO `dueño` VALUES (1,'1001001001','Carlos','Perez','carlos@gmail.com','81dc9bdb52d04dc20036dbd8313ed055',300111111,'Calle 1','imagenes/1779141855.png',2,1,1),(2,'1001001002','Laura','Gomez','laura@gmail.com','1234',300111112,'Calle 2','laura.jpg',2,2,1),(3,'1001001003','Mateo','Ruiz','mateo@gmail.com','1234',300111113,'Calle 3','mateo.jpg',2,3,1),(4,'1001001004','Valentina','Diaz','valentina@gmail.com','1234',300111114,'Calle 4','vale.jpg',2,4,1),(5,'1001001005','Andres','Torres','andres@gmail.com','1234',300111115,'Calle 5','andres.jpg',2,5,2),(6,'1001001006','Camila','Rojas','camila@gmail.com','1234',300111116,'Calle 6','camila.jpg',2,6,2),(7,'1001001007','Sebastian','Moreno','sebastian@gmail.com','1234',300111117,'Calle 7','sebas.jpg',2,7,2),(8,'1001001008','Daniela','Vargas','daniela@gmail.com','1234',300111118,'Calle 8','daniela.jpg',2,8,2),(9,'1001001009','Juan','Castro','juan@gmail.com','1234',300111119,'Calle 9','juan.jpg',2,9,1),(10,'1001001010','Sara','Mendez','sara@gmail.com','1234',300111120,'Calle 10','sara.jpg',2,10,1),(50,'1001001011','Carlos','Mendoza','carlos@email.com','81dc9bdb52d04dc20036dbd8313ed055',300111101,'Cra 1 #2-3',NULL,2,1,1),(51,'1001001012','Laura','Giraldo','laura@email.com','81dc9bdb52d04dc20036dbd8313ed055',300111102,'Cra 2 #3-4',NULL,2,2,1),(52,'1001001013','Pedro','Ramirez','pedro@email.com','81dc9bdb52d04dc20036dbd8313ed055',300111103,'Cra 3 #4-5',NULL,4,3,1),(53,'1001001014','Ana','Martinez','ana@email.com','81dc9bdb52d04dc20036dbd8313ed055',300111104,'Cra 4 #5-6',NULL,2,4,1);
/*!40000 ALTER TABLE `dueño` ENABLE KEYS */;
DROP TABLE IF EXISTS `estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estado` (
  `idEstado` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(25) NOT NULL,
  PRIMARY KEY (`idEstado`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `estado` DISABLE KEYS */;
INSERT INTO `estado` VALUES (1,'pendiente'),(2,'aprobado'),(3,'rechazado'),(4,'bloqueado'),(5,'en curso'),(6,'completado'),(7,'rechazado paseador');
/*!40000 ALTER TABLE `estado` ENABLE KEYS */;
DROP TABLE IF EXISTS `localidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `localidad` (
  `idLocalidad` int(11) NOT NULL AUTO_INCREMENT,
  `Localidad` varchar(10) NOT NULL,
  `Ciudad_idCiudad` int(11) NOT NULL,
  PRIMARY KEY (`idLocalidad`),
  KEY `fk_localidad_ciudad1_idx` (`Ciudad_idCiudad`),
  CONSTRAINT `fk_localidad_ciudad1` FOREIGN KEY (`Ciudad_idCiudad`) REFERENCES `ciudad` (`idCiudad`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `localidad` DISABLE KEYS */;
INSERT INTO `localidad` VALUES (1,'Suba',1),(2,'Kennedy',1),(3,'Engativa',1),(4,'Usaquen',1),(5,'Chapinero',1),(6,'Belen',2),(7,'Poblado',2),(8,'Laureles',2),(9,'Aguablanca',3),(10,'SanFernand',3),(11,'Bocagrande',4),(12,'Centro',4),(13,'Riomar',5),(14,'Cabecera',6),(15,'Centro',7),(16,'Palogrande',8);
/*!40000 ALTER TABLE `localidad` ENABLE KEYS */;
DROP TABLE IF EXISTS `log_paseo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_paseo` (
  `idLog` int(11) NOT NULL AUTO_INCREMENT,
  `idPaseo` int(11) NOT NULL,
  `EstadoAnterior` int(11) DEFAULT NULL,
  `EstadoNuevo` int(11) DEFAULT NULL,
  `FechaCambio` timestamp NOT NULL DEFAULT current_timestamp(),
  `Motivo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idLog`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `log_paseo` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_paseo` ENABLE KEYS */;
DROP TABLE IF EXISTS `paseador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paseador` (
  `idPaseador` int(11) NOT NULL AUTO_INCREMENT,
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
  `AprobadoPeligroso` tinyint(1) DEFAULT 0,
  `Multas` int(11) DEFAULT 0,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `Admin_idAdmin` int(11) NOT NULL,
  `Localidad_idLocalidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPaseador`),
  KEY `fk_paseador_estado1_idx` (`Estado_idEstado`),
  KEY `fk_paseador_admin1_idx` (`Admin_idAdmin`),
  KEY `fk_paseador_localidad1_idx` (`Localidad_idLocalidad`),
  CONSTRAINT `fk_paseador_admin1` FOREIGN KEY (`Admin_idAdmin`) REFERENCES `admin` (`idAdmin`),
  CONSTRAINT `fk_paseador_estado1` FOREIGN KEY (`Estado_idEstado`) REFERENCES `estado` (`idEstado`),
  CONSTRAINT `fk_paseador_localidad1` FOREIGN KEY (`Localidad_idLocalidad`) REFERENCES `localidad` (`idLocalidad`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `paseador` DISABLE KEYS */;
INSERT INTO `paseador` VALUES (1,'Kevin','Lopez','1020301001','1995-03-15','kevin@gmail.com','1234',311111111,2,'Experiencia 3 años paseando perros','kevin.jpg','hv_kevin.pdf','cert1.pdf',1,0,'2026-05-23 23:56:44',1,1),(2,'Natalia','Garcia','1020301002','1997-07-22','natalia@gmail.com','1234',311111112,2,'Ama los animales y tiene experiencia veterinaria','nata.jpg','hv_nata.pdf','cert2.pdf',1,0,'2026-05-23 23:56:44',1,2),(3,'Felipe','Martinez','1020301003','1993-11-08','felipe@gmail.com','1234',311111113,2,'Especialista en perros grandes','felipe.jpg','hv_felipe.pdf','cert3.pdf',0,0,'2026-05-23 23:56:44',2,3),(4,'Paula','Herrera','1020301004','1996-05-30','paula@gmail.com','1234',311111114,2,'Paseadora profesional certificada','paula.jpg','hv_paula.pdf','cert4.pdf',1,0,'2026-05-23 23:56:44',2,4),(5,'David','Jimenez','1020301005','1994-09-12','david@gmail.com','1234',311111115,2,'Cuidador y entrenador canino','david.jpg','hv_david.pdf','cert5.pdf',1,0,'2026-05-23 23:56:44',1,5),(16,'Pedro','Ramirez','1020301016','1999-01-25','pedro@aspirante.com','81dc9bdb52d04dc20036dbd8313ed055',311111116,3,'Aspirante en revision',NULL,'hv_pedro.pdf','cert_pedro.pdf',0,0,'2026-05-23 23:56:44',1,1),(17,'Maria','Lopez','1020301017','1998-04-14','maria@rechazado.com','81dc9bdb52d04dc20036dbd8313ed055',311111117,2,'Especialista en perros grandes',NULL,'hv_maria.pdf','cert_maria.pdf',1,0,'2026-05-23 23:56:44',2,2),(50,'Andres','Torres','1020301018','1995-03-15','andres@email.com','81dc9bdb52d04dc20036dbd8313ed055',311111001,2,'Paseador con experiencia en perros peligrosos',NULL,'hv_andres.pdf','cert_andres.pdf',1,0,'2026-05-23 23:56:44',1,1),(51,'Sofia','Rivera','1020301019','1997-07-22','sofia@email.com','81dc9bdb52d04dc20036dbd8313ed055',311111002,2,'Paseadora responsable con referencias',NULL,'hv_sofia.pdf','cert_sofia.pdf',0,0,'2026-05-23 23:56:44',1,2),(52,'Diego','Herrera','1020301022','1993-11-08','diego@email.com','81dc9bdb52d04dc20036dbd8313ed055',311111003,1,'Aspirante en proceso de revisión',NULL,'hv_diego.pdf','cert_diego.pdf',0,0,'2026-05-23 23:56:44',1,3),(53,'Camila','Rojas','1020301058','1996-05-30','camila@email.com','81dc9bdb52d04dc20036dbd8313ed055',311111004,4,'Paseadora bloqueada por mal comportamiento',NULL,'hv_camila.pdf','cert_camila.pdf',0,2,'2026-05-23 23:56:44',1,4);
/*!40000 ALTER TABLE `paseador` ENABLE KEYS */;
DROP TABLE IF EXISTS `paseo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paseo` (
  `idPaseo` int(11) NOT NULL AUTO_INCREMENT,
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
  `perro_idPerro6` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPaseo`),
  KEY `fk_paseo_paseador1_idx` (`Paseador_idPaseador`),
  KEY `fk_paseo_estado1_idx` (`Estado_idEstado`),
  KEY `fk_paseo_perro1_idx` (`perro_idPerro`),
  KEY `fk_paseo_perro2_idx` (`perro_idPerro2`),
  KEY `fk_paseo_perro3_idx` (`perro_idPerro3`),
  KEY `fk_paseo_perro4_idx` (`perro_idPerro4`),
  KEY `fk_paseo_perro5_idx` (`perro_idPerro5`),
  KEY `fk_paseo_perro6_idx` (`perro_idPerro6`),
  CONSTRAINT `fk_paseo_estado1` FOREIGN KEY (`Estado_idEstado`) REFERENCES `estado` (`idEstado`),
  CONSTRAINT `fk_paseo_paseador1` FOREIGN KEY (`Paseador_idPaseador`) REFERENCES `paseador` (`idPaseador`),
  CONSTRAINT `fk_paseo_perro1` FOREIGN KEY (`perro_idPerro`) REFERENCES `perro` (`idPerro`),
  CONSTRAINT `fk_paseo_perro2` FOREIGN KEY (`perro_idPerro2`) REFERENCES `perro` (`idPerro`),
  CONSTRAINT `fk_paseo_perro3` FOREIGN KEY (`perro_idPerro3`) REFERENCES `perro` (`idPerro`),
  CONSTRAINT `fk_paseo_perro4` FOREIGN KEY (`perro_idPerro4`) REFERENCES `perro` (`idPerro`),
  CONSTRAINT `fk_paseo_perro5` FOREIGN KEY (`perro_idPerro5`) REFERENCES `perro` (`idPerro`),
  CONSTRAINT `fk_paseo_perro6` FOREIGN KEY (`perro_idPerro6`) REFERENCES `perro` (`idPerro`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `paseo` DISABLE KEYS */;
INSERT INTO `paseo` VALUES (1,'2026-05-01 08:00:00','2026-05-01 09:00:00',0,'Todo bien',NULL,'2026-04-30 10:00:00',1,6,1,NULL,NULL,NULL,NULL,NULL),(2,'2026-05-02 10:00:00','2026-05-02 11:00:00',0,'Muy tranquilo',NULL,'2026-05-01 10:00:00',2,6,2,NULL,NULL,NULL,NULL,NULL),(3,'2026-05-03 09:00:00','2026-05-03 10:00:00',1,'Necesito bozal',NULL,'2026-05-02 10:00:00',4,5,3,NULL,NULL,NULL,NULL,NULL),(4,'2026-05-04 07:00:00','2026-05-04 08:00:00',0,'Activo',NULL,'2026-05-03 10:00:00',4,2,4,NULL,NULL,NULL,NULL,NULL),(5,'2026-05-05 12:00:00','2026-05-05 13:00:00',1,'Muy fuerte',NULL,'2026-05-04 10:00:00',5,1,5,NULL,NULL,NULL,NULL,NULL),(6,'2026-05-06 14:00:00','2026-05-06 15:00:00',0,NULL,'El dueño cancelo','2026-05-05 10:00:00',1,3,6,NULL,NULL,NULL,NULL,NULL),(7,'2026-05-07 15:00:00','2026-05-07 16:00:00',1,NULL,'El paseador cancelo','2026-05-06 10:00:00',2,7,7,NULL,NULL,NULL,NULL,NULL),(8,'2026-05-08 16:00:00','2026-05-08 17:00:00',0,'Excelente paseo',NULL,'2026-05-07 10:00:00',3,6,8,NULL,NULL,NULL,NULL,NULL),(9,'2026-05-09 17:00:00','2026-05-09 18:00:00',1,'Perro inquieto',NULL,'2026-05-08 10:00:00',4,5,9,NULL,NULL,NULL,NULL,NULL),(10,'2026-05-10 18:00:00','2026-05-10 19:00:00',1,'Sin problemas',NULL,'2026-05-09 10:00:00',5,2,10,NULL,NULL,NULL,NULL,NULL),(15,'2026-05-20 16:00:00','2026-05-20 17:00:00',0,'','','2026-05-19 10:00:00',1,1,1,NULL,NULL,NULL,NULL,NULL),(16,'2026-05-25 08:00:00','2026-05-25 09:00:00',0,'',NULL,'2026-05-24 10:00:00',1,6,1,NULL,NULL,NULL,NULL,NULL),(18,'2026-05-27 16:00:00','2026-05-27 17:00:00',0,'',NULL,'2026-05-26 10:00:00',1,6,1,NULL,NULL,NULL,NULL,NULL),(50,'2026-06-15 08:00:00','2026-06-15 09:00:00',1,'Paseo de Rocky (Peligroso) con paseador aprobado',NULL,'2026-06-14 10:00:00',50,2,52,NULL,NULL,NULL,NULL,NULL),(51,'2026-06-15 10:00:00','2026-06-15 11:00:00',0,'Bruno (Alto) + Lola (Bajo) - capacidad Alto=2',NULL,'2026-06-14 10:00:00',51,2,54,60,NULL,NULL,NULL,NULL),(52,'2026-06-16 09:00:00','2026-06-16 10:00:00',0,'Max + Luna (Bajo) + Toby (Medio) - capacidad Medio=3',NULL,'2026-06-15 10:00:00',50,2,50,51,55,NULL,NULL,NULL),(53,'2026-06-14 14:00:00','2026-06-14 15:00:00',0,'Nina (Medio) + Simba (Bajo) - capacidad Medio=3',NULL,'2026-06-13 10:00:00',51,6,56,59,NULL,NULL,NULL,NULL),(54,'2026-06-17 08:00:00','2026-06-17 09:00:00',0,'5 perros nivel Bajo - capacidad Bajo=5',NULL,'2026-06-16 10:00:00',51,1,50,51,61,59,60,NULL),(55,'2026-06-17 10:00:00','2026-06-17 11:00:00',0,'Thor (Alto) + Mia (Bajo) - capacidad Alto=2',NULL,'2026-06-16 10:00:00',50,1,53,61,NULL,NULL,NULL,NULL),(56,'2026-06-14 08:00:00','2026-06-14 09:00:00',0,'Rex + Zoe (Medio) - capacidad Medio=3',NULL,'2026-06-13 10:00:00',51,6,57,58,NULL,NULL,NULL,NULL),(57,'2026-06-18 08:00:00','2026-06-18 09:00:00',1,'Rocky (Peligroso) - segundo paseo con Andres',NULL,'2026-06-17 10:00:00',50,1,52,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `paseo` ENABLE KEYS */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_evitar_choque_horario`
BEFORE INSERT ON `paseo`
FOR EACH ROW
BEGIN
    DECLARE existing INT;
    SELECT COUNT(*) INTO existing FROM `paseo`
    WHERE `Perro_idPerro` = NEW.`Perro_idPerro`
      AND `FechaInicio` = NEW.`FechaInicio`
      AND `Estado_idEstado` NOT IN (3, 5);
    IF existing > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El perro ya tiene un paseo programado en esta fecha y hora.';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_validar_perro_peligroso`
BEFORE INSERT ON `paseo`
FOR EACH ROW
BEGIN
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
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_validar_capacidad_riesgo`
BEFORE INSERT ON `paseo`
FOR EACH ROW
BEGIN
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
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_bloquear_horario_peligroso`
BEFORE INSERT ON `paseo`
FOR EACH ROW
BEGIN
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
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_auditar_estado_paseo AFTER UPDATE ON paseo FOR EACH ROW
BEGIN
    IF OLD.Estado_idEstado != NEW.Estado_idEstado THEN
        INSERT INTO log_paseo (idPaseo, EstadoAnterior, EstadoNuevo, FechaCambio)
        VALUES (NEW.idPaseo, OLD.Estado_idEstado, NEW.Estado_idEstado, NOW());
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
DROP TABLE IF EXISTS `peligrosidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peligrosidad` (
  `idPeligrosidad` int(11) NOT NULL AUTO_INCREMENT,
  `Nivel` varchar(10) NOT NULL,
  PRIMARY KEY (`idPeligrosidad`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `peligrosidad` DISABLE KEYS */;
INSERT INTO `peligrosidad` VALUES (1,'Bajo'),(2,'Medio'),(3,'Alto'),(4,'Peligroso');
/*!40000 ALTER TABLE `peligrosidad` ENABLE KEYS */;
DROP TABLE IF EXISTS `perro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perro` (
  `idPerro` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(15) NOT NULL,
  `Peso` decimal(5,2) NOT NULL,
  `Recomendacion` varchar(250) NOT NULL,
  `Foto` varchar(50) DEFAULT NULL,
  `Estado_idEstado` int(11) NOT NULL DEFAULT 2,
  `Raza_idRaza` int(11) NOT NULL,
  `Dueño_idDueño` int(11) NOT NULL,
  `Peligrosidad_idPeligrosidad` int(11) NOT NULL,
  PRIMARY KEY (`idPerro`),
  KEY `fk_perro_estado1_idx` (`Estado_idEstado`),
  KEY `fk_perro_raza1_idx` (`Raza_idRaza`),
  KEY `fk_perro_due_idx` (`Dueño_idDueño`),
  KEY `fk_perro_peligrosidad1_idx` (`Peligrosidad_idPeligrosidad`),
  CONSTRAINT `fk_perro_dueno1` FOREIGN KEY (`Dueño_idDueño`) REFERENCES `dueño` (`idDueño`),
  CONSTRAINT `fk_perro_estado1` FOREIGN KEY (`Estado_idEstado`) REFERENCES `estado` (`idEstado`),
  CONSTRAINT `fk_perro_peligrosidad1` FOREIGN KEY (`Peligrosidad_idPeligrosidad`) REFERENCES `peligrosidad` (`idPeligrosidad`),
  CONSTRAINT `fk_perro_raza1` FOREIGN KEY (`Raza_idRaza`) REFERENCES `raza` (`idRaza`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `perro` DISABLE KEYS */;
INSERT INTO `perro` VALUES (1,'Max',12.50,'No acercarlo a gatos y perros mas grandes','max.jpg',2,1,1,2),(2,'Luna',8.00,'Muy amigable','luna.jpg',2,3,2,1),(3,'Rocky',25.00,'Usar bozal','rocky.jpg',2,14,3,4),(4,'Toby',18.00,'Le gusta correr','toby.jpg',2,8,4,2),(5,'Nala',30.00,'Evitar otros machos','nala.jpg',2,12,5,3),(6,'Simba',5.00,'Muy pequeño','simba.jpg',2,6,6,1),(7,'Zeus',40.00,'Perro protector','zeus.jpg',2,16,7,4),(8,'Milo',10.00,'Juguetón','milo.jpg',2,22,8,1),(9,'Kira',50.00,'Necesita espacio','kira.jpg',2,18,9,3),(10,'Bruno',65.00,'Muy fuerte','bruno.jpg',2,20,10,4),(50,'Max',9.50,'Jugueteón y tranquilo',NULL,2,3,50,1),(51,'Luna',7.50,'Muy amigable con otros perros',NULL,2,4,51,1),(52,'Rocky',28.00,'Requiere paseador certificado para perros peligrosos',NULL,2,14,51,4),(53,'Thor',24.00,'Perro energético, necesita espacio',NULL,2,16,53,3),(54,'Bruno',35.00,'Protector con extraños',NULL,2,12,52,3),(55,'Toby',18.50,'Le gusta correr junto a la bicicleta',NULL,2,8,50,2),(56,'Nina',11.00,'Dócil y cariñosa',NULL,2,22,53,2),(57,'Rex',20.00,'Juguetón pero territorial',NULL,2,2,51,2),(58,'Zoe',10.50,'Tranquila y obediente',NULL,2,21,52,2),(59,'Simba',5.00,'Muy pequeño, tener cuidado al caminar',NULL,2,6,50,1),(60,'Lola',14.00,'Amigable con niños',NULL,2,1,53,1),(61,'Mia',6.50,'Perfecta para principiantes',NULL,2,5,51,1);
/*!40000 ALTER TABLE `perro` ENABLE KEYS */;
DROP TABLE IF EXISTS `raza`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `raza` (
  `idRaza` int(11) NOT NULL AUTO_INCREMENT,
  `Raza` varchar(30) NOT NULL,
  `Tamaño_idTamaño` int(11) NOT NULL,
  PRIMARY KEY (`idRaza`),
  KEY `fk_raza_tam_idx` (`Tamaño_idTamaño`),
  CONSTRAINT `fk_raza_tamano1` FOREIGN KEY (`Tamaño_idTamaño`) REFERENCES `tamaño` (`idTamaño`)
) ENGINE=InnoDB AUTO_INCREMENT=376 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `raza` DISABLE KEYS */;
INSERT INTO `raza` VALUES (1,'Bulldog-Hembra',2),(2,'Bulldog-Macho',2),(3,'Pug-Hembra',1),(4,'Pug-Macho',1),(5,'Chihuahua-Hembr',1),(6,'Chihuahua-Macho',1),(7,'Labrador-Hembra',3),(8,'Labrador-Macho',3),(9,'Golden-Hembra',3),(10,'Golden-Macho',3),(11,'Pastor-Hembra',3),(12,'Pastor-Macho',3),(13,'Pitbull-Hembra',3),(14,'Pitbull-Macho',3),(15,'Husky-Hembra',3),(16,'Husky-Macho',3),(17,'GranDanes-Hembr',4),(18,'GranDanes-Macho',4),(19,'SanBernardo-Hem',4),(20,'SanBernardo-Mac',4),(21,'Cocker-Hembra',2),(22,'Cocker-Macho',2),(23,'Beagle-Hembra',2),(24,'Beagle-Macho',2),(346,'BUldog Sasaima Hembra',2),(347,'BUldog Sasaima Masculino',2);
/*!40000 ALTER TABLE `raza` ENABLE KEYS */;
DROP TABLE IF EXISTS `solicitudraza`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solicitudraza` (
  `idSolicitud` int(11) NOT NULL AUTO_INCREMENT,
  `NombreRaza` varchar(50) NOT NULL,
  `idDueño` int(11) NOT NULL,
  `Estado_idEstado` int(11) NOT NULL DEFAULT 1,
  `FechaSolicitud` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idSolicitud`),
  KEY `fk_solicitudraza_due_idx` (`idDueño`),
  KEY `fk_solicitudraza_estado1_idx` (`Estado_idEstado`),
  CONSTRAINT `fk_solicitudraza_dueno` FOREIGN KEY (`idDueño`) REFERENCES `dueño` (`idDueño`),
  CONSTRAINT `fk_solicitudraza_estado1` FOREIGN KEY (`Estado_idEstado`) REFERENCES `estado` (`idEstado`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `solicitudraza` DISABLE KEYS */;
INSERT INTO `solicitudraza` VALUES (1,'Bulldog Frances',1,2,'2026-05-18 13:34:02'),(2,'Husky Siberiano',2,3,'2026-05-18 13:34:02'),(3,'Golden Retriever',3,2,'2026-05-18 13:34:02'),(4,'BUldog Sasaima',1,2,'2026-05-18 15:41:33'),(5,'BUldog Sasaima',1,2,'2026-05-18 15:59:13');
/*!40000 ALTER TABLE `solicitudraza` ENABLE KEYS */;
DROP TABLE IF EXISTS `tamaño`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tamaño` (
  `idTamaño` int(11) NOT NULL AUTO_INCREMENT,
  `Tamaño` varchar(12) NOT NULL,
  PRIMARY KEY (`idTamaño`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `tamaño` DISABLE KEYS */;
INSERT INTO `tamaño` VALUES (1,'Pequeño'),(2,'Mediano'),(3,'Grande'),(4,'Gigante');
/*!40000 ALTER TABLE `tamaño` ENABLE KEYS */;
DROP TABLE IF EXISTS `tarifa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tarifa` (
  `idTarifa` int(11) NOT NULL AUTO_INCREMENT,
  `PrecioHora` decimal(10,0) NOT NULL,
  `FechaInicio` date NOT NULL,
  `Activa` tinyint(4) DEFAULT 1,
  `Paseador_idPaseador` int(11) NOT NULL,
  `Peligrosidad_idPeligrosidad` int(11) NOT NULL,
  PRIMARY KEY (`idTarifa`),
  KEY `fk_tarifa_paseador1_idx` (`Paseador_idPaseador`),
  KEY `fk_tarifa_peligrosidad1_idx` (`Peligrosidad_idPeligrosidad`),
  CONSTRAINT `fk_tarifa_paseador1` FOREIGN KEY (`Paseador_idPaseador`) REFERENCES `paseador` (`idPaseador`),
  CONSTRAINT `fk_tarifa_peligrosidad1` FOREIGN KEY (`Peligrosidad_idPeligrosidad`) REFERENCES `peligrosidad` (`idPeligrosidad`)
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `tarifa` DISABLE KEYS */;
INSERT INTO `tarifa` VALUES (1,12000,'2026-01-01',0,1,1),(2,15000,'2026-01-01',0,1,2),(3,18000,'2026-01-01',1,1,3),(4,22000,'2026-01-01',1,1,4),(5,13000,'2026-01-01',1,2,1),(6,16000,'2026-01-01',1,2,2),(7,19000,'2026-01-01',1,2,3),(8,24000,'2026-01-01',1,2,4),(9,14000,'2026-01-01',1,3,1),(10,17000,'2026-01-01',1,3,2),(11,21000,'2026-01-01',1,3,3),(12,26000,'2026-01-01',1,3,4),(13,12500,'2026-01-01',1,4,1),(14,15500,'2026-01-01',1,4,2),(15,18500,'2026-01-01',1,4,3),(16,23000,'2026-01-01',1,4,4),(17,13500,'2026-01-01',1,5,1),(18,16500,'2026-01-01',1,5,2),(19,20000,'2026-01-01',1,5,3),(20,25000,'2026-01-01',1,5,4),(50,25000,'2026-01-01',1,50,1),(51,30000,'2026-01-01',1,50,2),(52,35000,'2026-01-01',1,50,3),(53,45000,'2026-01-01',1,50,4),(54,22000,'2026-01-01',1,51,1),(55,27000,'2026-01-01',1,51,2),(56,32000,'2026-01-01',1,51,3),(57,28000,'2026-01-01',1,52,1),(58,33000,'2026-01-01',1,52,2),(59,25000,'2026-01-01',1,53,1),(190,15000,'2026-05-18',0,1,1),(191,16000,'2026-05-18',1,1,1),(192,17000,'2026-05-18',1,1,2);
/*!40000 ALTER TABLE `tarifa` ENABLE KEYS */;
DROP TABLE IF EXISTS `vw_aspirantes_pendientes`;
/*!50001 DROP VIEW IF EXISTS `vw_aspirantes_pendientes`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_aspirantes_pendientes` AS SELECT
 1 AS `idPaseador`,
  1 AS `Nombre`,
  1 AS `Apellido`,
  1 AS `Correo`,
  1 AS `Contacto`,
  1 AS `Informacion`,
  1 AS `FechaRegistro`,
  1 AS `DiasEspera`,
  1 AS `EstadoRevision` */;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `vw_estadisticas_admin`;
/*!50001 DROP VIEW IF EXISTS `vw_estadisticas_admin`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_estadisticas_admin` AS SELECT
 1 AS `AspirantesPendientes`,
  1 AS `PaseadoresActivos`,
  1 AS `PaseadoresBloqueados`,
  1 AS `PaseosPendientes`,
  1 AS `PaseosAprobados`,
  1 AS `PaseosEnCurso`,
  1 AS `PaseosCompletados`,
  1 AS `TotalPerros`,
  1 AS `DuenosActivos` */;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `vw_paseadores_activos`;
/*!50001 DROP VIEW IF EXISTS `vw_paseadores_activos`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_paseadores_activos` AS SELECT
 1 AS `idPaseador`,
  1 AS `Nombre`,
  1 AS `Apellido`,
  1 AS `Correo`,
  1 AS `Contacto`,
  1 AS `Informacion`,
  1 AS `AprobadoPeligroso`,
  1 AS `Multas`,
  1 AS `FechaRegistro`,
  1 AS `EstadoNombre`,
  1 AS `Localidad`,
  1 AS `Ciudad` */;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `vw_paseos_detalle`;
/*!50001 DROP VIEW IF EXISTS `vw_paseos_detalle`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_paseos_detalle` AS SELECT
 1 AS `idPaseo`,
  1 AS `FechaInicio`,
  1 AS `FechaFin`,
  1 AS `Bozal`,
  1 AS `Observaciones`,
  1 AS `MotivoCancelacion`,
  1 AS `FechaSolicitud`,
  1 AS `idPaseador`,
  1 AS `PaseadorNombre`,
  1 AS `Estado_idEstado`,
  1 AS `EstadoNombre`,
  1 AS `perro_idPerro`,
  1 AS `Perro1`,
  1 AS `perro_idPerro2`,
  1 AS `Perro2`,
  1 AS `perro_idPerro3`,
  1 AS `Perro3`,
  1 AS `perro_idPerro4`,
  1 AS `Perro4`,
  1 AS `perro_idPerro5`,
  1 AS `Perro5`,
  1 AS `perro_idPerro6`,
  1 AS `Perro6`,
  1 AS `idDuenno`,
  1 AS `DuenoNombre` */;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `vw_paseos_pendientes_vencer`;
/*!50001 DROP VIEW IF EXISTS `vw_paseos_pendientes_vencer`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_paseos_pendientes_vencer` AS SELECT
 1 AS `idPaseo`,
  1 AS `FechaInicio`,
  1 AS `FechaFin`,
  1 AS `FechaSolicitud`,
  1 AS `HorasRestantes`,
  1 AS `HorasTranscurridas`,
  1 AS `EstadoRespuesta`,
  1 AS `Paseador_idPaseador`,
  1 AS `PaseadorNombre` */;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `vw_perros_con_dueno`;
/*!50001 DROP VIEW IF EXISTS `vw_perros_con_dueno`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_perros_con_dueno` AS SELECT
 1 AS `idPerro`,
  1 AS `PerroNombre`,
  1 AS `Peso`,
  1 AS `Recomendacion`,
  1 AS `Peligrosidad`,
  1 AS `Raza`,
  1 AS `Tamanno`,
  1 AS `idDuenno`,
  1 AS `DuenoNombre`,
  1 AS `DuenoCorreo`,
  1 AS `DuenoContacto` */;
SET character_set_client = @saved_cs_client;
/*!50001 DROP VIEW IF EXISTS `vw_aspirantes_pendientes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_aspirantes_pendientes` AS select `p`.`idPaseador` AS `idPaseador`,`p`.`Nombre` AS `Nombre`,`p`.`Apellido` AS `Apellido`,`p`.`Correo` AS `Correo`,`p`.`Contacto` AS `Contacto`,`p`.`Informacion` AS `Informacion`,`p`.`FechaRegistro` AS `FechaRegistro`,to_days(current_timestamp()) - to_days(`p`.`FechaRegistro`) AS `DiasEspera`,case when to_days(current_timestamp()) - to_days(`p`.`FechaRegistro`) > 7 then 'Vencido' else 'En plazo' end AS `EstadoRevision` from `paseador` `p` where `p`.`Estado_idEstado` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `vw_estadisticas_admin`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_estadisticas_admin` AS select (select count(0) from `paseador` where `paseador`.`Estado_idEstado` = 1) AS `AspirantesPendientes`,(select count(0) from `paseador` where `paseador`.`Estado_idEstado` = 2) AS `PaseadoresActivos`,(select count(0) from `paseador` where `paseador`.`Estado_idEstado` = 4) AS `PaseadoresBloqueados`,(select count(0) from `paseo` where `paseo`.`Estado_idEstado` = 1) AS `PaseosPendientes`,(select count(0) from `paseo` where `paseo`.`Estado_idEstado` = 2) AS `PaseosAprobados`,(select count(0) from `paseo` where `paseo`.`Estado_idEstado` = 5) AS `PaseosEnCurso`,(select count(0) from `paseo` where `paseo`.`Estado_idEstado` = 6) AS `PaseosCompletados`,(select count(0) from `perro`) AS `TotalPerros`,(select count(0) from `dueño` where `dueño`.`Estado_idEstado` = 2) AS `DuenosActivos` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `vw_paseadores_activos`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_paseadores_activos` AS select `p`.`idPaseador` AS `idPaseador`,`p`.`Nombre` AS `Nombre`,`p`.`Apellido` AS `Apellido`,`p`.`Correo` AS `Correo`,`p`.`Contacto` AS `Contacto`,`p`.`Informacion` AS `Informacion`,`p`.`AprobadoPeligroso` AS `AprobadoPeligroso`,`p`.`Multas` AS `Multas`,`p`.`FechaRegistro` AS `FechaRegistro`,`e`.`Nombre` AS `EstadoNombre`,`l`.`Localidad` AS `Localidad`,`c`.`Ciudad` AS `Ciudad` from (((`paseador` `p` join `estado` `e` on(`p`.`Estado_idEstado` = `e`.`idEstado`)) left join `localidad` `l` on(`p`.`Localidad_idLocalidad` = `l`.`idLocalidad`)) left join `ciudad` `c` on(`l`.`Ciudad_idCiudad` = `c`.`idCiudad`)) where `p`.`Estado_idEstado` in (2,4) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `vw_paseos_detalle`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_paseos_detalle` AS select `p`.`idPaseo` AS `idPaseo`,`p`.`FechaInicio` AS `FechaInicio`,`p`.`FechaFin` AS `FechaFin`,`p`.`Bozal` AS `Bozal`,`p`.`Observaciones` AS `Observaciones`,`p`.`MotivoCancelacion` AS `MotivoCancelacion`,`p`.`FechaSolicitud` AS `FechaSolicitud`,`pas`.`idPaseador` AS `idPaseador`,concat(`pas`.`Nombre`,' ',`pas`.`Apellido`) AS `PaseadorNombre`,`p`.`Estado_idEstado` AS `Estado_idEstado`,`e`.`Nombre` AS `EstadoNombre`,`p`.`perro_idPerro` AS `perro_idPerro`,concat(`per1`.`Nombre`,' (',`r1`.`Raza`,')') AS `Perro1`,`p`.`perro_idPerro2` AS `perro_idPerro2`,concat(`per2`.`Nombre`,' (',`r2`.`Raza`,')') AS `Perro2`,`p`.`perro_idPerro3` AS `perro_idPerro3`,concat(`per3`.`Nombre`,' (',`r3`.`Raza`,')') AS `Perro3`,`p`.`perro_idPerro4` AS `perro_idPerro4`,concat(`per4`.`Nombre`,' (',`r4`.`Raza`,')') AS `Perro4`,`p`.`perro_idPerro5` AS `perro_idPerro5`,concat(`per5`.`Nombre`,' (',`r5`.`Raza`,')') AS `Perro5`,`p`.`perro_idPerro6` AS `perro_idPerro6`,concat(`per6`.`Nombre`,' (',`r6`.`Raza`,')') AS `Perro6`,`d`.`idDueño` AS `idDuenno`,concat(`d`.`Nombre`,' ',`d`.`Apellido`) AS `DuenoNombre` from (((((((((((((((`paseo` `p` join `paseador` `pas` on(`p`.`Paseador_idPaseador` = `pas`.`idPaseador`)) join `estado` `e` on(`p`.`Estado_idEstado` = `e`.`idEstado`)) left join `perro` `per1` on(`p`.`perro_idPerro` = `per1`.`idPerro`)) left join `raza` `r1` on(`per1`.`Raza_idRaza` = `r1`.`idRaza`)) left join `perro` `per2` on(`p`.`perro_idPerro2` = `per2`.`idPerro`)) left join `raza` `r2` on(`per2`.`Raza_idRaza` = `r2`.`idRaza`)) left join `perro` `per3` on(`p`.`perro_idPerro3` = `per3`.`idPerro`)) left join `raza` `r3` on(`per3`.`Raza_idRaza` = `r3`.`idRaza`)) left join `perro` `per4` on(`p`.`perro_idPerro4` = `per4`.`idPerro`)) left join `raza` `r4` on(`per4`.`Raza_idRaza` = `r4`.`idRaza`)) left join `perro` `per5` on(`p`.`perro_idPerro5` = `per5`.`idPerro`)) left join `raza` `r5` on(`per5`.`Raza_idRaza` = `r5`.`idRaza`)) left join `perro` `per6` on(`p`.`perro_idPerro6` = `per6`.`idPerro`)) left join `raza` `r6` on(`per6`.`Raza_idRaza` = `r6`.`idRaza`)) join `dueño` `d` on(`per1`.`Dueño_idDueño` = `d`.`idDueño`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `vw_paseos_pendientes_vencer`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_paseos_pendientes_vencer` AS select `p`.`idPaseo` AS `idPaseo`,`p`.`FechaInicio` AS `FechaInicio`,`p`.`FechaFin` AS `FechaFin`,`p`.`FechaSolicitud` AS `FechaSolicitud`,timestampdiff(HOUR,current_timestamp(),`p`.`FechaInicio`) AS `HorasRestantes`,timestampdiff(HOUR,`p`.`FechaSolicitud`,current_timestamp()) AS `HorasTranscurridas`,case when timestampdiff(HOUR,`p`.`FechaSolicitud`,current_timestamp()) >= 5 then 'Vencido' else 'Esperando respuesta' end AS `EstadoRespuesta`,`p`.`Paseador_idPaseador` AS `Paseador_idPaseador`,concat(`pas`.`Nombre`,' ',`pas`.`Apellido`) AS `PaseadorNombre` from (`paseo` `p` join `paseador` `pas` on(`p`.`Paseador_idPaseador` = `pas`.`idPaseador`)) where `p`.`Estado_idEstado` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `vw_perros_con_dueno`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_perros_con_dueno` AS select `per`.`idPerro` AS `idPerro`,`per`.`Nombre` AS `PerroNombre`,`per`.`Peso` AS `Peso`,`per`.`Recomendacion` AS `Recomendacion`,`pel`.`Nivel` AS `Peligrosidad`,`r`.`Raza` AS `Raza`,`t`.`Tamaño` AS `Tamanno`,`d`.`idDueño` AS `idDuenno`,concat(`d`.`Nombre`,' ',`d`.`Apellido`) AS `DuenoNombre`,`d`.`Correo` AS `DuenoCorreo`,`d`.`Contacto` AS `DuenoContacto` from ((((`perro` `per` join `peligrosidad` `pel` on(`per`.`Peligrosidad_idPeligrosidad` = `pel`.`idPeligrosidad`)) join `raza` `r` on(`per`.`Raza_idRaza` = `r`.`idRaza`)) join `tamaño` `t` on(`r`.`Tamaño_idTamaño` = `t`.`idTamaño`)) join `dueño` `d` on(`per`.`Dueño_idDueño` = `d`.`idDueño`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

