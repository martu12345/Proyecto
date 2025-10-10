-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: miproyecto
-- ------------------------------------------------------
-- Server version	9.4.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `administra`
--

DROP TABLE IF EXISTS `administra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `administra` (
  `IdServicio` int NOT NULL,
  `IdUsuario` int NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`IdServicio`,`IdUsuario`),
  KEY `fk_administra_admin` (`IdUsuario`),
  CONSTRAINT `fk_administra_servicio` FOREIGN KEY (`IdServicio`) REFERENCES `servicio` (`IdServicio`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administra`
--

LOCK TABLES `administra` WRITE;
/*!40000 ALTER TABLE `administra` DISABLE KEYS */;
/*!40000 ALTER TABLE `administra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `administrador`
--

DROP TABLE IF EXISTS `administrador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `administrador` (
  `idUsuarioAdministrador` int NOT NULL,
  `idUsuarioPropietario` int NOT NULL,
  PRIMARY KEY (`idUsuarioAdministrador`),
  KEY `fk_admin_propietario` (`idUsuarioPropietario`),
  CONSTRAINT `fk_admin_propietario` FOREIGN KEY (`idUsuarioPropietario`) REFERENCES `propietario` (`IdUsuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_admin_usuario` FOREIGN KEY (`idUsuarioAdministrador`) REFERENCES `usuario` (`IdUsuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrador`
--

LOCK TABLES `administrador` WRITE;
/*!40000 ALTER TABLE `administrador` DISABLE KEYS */;
/*!40000 ALTER TABLE `administrador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brinda`
--

DROP TABLE IF EXISTS `brinda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brinda` (
  `IdUsuario` int NOT NULL,
  `IdServicio` int NOT NULL,
  `notificacion` enum('no leido','leido') DEFAULT NULL,
  PRIMARY KEY (`IdUsuario`,`IdServicio`),
  KEY `IdServicio` (`IdServicio`),
  CONSTRAINT `brinda_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `empresa` (`IdUsuario`) ON DELETE CASCADE,
  CONSTRAINT `brinda_ibfk_2` FOREIGN KEY (`IdServicio`) REFERENCES `servicio` (`IdServicio`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brinda`
--

LOCK TABLES `brinda` WRITE;
/*!40000 ALTER TABLE `brinda` DISABLE KEYS */;
INSERT INTO `brinda` VALUES (4,5,NULL),(4,6,NULL),(6,7,NULL),(6,8,NULL),(6,9,NULL),(6,10,NULL),(6,11,NULL),(7,12,NULL),(7,13,NULL),(10,14,NULL),(10,15,NULL),(10,16,NULL),(10,17,NULL),(10,18,NULL);
/*!40000 ALTER TABLE `brinda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `IdUsuario` int NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdUsuario`),
  CONSTRAINT `fk_cliente_usuario` FOREIGN KEY (`IdUsuario`) REFERENCES `usuario` (`IdUsuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (1,'Martola','Ferreira','/Proyecto/public/imagen/clientes/68ce899a8938c_WIN_20250902_11_57_04_Pro.jpg'),(5,'Jorjito','Jorge','/Proyecto/public/imagen/clientes/68ce8fd400d4a_Captura de pantalla 2025-09-15 203856.png'),(9,'Martina','Pol','/Proyecto/public/imagen/clientes/68d9c846290ac_Captura de pantalla 2024-09-11 210437.png'),(11,'PaolA','ddeede','/Proyecto/public/imagen/clientes/68e80c85301b2_WIN_20250902_11_57_04_Pro.jpg');
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comunica`
--

DROP TABLE IF EXISTS `comunica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comunica` (
  `IdMensaje` int NOT NULL AUTO_INCREMENT,
  `IdUsuarioCliente` int NOT NULL,
  `IdUsuarioEmpresa` int NOT NULL,
  `Asunto` varchar(255) NOT NULL,
  `Contenido` varchar(1000) NOT NULL,
  `FechaHora` datetime NOT NULL,
  `idUsuarioEmisor` int NOT NULL,
  `notificacion` enum('no leído','leído') DEFAULT NULL,
  `IdMensajePadre` int DEFAULT NULL,
  PRIMARY KEY (`IdMensaje`),
  KEY `fk_comunica_cliente` (`IdUsuarioCliente`),
  KEY `fk_comunica_empresa` (`IdUsuarioEmpresa`),
  KEY `fk_comunica_emisor` (`idUsuarioEmisor`),
  KEY `fk_mensaje_respuesta` (`IdMensajePadre`),
  CONSTRAINT `fk_comunica_cliente` FOREIGN KEY (`IdUsuarioCliente`) REFERENCES `cliente` (`IdUsuario`) ON DELETE CASCADE,
  CONSTRAINT `fk_comunica_emisor` FOREIGN KEY (`idUsuarioEmisor`) REFERENCES `usuario` (`IdUsuario`),
  CONSTRAINT `fk_comunica_empresa` FOREIGN KEY (`IdUsuarioEmpresa`) REFERENCES `empresa` (`IdUsuario`) ON DELETE CASCADE,
  CONSTRAINT `fk_mensaje_respuesta` FOREIGN KEY (`IdMensajePadre`) REFERENCES `comunica` (`IdMensaje`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comunica`
--

LOCK TABLES `comunica` WRITE;
/*!40000 ALTER TABLE `comunica` DISABLE KEYS */;
INSERT INTO `comunica` VALUES (1,11,10,'ODIO A SOFIA','La odio ','2025-10-03 14:30:39',1,NULL,NULL),(2,11,4,'Odi ','HOLIS','2025-10-03 22:06:06',1,NULL,NULL),(3,11,10,'Rechazo de reserva','Eres horrible','2025-10-04 15:25:50',10,NULL,NULL),(4,11,10,'Rechazo de reserva','No podemos','2025-10-04 15:34:19',10,NULL,NULL),(5,11,10,'Rechazo de reserva','Lo odio','2025-10-04 15:36:29',10,NULL,NULL),(6,11,10,'Rechazo de reserva','No me gustan tus dientes','2025-10-05 03:03:01',10,NULL,NULL),(7,11,7,'hola jorge','como estas','2025-10-07 14:22:43',11,NULL,NULL);
/*!40000 ALTER TABLE `comunica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contrata`
--

DROP TABLE IF EXISTS `contrata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contrata` (
  `IdCita` int NOT NULL AUTO_INCREMENT,
  `IdUsuario` int NOT NULL,
  `IdServicio` int NOT NULL,
  `Fecha` date NOT NULL,
  `Hora` time NOT NULL,
  `Calificacion` int DEFAULT NULL,
  `Resena` varchar(255) DEFAULT NULL,
  `Estado` varchar(20) NOT NULL DEFAULT 'Pendiente',
  `notificacion` enum('no_leido','leido') DEFAULT NULL,
  PRIMARY KEY (`IdCita`),
  UNIQUE KEY `uq_contrata` (`IdUsuario`,`IdServicio`,`Fecha`,`Hora`),
  KEY `fk_contrata_servicio` (`IdServicio`),
  CONSTRAINT `fk_contrata_cliente` FOREIGN KEY (`IdUsuario`) REFERENCES `cliente` (`IdUsuario`) ON DELETE CASCADE,
  CONSTRAINT `fk_contrata_servicio` FOREIGN KEY (`IdServicio`) REFERENCES `servicio` (`IdServicio`) ON DELETE CASCADE,
  CONSTRAINT `chk_calificacion` CHECK (((`Calificacion` between 1 and 5) or (`Calificacion` is null)))
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contrata`
--

LOCK TABLES `contrata` WRITE;
/*!40000 ALTER TABLE `contrata` DISABLE KEYS */;
INSERT INTO `contrata` VALUES (2,11,14,'2025-10-09','03:44:00',2,NULL,'Cancelado','leido'),(5,11,14,'2025-10-02','04:07:00',3,'NAAAA','Finalizado','leido'),(6,11,14,'2025-10-02','06:50:00',4,'ameee','Finalizado','leido'),(7,11,14,'2025-10-02','10:20:00',2,'DEEAA','Finalizado','leido'),(8,11,14,'2025-10-03','03:00:00',3,'YES','Finalizado','leido'),(9,11,14,'2025-10-03','06:20:00',5,'OKIS','Finalizado','leido'),(10,11,14,'2025-10-03','08:20:00',1,'labubu','Finalizado','leido'),(11,11,14,'2025-10-03','10:30:00',2,NULL,'Finalizado','leido'),(12,11,14,'2025-10-03','12:40:00',2,NULL,'Finalizado','leido'),(13,11,14,'2025-10-03','14:50:00',2,NULL,'Finalizado','leido'),(16,11,14,'2025-10-05','12:00:00',2,NULL,'Cancelado','leido'),(17,11,14,'2025-10-10','11:00:00',2,NULL,'Finalizado','leido'),(18,11,14,'2025-10-24','11:00:00',2,NULL,'Cancelado','leido'),(19,11,5,'2025-10-03','11:00:00',2,NULL,'Cancelado','no_leido'),(20,11,5,'2025-10-10','11:00:00',2,NULL,'Pendiente','no_leido'),(21,11,8,'2025-10-09','12:22:00',2,NULL,'Cancelado','no_leido'),(22,11,5,'2025-10-05','03:00:00',2,NULL,'Cancelado','no_leido'),(23,11,5,'2025-10-09','23:33:00',2,NULL,'Pendiente','no_leido'),(25,11,1,'2025-10-03','21:59:00',2,NULL,'Cancelado','no_leido'),(26,11,1,'2025-10-10','21:59:00',2,NULL,'Pendiente','no_leido'),(28,11,1,'2025-10-11','11:00:00',2,NULL,'Pendiente','no_leido'),(29,11,1,'2025-10-10','22:59:00',2,NULL,'Pendiente','no_leido'),(30,11,16,'2025-10-18','12:00:00',2,NULL,'En proceso','leido'),(31,11,14,'2025-10-17','11:00:00',2,NULL,'En proceso','leido'),(32,11,14,'2025-10-09','12:00:00',2,NULL,'Cancelado','leido'),(33,11,14,'2025-10-19','12:00:00',2,NULL,'Cancelado','leido'),(34,11,14,'2025-10-11','11:00:00',2,NULL,'Cancelado','leido'),(35,11,14,'2025-10-24','20:40:00',2,NULL,'En proceso','leido'),(36,11,14,'2025-10-05','09:20:00',2,NULL,'Cancelado','leido'),(37,11,17,'2025-10-04','10:57:00',2,NULL,'Finalizado','leido'),(38,11,17,'2025-10-04','18:26:00',2,NULL,'Finalizado','leido'),(39,11,17,'2025-10-03','10:00:00',5,'Excelente servicio, muy amable y rápido.','Finalizado','leido'),(40,11,17,'2025-10-03','14:30:00',4,'Buen servicio, pero tardaron un poco más de lo esperado.','Finalizado','leido'),(41,11,14,'2025-10-16','11:00:00',2,NULL,'Pendiente','leido'),(42,11,18,'2025-10-04','11:00:00',2,NULL,'Cancelado','leido'),(44,11,18,'2025-10-05','11:00:00',2,NULL,'Cancelado','leido'),(45,11,18,'2025-10-05','03:40:00',2,NULL,'Cancelado','leido'),(48,11,14,'2025-10-23','02:00:00',2,NULL,'Pendiente','leido'),(49,11,16,'2025-10-16','03:49:00',2,NULL,'Pendiente','leido'),(58,11,14,'2025-10-18','12:00:00',2,NULL,'Pendiente','leido'),(65,11,14,'2025-10-19','09:20:00',2,NULL,'Pendiente','leido'),(66,11,14,'2025-10-18','11:00:00',2,NULL,'Pendiente','leido'),(67,11,14,'2025-10-18','10:00:00',2,NULL,'Pendiente','leido'),(68,11,18,'2025-10-10','11:00:00',2,NULL,'Pendiente','leido'),(69,11,18,'2025-10-10','10:00:00',2,NULL,'Pendiente','leido'),(70,11,18,'2025-10-10','08:00:00',2,NULL,'Pendiente','leido'),(71,11,18,'2025-10-10','07:00:00',2,NULL,'Pendiente','leido'),(72,11,18,'2025-10-10','06:00:00',2,NULL,'Pendiente','leido'),(73,11,18,'2025-10-10','05:00:00',2,NULL,'Pendiente','leido'),(74,11,18,'2025-10-10','04:00:00',2,NULL,'Pendiente','leido'),(75,11,18,'2025-10-10','03:00:00',2,NULL,'Pendiente','leido'),(76,11,18,'2025-10-10','02:00:00',2,NULL,'Pendiente','leido'),(77,11,14,'2025-10-24','02:00:00',2,NULL,'Pendiente','leido'),(78,11,14,'2025-10-11','21:00:00',2,NULL,'Pendiente','leido'),(79,11,14,'2025-10-19','02:30:00',2,NULL,'Pendiente',NULL),(80,11,14,'2025-10-14','03:30:00',2,NULL,'Pendiente','leido'),(81,11,14,'2025-10-11','02:00:00',2,NULL,'Pendiente','leido'),(82,11,2,'2025-10-09','20:32:00',2,NULL,'Pendiente','no_leido'),(83,11,14,'2025-10-30','02:59:00',NULL,NULL,'Pendiente','leido');
/*!40000 ALTER TABLE `contrata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `denuncia`
--

DROP TABLE IF EXISTS `denuncia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `denuncia` (
  `idDenuncia` int NOT NULL AUTO_INCREMENT,
  `idCliente` int NOT NULL,
  `idEmpresa` int NOT NULL,
  `motivo` text NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idDenuncia`),
  KEY `idCliente` (`idCliente`),
  KEY `idEmpresa` (`idEmpresa`),
  CONSTRAINT `denuncia_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`IdUsuario`),
  CONSTRAINT `denuncia_ibfk_2` FOREIGN KEY (`idEmpresa`) REFERENCES `empresa` (`IdUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `denuncia`
--

LOCK TABLES `denuncia` WRITE;
/*!40000 ALTER TABLE `denuncia` DISABLE KEYS */;
/*!40000 ALTER TABLE `denuncia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresa`
--

DROP TABLE IF EXISTS `empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresa` (
  `IdUsuario` int NOT NULL,
  `NombreEmpresa` varchar(100) NOT NULL,
  `Calle` varchar(100) NOT NULL,
  `Numero` varchar(10) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IdUsuario`),
  CONSTRAINT `fk_empresa_usuario` FOREIGN KEY (`IdUsuario`) REFERENCES `usuario` (`IdUsuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresa`
--

LOCK TABLES `empresa` WRITE;
/*!40000 ALTER TABLE `empresa` DISABLE KEYS */;
INSERT INTO `empresa` VALUES (2,'Manitas ','Boulevard','1103','empresa_2.jpg'),(3,'MartinaF','Peña','2003',NULL),(4,'Manitass','Peña','1120','empresa_4.jpg'),(6,'Jorge','Peña','3904',NULL),(7,'AlejandraS','Peña','3904','empresa_7.png'),(8,'Pepe','Perez','1120',NULL),(10,'Eduar','deded','1222','empresa_10.png');
/*!40000 ALTER TABLE `empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `propietario`
--

DROP TABLE IF EXISTS `propietario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `propietario` (
  `IdUsuario` int NOT NULL,
  PRIMARY KEY (`IdUsuario`),
  CONSTRAINT `propietario_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `usuario` (`IdUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `propietario`
--

LOCK TABLES `propietario` WRITE;
/*!40000 ALTER TABLE `propietario` DISABLE KEYS */;
/*!40000 ALTER TABLE `propietario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio`
--

DROP TABLE IF EXISTS `servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicio` (
  `IdServicio` int NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(100) NOT NULL,
  `Categoria` enum('Hogar','Autos','Belleza','Cuidado de niños','Digital','Cocina','Salud','Mascotas','Eventos','Educación','Transporte','Arte y Cultura') NOT NULL,
  `Descripcion` varchar(500) DEFAULT NULL,
  `Precio` int NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `departamento` varchar(255) NOT NULL DEFAULT '',
  `Duracion` decimal(3,1) NOT NULL,
  PRIMARY KEY (`IdServicio`),
  CONSTRAINT `chk_precio` CHECK ((`Precio` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio`
--

LOCK TABLES `servicio` WRITE;
/*!40000 ALTER TABLE `servicio` DISABLE KEYS */;
INSERT INTO `servicio` VALUES (1,'Maquillaje a domicilio ','Belleza','Hacemos maquillaje para todo el mundo',100,'1758148751_Captura de pantalla 2025-07-29 190549.png','Montevideo',1.0),(2,'Maquillaje a domicilio ','Belleza','Hacemos maquillaje para todo el mundo',100,'1758148751_Captura de pantalla 2025-07-29 190549.png','Artigas',1.0),(3,'Correcion de maquillaje','Belleza','Coreccion de tu maquillaje para que quedes perfecta',1200,'1758148788_Captura de pantalla 2024-09-21 235022.png','Montevideo',1.0),(4,'Correcion de maquillaje','Belleza','Coreccion de tu maquillaje para que quedes perfecta',1200,'1758148788_Captura de pantalla 2024-09-21 235022.png','Montevideo',1.0),(5,'Arreglos de  AUTOS','Autos','Arreglamos autitos',1450,'1758318469_Captura de pantalla 2024-09-16 225841.png','Artigas',1.0),(6,'cocinando con marta','Cocina','Cocicnamos jutnos ',170,'1758321129_Captura de pantalla 2024-09-16 225841.png','Soriano',1.0),(7,'Cocina fria ','Cocina','Cocinamso cocina fria peor no limpaimos ',1500,'1758368184_Captura de pantalla 2024-09-11 210437.png','Florida',1.0),(8,'Arreglos de  autos','Autos','Arreglamos autitos',1455,'1758373882_Captura de pantalla 2024-09-11 210437.png','Canelones',1.0),(9,'Hacemos pepas para comer ','Cocina','Hacemos ppeitas para comer ',12,'1758373959_Captura de pantalla 2024-09-23 220654.png','Artigas',1.0),(10,'reparaciones de ruedas','Autos','Reparamos tus ruedas',135000,'1758375851_Captura de pantalla 2024-09-22 004915.png','Canelones',1.0),(11,'All face ','Belleza','Maqui para todos ',123,'1758375936_Captura de pantalla 2024-09-23 223516.png','Soriano',1.0),(12,'PApaS','Cocina','Hacemos papas fritas para todos ',1202,'1759083199_Captura de pantalla 2024-09-11 210437.png','San José',1.0),(13,'JorgeS','Educación','MUerte a las papas',458888,'1758977739_Captura de pantalla 2024-10-20 140811.png','Durazno',1.0),(14,'Duracion','Cuidado de niños','Hacemos donas',23,'1759365136_Captura de pantalla 2024-10-13 200852.png','Río Negro',2.0),(15,'Cocina a domicilio','Mascotas','asdvfvsdf',1,'','Rocha',3.0),(16,'PApas','Arte y Cultura','3vsfsdf',33,'1759526088_MANITAS (2).png','0',1.0),(17,'Prueba de finalizacion','Transporte','Hacemos no se que ',390,'1759586122_Captura de pantalla 2025-09-15 203428.png','San José',1.0),(18,'Papas a la parmesana','Arte y Cultura','djfrfbefnerio ewfboaiuurs',123,'1759620847_MANITAS (1).png','Rocha',2.0);
/*!40000 ALTER TABLE `servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telefono`
--

DROP TABLE IF EXISTS `telefono`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telefono` (
  `IdUsuario` int NOT NULL,
  `telefono` varchar(20) NOT NULL,
  PRIMARY KEY (`IdUsuario`,`telefono`),
  CONSTRAINT `fk_telefono_usuario` FOREIGN KEY (`IdUsuario`) REFERENCES `usuario` (`IdUsuario`) ON DELETE CASCADE,
  CONSTRAINT `chk_telefono` CHECK (regexp_like(`telefono`,_utf8mb4'^(09|2)[0-9]{7}$'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telefono`
--

LOCK TABLES `telefono` WRITE;
/*!40000 ALTER TABLE `telefono` DISABLE KEYS */;
INSERT INTO `telefono` VALUES (1,'092898383'),(2,'098888787'),(3,'091888989'),(4,'098777666'),(5,'098777666'),(6,'098765567'),(7,'094555444'),(8,'098999222'),(9,'096543454'),(10,'098765454'),(11,'097666555');
/*!40000 ALTER TABLE `telefono` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `IdUsuario` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  PRIMARY KEY (`IdUsuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'yomartinasofia@gmail.com','$2y$10$Fo0tGn9lkN/A4m4EmbmazeP5uT5a2URp2K451BfAQs./6V0qS1mL.'),(2,'manitas@gmail.com','$2y$10$kH5SZeLpUrPMoXD8HCRAI.sZflfyV1rNpoWcvYnrf.y9gO4VVMENy'),(3,'yomar@gmail.com','$2y$10$RJ2SeyVn4i/U6McZe/mSwO13z9d7j5pkdvyyJkMuxUipHXSrR3vR2'),(4,'manitass@gmail.com','$2y$10$nKaRDHQiGsqyioUYB7ccCuwaTa2I3Gjj68q9i9F0zi.Oytlm2qJGe'),(5,'jorge@gmail.com','$2y$10$LRURtac3FTWPk2qFUNjFfOy2TYxi4e4LbWQErDki8BoWV2QCl620K'),(6,'sofi@gmail.com','$2y$10$jZeuvUfx.jKqSFSsDxcZ/.1NFuyV5UxZbFaA9X.PkG4Y6M0YrZBPG'),(7,'alejandra@gmail.com','$2y$10$VmZj2HY6NKCOyQnh.yfQ0.4r9vqYwf3QvkeI4sybyQEjp91ci0PrO'),(8,'pepe@gmail.com','$2y$10$J2mNW6mRxRK/Fxyae5ajbOjLbKhABSbbKzaeY3BvKv16SuRSsTEg6'),(9,'pepa@gmail.com','$2y$10$LyBQto.dv3OjiLGS1u3LTOcmS3PqwUCPrDq8lXV0fUdrlKh7pBBX6'),(10,'eduar@gmail.com','$2y$10$.05JXUo38YrzAl8jxEnrUerTn98qCBAAaYyxQO24AG7ByzyScAle.'),(11,'mateo@gmail.com','$2y$10$tsffOOE/q2kIcAexeF32uO5fPhv0KXe3arQZSWWPVRr82wPMLTmae');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-10 15:27:19
