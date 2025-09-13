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
  PRIMARY KEY (`IdServicio`,`IdUsuario`),
  KEY `fk_administra_admin` (`IdUsuario`),
  CONSTRAINT `fk_administra_admin` FOREIGN KEY (`IdUsuario`) REFERENCES `administrador` (`IdUsuario`) ON DELETE CASCADE,
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
  `IdUsuario` int NOT NULL,
  PRIMARY KEY (`IdUsuario`),
  CONSTRAINT `fk_administrador_usuario` FOREIGN KEY (`IdUsuario`) REFERENCES `usuario` (`IdUsuario`) ON DELETE CASCADE
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
  PRIMARY KEY (`IdUsuario`),
  CONSTRAINT `fk_cliente_usuario` FOREIGN KEY (`IdUsuario`) REFERENCES `usuario` (`IdUsuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
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
  `Contenido` varchar(1000) NOT NULL,
  `FechaHora` datetime NOT NULL,
  PRIMARY KEY (`IdMensaje`),
  KEY `fk_comunica_cliente` (`IdUsuarioCliente`),
  KEY `fk_comunica_empresa` (`IdUsuarioEmpresa`),
  CONSTRAINT `fk_comunica_cliente` FOREIGN KEY (`IdUsuarioCliente`) REFERENCES `cliente` (`IdUsuario`) ON DELETE CASCADE,
  CONSTRAINT `fk_comunica_empresa` FOREIGN KEY (`IdUsuarioEmpresa`) REFERENCES `empresa` (`IdUsuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comunica`
--

LOCK TABLES `comunica` WRITE;
/*!40000 ALTER TABLE `comunica` DISABLE KEYS */;
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
  `Reseña` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`IdCita`),
  UNIQUE KEY `uq_contrata` (`IdUsuario`,`IdServicio`,`Fecha`,`Hora`),
  KEY `fk_contrata_servicio` (`IdServicio`),
  CONSTRAINT `fk_contrata_cliente` FOREIGN KEY (`IdUsuario`) REFERENCES `cliente` (`IdUsuario`) ON DELETE CASCADE,
  CONSTRAINT `fk_contrata_servicio` FOREIGN KEY (`IdServicio`) REFERENCES `servicio` (`IdServicio`) ON DELETE CASCADE,
  CONSTRAINT `chk_calificacion` CHECK (((`Calificacion` between 1 and 5) or (`Calificacion` is null)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contrata`
--

LOCK TABLES `contrata` WRITE;
/*!40000 ALTER TABLE `contrata` DISABLE KEYS */;
/*!40000 ALTER TABLE `contrata` ENABLE KEYS */;
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
  PRIMARY KEY (`IdUsuario`),
  CONSTRAINT `fk_empresa_usuario` FOREIGN KEY (`IdUsuario`) REFERENCES `usuario` (`IdUsuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresa`
--

LOCK TABLES `empresa` WRITE;
/*!40000 ALTER TABLE `empresa` DISABLE KEYS */;
/*!40000 ALTER TABLE `empresa` ENABLE KEYS */;
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
  `disponibilidad` tinyint(1) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `departamento` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`IdServicio`),
  CONSTRAINT `chk_precio` CHECK ((`Precio` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio`
--

LOCK TABLES `servicio` WRITE;
/*!40000 ALTER TABLE `servicio` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
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

-- Dump completed on 2025-09-13 11:25:40
