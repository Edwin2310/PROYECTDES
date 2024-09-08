-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2024 at 09:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbproyectodes`
--

-- --------------------------------------------------------

--
-- Table structure for table `documentos.tbladjuntosctc`
--

CREATE TABLE `documentos.tbladjuntosctc` (
  `IdAdjuntoctc` bigint(20) NOT NULL,
  `IdUsuario` bigint(20) NOT NULL,
  `FechaAdjuntoCTC` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DictamenAdjunto` varchar(500) NOT NULL,
  `IdSolicitud` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documentos.tblarchivosadjuntos`
--

CREATE TABLE `documentos.tblarchivosadjuntos` (
  `IdAdjuntos` bigint(20) NOT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  `FechaAdjunto` datetime DEFAULT NULL,
  `Solicitud` varchar(500) DEFAULT NULL,
  `PlanEstudios` varchar(500) DEFAULT NULL,
  `PlantaDocente` varchar(500) DEFAULT NULL,
  `Diagnostico` varchar(500) DEFAULT NULL,
  `IdSolicitud` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documentos.tbldictamenesctc`
--

CREATE TABLE `documentos.tbldictamenesctc` (
  `IdDictamen` bigint(20) NOT NULL,
  `IdSesion` bigint(20) NOT NULL,
  `NumDictamenCTC` bigint(20) NOT NULL,
  `ObervacionesDictamen` varchar(100) DEFAULT NULL,
  `IdUniversidad` bigint(20) NOT NULL,
  `IdEstadoDictamen` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documentos.tblobservaciones`
--

CREATE TABLE `documentos.tblobservaciones` (
  `IdObservacion` bigint(20) NOT NULL,
  `Observacion` varchar(255) DEFAULT NULL,
  `DocObservacion` varchar(255) DEFAULT NULL,
  `Solicitud` varchar(500) DEFAULT NULL,
  `PlanEstudios` varchar(500) DEFAULT NULL,
  `PlantaDocente` varchar(500) DEFAULT NULL,
  `Diagnostico` varchar(500) DEFAULT NULL,
  `CreadoPor` varchar(50) DEFAULT NULL,
  `FechaObservacion` datetime NOT NULL DEFAULT current_timestamp(),
  `IdSolicitud` bigint(20) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documentos.tblopinionesrazonadas`
--

CREATE TABLE `documentos.tblopinionesrazonadas` (
  `IdOpinionrazonada` bigint(20) NOT NULL,
  `AdjuntoObservaciones` varchar(500) DEFAULT NULL,
  `AdjuntoOrDes` varchar(500) DEFAULT NULL,
  `Observaciones` varchar(150) DEFAULT NULL,
  `PlanEstudios` varchar(500) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `FechaCreacion` datetime NOT NULL DEFAULT current_timestamp(),
  `CreadoPor` varchar(50) DEFAULT NULL,
  `FechaModificacion` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ModificadoPor` varchar(50) DEFAULT NULL,
  `IdSolicitud` bigint(20) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documentos.tblplanesestudio`
--

CREATE TABLE `documentos.tblplanesestudio` (
  `IdPlanestudio` bigint(20) NOT NULL,
  `NumRegistro` varchar(50) DEFAULT NULL,
  `AdjuntoPlan` varchar(500) DEFAULT NULL,
  `IdUniversidad` bigint(20) NOT NULL,
  `IdSolicitud` bigint(20) NOT NULL,
  `IdCarrera` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento.tblcarreras`
--

CREATE TABLE `mantenimiento.tblcarreras` (
  `IdCarrera` bigint(20) NOT NULL,
  `NomCarrera` varchar(50) DEFAULT NULL,
  `IdUniversidad` bigint(20) DEFAULT NULL,
  `IdModalidad` bigint(20) DEFAULT NULL,
  `IdGrado` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mantenimiento.tblcarreras`
--

INSERT INTO `mantenimiento.tblcarreras` (`IdCarrera`, `NomCarrera`, `IdUniversidad`, `IdModalidad`, `IdGrado`) VALUES
(1, 'ODONTOLOGÍA', 1, 2, 3),
(2, 'INGENIERIA EN AGRONEGOCIOS', 1, 2, 3),
(3, 'INGENIERIA EN AGRONEGOCIOS', 1, 2, 3),
(4, 'Ingenieria en Negocios', 1, 2, 3),
(5, 'INGENIERIA EN NEGOCIOS AGROPECUARIOS', 1, 2, 3),
(6, 'Medicina', 1, 2, 3),
(7, 'Informatica Administrativa', 1, 2, 3),
(8, 'INGENIERÍA CIVIL', 1, 2, 3),
(9, 'MEDICINA', 1, 2, 3),
(10, 'MI PERRO EN DOCTORADO', 1, 2, 3),
(11, 'MI PERRO EN DOCTORADO', 1, 2, 3),
(12, 'MI PERRO EN DOCTORADO', 1, 2, 3),
(13, 'MI PERRO EN DOCTORADO', 1, 2, 3),
(14, 'MI PERRO EN DOCTORADO', 1, 2, 3),
(15, 'MI PERRO EN DOCTORADO', 1, 2, 3),
(16, 'INFORMATICA', 1, 2, 3),
(17, 'INGENIERIA QUIMICA', 1, 2, 3),
(18, 'INGENIERIA QUIMICA', 1, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento.tblcategorias`
--

CREATE TABLE `mantenimiento.tblcategorias` (
  `IdCategoria` bigint(20) NOT NULL,
  `CodArbitrios` bigint(20) DEFAULT NULL,
  `NomCategoria` varchar(50) DEFAULT NULL,
  `Monto` decimal(18,2) DEFAULT NULL,
  `NumRef` bigint(20) DEFAULT NULL,
  `IdTiposolicitud` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mantenimiento.tblcategorias`
--

INSERT INTO `mantenimiento.tblcategorias` (`IdCategoria`, `CodArbitrios`, `NomCategoria`, `Monto`, `NumRef`, `IdTiposolicitud`) VALUES
(1, 801, 'APROBACIÓN DE CREACIÓN Y AUTORIZACIÓN DE UN CENTRO', 100000.00, NULL, 1),
(2, 802, 'APROBACIÓN DE CREACIÓN Y AUTORIZACIÓN DE UN CENTRO', 30000.00, NULL, 1),
(3, 803, 'APROBACIÓN DE CREACIÓN Y AUTORIZACIÓN DE UN CENTRO', 15000.00, NULL, 1),
(4, 804, 'APROBACIÓN DE CREACIÓN Y AUTORIZACIÓN DE UN ASOCIA', 12000.00, NULL, 1),
(5, 805, 'CREACIÓN DE FACULTAD EN EL DISTRITO CENTRAL (M.D.C', 15000.00, NULL, 1),
(6, 806, 'CREACIÓN DE FACULTAD FUERA DEL  DISTRITO CENTRAL (', 10000.00, NULL, 1),
(7, 807, 'APROBACIÓN DE CAMBIO DE DOMICILIO Y/O INSTALACIONE', 6000.00, NULL, 1),
(8, 808, 'APROBACIÓN DE CAMBIO DE DOMICILIO Y/O INSTALACIONE', 6000.00, NULL, 1),
(9, 809, 'DESCONCENTRACIÓN DE UN CENTRO YA ESTABLECIDO EN EL', 10000.00, NULL, 1),
(10, 810, 'DESCONCENTRACIÓN DE UN CENTRO YA ESTABLECIDO FUERA', 5000.00, NULL, 1),
(11, 898, 'CAMBIO DE CATEGORÍA  INSTITUCIONAL', 30000.00, NULL, 1),
(12, 828, 'REGISTRO DE NORMAS ACADÉMICAS', 2500.00, NULL, 1),
(13, 828, 'REFORMAS A ESTATUTOS', 5000.00, NULL, 1),
(14, 834, 'REFORMAS AL PLAN DE ARBITRIOS', 2000.00, NULL, 1),
(15, 835, 'REGISTRO DE REFORMAS A LAS NORMAS ACADÉMICAS', 2000.00, NULL, 1),
(16, 836, 'CAMBIO DE NOMBRE DEL CENTRO', 20000.00, NULL, 1),
(17, 812, 'POR CREACIÓN DE CARRERA – GRADO ASOCIADO, SI YA EX', 20000.00, NULL, 2),
(18, 813, 'POR CREACIÓN DE CARRERA – GRADO ASOCIADO, SI NO EX', 15000.00, NULL, 2),
(19, 814, 'POR CREACIÓN DE CARRERA – LICENCIATURA, SI YA EXIS', 25000.00, NULL, 2),
(20, 815, 'POR CREACIÓN DE CARRERA – LICENCIATURA, SI NO EXIS', 20000.00, NULL, 2),
(21, 816, 'POR CREACIÓN DE CARRERA – MAESTRÍA O ESPECIALIDAD ', 35000.00, NULL, 2),
(22, 817, 'POR CREACIÓN DE CARRERA – MAESTRÍA O ESPECIALIDAD,', 25000.00, NULL, 2),
(23, 818, 'POR CREACIÓN DE CARRERA – DOCTORADO SI YA EXISTE', 45000.00, NULL, 2),
(24, 819, 'POR CREACIÓN DE CARRERA – DOCTORADO, SI NO EXISTE', 35000.00, NULL, 2),
(25, 820, 'POR CREACIÓN DE CARRERA RELACIONADO CON EL MANEJO ', 10000.00, NULL, 2),
(26, 821, 'POR SUPRESIÓN DE CARRERA', 7500.00, NULL, 2),
(27, 822, 'CUANDO LA INSTITUCIÓN DE EDUCACIÓN SUPERIOR PIDA L', 2000.00, NULL, 2),
(28, 823, 'POR FUSIÓN DE CARRERA', 7000.00, NULL, 2),
(29, 824, 'AUTORIZACIÓN PARA FUNCIONAMIENTO DE PROGRAMA ESPEC', 12000.00, NULL, 2),
(30, 825, 'APROBACIÓN DIPLOMADOS (ART.81)', 5000.00, NULL, 2),
(31, 826, 'REGISTRO DE DIPLOMADOS (CURRICULUM)', 2500.00, NULL, 2),
(32, 827, 'APROBACIÓN DE PLANES DE TRANSICIÓN', 5000.00, NULL, 2),
(33, 829, 'REFORMAS PLANES DE ESTUDIO, GRADO ASOCIADO', 3200.00, NULL, 2),
(34, 830, 'REFORMAS PLANES DE ESTUDIO, LICENCIATURA', 3500.00, NULL, 2),
(35, 831, 'REFORMAS PLANES DE ESTUDIO, MAESTRÍA-ESPECIALIDAD', 5000.00, NULL, 2),
(36, 832, 'REFORMAS PLANES DE ESTUDIO, DOCTORADO', 5000.00, NULL, 2),
(37, 837, 'REGISTRO DE CAMBIO DE CÓDIGO CARRERAS', 1000.00, NULL, 2),
(38, 838, 'CAMBIO DE CÓDIGO Y/O NOMBRE DE ASIGNATURA', 1000.00, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento.tblconsejales`
--

CREATE TABLE `mantenimiento.tblconsejales` (
  `IdConsejal` bigint(20) NOT NULL,
  `PrimerNombre` varchar(50) DEFAULT NULL,
  `PrimerApellido` varchar(50) DEFAULT NULL,
  `CategoriaConsejal` varchar(50) DEFAULT NULL,
  `EstadoConsejal` varchar(50) DEFAULT NULL,
  `CorreoConsejal` varchar(50) DEFAULT NULL,
  `IdUniversidad` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento.tbldeptos`
--

CREATE TABLE `mantenimiento.tbldeptos` (
  `IdDepartamento` bigint(20) NOT NULL,
  `NomDepto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mantenimiento.tbldeptos`
--

INSERT INTO `mantenimiento.tbldeptos` (`IdDepartamento`, `NomDepto`) VALUES
(1, 'Atlántida'),
(2, 'Colón'),
(3, 'Comayagua'),
(4, 'Copán'),
(5, 'Cortés'),
(6, 'Choluteca'),
(7, 'El Paraíso'),
(8, 'Francisco Morazán'),
(9, 'Gracias a Dios'),
(10, 'Intibucá'),
(11, 'Islas de la Bahía'),
(12, 'La Paz'),
(13, 'Lempira'),
(14, 'Ocotepeque'),
(15, 'Olancho'),
(16, 'Santa Bárbara'),
(17, 'Valle'),
(18, 'Yoro');

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento.tbldictamenesestados`
--

CREATE TABLE `mantenimiento.tbldictamenesestados` (
  `IdEstadodictamen` bigint(20) NOT NULL,
  `EstadoCTC` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mantenimiento.tbldictamenesestados`
--

INSERT INTO `mantenimiento.tbldictamenesestados` (`IdEstadodictamen`, `EstadoCTC`) VALUES
(1, 'FAVORABLE'),
(2, 'DESFAVORABLE');

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento.tblestadossolicitudes`
--

CREATE TABLE `mantenimiento.tblestadossolicitudes` (
  `IdEstado` bigint(20) NOT NULL,
  `EstadoSolicitud` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mantenimiento.tblestadossolicitudes`
--

INSERT INTO `mantenimiento.tblestadossolicitudes` (`IdEstado`, `EstadoSolicitud`) VALUES
(1, 'REVISION DE DOCUMENTOS'),
(2, 'PENDIENTE SUBSANACION DE DOCUMENTOS'),
(3, 'SUBSANACION DE DOCUMENTOS REALIZADA'),
(4, 'PRESENTAR DOCUMENTOS EN FISICO'),
(5, 'AGENDADO PARA ADMISION DEL CES'),
(6, 'ACUERDO DE ADMISION DEL CES'),
(7, 'AGENDADO PARA CTC'),
(8, 'DICTAMINADO POR EL CTC'),
(9, 'ANALISIS CURRICULAR'),
(10, 'ATENDER OBSERVACIONES Y/O CORRECCIONES'),
(11, 'OBSERVACIONES Y/O CORRECCIONES ATENDIDAS'),
(12, 'CORRECCION DE OPINION RAZONADA DGCA'),
(13, 'OPINION RAZONADA EMITIDA'),
(14, 'AGENDADO PARA APROBACION DEL CES'),
(15, 'ACUERDO DE APROBACION DEL CES'),
(16, 'REGISTRO DEL PLAN DE ESTUDIOS'),
(17, 'ENTREGA DEL PLAN DE ESTUDIOS'),
(18, 'SUBSANACION DE OPINION RAZONADA'),
(19, 'PENDIENTE DE APROBACION DGAC'),
(20, 'APROBADO POR DGAC'),
(21, 'PENDIENTE APROBACION D E'),
(22, 'APROBADO POR D E'),
(23, 'PENDIENTE APROBACION SEC.ADJUNTA'),
(24, 'APROBADO POR SEC.ADJUNTA'),
(25, 'CORRECCION DE OPINION RAZONADA D E'),
(26, 'CORRECCION DE OPINION RAZONADA DSA');

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento.tblestadosusuarios`
--

CREATE TABLE `mantenimiento.tblestadosusuarios` (
  `IdEstado` bigint(20) NOT NULL,
  `EstadoUsuario` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mantenimiento.tblestadosusuarios`
--

INSERT INTO `mantenimiento.tblestadosusuarios` (`IdEstado`, `EstadoUsuario`) VALUES
(1, 'Activo'),
(2, 'Inactivo'),
(3, 'Bloqueado'),
(4, 'Nuevo');

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento.tblgradosacademicos`
--

CREATE TABLE `mantenimiento.tblgradosacademicos` (
  `IdGrado` bigint(20) NOT NULL,
  `NomGrado` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mantenimiento.tblgradosacademicos`
--

INSERT INTO `mantenimiento.tblgradosacademicos` (`IdGrado`, `NomGrado`) VALUES
(1, 'Tecnólogo'),
(2, 'Licenciatura'),
(3, 'Maestría'),
(4, 'Especialidad'),
(5, 'Doctorado'),
(6, 'Doctorado en Medicina y K');

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento.tblmodalidades`
--

CREATE TABLE `mantenimiento.tblmodalidades` (
  `IdModalidad` bigint(20) NOT NULL,
  `NomModalidad` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mantenimiento.tblmodalidades`
--

INSERT INTO `mantenimiento.tblmodalidades` (`IdModalidad`, `NomModalidad`) VALUES
(1, 'Presencial'),
(2, 'Distancia'),
(3, 'Semi presencial con media'),
(4, 'Virtual');

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento.tblmunicipios`
--

CREATE TABLE `mantenimiento.tblmunicipios` (
  `IdMunicipio` bigint(20) NOT NULL,
  `NomMunicipio` varchar(50) DEFAULT NULL,
  `IdDepartamento` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mantenimiento.tblmunicipios`
--

INSERT INTO `mantenimiento.tblmunicipios` (`IdMunicipio`, `NomMunicipio`, `IdDepartamento`) VALUES
(1, 'Arizona', 1),
(2, 'El Porvenir', 1),
(3, 'Esparta', 1),
(4, 'Jutiapa', 1),
(5, 'La Ceiba', 1),
(6, 'La Masica', 1),
(7, 'San Francisco', 1),
(8, 'Tela', 1),
(9, 'Balfate', 2),
(10, 'Bonito Oriental', 2),
(11, 'Iriona', 2),
(12, 'Limón', 2),
(13, 'Sabá', 2),
(14, 'Santa Fé', 2),
(15, 'Santa Rosa de Aguán', 2),
(16, 'Sonaguera', 2),
(17, 'Tocoa', 2),
(18, 'Trujillo', 2),
(19, 'Ajuterique', 3),
(20, 'Comayagua', 3),
(21, 'El Rosario', 3),
(22, 'Esquías', 3),
(23, 'Humuya', 3),
(24, 'La Libertad', 3),
(25, 'La Trinidad', 3),
(26, 'Lamaní', 3),
(27, 'Las Lajas', 3),
(28, 'Lejamaní', 3),
(29, 'Meámbar', 3),
(30, 'Minas de Oro', 3),
(31, 'Ojos de Agua', 3),
(32, 'San Jerónimo', 3),
(33, 'San José de Comayagua', 3),
(34, 'San Luis', 3),
(35, 'San Sebastián', 3),
(36, 'Siguatepeque', 3),
(37, 'Taulabe', 3),
(38, 'Villa de San Antonio', 3),
(39, 'Cabañas', 4),
(40, 'Concepción', 4),
(41, 'Copán Ruinas', 4),
(42, 'Corquín', 4),
(43, 'Cucuyagua', 4),
(44, 'Dolores', 4),
(45, 'Dulce Nombre', 4),
(46, 'El Paraíso', 4),
(47, 'Florida', 4),
(48, 'La Jigua', 4),
(49, 'La Unión', 4),
(50, 'Nueva Arcadia', 4),
(51, 'San Agustín', 4),
(52, 'San Antonio', 4),
(53, 'San Jerónimo', 4),
(54, 'Cabañas', 4),
(55, 'San José', 4),
(56, 'San Juan de Opoa', 4),
(57, 'San Nicolás', 4),
(58, 'San Pedro de Copán', 4),
(59, 'Santa Rita', 4),
(60, 'Santa Rosa de Copán', 4),
(61, 'Trinidad de Copán', 4),
(62, 'Veracruz', 4),
(63, 'Choloma', 5),
(64, 'La Lima', 5),
(65, 'Omoa', 5),
(66, 'Pimienta', 5),
(67, 'Potrerillos', 5),
(68, 'Puerto Cortés', 5),
(69, 'San Antonio de Cortés', 5),
(70, 'San Francisco de Yojoa', 5),
(71, 'San Manuel', 5),
(72, 'San Pedro Sula', 5),
(73, 'Santa Cruz de Yojoa', 5),
(74, 'Villanueva', 5),
(75, 'Apacilagua', 6),
(76, 'Apacilagua', 6),
(77, 'Choluteca', 6),
(78, 'Concepción de María', 6),
(79, 'Duyure', 6),
(80, 'El Corpus', 6),
(81, 'El Triunfo', 6),
(82, 'Marcovia', 6),
(83, 'Morolica', 6),
(84, 'Namasigüe', 6),
(85, 'Orocuina', 6),
(86, 'Pespire', 6),
(87, 'San Antonio de Flores', 6),
(88, 'San Isidro', 6),
(89, 'San José', 6),
(90, 'San Marcos de Colón', 6),
(91, 'Santa Ana de Yusguare', 6),
(92, 'Alauca', 7),
(93, 'Yuscarán', 7),
(94, 'Danlí', 7),
(95, 'El Paraíso', 7),
(96, 'Guinope', 7),
(97, 'Jacaleapa', 7),
(98, 'Liure', 7),
(99, 'Morocelí', 7),
(100, 'Oropolí', 7),
(101, 'Potrerillos', 7),
(102, 'San Antonio de Flores', 7),
(103, 'San Lucas', 7),
(104, 'San Matías', 7),
(105, 'Soledad', 7),
(106, 'Teupasenti', 7),
(107, 'Texiguat', 7),
(108, 'Trojes', 7),
(109, 'Vado Ancho', 7),
(110, 'Yauyupe', 7),
(111, 'Alubarén', 8),
(112, 'Cantarranas', 8),
(113, 'Cedros', 8),
(114, 'Curarén', 8),
(115, 'Distrito Central', 8),
(116, 'El Porvenir', 8),
(117, 'Guaimaca', 8),
(118, 'La Libertad', 8),
(119, 'La Venta', 8),
(120, 'Lepaterique', 8),
(121, 'Maraita', 8),
(122, 'Marale', 8),
(123, 'Nueva Armenia', 8),
(124, 'Ojojona', 8),
(125, 'Orica', 8),
(126, 'Reitoca', 8),
(127, 'Sabanagrande', 8),
(128, 'San Antonio de Oriente', 8),
(129, 'San Buenaventura', 8),
(130, 'San Ignacio', 8),
(131, 'San Miguelito', 8),
(132, 'Santa Ana', 8),
(133, 'Santa Lucía', 8),
(134, 'Talanga', 8),
(135, 'Valle de Ángeles', 8),
(136, 'Vallecillo', 8),
(137, 'Villa de San Francisco', 8),
(138, 'Ahuas', 9),
(140, 'Brus Laguna', 9),
(141, 'Juan Francisco Bulnes', 9),
(142, 'Puerto Lempira', 9),
(143, 'Ramón Villeda Morales', 9),
(144, 'Wampusirpi', 9),
(145, 'Camasca', 10),
(146, 'Colomoncagua', 10),
(147, 'Concepción', 10),
(148, 'Dolores', 10),
(149, 'Intibucá', 10),
(150, 'Jesús de Otoro', 10),
(151, 'Magdalena', 10),
(152, 'Masaguara', 10),
(153, 'San Francisco de Opalaca\r\n', 10),
(154, 'San Isidro', 10),
(155, 'San Juan', 10),
(156, 'San Marcos de Sierra', 10),
(157, 'San Miguelito', 10),
(158, 'Santa Lucía de Intibucá', 10),
(159, 'Yamaranguila', 10),
(160, 'Guanaja', 11),
(161, 'José Santos Guardiola', 11),
(162, 'Roatán', 11),
(163, 'Útila', 11),
(164, 'Aguaqueterique', 12),
(165, 'Cabañas', 12),
(166, 'Cane', 12),
(167, 'Chinacla', 12),
(168, 'Guajiquiro', 12),
(169, 'La Paz', 12),
(170, 'Lauterique', 12),
(171, 'Marcala', 12),
(172, 'Mercedes de Oriente', 12),
(173, 'Opatoro', 12),
(174, 'San Antonio del Norte', 12),
(175, 'San José', 12),
(176, 'San Juan', 12),
(177, 'San Pedro de Tutule', 12),
(178, 'Santa Ana', 12),
(179, 'Santiago de Puringla', 12),
(180, 'Yarula', 12),
(181, 'Belén', 13),
(182, 'Belén', 13),
(183, 'Candelaria', 13),
(184, 'Cololaca', 13),
(185, 'Erandique', 13),
(186, 'Gracias', 13),
(187, 'Gualcince', 13),
(188, 'Guarita', 13),
(189, 'La Campa', 13),
(190, 'La Iguala', 13),
(191, 'La Unión', 13),
(192, 'La Virtud', 13),
(193, 'Las Flores', 13),
(194, 'Lepaera', 13),
(195, 'Mapulaca', 13),
(196, 'Piraera', 13),
(197, 'San Andrés', 13),
(198, 'San Francisco', 13),
(199, 'San Juan Guarita', 13),
(200, 'San Manuel Colohete', 13),
(201, 'San Marcos de Caiquín', 13),
(202, 'San Sebastian', 13),
(203, 'Santa Cruz', 13),
(204, 'Talgua', 13),
(205, 'Tambla', 13),
(206, 'Valladolid', 13),
(207, 'Virginia', 13),
(208, 'Belén Gualcho', 14),
(209, 'Belén Gualcho', 14),
(210, 'Concepción', 14),
(211, 'Dolores Merendon', 14),
(212, 'Fraternidad', 14),
(213, 'La Encarnación', 14),
(214, 'La Labor', 14),
(215, 'Lucerna', 14),
(216, 'Mercedes', 14),
(217, 'Ocotepeque', 14),
(218, 'San Fernando', 14),
(219, 'San Francisco del Valle', 14),
(220, 'San Jorge', 14),
(221, 'San Marcos', 14),
(222, 'Santa Fé', 14),
(223, 'Sensenti', 14),
(224, 'Sinuapa', 14),
(225, 'Campamento', 15),
(226, 'Campamento', 15),
(227, 'Catacamas', 15),
(228, 'Concordia', 15),
(229, 'Dulce Nombre de Culmí', 15),
(230, 'El Rosario', 15),
(231, 'Esquipulas del Norte', 15),
(232, 'Gualaco', 15),
(233, 'Guarizama', 15),
(234, 'Guata', 15),
(235, 'Guayape', 15),
(236, 'Jano', 15),
(237, 'La Unión', 15),
(238, 'Mangulile', 15),
(239, 'Manto', 15),
(240, 'Patuca', 15),
(241, 'Salamá', 15),
(242, 'San Esteban', 15),
(243, 'San Francisco de Becerra', 15),
(244, 'San Francisco de la Paz', 15),
(245, 'Santa Maria del Real', 15),
(246, 'Silca', 15),
(247, 'Yocón', 15),
(248, 'Arada', 16),
(251, 'Azacualpa', 16),
(252, 'Ceguaca', 16),
(253, 'Chinda', 16),
(254, 'Concepción del Norte', 16),
(255, 'Concepción del Sur', 16),
(256, 'El Nispero', 16),
(257, 'Gualala', 16),
(258, 'Ilama', 16),
(259, 'Las Vegas', 16),
(260, 'Macuelizo', 16),
(261, 'Naranjito', 16),
(262, 'Nueva Frontera', 16),
(263, 'Nuevo Celilac', 16),
(264, 'Petoa', 16),
(265, 'Protección', 16),
(266, 'Quimistán', 16),
(267, 'San Francisco de Ojuera', 16),
(268, 'San José de Colinas', 16),
(269, 'San Luis', 16),
(270, 'San Marcos', 16),
(271, 'San Nicolás', 16),
(272, 'San Pedro Zacapa', 16),
(273, 'San Vicente Centenario', 16),
(274, 'Santa Bárbara', 16),
(276, 'Alianza', 17),
(277, 'Amapala', 17),
(278, 'Aramecina', 17),
(279, 'Caridad', 17),
(280, 'Goascorán', 17),
(282, 'San Francisco de Coray', 17),
(283, 'San Lorenzo', 17),
(284, 'Langue', 17),
(285, 'Arenal', 18),
(286, 'El Negrito', 18),
(287, 'El Progreso', 18),
(288, 'Jocón', 18),
(289, 'Morazán', 18),
(290, 'Olanchito', 18),
(291, 'Sulaco', 18),
(292, 'Victoria', 18),
(293, 'Yorito', 18),
(294, 'Yoro', 18);

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento.tblsedes`
--

CREATE TABLE `mantenimiento.tblsedes` (
  `IdSedes` bigint(20) NOT NULL,
  `NomSedes` text NOT NULL,
  `IdUniversidad` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mantenimiento.tblsedes`
--

INSERT INTO `mantenimiento.tblsedes` (`IdSedes`, `NomSedes`, `IdUniversidad`) VALUES
(1, 'NOSE UNAH 1', 1),
(2, 'NOSE UNAH 2', 1),
(3, 'NOSE UTH 1', 1),
(4, 'NOSE UTH 2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento.tbltiposolicitudes`
--

CREATE TABLE `mantenimiento.tbltiposolicitudes` (
  `IdTiposolicitud` bigint(20) NOT NULL,
  `NomTipoSolicitud` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mantenimiento.tbltiposolicitudes`
--

INSERT INTO `mantenimiento.tbltiposolicitudes` (`IdTiposolicitud`, `NomTipoSolicitud`) VALUES
(1, 'INSTITUCIONAL'),
(2, 'ACADEMICO'),
(3, 'OTROS');

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento.tbluniversidadescentros`
--

CREATE TABLE `mantenimiento.tbluniversidadescentros` (
  `IdUniversidad` bigint(20) NOT NULL,
  `NomUniversidad` varchar(50) NOT NULL,
  `IdMunicipio` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mantenimiento.tbluniversidadescentros`
--

INSERT INTO `mantenimiento.tbluniversidadescentros` (`IdUniversidad`, `NomUniversidad`, `IdMunicipio`) VALUES
(1, 'UNAH', 115),
(2, 'UPNFM', 115),
(3, 'UNACIFOR', 36),
(4, 'UNAG', 227),
(5, 'UNPH', 115),
(6, 'UDH', 115),
(7, 'UNITEC', 115),
(8, 'UJCV', 115),
(9, 'UMH', 115),
(10, 'UPH', 115),
(11, 'UPI', 115),
(12, 'USMAH', 72),
(13, 'UCENM', 115),
(14, 'EAP ZAMORANO', 128),
(15, 'USAP', 72),
(16, 'UNICAH', 115),
(17, 'SMNSS', 115),
(18, 'UJN', 72),
(19, 'UNEV', 72),
(20, 'UTH', 72),
(21, 'UCRISH', 72);

-- --------------------------------------------------------

--
-- Table structure for table `proceso.tblacuerdoscesadmin`
--

CREATE TABLE `proceso.tblacuerdoscesadmin` (
  `IdAcuerdoadmin` bigint(20) NOT NULL,
  `NumActaAdmin` varchar(25) DEFAULT NULL,
  `AcuerdoAdmision` varchar(25) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `IdSolicitud` bigint(20) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proceso.tblacuerdoscesaprob`
--

CREATE TABLE `proceso.tblacuerdoscesaprob` (
  `IdAcuerdoaprob` bigint(20) NOT NULL,
  `NumActaAprobacion` varchar(25) DEFAULT NULL,
  `AcuerdoAprobacion` varchar(25) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `IdSolicitud` bigint(20) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proceso.tblbtcsolicitudes`
--

CREATE TABLE `proceso.tblbtcsolicitudes` (
  `IdBtcsolicitud` bigint(20) NOT NULL,
  `FechaSolicitud` datetime DEFAULT current_timestamp(),
  `Observacion` varchar(100) DEFAULT NULL,
  `IdSolicitud` bigint(20) DEFAULT NULL,
  `IdUniversidad` bigint(20) DEFAULT NULL,
  `IdCarrera` bigint(20) DEFAULT NULL,
  `IdEstado` bigint(20) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proceso.tbldictamenesuniversidades`
--

CREATE TABLE `proceso.tbldictamenesuniversidades` (
  `IdDicuni` bigint(20) NOT NULL,
  `IdDictamen` bigint(20) NOT NULL,
  `IdUniversidad` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proceso.tblopinionesconsejales`
--

CREATE TABLE `proceso.tblopinionesconsejales` (
  `IdOpinionconsejal` bigint(20) NOT NULL,
  `IdOpinionrazonada` bigint(20) DEFAULT NULL,
  `IdConsejal` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proceso.tblseguimientos`
--

CREATE TABLE `proceso.tblseguimientos` (
  `IdSeguimiento` bigint(20) NOT NULL,
  `NumeroDeActa` varchar(50) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `IdSolicitud` bigint(20) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  `IdEstado` bigint(20) DEFAULT NULL,
  `IdDictamenctc` bigint(20) DEFAULT NULL,
  `IdOpinionrazonada` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proceso.tblsesiones`
--

CREATE TABLE `proceso.tblsesiones` (
  `IdSesion` bigint(20) NOT NULL,
  `Sesion` varchar(20) NOT NULL,
  `IdSolicitud` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proceso.tblsesionesctc`
--

CREATE TABLE `proceso.tblsesionesctc` (
  `IdSesion` bigint(20) NOT NULL,
  `SesionCTC` varchar(20) NOT NULL,
  `IdSolicitud` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proceso.tblsolicitudes`
--

CREATE TABLE `proceso.tblsolicitudes` (
  `IdSolicitud` bigint(20) NOT NULL,
  `NumReferencia` varchar(40) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `NombreCompleto` varchar(100) DEFAULT NULL,
  `CorreoElectronico` varchar(100) DEFAULT NULL,
  `FechaIngreso` datetime DEFAULT current_timestamp(),
  `FechaModificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CodigoPago` bigint(20) DEFAULT NULL,
  `NombreCarrera` varchar(255) DEFAULT '',
  `IdCategoria` bigint(20) DEFAULT NULL,
  `IdCarrera` bigint(20) DEFAULT NULL,
  `IdGrado` bigint(20) DEFAULT NULL,
  `IdModalidad` bigint(20) DEFAULT NULL,
  `IdUniversidad` bigint(20) DEFAULT NULL,
  `IdDepartamento` bigint(20) DEFAULT NULL,
  `IdMunicipio` bigint(20) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  `IdEstado` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seguridad.tblbitacora`
--

CREATE TABLE `seguridad.tblbitacora` (
  `IdBitacora` int(11) NOT NULL,
  `FechaHora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `IdUsuario` bigint(20) NOT NULL,
  `IdObjeto` bigint(20) NOT NULL,
  `Accion` varchar(100) NOT NULL,
  `Descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seguridad.tblbitacora`
--

INSERT INTO `seguridad.tblbitacora` (`IdBitacora`, `FechaHora`, `IdUsuario`, `IdObjeto`, `Accion`, `Descripcion`) VALUES
(2551, '2024-08-16 16:09:16', 12, 9, 'acceso denegado', 'Reportes'),
(2552, '2024-08-16 16:09:18', 12, 10, 'acceso denegado', 'Mantenimiento Sistema'),
(2553, '2024-08-16 16:09:18', 12, 11, 'accedio al modulo', 'Seguridad'),
(2554, '2024-08-16 16:09:20', 12, 21, 'accedio al modulo', 'Bitácora de Sistema'),
(2555, '2024-08-16 16:09:27', 12, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2556, '2024-08-16 16:09:31', 12, 4, 'acceso denegado', 'Consultar Solicitudes'),
(2557, '2024-08-16 16:09:33', 12, 8, 'acceso denegado', 'Mantenimiento Solicitudes'),
(2558, '2024-08-16 16:09:35', 12, 11, 'accedio al modulo', 'Seguridad'),
(2559, '2024-08-16 16:09:36', 12, 21, 'accedio al modulo', 'Bitácora de Sistema'),
(2560, '2024-08-16 16:09:43', 12, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2561, '2024-08-16 16:37:54', 12, 3, 'acceso denegado', 'Nuevo Ingreso de Solicitud'),
(2562, '2024-08-16 16:37:54', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2563, '2024-08-16 16:37:55', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2564, '2024-08-16 16:37:56', 12, 9, 'accedio al modulo', 'Reportes'),
(2565, '2024-08-16 16:38:18', 12, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2566, '2024-08-16 16:38:20', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2567, '2024-08-16 16:38:22', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2568, '2024-08-16 16:38:23', 12, 9, 'accedio al modulo', 'Reportes'),
(2569, '2024-08-16 16:39:35', 12, 9, 'acceso denegado', 'Reportes'),
(2570, '2024-08-16 16:39:39', 12, 10, 'acceso denegado', 'Mantenimiento Sistema'),
(2571, '2024-08-16 16:39:40', 12, 11, 'accedio al modulo', 'Seguridad'),
(2572, '2024-08-16 16:39:44', 12, 21, 'accedio al modulo', 'Bitácora de Sistema'),
(2573, '2024-08-16 16:39:49', 12, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2574, '2024-08-16 16:40:39', 12, 3, 'acceso denegado', 'Nuevo Ingreso de Solicitud'),
(2575, '2024-08-16 16:40:41', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2576, '2024-08-16 16:40:42', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2577, '2024-08-16 16:40:42', 12, 9, 'accedio al modulo', 'Reportes'),
(2578, '2024-08-16 16:46:38', 12, 11, 'acceso denegado', 'Seguridad'),
(2579, '2024-08-16 16:46:40', 12, 9, 'accedio al modulo', 'Informes'),
(2580, '2024-08-16 16:46:57', 12, 8, 'acceso denegado', 'Mantenimiento Solicitudes'),
(2581, '2024-08-16 16:47:06', 12, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2582, '2024-08-16 16:51:52', 12, 10, 'acceso denegado', 'Mantenimiento Sistema'),
(2583, '2024-08-16 16:51:53', 12, 9, 'accedio al modulo', 'Reportes'),
(2584, '2024-08-16 16:51:54', 12, 8, 'acceso denegado', 'Mantenimiento Solicitudes'),
(2585, '2024-08-16 16:51:55', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2586, '2024-08-16 17:03:23', 12, 11, 'acceso denegado', 'Seguridad'),
(2587, '2024-08-16 17:03:24', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2588, '2024-08-16 17:03:25', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2589, '2024-08-16 17:03:26', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2590, '2024-08-16 17:03:31', 12, 11, 'acceso denegado', 'Seguridad'),
(2591, '2024-08-16 17:03:33', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2592, '2024-08-16 17:03:34', 12, 9, 'accedio al modulo', 'Reportes'),
(2593, '2024-08-16 17:03:36', 12, 8, 'acceso denegado', 'Mantenimiento Solicitudes'),
(2594, '2024-08-16 17:03:38', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2595, '2024-08-16 17:03:40', 12, 3, 'acceso denegado', 'Nuevo Ingreso de Solicitud'),
(2596, '2024-08-16 17:03:42', 12, 3, 'acceso denegado', 'Nuevo Ingreso de Solicitud'),
(2597, '2024-08-16 17:29:59', 12, 3, 'acceso denegado', 'Nuevo Ingreso de Solicitud'),
(2598, '2024-08-16 17:30:01', 12, 11, 'acceso denegado', 'Seguridad'),
(2599, '2024-08-16 17:30:03', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2600, '2024-08-16 17:30:04', 12, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2601, '2024-08-16 17:30:16', 12, 3, 'acceso denegado', 'Nuevo Ingreso de Solicitud'),
(2602, '2024-08-16 17:30:28', 12, 3, 'acceso denegado', 'Nuevo Ingreso de Solicitud'),
(2603, '2024-08-16 17:30:52', 12, 3, 'acceso denegado', 'Nuevo Ingreso de Solicitud'),
(2604, '2024-08-16 17:31:18', 12, 3, 'acceso denegado', 'Nuevo Ingreso de Solicitud'),
(2605, '2024-08-16 17:39:55', 12, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2606, '2024-08-16 17:39:58', 12, 4, 'acceso denegado', 'Consultar Solicitudes'),
(2607, '2024-08-16 17:40:00', 12, 8, 'acceso denegado', 'Mantenimiento Solicitudes'),
(2608, '2024-08-16 17:40:01', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2609, '2024-08-16 17:40:03', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2610, '2024-08-16 17:40:03', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2611, '2024-08-16 17:40:04', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2612, '2024-08-16 18:12:06', 15, 4, 'acceso denegado', 'Consultar Solicitudes'),
(2613, '2024-08-16 18:12:08', 15, 11, 'acceso denegado', 'Seguridad'),
(2614, '2024-08-16 18:13:10', 15, 3, 'acceso denegado', 'Nuevo Ingreso de Solicitud'),
(2615, '2024-08-16 18:13:12', 15, 11, 'acceso denegado', 'Seguridad'),
(2616, '2024-08-16 18:13:20', 15, 10, 'acceso denegado', 'Mantenimiento Sistema'),
(2617, '2024-08-16 18:13:21', 15, 9, 'accedio al modulo', 'Reportes'),
(2618, '2024-08-16 18:13:23', 15, 8, 'acceso denegado', 'Mantenimiento Solicitudes'),
(2619, '2024-08-16 18:13:24', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2620, '2024-08-16 18:13:25', 15, 3, 'acceso denegado', 'Nuevo Ingreso de Solicitud'),
(2621, '2024-08-16 18:13:28', 15, 11, 'acceso denegado', 'Seguridad'),
(2622, '2024-08-16 18:13:29', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2623, '2024-08-16 18:13:30', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2624, '2024-08-16 18:13:40', 15, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2625, '2024-08-16 18:13:43', 15, 11, 'acceso denegado', 'Seguridad'),
(2626, '2024-08-16 18:13:43', 15, 17, 'accedio al modulo', 'Permisos'),
(2627, '2024-08-16 18:13:53', 15, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2628, '2024-08-16 18:29:02', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2629, '2024-08-16 18:29:04', 15, 8, 'acceso denegado', 'Mantenimiento Solicitudes'),
(2630, '2024-08-16 19:07:00', 15, 18, 'accedio al modulo', 'Parámetros'),
(2631, '2024-08-16 19:07:02', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2632, '2024-08-16 19:07:19', 15, 18, 'accedio al modulo', 'Parámetros'),
(2633, '2024-08-16 19:07:20', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2634, '2024-08-16 19:54:40', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2635, '2024-08-16 19:54:42', 15, 8, 'acceso denegado', 'Mantenimiento Solicitudes'),
(2636, '2024-08-16 19:55:15', 15, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2637, '2024-08-16 19:55:20', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2638, '2024-08-16 20:20:20', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2639, '2024-08-16 21:37:31', 15, 9, 'acceso denegado', 'Reportes'),
(2640, '2024-08-16 21:37:33', 15, 11, 'acceso denegado', 'Seguridad'),
(2641, '2024-08-16 21:37:34', 15, 17, 'accedio al modulo', 'Permisos'),
(2642, '2024-08-16 21:37:42', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2643, '2024-08-16 23:03:19', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2644, '2024-08-16 23:08:02', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2645, '2024-08-16 23:08:33', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2646, '2024-08-16 23:08:35', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2647, '2024-08-16 23:08:48', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2648, '2024-08-16 23:12:44', 15, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2649, '2024-08-16 23:12:44', 15, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2650, '2024-08-16 23:15:59', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2651, '2024-08-16 23:15:59', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2652, '2024-08-16 23:19:36', 15, 9, 'accedio al modulo', 'Reportes'),
(2653, '2024-08-16 23:19:38', 15, 11, 'acceso denegado', 'Seguridad'),
(2654, '2024-08-16 23:19:39', 15, 17, 'accedio al modulo', 'Permisos'),
(2655, '2024-08-16 23:19:46', 15, 9, 'accedio al modulo', 'Reportes'),
(2656, '2024-08-16 23:25:42', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2657, '2024-08-16 23:27:04', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2658, '2024-08-16 23:27:36', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2659, '2024-08-16 23:27:38', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2660, '2024-08-16 23:28:44', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2661, '2024-08-16 23:29:07', 15, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2662, '2024-08-16 23:29:07', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2663, '2024-08-16 23:33:49', 15, 9, 'accedio al modulo', 'Reportes'),
(2664, '2024-08-16 23:33:50', 15, 11, 'accedio al modulo', 'Seguridad'),
(2665, '2024-08-16 23:33:51', 15, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2666, '2024-08-16 23:33:52', 15, 9, 'accedio al modulo', 'Reportes'),
(2667, '2024-08-16 23:34:40', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2668, '2024-08-16 23:36:02', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2669, '2024-08-16 23:39:30', 15, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2670, '2024-08-16 23:39:31', 15, 9, 'accedio al modulo', 'Reportes'),
(2671, '2024-08-16 23:39:32', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2672, '2024-08-16 23:39:33', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2673, '2024-08-16 23:39:34', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2674, '2024-08-16 23:43:10', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2675, '2024-08-16 23:43:13', 15, 9, 'accedio al modulo', 'Reportes'),
(2676, '2024-08-16 23:49:21', 15, 9, 'accedio al modulo', 'Informes'),
(2677, '2024-08-16 23:54:53', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2678, '2024-08-16 23:54:58', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2679, '2024-08-16 23:55:18', 15, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2680, '2024-08-16 23:57:21', 15, 27, 'accedio al modulo', 'GradosAcademicos'),
(2681, '2024-08-16 23:57:26', 15, 26, 'acceso denegado', 'Modalidades'),
(2682, '2024-08-16 23:57:29', 15, 22, 'acceso denegado', 'Departamentos'),
(2683, '2024-08-16 23:57:32', 15, 23, 'accedio al modulo', 'Municipios'),
(2684, '2024-08-16 23:57:35', 15, 10, 'acceso denegado', 'Mantenimiento Sistema'),
(2685, '2024-08-16 23:57:38', 15, 11, 'accedio al modulo', 'Seguridad'),
(2686, '2024-08-16 23:57:39', 15, 15, 'accedio al modulo', 'Usuarios'),
(2687, '2024-08-16 23:57:40', 15, 19, 'accedio al modulo', 'Roles'),
(2688, '2024-08-16 23:57:41', 15, 11, 'accedio al modulo', 'Seguridad'),
(2689, '2024-08-16 23:57:42', 15, 16, 'accedio al modulo', 'Consejeros'),
(2690, '2024-08-16 23:57:44', 15, 11, 'accedio al modulo', 'Seguridad'),
(2691, '2024-08-16 23:57:44', 15, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2692, '2024-08-16 23:57:45', 15, 24, 'accedio al modulo', 'Universidades'),
(2693, '2024-08-16 23:57:47', 15, 25, 'accedio al modulo', 'Carreras'),
(2694, '2024-08-16 23:57:49', 15, 28, 'accedio al modulo', 'Tipos De Solicitudes'),
(2695, '2024-08-16 23:57:51', 15, 8, 'acceso denegado', 'Mantenimiento Solicitudes'),
(2696, '2024-08-16 23:57:55', 15, 17, 'accedio al modulo', 'Permisos'),
(2697, '2024-08-16 23:58:08', 15, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2698, '2024-08-16 23:58:10', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2699, '2024-08-16 23:58:11', 15, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2700, '2024-08-16 23:58:13', 15, 27, 'accedio al modulo', 'GradosAcademicos'),
(2701, '2024-08-16 23:58:33', 15, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2702, '2024-08-16 23:59:55', 15, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2703, '2024-08-16 23:59:58', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2704, '2024-08-17 00:00:52', 15, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2705, '2024-08-17 00:04:09', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2706, '2024-08-17 00:04:14', 15, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2707, '2024-08-17 00:27:07', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2708, '2024-08-17 00:27:10', 15, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2709, '2024-08-17 00:27:12', 15, 27, 'accedio al modulo', 'GradosAcademicos'),
(2710, '2024-08-17 00:30:05', 15, 22, 'accedio al modulo', 'Departamentos'),
(2711, '2024-08-17 00:30:43', 15, 11, 'accedio al modulo', 'Seguridad'),
(2712, '2024-08-17 00:30:44', 15, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2713, '2024-08-17 00:30:50', 15, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2714, '2024-08-17 00:30:51', 15, 11, 'accedio al modulo', 'Seguridad'),
(2715, '2024-08-17 00:30:56', 15, 21, 'accedio al modulo', 'Bitácora de Sistema'),
(2716, '2024-08-17 00:32:01', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2717, '2024-08-17 00:32:05', 15, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2718, '2024-08-17 00:32:07', 15, 27, 'accedio al modulo', 'GradosAcademicos'),
(2719, '2024-08-17 00:40:37', 15, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2720, '2024-08-17 00:40:38', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2721, '2024-08-17 00:42:16', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2722, '2024-08-17 00:45:28', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2723, '2024-08-17 00:46:25', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2724, '2024-08-17 00:46:59', 15, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2725, '2024-08-17 00:47:00', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2726, '2024-08-17 00:47:05', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2727, '2024-08-17 00:47:23', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2728, '2024-08-17 00:48:15', 15, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2729, '2024-08-17 00:48:17', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2730, '2024-08-17 00:48:56', 15, 9, 'accedio al modulo', 'Reportes'),
(2731, '2024-08-17 01:06:44', 15, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2732, '2024-08-17 01:06:48', 15, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2733, '2024-08-17 01:06:49', 15, 27, 'accedio al modulo', 'GradosAcademicos'),
(2734, '2024-08-17 01:06:53', 15, 3, 'accedio al modulo', 'Nuevo Ingreso de Solicitud'),
(2735, '2024-08-17 01:08:40', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2736, '2024-08-17 01:08:47', 15, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2737, '2024-08-17 01:08:47', 15, 9, 'accedio al modulo', 'Reportes'),
(2738, '2024-08-17 01:08:49', 15, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2739, '2024-08-20 00:15:16', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2740, '2024-08-20 00:20:13', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2741, '2024-08-20 00:20:55', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2742, '2024-08-20 01:17:19', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2743, '2024-08-20 01:20:07', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2744, '2024-08-20 01:23:15', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2745, '2024-08-20 01:31:43', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2746, '2024-08-20 01:47:40', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2747, '2024-08-20 22:24:19', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2748, '2024-08-20 23:41:11', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2749, '2024-08-20 23:43:42', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2750, '2024-08-21 00:04:40', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2751, '2024-08-21 00:14:07', 12, 22, 'accedio al modulo', 'Departamentos'),
(2752, '2024-08-21 00:30:45', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2753, '2024-08-21 00:38:51', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2754, '2024-08-21 00:42:06', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2755, '2024-08-21 00:50:01', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2756, '2024-08-21 00:50:02', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2757, '2024-08-21 01:20:09', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2758, '2024-08-21 01:22:14', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2759, '2024-08-21 01:39:24', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2760, '2024-08-21 01:56:50', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2761, '2024-08-21 03:27:10', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2762, '2024-08-21 03:29:33', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2763, '2024-08-21 03:29:35', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2764, '2024-08-21 03:31:31', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2765, '2024-08-21 03:54:48', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2766, '2024-08-21 03:56:39', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2767, '2024-08-21 03:56:43', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2768, '2024-08-21 04:06:12', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2769, '2024-08-21 04:32:18', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2770, '2024-08-21 04:36:00', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2771, '2024-08-21 04:36:15', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2772, '2024-08-21 04:36:22', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2773, '2024-08-21 22:08:59', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2774, '2024-08-21 22:19:04', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2775, '2024-08-22 21:45:44', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2776, '2024-08-22 21:45:46', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2777, '2024-08-22 21:45:51', 12, 30, 'accedio al modulo', 'Estado De Solicitudes'),
(2778, '2024-08-22 21:45:53', 12, 10, 'acceso denegado', 'Mantenimiento Sistema'),
(2779, '2024-08-22 21:45:55', 12, 22, 'accedio al modulo', 'Departamentos'),
(2780, '2024-08-22 21:46:03', 12, 25, 'accedio al modulo', 'Carreras'),
(2781, '2024-08-23 03:34:43', 12, 22, 'accedio al modulo', 'Departamentos'),
(2782, '2024-08-23 03:34:46', 12, 23, 'accedio al modulo', 'Municipios'),
(2783, '2024-08-23 03:34:48', 12, 24, 'acceso denegado', 'Universidades'),
(2784, '2024-08-23 03:34:51', 12, 25, 'accedio al modulo', 'Carreras'),
(2785, '2024-08-23 03:34:57', 12, 25, 'accedio al modulo', 'Carreras'),
(2786, '2024-08-23 03:35:02', 12, 26, 'accedio al modulo', 'Modalidades'),
(2787, '2024-08-23 03:35:03', 12, 27, 'acceso denegado', 'GradosAcademicos'),
(2788, '2024-08-23 03:35:07', 12, 28, 'accedio al modulo', 'Tipos De Solicitudes'),
(2789, '2024-08-23 03:35:09', 12, 29, 'acceso denegado', 'Categorias De Solicitudes'),
(2790, '2024-08-23 03:35:13', 12, 22, 'acceso denegado', 'Departamentos'),
(2791, '2024-08-23 03:35:15', 12, 23, 'accedio al modulo', 'Municipios'),
(2792, '2024-08-23 03:35:17', 12, 24, 'acceso denegado', 'Universidades'),
(2793, '2024-08-23 03:35:19', 12, 25, 'accedio al modulo', 'Carreras'),
(2794, '2024-08-23 03:35:21', 12, 26, 'accedio al modulo', 'Modalidades'),
(2795, '2024-08-23 03:35:23', 12, 27, 'acceso denegado', 'GradosAcademicos'),
(2796, '2024-08-23 03:35:25', 12, 28, 'accedio al modulo', 'Tipos De Solicitudes'),
(2797, '2024-08-23 03:35:27', 12, 29, 'acceso denegado', 'Categorias De Solicitudes'),
(2798, '2024-08-23 03:35:29', 12, 30, 'acceso denegado', 'Estado De Solicitudes'),
(2799, '2024-08-23 03:35:32', 12, 11, 'acceso denegado', 'Seguridad'),
(2800, '2024-08-23 03:35:34', 12, 17, 'accedio al modulo', 'Permisos'),
(2801, '2024-08-23 03:36:03', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2802, '2024-08-23 03:36:05', 12, 30, 'accedio al modulo', 'Estado De Solicitudes'),
(2803, '2024-08-23 03:36:10', 12, 29, 'accedio al modulo', 'Categorias De Solicitudes'),
(2804, '2024-08-23 03:43:10', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2805, '2024-08-23 03:43:11', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2806, '2024-08-23 03:43:14', 12, 11, 'accedio al modulo', 'Seguridad'),
(2807, '2024-08-23 03:43:16', 12, 21, 'accedio al modulo', 'Bitácora de Sistema'),
(2808, '2024-08-23 03:43:24', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2809, '2024-08-24 00:52:41', 12, 25, 'accedio al modulo', 'Carreras'),
(2810, '2024-08-24 00:53:37', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2811, '2024-08-24 00:53:38', 12, 25, 'accedio al modulo', 'Carreras'),
(2812, '2024-08-24 01:50:16', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2813, '2024-08-24 20:41:55', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2814, '2024-08-24 21:41:40', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2815, '2024-08-24 21:49:40', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2816, '2024-08-24 21:49:40', 12, 4, 'accedio al modulo', 'Consultar Solicitudes'),
(2817, '2024-08-24 21:49:42', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2818, '2024-08-24 21:51:00', 12, 22, 'accedio al modulo', 'Departamentos'),
(2819, '2024-08-24 21:56:28', 12, 23, 'accedio al modulo', 'Municipios'),
(2820, '2024-08-24 21:56:56', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2821, '2024-08-24 21:56:57', 12, 24, 'accedio al modulo', 'Universidades'),
(2822, '2024-08-24 22:05:18', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2823, '2024-08-24 22:05:19', 12, 25, 'accedio al modulo', 'Carreras'),
(2824, '2024-08-24 22:07:45', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2825, '2024-08-24 22:07:46', 12, 26, 'accedio al modulo', 'Modalidades'),
(2826, '2024-08-24 22:17:32', 12, 11, 'accedio al modulo', 'Seguridad'),
(2827, '2024-08-24 22:17:32', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2828, '2024-08-24 22:17:34', 12, 27, 'accedio al modulo', 'GradosAcademicos'),
(2829, '2024-08-24 22:18:51', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2830, '2024-08-24 22:18:52', 12, 28, 'accedio al modulo', 'Tipos De Solicitudes'),
(2831, '2024-08-24 22:29:18', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2832, '2024-08-24 22:29:19', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2833, '2024-08-24 22:34:40', 12, 29, 'accedio al modulo', 'Categorias De Solicitudes'),
(2834, '2024-08-24 22:38:40', 12, 10, 'accedio al modulo', 'Mantenimiento Sistema'),
(2835, '2024-08-24 22:38:42', 12, 30, 'accedio al modulo', 'Estado De Solicitudes'),
(2836, '2024-08-24 23:13:10', 12, 11, 'accedio al modulo', 'Seguridad'),
(2837, '2024-08-24 23:13:11', 12, 18, 'accedio al modulo', 'Parametros'),
(2838, '2024-08-24 23:17:15', 12, 11, 'accedio al modulo', 'Seguridad'),
(2839, '2024-08-24 23:17:16', 12, 17, 'accedio al modulo', 'Permisos'),
(2840, '2024-08-24 23:23:26', 12, 11, 'accedio al modulo', 'Seguridad'),
(2841, '2024-08-24 23:23:27', 12, 19, 'accedio al modulo', 'Roles'),
(2842, '2024-08-24 23:33:55', 12, 11, 'accedio al modulo', 'Seguridad'),
(2843, '2024-08-24 23:33:57', 12, 20, 'accedio al modulo', 'Objetos'),
(2844, '2024-08-24 23:53:13', 12, 11, 'accedio al modulo', 'Seguridad'),
(2845, '2024-08-24 23:53:14', 12, 21, 'accedio al modulo', 'Bitácora de Sistema'),
(2846, '2024-08-24 23:54:47', 12, 9, 'accedio al modulo', 'Reportes'),
(2847, '2024-08-24 23:55:21', 12, 15, 'accedio al modulo', 'Usuarios'),
(2848, '2024-08-25 00:16:56', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes'),
(2849, '2024-08-25 00:18:57', 12, 8, 'accedio al modulo', 'Mantenimiento Solicitudes');

-- --------------------------------------------------------

--
-- Table structure for table `seguridad.tblhistcontraseñas`
--

CREATE TABLE `seguridad.tblhistcontraseñas` (
  `IdHist` bigint(20) NOT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  `Contraseña` varchar(255) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seguridad.tblhistcontraseñas`
--

INSERT INTO `seguridad.tblhistcontraseñas` (`IdHist`, `IdUsuario`, `Contraseña`, `FechaCreacion`) VALUES
(16, 1, '$2y$10$LdESfZlYdsT1CK6/dLNMTu68.ceFi.SiieBQA/./VmCcxDMvN2fb2', '2024-09-04 09:12:20');

-- --------------------------------------------------------

--
-- Table structure for table `seguridad.tblmsroles`
--

CREATE TABLE `seguridad.tblmsroles` (
  `IdRol` bigint(20) NOT NULL,
  `Rol` varchar(255) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `CreadoPor` varchar(255) DEFAULT '',
  `FechaModificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ModificadoPor` varchar(255) DEFAULT '',
  `IdUsuario` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seguridad.tblmsroles`
--

INSERT INTO `seguridad.tblmsroles` (`IdRol`, `Rol`, `Descripcion`, `FechaCreacion`, `CreadoPor`, `FechaModificacion`, `ModificadoPor`, `IdUsuario`) VALUES
(1, 'Administrador', 'Permiso Absoluto', '2024-06-23 21:27:39', NULL, '2024-09-03 11:57:47', NULL, 10),
(2, 'Secretaria', 'Notificación Mediante Correo De Recibido De Solicitudes', '2024-06-23 21:29:44', NULL, '2024-09-03 11:57:47', NULL, 10),
(3, 'Auxiliar De Control Documental', 'Permiso a Módulo De Mantenimiento De Solicitudes', '2024-06-23 21:31:08', NULL, '2024-09-03 11:57:47', NULL, 10),
(4, 'Encargado Del CES', 'Permiso a Módulo De Mantenimiento De Solicitudes,Consultas Y Asiganciones', '2024-06-23 21:32:13', NULL, '2024-09-03 11:57:47', NULL, 10),
(5, 'Encargado Del CTC', 'Permiso a Módulo De Seguimiento Académico,Módulo Dictamen CTC', '2024-06-23 21:33:08', NULL, '2024-09-03 11:57:47', NULL, 10),
(6, 'Analista Curricular', 'Permiso a Módulo De Seguimiento Académico,Módulo Dictamen CTC,Módulo De Mantenimiento De Solicitudes,Modulo Análisis Curricular', '2024-06-23 21:34:54', NULL, '2024-09-03 11:57:47', NULL, 10),
(7, 'Jefes Departamento', 'Permiso a Formulario Ingreso Solicitud,Consulta Solicitudes,Seguimiento Académico,Mantenimiento Solicitudes,Reportes', '2024-06-23 21:36:40', NULL, '2024-09-03 11:57:47', NULL, 10),
(8, 'Encargado De Digitalización', 'Permiso a Modulo De Mantenimiento De SolicitudesS', '2024-06-23 21:38:17', NULL, '2024-09-03 11:57:47', NULL, 10),
(9, 'Instituto De Educación Superior', 'Permiso a Menú Vista Universidad,Formulario Ingreso De Solicitud, Consulta Solicitudes Universidad, Subsanación Documentos De La Solicitud,Dictamen CTC,Subir Adjuntos,Análisis Curricular,Emisión De Opinión Razonada,Plan De Estudios Registrado Firmado y Fo', '2024-06-23 21:40:49', NULL, '2024-09-03 11:57:47', NULL, 10),
(10, 'Consejales', 'PARA MANTENIMIENTO USUARIO', '2024-07-28 19:52:36', NULL, '2024-09-03 11:57:47', NULL, 10),
(11, 'Universidad', 'Para todas las universidades', '2024-09-05 23:08:32', '', '2024-09-05 23:08:32', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `seguridad.tblobjetos`
--

CREATE TABLE `seguridad.tblobjetos` (
  `IdObjeto` bigint(20) NOT NULL,
  `Objeto` varchar(50) NOT NULL,
  `TipoObjeto` varchar(50) DEFAULT '',
  `Descripcion` varchar(255) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `CreadoPor` varchar(50) DEFAULT NULL,
  `FechaModificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ModificadoPor` varchar(50) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seguridad.tblobjetos`
--

INSERT INTO `seguridad.tblobjetos` (`IdObjeto`, `Objeto`, `TipoObjeto`, `Descripcion`, `FechaCreacion`, `CreadoPor`, `FechaModificacion`, `ModificadoPor`, `IdUsuario`) VALUES
(1, 'LOGIN INGRESAR', 'BOTON', 'CREADO PARA INICIO DE SESIÓN DE USUARIOS', '2024-06-21 19:54:59', '1', '2024-06-24 21:51:56', NULL, 1),
(2, 'LOGIN OLVIDASTE CONTRASEÑA', 'HREF', 'CREADO PARA RECUPERAR LA CONTRASEÑA OLVIDADA', '2024-06-21 20:00:52', '1', '2024-06-24 21:51:56', NULL, 1),
(3, 'INGRESO DE SOLICITUDES', 'BOTON', 'CREADO PARA LLENAR EL FORMULARIO INGRESO DE SOLICITUD', '2024-06-21 20:12:02', '1', '2024-06-24 21:51:56', NULL, 1),
(4, 'CONSULTAR SOLICTUDES', 'BOTON', 'CREADO PARA VER LAS CONSULTAS DE SOLICITUDES POR UNIVERSIDADES', '2024-06-21 20:13:04', '1', '2024-06-24 21:51:56', NULL, 1),
(5, 'GESTION DE SOLICITUDES', 'BOTON', 'CREADO PARA VER EL FORMULARIO INGRESO DE SOLICITUD EMPLEADO', '2024-06-21 20:15:19', '1', '2024-06-24 21:51:56', NULL, 1),
(7, 'SEGUIMIENTO ACADEMICO', 'BOTON', 'CREADO PARA CONSULTAR SOLICITUDES', '2024-06-21 20:57:13', '1', '2024-06-24 21:51:56', NULL, 1),
(8, 'MANTENIMIENTO SOLICITUDES', 'BOTON', 'CREADO PARA VER REVISION,ACUERDOS,DICTAMEN,ANALIS,ENTREGA DE PLAN DE ESTUDIOS', '2024-06-21 20:59:08', '1', '2024-06-24 21:51:56', NULL, 1),
(9, 'REPORTES ', 'BOTON', 'CREADO PARA GENERAR REPORTERIA', '2024-06-21 21:00:05', '1', '2024-06-24 21:51:56', NULL, 1),
(10, 'MANTENIMIENTO', 'BOTON', 'CREADO PARA MANEJAR EL CRUD A DEPARTAMENTOS,MUNICIPIOS,UNIVERSIDADES ETC.', '2024-06-21 21:01:45', '1', '2024-06-24 21:51:56', NULL, 1),
(11, 'SEGURIDAD', 'BOTON', 'CREADO PARA MANEJAR EL CRUD A USUARIOS,CONSEJALES,PERMISOS,ROLES ETC.', '2024-06-21 21:02:58', '1', '2024-06-24 21:51:56', NULL, 1),
(15, 'USUARIOS', 'HREF', 'PARA MANTENIMIENTO USUARIO', '2024-07-09 19:06:03', '15', '2024-07-09 11:06:03', NULL, 1),
(16, 'CONSEJALES', 'HREF', 'PARA MANTENIMIENTO CONSEJALES', '2024-07-09 19:07:01', '15', '2024-07-09 11:07:01', NULL, 1),
(17, 'PERMISOS', 'HREF', 'PARA MANTENIMIENTO PERMISOS', '2024-07-09 19:07:53', '15', '2024-07-09 11:07:53', NULL, 1),
(18, 'PARAMETROS', 'HREF', 'PARA MANTENIMIENTO PARAMETROS', '2024-07-09 19:08:57', '15', '2024-07-09 11:08:57', NULL, 1),
(19, 'ROLES', 'HREF', 'PARA MANTENIMIENTO ROLES', '2024-07-09 19:10:50', '15', '2024-07-09 11:10:50', NULL, 1),
(20, 'OBJETOS', 'HREF', 'PARA MANTENIMIENTO OBJETOS', '2024-07-09 19:11:07', '15', '2024-07-09 11:11:07', NULL, 1),
(21, 'BITACORA', 'HREF', 'PARA MANTENIMIENTO BITACORA', '2024-07-09 19:11:25', '15', '2024-07-09 11:11:25', NULL, 1),
(22, 'DEPARTAMENTOS', 'HREF', 'PARA MANTENIMIENTO DEPARTAMENTOS', '2024-07-09 19:54:40', '12', '2024-07-09 11:54:40', NULL, 1),
(23, 'MUNICIPIOS', 'HREF', 'PARA MANTENIMIENTO MUNICIPIOS', '2024-07-09 19:56:23', '12', '2024-07-09 11:56:23', NULL, 1),
(24, 'UNIVERSIDAD', 'HREF', 'PARA MANTENIMIENTO UNIVERSIDAD', '2024-07-09 20:13:01', '12', '2024-07-09 12:13:01', NULL, 1),
(25, 'CARRERAS', 'HREF', 'PARA MANTENIMIENTO CARRERAS', '2024-07-09 20:13:44', '12', '2024-07-09 12:13:44', NULL, 1),
(26, 'MODALIDAD', 'HREF', 'PARA MANTENIMIENTO MODALIDAD', '2024-07-09 20:14:19', '12', '2024-07-09 12:14:19', NULL, 1),
(27, 'GRADOSACADEMICOS', 'HREF', 'PARA MANTENIMIENTO GRADOS', '2024-07-09 20:15:08', '12', '2024-07-09 12:15:08', NULL, 1),
(28, 'TIPOSDESOLICITUD', 'HREF', 'PARA MANTENIMIENTO TIPO SOLICITUD', '2024-07-09 20:15:48', '12', '2024-07-09 12:15:48', NULL, 1),
(29, 'CATEGORIASOLICITUD', 'HREF', 'PARA MANTENIMIENTO CATEGORIA SOLICITUD', '2024-07-09 20:16:54', '12', '2024-07-09 12:16:54', NULL, 1),
(30, 'ESTADOSDESOLICITUD', 'HREF', 'PARA MANTENIMIENTO ESTADOS SOLICITUD', '2024-07-09 20:17:34', '12', '2024-07-09 12:17:34', NULL, 1),
(31, 'REVISION DE DOCUMENTACION', 'HREF', 'PARA REVISION DE DOCUMENTACION', '2024-08-02 18:40:14', NULL, '2024-08-02 18:40:14', NULL, 1),
(32, 'ACUERDO DE ADMISION', 'HREF', 'PARA ACUERDO DE ADMISION', '2024-08-02 18:44:45', NULL, '2024-08-02 18:44:45', NULL, 1),
(33, 'DICTAMEN CTC', 'HREF', 'PARA DICTAMEN CTC', '2024-08-02 18:49:35', NULL, '2024-08-02 18:49:35', NULL, 1),
(34, 'ANALISIS CURRICULAR', 'HREF', 'Para analisis curricular', '2024-08-02 18:52:46', NULL, '2024-08-02 18:52:46', NULL, 1),
(35, 'ACUERDO DE APROBACION', 'HREF', 'PARA ACUERDO DE APROBACION', '2024-08-02 18:54:22', NULL, '2024-08-02 18:54:22', NULL, 1),
(36, 'ENTREGA PLAN DE ESTUDIO', 'HREF', 'PARA ENTREGA PLAN DE ESTUDIO', '2024-08-02 18:55:39', NULL, '2024-08-02 18:55:39', NULL, 1),
(37, 'AGENDA SOLICITUDES AL CES', 'HREF', 'PARA AGENDA DE SOLICITUDES AL CES', '2024-08-02 18:58:55', NULL, '2024-08-02 18:58:55', NULL, 1),
(38, 'ASIGNAR ACUERDO DE ADMISION CES', 'HREF', 'PARA ACUERDO DE ADMISION', '2024-08-02 19:02:34', NULL, '2024-08-02 19:02:34', NULL, 1),
(39, 'AGENDA SOLICITUDES AL CTC', 'HREF', 'PARA AGENDA SOLICITUDES CTC', '2024-08-02 19:06:17', NULL, '2024-08-02 19:06:17', NULL, 1),
(40, 'ASIGNAR DICTAMEN CTC', 'HREF', 'PARA DICTAMEN CTC', '2024-08-02 19:08:22', NULL, '2024-08-02 19:08:22', NULL, 1),
(41, 'EMITIR OPINION RAZONADA', 'HREF', 'PARA EMITIR OPINION RAZONADA', '2024-08-02 19:13:06', NULL, '2024-08-02 19:13:06', NULL, 1),
(42, 'REVISAR Y APROBAR OPINION RAZONADA', 'HREF', 'PARA REVISAR Y APROBAR OPINION RAZONADA', '2024-08-02 19:14:52', NULL, '2024-08-02 19:14:52', NULL, 1),
(43, 'AGENDA SOLICITUDES AL CES (ACUERDO APROBACION)', 'HREF', 'PARA AGENDA SOLICITUD, ACUERDO APROBACION', '2024-08-02 19:18:48', NULL, '2024-08-02 19:18:48', NULL, 1),
(44, 'ASIGNAR ACUERDO DE ADMISION CES (ACUERDO APROBACIO', 'HREF', 'PARA ASIGNAR ACUERDO DE ADMISION CES DE ACUERDO APROBACION', '2024-08-02 19:21:40', NULL, '2024-08-02 19:21:40', NULL, 1),
(49, 'CERRAR SESION', 'HREF', 'PARA CERRAR LA SESION DE USUARIO.', '2024-08-20 22:00:59', NULL, '2024-08-20 22:00:59', NULL, 1),
(50, 'DGAC', 'HREF', 'REVISAR Y APROBAR OPINION RAZONADA DGAC', '2024-08-22 17:53:44', NULL, '2024-08-22 17:53:44', NULL, 1),
(51, 'DIRECCION EJECUTIVA', 'HREF', 'REVISAR Y APROBAR OPINION RAZONADA DIRECCION EJECUTIVA', '2024-08-22 17:54:38', NULL, '2024-08-22 17:54:38', NULL, 1),
(52, 'DSA', '', 'REVISAR Y APROBAR OPINION RAZONADA', '2024-08-22 17:55:03', NULL, '2024-08-22 17:55:03', NULL, 1),
(53, 'DESCARGAR BASE DE DATOS', 'ONCLICK', 'PARA DESCARGAR EL RESPALDO DE LA BASE DE DATOS.', '2024-08-22 18:08:23', NULL, '2024-08-22 18:08:23', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `seguridad.tblparametros`
--

CREATE TABLE `seguridad.tblparametros` (
  `IdParametro` bigint(20) NOT NULL,
  `Parametro` varchar(100) NOT NULL,
  `Valor` varchar(100) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `CreadoPor` varchar(50) DEFAULT NULL,
  `FechaModificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ModificadoPor` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seguridad.tblparametros`
--

INSERT INTO `seguridad.tblparametros` (`IdParametro`, `Parametro`, `Valor`, `IdUsuario`, `FechaCreacion`, `CreadoPor`, `FechaModificacion`, `ModificadoPor`) VALUES
(1, 'Max_Login_Intentos', '3', 11, '2024-07-08 15:34:15', 'Edwin Aron Martinez', '2024-07-08 15:34:15', NULL),
(2, 'Max_Clave_Validacion', '1', 11, '2024-07-14 12:19:57', 'Edwin Aron Martinez', '2024-07-18 20:45:43', NULL),
(3, 'Max_Cod_Validacion', '1', 11, '2024-07-14 12:20:22', 'Edwin Aron Martinez', '2024-07-18 16:15:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `seguridad.tblpermisos`
--

CREATE TABLE `seguridad.tblpermisos` (
  `IdPermiso` bigint(20) NOT NULL,
  `IdRol` bigint(20) DEFAULT NULL,
  `IdObjeto` bigint(20) NOT NULL,
  `PermisoInsercion` varchar(5) NOT NULL,
  `PermisoEliminacion` varchar(5) NOT NULL,
  `PermisoActualizacion` varchar(5) NOT NULL,
  `PermisoConsultar` varchar(5) NOT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `CreadoPor` varchar(50) DEFAULT NULL,
  `FechaModificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ModificadoPor` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seguridad.tblpermisos`
--

INSERT INTO `seguridad.tblpermisos` (`IdPermiso`, `IdRol`, `IdObjeto`, `PermisoInsercion`, `PermisoEliminacion`, `PermisoActualizacion`, `PermisoConsultar`, `FechaCreacion`, `CreadoPor`, `FechaModificacion`, `ModificadoPor`) VALUES
(36, 1, 17, '1', '1', '0', '1', '2024-07-21 13:54:42', NULL, '2024-08-22 23:10:45', NULL),
(83, 1, 11, '0', '0', '0', '0', '2024-08-04 13:35:05', NULL, '2024-08-04 13:35:05', NULL),
(124, 1, 4, '0', '0', '0', '0', '2024-08-22 18:15:53', NULL, '2024-08-22 18:15:53', NULL),
(125, 1, 8, '0', '0', '0', '0', '2024-08-22 18:16:04', NULL, '2024-08-22 18:16:04', NULL),
(126, 1, 31, '0', '0', '0', '0', '2024-08-22 18:36:05', NULL, '2024-08-22 18:36:05', NULL),
(127, 1, 32, '0', '0', '0', '0', '2024-08-22 18:36:18', NULL, '2024-08-22 18:36:18', NULL),
(128, 1, 37, '0', '0', '0', '0', '2024-08-22 18:40:40', NULL, '2024-08-22 18:40:40', NULL),
(129, 1, 38, '0', '0', '0', '0', '2024-08-22 18:40:47', NULL, '2024-08-22 18:40:47', NULL),
(131, 1, 39, '0', '0', '0', '0', '2024-08-22 18:44:10', NULL, '2024-08-22 18:44:10', NULL),
(132, 1, 40, '0', '0', '0', '0', '2024-08-22 18:44:10', NULL, '2024-08-22 18:44:10', NULL),
(133, 1, 5, '0', '0', '0', '0', '2024-08-22 22:16:57', NULL, '2024-08-22 22:16:57', NULL),
(136, 1, 10, '0', '0', '0', '0', '2024-08-22 23:09:27', NULL, '2024-08-22 23:09:27', NULL),
(137, 1, 9, '0', '0', '0', '0', '2024-08-22 23:11:59', NULL, '2024-08-22 23:11:59', NULL),
(138, 1, 34, '0', '0', '0', '0', '2024-08-26 09:17:18', NULL, '2024-08-26 09:17:18', NULL),
(139, 1, 41, '0', '0', '0', '0', '2024-08-26 09:20:53', NULL, '2024-08-26 09:20:53', NULL),
(141, 1, 42, '0', '0', '0', '0', '2024-08-26 09:28:45', NULL, '2024-08-26 09:28:45', NULL),
(142, 1, 50, '0', '0', '0', '0', '2024-08-26 11:11:45', NULL, '2024-08-26 11:11:45', NULL),
(143, 1, 51, '0', '0', '0', '0', '2024-08-26 11:12:51', NULL, '2024-08-26 11:12:51', NULL),
(144, 1, 52, '0', '0', '0', '0', '2024-08-26 11:13:46', NULL, '2024-08-26 11:13:46', NULL),
(145, 1, 35, '0', '0', '0', '0', '2024-08-26 11:15:58', NULL, '2024-08-26 11:15:58', NULL),
(146, 1, 43, '0', '0', '0', '0', '2024-08-26 11:20:30', NULL, '2024-08-26 11:20:30', NULL),
(147, 1, 44, '0', '0', '0', '0', '2024-08-26 11:20:30', NULL, '2024-08-26 11:20:30', NULL),
(148, 1, 36, '0', '0', '0', '0', '2024-08-26 11:22:03', NULL, '2024-08-26 11:22:03', NULL),
(149, 11, 3, '0', '0', '0', '0', '2024-08-26 11:27:57', NULL, '2024-08-26 11:27:57', NULL),
(150, 11, 4, '0', '0', '0', '0', '2024-08-26 11:27:57', NULL, '2024-08-26 11:27:57', NULL),
(151, 1, 22, '0', '0', '0', '0', '2024-08-26 11:40:12', NULL, '2024-08-26 11:40:12', NULL),
(152, 1, 23, '0', '0', '0', '0', '2024-08-26 11:40:12', NULL, '2024-08-26 11:40:12', NULL),
(153, 1, 24, '0', '0', '0', '0', '2024-08-26 11:40:32', NULL, '2024-08-26 11:40:32', NULL),
(154, 1, 25, '0', '0', '0', '0', '2024-08-26 11:40:32', NULL, '2024-08-26 11:40:32', NULL),
(155, 1, 26, '0', '0', '0', '0', '2024-08-26 11:40:51', NULL, '2024-08-26 11:40:51', NULL),
(156, 1, 27, '0', '0', '0', '0', '2024-08-26 11:40:51', NULL, '2024-08-26 11:40:51', NULL),
(157, 1, 28, '0', '0', '0', '0', '2024-08-26 11:41:11', NULL, '2024-08-26 11:41:11', NULL),
(158, 1, 29, '0', '0', '0', '0', '2024-08-26 11:41:11', NULL, '2024-08-26 11:41:11', NULL),
(159, 1, 30, '0', '0', '0', '0', '2024-08-26 11:41:44', NULL, '2024-08-26 11:41:44', NULL),
(160, 1, 53, '0', '0', '0', '0', '2024-08-26 11:41:44', NULL, '2024-08-26 11:41:44', NULL),
(161, 1, 15, '0', '0', '0', '0', '2024-08-26 11:47:46', NULL, '2024-08-26 11:47:46', NULL),
(162, 1, 18, '0', '0', '0', '0', '2024-08-26 11:47:46', NULL, '2024-08-26 11:47:46', NULL),
(163, 1, 19, '0', '0', '0', '0', '2024-08-26 11:47:46', NULL, '2024-08-26 11:47:46', NULL),
(164, 1, 20, '0', '0', '0', '0', '2024-08-26 11:47:46', NULL, '2024-08-26 11:47:46', NULL),
(165, 1, 21, '0', '0', '0', '0', '2024-08-26 11:47:46', NULL, '2024-08-26 11:47:46', NULL),
(166, 1, 16, '0', '0', '0', '0', '2024-08-26 11:49:03', NULL, '2024-08-26 11:49:03', NULL),
(168, 1, 3, '0', '0', '0', '0', '2024-08-28 12:58:45', NULL, '2024-08-28 12:58:45', NULL),
(169, 1, 33, '0', '0', '0', '0', '2024-08-29 07:59:13', NULL, '2024-08-29 07:59:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `seguridad.tblresetcontraseñas`
--

CREATE TABLE `seguridad.tblresetcontraseñas` (
  `IdReset` int(11) NOT NULL,
  `CorreoElectronico` varchar(100) NOT NULL,
  `CodigoValidacion` int(11) NOT NULL,
  `Token` varchar(200) NOT NULL,
  `FechaVencimiento` timestamp NOT NULL DEFAULT current_timestamp(),
  `ContraseñaTemp` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seguridad.tblresetcontraseñas`
--

INSERT INTO `seguridad.tblresetcontraseñas` (`IdReset`, `CorreoElectronico`, `CodigoValidacion`, `Token`, `FechaVencimiento`, `ContraseñaTemp`) VALUES
(27, 'kacastroe@unah.hn', 786771, 'ace69c089b6072ad7ed9a27d021e3af6', '2024-09-04 21:32:33', '');

-- --------------------------------------------------------

--
-- Table structure for table `seguridad.tblusuarios`
--

CREATE TABLE `seguridad.tblusuarios` (
  `IdUsuario` bigint(20) NOT NULL,
  `Contraseña` varchar(255) NOT NULL,
  `IdRol` bigint(20) DEFAULT NULL,
  `FechaUltimaConexion` datetime DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `CreadoPor` varchar(50) DEFAULT NULL,
  `FechaModificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ModificadoPor` varchar(50) DEFAULT NULL,
  `PrimerIngreso` bigint(20) DEFAULT 0,
  `FechaVencimiento` datetime DEFAULT NULL,
  `RegistroClave` varchar(255) DEFAULT NULL,
  `EstadoUsuario` bigint(20) NOT NULL,
  `NumIntentos` bigint(20) DEFAULT 0,
  `IdUniversidad` bigint(20) NOT NULL,
  `CorreoElectronico` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seguridad.tblusuarios`
--

INSERT INTO `seguridad.tblusuarios` (`IdUsuario`, `Contraseña`, `IdRol`, `FechaUltimaConexion`, `FechaCreacion`, `CreadoPor`, `FechaModificacion`, `ModificadoPor`, `PrimerIngreso`, `FechaVencimiento`, `RegistroClave`, `EstadoUsuario`, `NumIntentos`, `IdUniversidad`, `CorreoElectronico`) VALUES
(1, '$2y$10$LdESfZlYdsT1CK6/dLNMTu68.ceFi.SiieBQA/./VmCcxDMvN2fb2', 1, '2024-09-07 13:05:06', '2024-06-23 19:21:57', 'Edwin Martinez', '2024-09-07 13:05:06', NULL, NULL, NULL, NULL, 1, 0, 1, 'keucedaa@unah.hn'),
(2, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 1, NULL, '2024-06-24 20:51:11', '1', '2024-09-05 19:11:14', NULL, NULL, NULL, NULL, 3, 3, 1, ''),
(3, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 3, NULL, '2024-06-24 20:53:15', '1', '2024-08-31 17:25:46', NULL, NULL, NULL, NULL, 1, 0, 1, NULL),
(4, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 3, NULL, '2024-06-24 20:54:28', '1', '2024-08-31 17:26:08', NULL, NULL, NULL, NULL, 1, 0, 1, NULL),
(5, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 5, NULL, '2024-06-24 20:55:17', '1', '2024-08-31 17:25:46', NULL, NULL, NULL, NULL, 1, 0, 1, NULL),
(6, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 6, NULL, '2024-06-24 20:58:05', '1', '2024-08-31 17:25:46', NULL, NULL, NULL, NULL, 1, 0, 1, NULL),
(7, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 7, NULL, '2024-06-24 20:58:51', '1', '2024-08-31 17:25:46', NULL, NULL, NULL, NULL, 1, 0, 1, NULL),
(8, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 8, NULL, '2024-06-24 21:00:03', '1', '2024-08-31 17:25:46', NULL, NULL, NULL, NULL, 1, 0, 1, NULL),
(9, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 10, NULL, '2024-06-24 21:01:59', '1', '2024-08-31 17:25:46', NULL, NULL, NULL, NULL, 1, 0, 1, NULL),
(10, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 1, '2024-08-10 19:23:34', '2024-06-25 00:20:36', '11', '2024-08-31 17:25:46', NULL, NULL, NULL, NULL, 1, 0, 1, NULL),
(11, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 1, NULL, '2024-06-27 12:47:55', '11', '2024-08-31 17:25:46', NULL, NULL, NULL, NULL, 1, 0, 1, NULL),
(12, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 1, NULL, '2024-06-27 12:49:08', '11', '2024-09-04 07:36:44', NULL, NULL, NULL, NULL, 1, 0, 1, 'kcastro0517@hotmail.com'),
(13, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 1, NULL, '2024-06-27 12:50:39', '11', '2024-08-31 17:25:46', NULL, NULL, NULL, NULL, 1, 0, 1, NULL),
(14, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 1, NULL, '2024-06-27 12:51:52', '11', '2024-08-31 17:25:46', NULL, NULL, NULL, NULL, 1, 0, 1, NULL),
(15, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 1, NULL, '2024-06-27 12:52:55', '11', '2024-08-31 17:25:46', NULL, NULL, NULL, NULL, 1, 0, 1, NULL),
(16, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 1, NULL, '2024-06-28 11:10:11', '11', '2024-08-31 17:25:46', NULL, NULL, NULL, NULL, 1, 0, 1, NULL),
(17, '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', 1, '2024-08-07 22:43:44', '2024-06-23 19:21:57', '11', '2024-08-31 17:26:30', NULL, NULL, NULL, NULL, 1, 0, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `seguridad.tblusuariospersonal`
--

CREATE TABLE `seguridad.tblusuariospersonal` (
  `IdUsuarioPersonal` bigint(20) NOT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  `NombreUsuario` varchar(255) DEFAULT NULL,
  `NumIdentidad` varchar(20) DEFAULT NULL,
  `NumEmpleado` bigint(20) DEFAULT NULL,
  `Usuario` varchar(255) DEFAULT NULL,
  `EmpleadoDes` bigint(20) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seguridad.tblusuariospersonal`
--

INSERT INTO `seguridad.tblusuariospersonal` (`IdUsuarioPersonal`, `IdUsuario`, `NombreUsuario`, `NumIdentidad`, `NumEmpleado`, `Usuario`, `EmpleadoDes`, `Direccion`) VALUES
(1, 1, 'Kevin Castro', '080199908659', 2, 'Kevin', 2, 'Col.Aleman'),
(2, 13, 'Pablito Clavo un Clavito', '43547747564', 2, 'Pablo', 1, 'Por hay');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `documentos.tbladjuntosctc`
--
ALTER TABLE `documentos.tbladjuntosctc`
  ADD PRIMARY KEY (`IdAdjuntoctc`),
  ADD KEY `ID_SOLICITUD` (`IdSolicitud`),
  ADD KEY `ID_USUARIO` (`IdUsuario`);

--
-- Indexes for table `documentos.tblarchivosadjuntos`
--
ALTER TABLE `documentos.tblarchivosadjuntos`
  ADD PRIMARY KEY (`IdAdjuntos`),
  ADD KEY `fk_adjuntos` (`IdUsuario`),
  ADD KEY `tbl_adjuntos_ibfk_1` (`IdSolicitud`);

--
-- Indexes for table `documentos.tbldictamenesctc`
--
ALTER TABLE `documentos.tbldictamenesctc`
  ADD PRIMARY KEY (`IdDictamen`),
  ADD KEY `IDSESSIONCTC` (`IdSesion`),
  ADD KEY `ID_UNIVERSIDADCTC` (`IdUniversidad`),
  ADD KEY `fk_id_estado_dictamen` (`IdEstadoDictamen`);

--
-- Indexes for table `documentos.tblobservaciones`
--
ALTER TABLE `documentos.tblobservaciones`
  ADD PRIMARY KEY (`IdObservacion`),
  ADD KEY `ID_USUARIO_OBS` (`IdUsuario`),
  ADD KEY `documentos.tblobservaciones_ibfk_1` (`IdSolicitud`);

--
-- Indexes for table `documentos.tblopinionesrazonadas`
--
ALTER TABLE `documentos.tblopinionesrazonadas`
  ADD PRIMARY KEY (`IdOpinionrazonada`),
  ADD KEY `fk_id_usuario2` (`IdUsuario`),
  ADD KEY `fk_solicitud2` (`IdSolicitud`);

--
-- Indexes for table `documentos.tblplanesestudio`
--
ALTER TABLE `documentos.tblplanesestudio`
  ADD PRIMARY KEY (`IdPlanestudio`),
  ADD KEY `fk_idUni` (`IdUniversidad`),
  ADD KEY `fk_idSoli` (`IdSolicitud`),
  ADD KEY `fk_idCarre` (`IdCarrera`);

--
-- Indexes for table `mantenimiento.tblcarreras`
--
ALTER TABLE `mantenimiento.tblcarreras`
  ADD PRIMARY KEY (`IdCarrera`),
  ADD KEY `ID_GRADO_C` (`IdGrado`),
  ADD KEY `ID_MODALIDAD_C` (`IdModalidad`),
  ADD KEY `ID_UNIVERSIDAD_C` (`IdUniversidad`);

--
-- Indexes for table `mantenimiento.tblcategorias`
--
ALTER TABLE `mantenimiento.tblcategorias`
  ADD PRIMARY KEY (`IdCategoria`),
  ADD KEY `ID_TIPO_SOLICITUD_CAT` (`IdTiposolicitud`);

--
-- Indexes for table `mantenimiento.tblconsejales`
--
ALTER TABLE `mantenimiento.tblconsejales`
  ADD PRIMARY KEY (`IdConsejal`),
  ADD KEY `fk_id_universidad2` (`IdUniversidad`);

--
-- Indexes for table `mantenimiento.tbldeptos`
--
ALTER TABLE `mantenimiento.tbldeptos`
  ADD PRIMARY KEY (`IdDepartamento`);

--
-- Indexes for table `mantenimiento.tbldictamenesestados`
--
ALTER TABLE `mantenimiento.tbldictamenesestados`
  ADD PRIMARY KEY (`IdEstadodictamen`);

--
-- Indexes for table `mantenimiento.tblestadossolicitudes`
--
ALTER TABLE `mantenimiento.tblestadossolicitudes`
  ADD PRIMARY KEY (`IdEstado`);

--
-- Indexes for table `mantenimiento.tblestadosusuarios`
--
ALTER TABLE `mantenimiento.tblestadosusuarios`
  ADD PRIMARY KEY (`IdEstado`);

--
-- Indexes for table `mantenimiento.tblgradosacademicos`
--
ALTER TABLE `mantenimiento.tblgradosacademicos`
  ADD PRIMARY KEY (`IdGrado`);

--
-- Indexes for table `mantenimiento.tblmodalidades`
--
ALTER TABLE `mantenimiento.tblmodalidades`
  ADD PRIMARY KEY (`IdModalidad`);

--
-- Indexes for table `mantenimiento.tblmunicipios`
--
ALTER TABLE `mantenimiento.tblmunicipios`
  ADD PRIMARY KEY (`IdMunicipio`),
  ADD KEY `ID_DEPARTAMENTO` (`IdDepartamento`);

--
-- Indexes for table `mantenimiento.tblsedes`
--
ALTER TABLE `mantenimiento.tblsedes`
  ADD PRIMARY KEY (`IdSedes`),
  ADD KEY `ID_UNIVERSIDAD` (`IdUniversidad`);

--
-- Indexes for table `mantenimiento.tbltiposolicitudes`
--
ALTER TABLE `mantenimiento.tbltiposolicitudes`
  ADD PRIMARY KEY (`IdTiposolicitud`);

--
-- Indexes for table `mantenimiento.tbluniversidadescentros`
--
ALTER TABLE `mantenimiento.tbluniversidadescentros`
  ADD PRIMARY KEY (`IdUniversidad`),
  ADD KEY `ID_MUNICIPIO` (`IdMunicipio`);

--
-- Indexes for table `proceso.tblacuerdoscesadmin`
--
ALTER TABLE `proceso.tblacuerdoscesadmin`
  ADD PRIMARY KEY (`IdAcuerdoadmin`),
  ADD KEY `proceso.tblacuerdoscesadmin_ibfk_1` (`IdSolicitud`),
  ADD KEY `proceso.tblacuerdoscesadmin_ibfk_2` (`IdUsuario`);

--
-- Indexes for table `proceso.tblacuerdoscesaprob`
--
ALTER TABLE `proceso.tblacuerdoscesaprob`
  ADD PRIMARY KEY (`IdAcuerdoaprob`),
  ADD KEY `proceso.tblacuerdoscesaprob_ibfk_1` (`IdSolicitud`),
  ADD KEY `proceso.tblacuerdoscesaprob_ibfk_2` (`IdUsuario`);

--
-- Indexes for table `proceso.tblbtcsolicitudes`
--
ALTER TABLE `proceso.tblbtcsolicitudes`
  ADD PRIMARY KEY (`IdBtcsolicitud`),
  ADD KEY `ID_CARRERA_BTS` (`IdCarrera`),
  ADD KEY `ID_ESTADO_BTS` (`IdEstado`),
  ADD KEY `ID_SOLICITUD_BTS` (`IdSolicitud`),
  ADD KEY `ID_UNIVERSIDAD_BTS` (`IdUniversidad`),
  ADD KEY `ID_USUARIO_BTS` (`IdUsuario`);

--
-- Indexes for table `proceso.tbldictamenesuniversidades`
--
ALTER TABLE `proceso.tbldictamenesuniversidades`
  ADD PRIMARY KEY (`IdDicuni`),
  ADD UNIQUE KEY `ID_UNIVERSIDAD` (`IdUniversidad`),
  ADD KEY `ID_DICTAMEN` (`IdDictamen`);

--
-- Indexes for table `proceso.tblopinionesconsejales`
--
ALTER TABLE `proceso.tblopinionesconsejales`
  ADD PRIMARY KEY (`IdOpinionconsejal`),
  ADD KEY `ID_CONSEJAL` (`IdConsejal`),
  ADD KEY `ID_OPINION_RAZONADA` (`IdOpinionrazonada`);

--
-- Indexes for table `proceso.tblseguimientos`
--
ALTER TABLE `proceso.tblseguimientos`
  ADD PRIMARY KEY (`IdSeguimiento`),
  ADD KEY `ID_ESTADO_SG` (`IdEstado`),
  ADD KEY `ID_SOLICITUD_SG` (`IdSolicitud`),
  ADD KEY `ID_USUARIO_SG` (`IdUsuario`);

--
-- Indexes for table `proceso.tblsesiones`
--
ALTER TABLE `proceso.tblsesiones`
  ADD PRIMARY KEY (`IdSesion`),
  ADD KEY `ID_SOLICITUD` (`IdSolicitud`);

--
-- Indexes for table `proceso.tblsesionesctc`
--
ALTER TABLE `proceso.tblsesionesctc`
  ADD PRIMARY KEY (`IdSesion`),
  ADD KEY `ID_SOLICITUD` (`IdSolicitud`);

--
-- Indexes for table `proceso.tblsolicitudes`
--
ALTER TABLE `proceso.tblsolicitudes`
  ADD PRIMARY KEY (`IdSolicitud`),
  ADD KEY `FK_CARRERA` (`IdCarrera`),
  ADD KEY `FK_CATEGORIA` (`IdCategoria`),
  ADD KEY `FK_DEPARTAMENTO` (`IdDepartamento`),
  ADD KEY `FK_ESTADO_IDX` (`IdEstado`),
  ADD KEY `FK_GRADO` (`IdGrado`),
  ADD KEY `FK_MODALIDAD` (`IdModalidad`),
  ADD KEY `FK_MUNICIPIO` (`IdMunicipio`),
  ADD KEY `FK_UNIVERSIDAD` (`IdUniversidad`),
  ADD KEY `FK_USUARIO` (`IdUsuario`);

--
-- Indexes for table `seguridad.tblbitacora`
--
ALTER TABLE `seguridad.tblbitacora`
  ADD PRIMARY KEY (`IdBitacora`),
  ADD KEY `ID_USUARIO` (`IdUsuario`),
  ADD KEY `ID_OBJETO` (`IdObjeto`);

--
-- Indexes for table `seguridad.tblhistcontraseñas`
--
ALTER TABLE `seguridad.tblhistcontraseñas`
  ADD PRIMARY KEY (`IdHist`),
  ADD KEY `IdUsuario` (`IdUsuario`);

--
-- Indexes for table `seguridad.tblmsroles`
--
ALTER TABLE `seguridad.tblmsroles`
  ADD PRIMARY KEY (`IdRol`),
  ADD KEY `fk_roles2` (`Rol`),
  ADD KEY `fk_creadopor` (`CreadoPor`),
  ADD KEY `fk_modifcadopor` (`ModificadoPor`),
  ADD KEY `fk_idUsuario` (`IdUsuario`);

--
-- Indexes for table `seguridad.tblobjetos`
--
ALTER TABLE `seguridad.tblobjetos`
  ADD PRIMARY KEY (`IdObjeto`),
  ADD KEY `fk_usuariosobj` (`IdUsuario`);

--
-- Indexes for table `seguridad.tblparametros`
--
ALTER TABLE `seguridad.tblparametros`
  ADD PRIMARY KEY (`IdParametro`),
  ADD KEY `ID_USUARIO_PM` (`IdUsuario`);

--
-- Indexes for table `seguridad.tblpermisos`
--
ALTER TABLE `seguridad.tblpermisos`
  ADD PRIMARY KEY (`IdPermiso`),
  ADD KEY `IdRol` (`IdRol`),
  ADD KEY `fk_id_objeto` (`IdObjeto`);

--
-- Indexes for table `seguridad.tblresetcontraseñas`
--
ALTER TABLE `seguridad.tblresetcontraseñas`
  ADD PRIMARY KEY (`IdReset`);

--
-- Indexes for table `seguridad.tblusuarios`
--
ALTER TABLE `seguridad.tblusuarios`
  ADD PRIMARY KEY (`IdUsuario`),
  ADD KEY `fk_id_estado` (`EstadoUsuario`),
  ADD KEY `fk_id_universidad` (`IdUniversidad`),
  ADD KEY `fk_id_rol` (`IdRol`);

--
-- Indexes for table `seguridad.tblusuariospersonal`
--
ALTER TABLE `seguridad.tblusuariospersonal`
  ADD PRIMARY KEY (`IdUsuarioPersonal`),
  ADD KEY `fk_iduser` (`IdUsuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `documentos.tbladjuntosctc`
--
ALTER TABLE `documentos.tbladjuntosctc`
  MODIFY `IdAdjuntoctc` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `documentos.tblarchivosadjuntos`
--
ALTER TABLE `documentos.tblarchivosadjuntos`
  MODIFY `IdAdjuntos` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `documentos.tbldictamenesctc`
--
ALTER TABLE `documentos.tbldictamenesctc`
  MODIFY `IdDictamen` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `documentos.tblobservaciones`
--
ALTER TABLE `documentos.tblobservaciones`
  MODIFY `IdObservacion` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `documentos.tblopinionesrazonadas`
--
ALTER TABLE `documentos.tblopinionesrazonadas`
  MODIFY `IdOpinionrazonada` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `documentos.tblplanesestudio`
--
ALTER TABLE `documentos.tblplanesestudio`
  MODIFY `IdPlanestudio` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65164;

--
-- AUTO_INCREMENT for table `mantenimiento.tblcarreras`
--
ALTER TABLE `mantenimiento.tblcarreras`
  MODIFY `IdCarrera` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `mantenimiento.tblcategorias`
--
ALTER TABLE `mantenimiento.tblcategorias`
  MODIFY `IdCategoria` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `mantenimiento.tblconsejales`
--
ALTER TABLE `mantenimiento.tblconsejales`
  MODIFY `IdConsejal` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mantenimiento.tbldeptos`
--
ALTER TABLE `mantenimiento.tbldeptos`
  MODIFY `IdDepartamento` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `mantenimiento.tbldictamenesestados`
--
ALTER TABLE `mantenimiento.tbldictamenesestados`
  MODIFY `IdEstadodictamen` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mantenimiento.tblestadossolicitudes`
--
ALTER TABLE `mantenimiento.tblestadossolicitudes`
  MODIFY `IdEstado` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `mantenimiento.tblestadosusuarios`
--
ALTER TABLE `mantenimiento.tblestadosusuarios`
  MODIFY `IdEstado` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mantenimiento.tblgradosacademicos`
--
ALTER TABLE `mantenimiento.tblgradosacademicos`
  MODIFY `IdGrado` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mantenimiento.tblmodalidades`
--
ALTER TABLE `mantenimiento.tblmodalidades`
  MODIFY `IdModalidad` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mantenimiento.tblsedes`
--
ALTER TABLE `mantenimiento.tblsedes`
  MODIFY `IdSedes` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mantenimiento.tbltiposolicitudes`
--
ALTER TABLE `mantenimiento.tbltiposolicitudes`
  MODIFY `IdTiposolicitud` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mantenimiento.tbluniversidadescentros`
--
ALTER TABLE `mantenimiento.tbluniversidadescentros`
  MODIFY `IdUniversidad` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `proceso.tblacuerdoscesadmin`
--
ALTER TABLE `proceso.tblacuerdoscesadmin`
  MODIFY `IdAcuerdoadmin` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `proceso.tblacuerdoscesaprob`
--
ALTER TABLE `proceso.tblacuerdoscesaprob`
  MODIFY `IdAcuerdoaprob` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proceso.tblbtcsolicitudes`
--
ALTER TABLE `proceso.tblbtcsolicitudes`
  MODIFY `IdBtcsolicitud` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proceso.tbldictamenesuniversidades`
--
ALTER TABLE `proceso.tbldictamenesuniversidades`
  MODIFY `IdDicuni` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proceso.tblseguimientos`
--
ALTER TABLE `proceso.tblseguimientos`
  MODIFY `IdSeguimiento` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proceso.tblsesionesctc`
--
ALTER TABLE `proceso.tblsesionesctc`
  MODIFY `IdSesion` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proceso.tblsolicitudes`
--
ALTER TABLE `proceso.tblsolicitudes`
  MODIFY `IdSolicitud` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `seguridad.tblbitacora`
--
ALTER TABLE `seguridad.tblbitacora`
  MODIFY `IdBitacora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2850;

--
-- AUTO_INCREMENT for table `seguridad.tblhistcontraseñas`
--
ALTER TABLE `seguridad.tblhistcontraseñas`
  MODIFY `IdHist` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `seguridad.tblmsroles`
--
ALTER TABLE `seguridad.tblmsroles`
  MODIFY `IdRol` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `seguridad.tblobjetos`
--
ALTER TABLE `seguridad.tblobjetos`
  MODIFY `IdObjeto` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `seguridad.tblparametros`
--
ALTER TABLE `seguridad.tblparametros`
  MODIFY `IdParametro` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `seguridad.tblresetcontraseñas`
--
ALTER TABLE `seguridad.tblresetcontraseñas`
  MODIFY `IdReset` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `seguridad.tblusuarios`
--
ALTER TABLE `seguridad.tblusuarios`
  MODIFY `IdUsuario` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `seguridad.tblusuariospersonal`
--
ALTER TABLE `seguridad.tblusuariospersonal`
  MODIFY `IdUsuarioPersonal` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documentos.tbladjuntosctc`
--
ALTER TABLE `documentos.tbladjuntosctc`
  ADD CONSTRAINT `ID_SOLICITUDCTC` FOREIGN KEY (`IdAdjuntoctc`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ID_USUARIOCTC` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE;

--
-- Constraints for table `documentos.tblarchivosadjuntos`
--
ALTER TABLE `documentos.tblarchivosadjuntos`
  ADD CONSTRAINT `fk_adjuntos` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_adjuntos_ibfk_1` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE;

--
-- Constraints for table `documentos.tbldictamenesctc`
--
ALTER TABLE `documentos.tbldictamenesctc`
  ADD CONSTRAINT `IDSESSIONCTC` FOREIGN KEY (`IdSesion`) REFERENCES `proceso.tblsesionesctc` (`IdSesion`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ID_UNIVERSIDADCTC` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_estado_dictamen` FOREIGN KEY (`IdEstadoDictamen`) REFERENCES `mantenimiento.tbldictamenesestados` (`IdEstadodictamen`) ON UPDATE CASCADE;

--
-- Constraints for table `documentos.tblobservaciones`
--
ALTER TABLE `documentos.tblobservaciones`
  ADD CONSTRAINT `ID_USUARIO_OBS` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `documentos.tblobservaciones_ibfk_1` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE;

--
-- Constraints for table `documentos.tblopinionesrazonadas`
--
ALTER TABLE `documentos.tblopinionesrazonadas`
  ADD CONSTRAINT `fk_id_usuario2` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_solicitud2` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE;

--
-- Constraints for table `documentos.tblplanesestudio`
--
ALTER TABLE `documentos.tblplanesestudio`
  ADD CONSTRAINT `fk_idCarre` FOREIGN KEY (`IdCarrera`) REFERENCES `mantenimiento.tblcarreras` (`IdCarrera`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_idSoli` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_idUni` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE;

--
-- Constraints for table `mantenimiento.tblcarreras`
--
ALTER TABLE `mantenimiento.tblcarreras`
  ADD CONSTRAINT `ID_GRADO_C` FOREIGN KEY (`IdGrado`) REFERENCES `mantenimiento.tblgradosacademicos` (`IdGrado`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ID_MODALIDAD_C` FOREIGN KEY (`IdModalidad`) REFERENCES `mantenimiento.tblmodalidades` (`IdModalidad`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ID_UNIVERSIDAD_C` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE;

--
-- Constraints for table `mantenimiento.tblcategorias`
--
ALTER TABLE `mantenimiento.tblcategorias`
  ADD CONSTRAINT `ID_TIPO_SOLICITUD_CAT` FOREIGN KEY (`IdTiposolicitud`) REFERENCES `mantenimiento.tbltiposolicitudes` (`IdTiposolicitud`) ON UPDATE CASCADE;

--
-- Constraints for table `mantenimiento.tblconsejales`
--
ALTER TABLE `mantenimiento.tblconsejales`
  ADD CONSTRAINT `fk_id_universidad2` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE;

--
-- Constraints for table `mantenimiento.tblmunicipios`
--
ALTER TABLE `mantenimiento.tblmunicipios`
  ADD CONSTRAINT `ID_DEPARTAMENTO` FOREIGN KEY (`IdDepartamento`) REFERENCES `mantenimiento.tbldeptos` (`IdDepartamento`) ON UPDATE CASCADE;

--
-- Constraints for table `mantenimiento.tblsedes`
--
ALTER TABLE `mantenimiento.tblsedes`
  ADD CONSTRAINT `ID_UNIVERSIDAD_S` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE;

--
-- Constraints for table `mantenimiento.tbluniversidadescentros`
--
ALTER TABLE `mantenimiento.tbluniversidadescentros`
  ADD CONSTRAINT `ID_MUNICIPIO_UNI` FOREIGN KEY (`IdMunicipio`) REFERENCES `mantenimiento.tblmunicipios` (`IdMunicipio`) ON UPDATE CASCADE;

--
-- Constraints for table `proceso.tblacuerdoscesadmin`
--
ALTER TABLE `proceso.tblacuerdoscesadmin`
  ADD CONSTRAINT `proceso.tblacuerdoscesadmin_ibfk_1` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE,
  ADD CONSTRAINT `proceso.tblacuerdoscesadmin_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE;

--
-- Constraints for table `proceso.tblacuerdoscesaprob`
--
ALTER TABLE `proceso.tblacuerdoscesaprob`
  ADD CONSTRAINT `proceso.tblacuerdoscesaprob_ibfk_1` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE,
  ADD CONSTRAINT `proceso.tblacuerdoscesaprob_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE;

--
-- Constraints for table `proceso.tblbtcsolicitudes`
--
ALTER TABLE `proceso.tblbtcsolicitudes`
  ADD CONSTRAINT `ID_CARRERA_BTS` FOREIGN KEY (`IdCarrera`) REFERENCES `mantenimiento.tblcarreras` (`IdCarrera`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ID_ESTADO_BTS` FOREIGN KEY (`IdEstado`) REFERENCES `mantenimiento.tblestadossolicitudes` (`IdEstado`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ID_SOLICITUD_BTS` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ID_UNIVERSIDAD_BTS` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ID_USUARIO_BTS` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE;

--
-- Constraints for table `proceso.tbldictamenesuniversidades`
--
ALTER TABLE `proceso.tbldictamenesuniversidades`
  ADD CONSTRAINT `ID_DICTAMEN_CTC` FOREIGN KEY (`IdDictamen`) REFERENCES `documentos.tbldictamenesctc` (`IdDictamen`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ID_UNIVERSIDAD_CTC` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE;

--
-- Constraints for table `proceso.tblopinionesconsejales`
--
ALTER TABLE `proceso.tblopinionesconsejales`
  ADD CONSTRAINT `ID_CONSEJAL` FOREIGN KEY (`IdConsejal`) REFERENCES `mantenimiento.tblconsejales` (`IdConsejal`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ID_OPINION_RAZONADA` FOREIGN KEY (`IdOpinionrazonada`) REFERENCES `documentos.tblopinionesrazonadas` (`IdOpinionrazonada`) ON UPDATE CASCADE;

--
-- Constraints for table `proceso.tblseguimientos`
--
ALTER TABLE `proceso.tblseguimientos`
  ADD CONSTRAINT `ID_ESTADO_SG` FOREIGN KEY (`IdEstado`) REFERENCES `mantenimiento.tblestadossolicitudes` (`IdEstado`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ID_SOLICITUD_SG` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ID_USUARIO_SG` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE;

--
-- Constraints for table `proceso.tblsesiones`
--
ALTER TABLE `proceso.tblsesiones`
  ADD CONSTRAINT `ID_SOLICITUD_SE` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE;

--
-- Constraints for table `proceso.tblsesionesctc`
--
ALTER TABLE `proceso.tblsesionesctc`
  ADD CONSTRAINT `IDSOLICITUCC` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE;

--
-- Constraints for table `proceso.tblsolicitudes`
--
ALTER TABLE `proceso.tblsolicitudes`
  ADD CONSTRAINT `FK_CARRERA` FOREIGN KEY (`IdCarrera`) REFERENCES `mantenimiento.tblcarreras` (`IdCarrera`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CATEGORIA` FOREIGN KEY (`IdCategoria`) REFERENCES `mantenimiento.tblcategorias` (`IdCategoria`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_DEPARTAMENTO` FOREIGN KEY (`IdDepartamento`) REFERENCES `mantenimiento.tbldeptos` (`IdDepartamento`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_ESTADO_IDX` FOREIGN KEY (`IdEstado`) REFERENCES `mantenimiento.tblestadossolicitudes` (`IdEstado`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_GRADO` FOREIGN KEY (`IdGrado`) REFERENCES `mantenimiento.tblgradosacademicos` (`IdGrado`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MODALIDAD` FOREIGN KEY (`IdModalidad`) REFERENCES `mantenimiento.tblmodalidades` (`IdModalidad`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_MUNICIPIO` FOREIGN KEY (`IdMunicipio`) REFERENCES `mantenimiento.tblmunicipios` (`IdMunicipio`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_UNIVERSIDAD` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_USUARIO` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE;

--
-- Constraints for table `seguridad.tblbitacora`
--
ALTER TABLE `seguridad.tblbitacora`
  ADD CONSTRAINT `ID_OBJETO` FOREIGN KEY (`IdObjeto`) REFERENCES `seguridad.tblobjetos` (`IdObjeto`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ID_USUARIO` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE;

--
-- Constraints for table `seguridad.tblhistcontraseñas`
--
ALTER TABLE `seguridad.tblhistcontraseñas`
  ADD CONSTRAINT `ID_USUARIO_HIS` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`);

--
-- Constraints for table `seguridad.tblmsroles`
--
ALTER TABLE `seguridad.tblmsroles`
  ADD CONSTRAINT `fk_idUsuario` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE;

--
-- Constraints for table `seguridad.tblobjetos`
--
ALTER TABLE `seguridad.tblobjetos`
  ADD CONSTRAINT `fk_usuariosobj` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE;

--
-- Constraints for table `seguridad.tblparametros`
--
ALTER TABLE `seguridad.tblparametros`
  ADD CONSTRAINT `ID_USUARIO_PM` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`);

--
-- Constraints for table `seguridad.tblpermisos`
--
ALTER TABLE `seguridad.tblpermisos`
  ADD CONSTRAINT `fk_id_msroles` FOREIGN KEY (`IdRol`) REFERENCES `seguridad.tblmsroles` (`IdRol`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_objeto` FOREIGN KEY (`IdObjeto`) REFERENCES `seguridad.tblobjetos` (`IdObjeto`) ON UPDATE CASCADE;

--
-- Constraints for table `seguridad.tblusuarios`
--
ALTER TABLE `seguridad.tblusuarios`
  ADD CONSTRAINT `fk_id_estado` FOREIGN KEY (`EstadoUsuario`) REFERENCES `mantenimiento.tblestadosusuarios` (`IdEstado`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_rol` FOREIGN KEY (`IdRol`) REFERENCES `seguridad.tblmsroles` (`IdRol`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_universidad` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE;

--
-- Constraints for table `seguridad.tblusuariospersonal`
--
ALTER TABLE `seguridad.tblusuariospersonal`
  ADD CONSTRAINT `fk_iduser` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
