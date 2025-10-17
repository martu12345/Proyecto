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
  `idAdministra` int NOT NULL AUTO_INCREMENT,
  `idUsuario` int NOT NULL,
  `idServicio` int NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`idAdministra`),
  UNIQUE KEY `idx_servicio_usuario` (`idServicio`,`idUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `asunto` varchar(50) DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `motivo` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`idDenuncia`),
  KEY `idCliente` (`idCliente`),
  KEY `idEmpresa` (`idEmpresa`),
  CONSTRAINT `denuncia_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`IdUsuario`),
  CONSTRAINT `denuncia_ibfk_2` FOREIGN KEY (`idEmpresa`) REFERENCES `empresa` (`IdUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-17 18:07:03
