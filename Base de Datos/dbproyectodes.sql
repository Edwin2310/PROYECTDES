/*
Navicat MySQL Data Transfer

Source Server         : db
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : dbproyectodes

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2024-09-03 12:56:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for documentos.tbladjuntosctc
-- ----------------------------
DROP TABLE IF EXISTS `documentos.tbladjuntosctc`;
CREATE TABLE `documentos.tbladjuntosctc` (
  `IdAdjuntoctc` bigint(20) NOT NULL AUTO_INCREMENT,
  `IdUsuario` bigint(20) NOT NULL,
  `FechaAdjuntoCTC` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DictamenAdjunto` varchar(500) NOT NULL,
  `IdSolicitud` bigint(20) NOT NULL,
  PRIMARY KEY (`IdAdjuntoctc`),
  KEY `ID_SOLICITUD` (`IdSolicitud`),
  KEY `ID_USUARIO` (`IdUsuario`),
  CONSTRAINT `ID_SOLICITUDCTC` FOREIGN KEY (`IdAdjuntoctc`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE,
  CONSTRAINT `ID_USUARIOCTC` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of documentos.tbladjuntosctc
-- ----------------------------

-- ----------------------------
-- Table structure for documentos.tblarchivosadjuntos
-- ----------------------------
DROP TABLE IF EXISTS `documentos.tblarchivosadjuntos`;
CREATE TABLE `documentos.tblarchivosadjuntos` (
  `IdAdjuntos` bigint(20) NOT NULL AUTO_INCREMENT,
  `IdUsuario` bigint(20) DEFAULT NULL,
  `FechaAdjunto` datetime DEFAULT NULL,
  `Solicitud` varchar(500) DEFAULT NULL,
  `PlanEstudios` varchar(500) DEFAULT NULL,
  `PlantaDocente` varchar(500) DEFAULT NULL,
  `Diagnostico` varchar(500) DEFAULT NULL,
  `IdSolicitud` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdAdjuntos`),
  KEY `fk_adjuntos` (`IdUsuario`),
  KEY `tbl_adjuntos_ibfk_1` (`IdSolicitud`),
  CONSTRAINT `fk_adjuntos` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE,
  CONSTRAINT `tbl_adjuntos_ibfk_1` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of documentos.tblarchivosadjuntos
-- ----------------------------

-- ----------------------------
-- Table structure for documentos.tbldictamenesctc
-- ----------------------------
DROP TABLE IF EXISTS `documentos.tbldictamenesctc`;
CREATE TABLE `documentos.tbldictamenesctc` (
  `IdDictamen` bigint(20) NOT NULL AUTO_INCREMENT,
  `IdSesion` bigint(20) NOT NULL,
  `NumDictamenCTC` bigint(20) NOT NULL,
  `ObervacionesDictamen` varchar(100) DEFAULT NULL,
  `IdUniversidad` bigint(20) NOT NULL,
  `IdEstadoDictamen` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdDictamen`),
  KEY `IDSESSIONCTC` (`IdSesion`),
  KEY `ID_UNIVERSIDADCTC` (`IdUniversidad`),
  KEY `fk_id_estado_dictamen` (`IdEstadoDictamen`),
  CONSTRAINT `IDSESSIONCTC` FOREIGN KEY (`IdSesion`) REFERENCES `proceso.tblsesionesctc` (`IdSesion`) ON UPDATE CASCADE,
  CONSTRAINT `ID_UNIVERSIDADCTC` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE,
  CONSTRAINT `fk_id_estado_dictamen` FOREIGN KEY (`IdEstadoDictamen`) REFERENCES `mantenimiento.tbldictamenesestados` (`IdEstadodictamen`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of documentos.tbldictamenesctc
-- ----------------------------

-- ----------------------------
-- Table structure for documentos.tblobservaciones
-- ----------------------------
DROP TABLE IF EXISTS `documentos.tblobservaciones`;
CREATE TABLE `documentos.tblobservaciones` (
  `IdObservacion` bigint(20) NOT NULL AUTO_INCREMENT,
  `Observacion` varchar(255) DEFAULT NULL,
  `DocObservacion` varchar(255) DEFAULT NULL,
  `Solicitud` varchar(500) DEFAULT NULL,
  `PlanEstudios` varchar(500) DEFAULT NULL,
  `PlantaDocente` varchar(500) DEFAULT NULL,
  `Diagnostico` varchar(500) DEFAULT NULL,
  `CreadoPor` varchar(50) DEFAULT NULL,
  `FechaObservacion` datetime NOT NULL DEFAULT current_timestamp(),
  `IdSolicitud` bigint(20) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdObservacion`),
  KEY `ID_USUARIO_OBS` (`IdUsuario`),
  KEY `documentos.tblobservaciones_ibfk_1` (`IdSolicitud`),
  CONSTRAINT `ID_USUARIO_OBS` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE,
  CONSTRAINT `documentos.tblobservaciones_ibfk_1` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of documentos.tblobservaciones
-- ----------------------------

-- ----------------------------
-- Table structure for documentos.tblopinionesrazonadas
-- ----------------------------
DROP TABLE IF EXISTS `documentos.tblopinionesrazonadas`;
CREATE TABLE `documentos.tblopinionesrazonadas` (
  `IdOpinionrazonada` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `IdUsuario` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdOpinionrazonada`),
  KEY `fk_id_usuario2` (`IdUsuario`),
  KEY `fk_solicitud2` (`IdSolicitud`),
  CONSTRAINT `fk_id_usuario2` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud2` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of documentos.tblopinionesrazonadas
-- ----------------------------

-- ----------------------------
-- Table structure for documentos.tblplanesestudio
-- ----------------------------
DROP TABLE IF EXISTS `documentos.tblplanesestudio`;
CREATE TABLE `documentos.tblplanesestudio` (
  `IdPlanestudio` bigint(20) NOT NULL AUTO_INCREMENT,
  `NumRegistro` varchar(50) DEFAULT NULL,
  `AdjuntoPlan` varchar(500) DEFAULT NULL,
  `IdUniversidad` bigint(20) NOT NULL,
  `IdSolicitud` bigint(20) NOT NULL,
  `IdCarrera` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdPlanestudio`),
  KEY `fk_idUni` (`IdUniversidad`),
  KEY `fk_idSoli` (`IdSolicitud`),
  KEY `fk_idCarre` (`IdCarrera`),
  CONSTRAINT `fk_idCarre` FOREIGN KEY (`IdCarrera`) REFERENCES `mantenimiento.tblcarreras` (`IdCarrera`) ON UPDATE CASCADE,
  CONSTRAINT `fk_idSoli` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE,
  CONSTRAINT `fk_idUni` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of documentos.tblplanesestudio
-- ----------------------------

-- ----------------------------
-- Table structure for mantenimiento.tblcarreras
-- ----------------------------
DROP TABLE IF EXISTS `mantenimiento.tblcarreras`;
CREATE TABLE `mantenimiento.tblcarreras` (
  `IdCarrera` bigint(20) NOT NULL AUTO_INCREMENT,
  `NomCarrera` varchar(50) DEFAULT NULL,
  `IdUniversidad` bigint(20) DEFAULT NULL,
  `IdModalidad` bigint(20) DEFAULT NULL,
  `IdGrado` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdCarrera`),
  KEY `ID_GRADO_C` (`IdGrado`),
  KEY `ID_MODALIDAD_C` (`IdModalidad`),
  KEY `ID_UNIVERSIDAD_C` (`IdUniversidad`),
  CONSTRAINT `ID_GRADO_C` FOREIGN KEY (`IdGrado`) REFERENCES `mantenimiento.tblgradosacademicos` (`IdGrado`) ON UPDATE CASCADE,
  CONSTRAINT `ID_MODALIDAD_C` FOREIGN KEY (`IdModalidad`) REFERENCES `mantenimiento.tblmodalidades` (`IdModalidad`) ON UPDATE CASCADE,
  CONSTRAINT `ID_UNIVERSIDAD_C` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of mantenimiento.tblcarreras
-- ----------------------------
INSERT INTO `mantenimiento.tblcarreras` VALUES ('1', 'ODONTOLOGÍA', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('2', 'INGENIERIA EN AGRONEGOCIOS', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('3', 'INGENIERIA EN AGRONEGOCIOS', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('4', 'Ingenieria en Negocios', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('5', 'INGENIERIA EN NEGOCIOS AGROPECUARIOS', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('6', 'Medicina', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('7', 'Informatica Administrativa', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('8', 'INGENIERÍA CIVIL', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('9', 'MEDICINA', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('10', 'MI PERRO EN DOCTORADO', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('11', 'MI PERRO EN DOCTORADO', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('12', 'MI PERRO EN DOCTORADO', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('13', 'MI PERRO EN DOCTORADO', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('14', 'MI PERRO EN DOCTORADO', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('15', 'MI PERRO EN DOCTORADO', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('16', 'INFORMATICA', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('17', 'INGENIERIA QUIMICA', '1', '2', '3');
INSERT INTO `mantenimiento.tblcarreras` VALUES ('18', 'INGENIERIA QUIMICA', '1', '2', '3');

-- ----------------------------
-- Table structure for mantenimiento.tblcategorias
-- ----------------------------
DROP TABLE IF EXISTS `mantenimiento.tblcategorias`;
CREATE TABLE `mantenimiento.tblcategorias` (
  `IdCategoria` bigint(20) NOT NULL AUTO_INCREMENT,
  `CodArbitrios` bigint(20) DEFAULT NULL,
  `NomCategoria` varchar(50) DEFAULT NULL,
  `Monto` decimal(18,2) DEFAULT NULL,
  `NumRef` bigint(20) DEFAULT NULL,
  `IdTiposolicitud` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdCategoria`),
  KEY `ID_TIPO_SOLICITUD_CAT` (`IdTiposolicitud`),
  CONSTRAINT `ID_TIPO_SOLICITUD_CAT` FOREIGN KEY (`IdTiposolicitud`) REFERENCES `mantenimiento.tbltiposolicitudes` (`IdTiposolicitud`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of mantenimiento.tblcategorias
-- ----------------------------
INSERT INTO `mantenimiento.tblcategorias` VALUES ('1', '801', 'APROBACIÓN DE CREACIÓN Y AUTORIZACIÓN DE UN CENTRO', '100000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('2', '802', 'APROBACIÓN DE CREACIÓN Y AUTORIZACIÓN DE UN CENTRO', '30000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('3', '803', 'APROBACIÓN DE CREACIÓN Y AUTORIZACIÓN DE UN CENTRO', '15000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('4', '804', 'APROBACIÓN DE CREACIÓN Y AUTORIZACIÓN DE UN ASOCIA', '12000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('5', '805', 'CREACIÓN DE FACULTAD EN EL DISTRITO CENTRAL (M.D.C', '15000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('6', '806', 'CREACIÓN DE FACULTAD FUERA DEL  DISTRITO CENTRAL (', '10000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('7', '807', 'APROBACIÓN DE CAMBIO DE DOMICILIO Y/O INSTALACIONE', '6000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('8', '808', 'APROBACIÓN DE CAMBIO DE DOMICILIO Y/O INSTALACIONE', '6000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('9', '809', 'DESCONCENTRACIÓN DE UN CENTRO YA ESTABLECIDO EN EL', '10000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('10', '810', 'DESCONCENTRACIÓN DE UN CENTRO YA ESTABLECIDO FUERA', '5000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('11', '898', 'CAMBIO DE CATEGORÍA  INSTITUCIONAL', '30000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('12', '828', 'REGISTRO DE NORMAS ACADÉMICAS', '2500.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('13', '828', 'REFORMAS A ESTATUTOS', '5000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('14', '834', 'REFORMAS AL PLAN DE ARBITRIOS', '2000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('15', '835', 'REGISTRO DE REFORMAS A LAS NORMAS ACADÉMICAS', '2000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('16', '836', 'CAMBIO DE NOMBRE DEL CENTRO', '20000.00', null, '1');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('17', '812', 'POR CREACIÓN DE CARRERA – GRADO ASOCIADO, SI YA EX', '20000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('18', '813', 'POR CREACIÓN DE CARRERA – GRADO ASOCIADO, SI NO EX', '15000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('19', '814', 'POR CREACIÓN DE CARRERA – LICENCIATURA, SI YA EXIS', '25000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('20', '815', 'POR CREACIÓN DE CARRERA – LICENCIATURA, SI NO EXIS', '20000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('21', '816', 'POR CREACIÓN DE CARRERA – MAESTRÍA O ESPECIALIDAD ', '35000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('22', '817', 'POR CREACIÓN DE CARRERA – MAESTRÍA O ESPECIALIDAD,', '25000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('23', '818', 'POR CREACIÓN DE CARRERA – DOCTORADO SI YA EXISTE', '45000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('24', '819', 'POR CREACIÓN DE CARRERA – DOCTORADO, SI NO EXISTE', '35000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('25', '820', 'POR CREACIÓN DE CARRERA RELACIONADO CON EL MANEJO ', '10000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('26', '821', 'POR SUPRESIÓN DE CARRERA', '7500.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('27', '822', 'CUANDO LA INSTITUCIÓN DE EDUCACIÓN SUPERIOR PIDA L', '2000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('28', '823', 'POR FUSIÓN DE CARRERA', '7000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('29', '824', 'AUTORIZACIÓN PARA FUNCIONAMIENTO DE PROGRAMA ESPEC', '12000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('30', '825', 'APROBACIÓN DIPLOMADOS (ART.81)', '5000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('31', '826', 'REGISTRO DE DIPLOMADOS (CURRICULUM)', '2500.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('32', '827', 'APROBACIÓN DE PLANES DE TRANSICIÓN', '5000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('33', '829', 'REFORMAS PLANES DE ESTUDIO, GRADO ASOCIADO', '3200.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('34', '830', 'REFORMAS PLANES DE ESTUDIO, LICENCIATURA', '3500.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('35', '831', 'REFORMAS PLANES DE ESTUDIO, MAESTRÍA-ESPECIALIDAD', '5000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('36', '832', 'REFORMAS PLANES DE ESTUDIO, DOCTORADO', '5000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('37', '837', 'REGISTRO DE CAMBIO DE CÓDIGO CARRERAS', '1000.00', null, '2');
INSERT INTO `mantenimiento.tblcategorias` VALUES ('38', '838', 'CAMBIO DE CÓDIGO Y/O NOMBRE DE ASIGNATURA', '1000.00', null, '2');

-- ----------------------------
-- Table structure for mantenimiento.tblconsejales
-- ----------------------------
DROP TABLE IF EXISTS `mantenimiento.tblconsejales`;
CREATE TABLE `mantenimiento.tblconsejales` (
  `IdConsejal` bigint(20) NOT NULL AUTO_INCREMENT,
  `PrimerNombre` varchar(50) DEFAULT NULL,
  `PrimerApellido` varchar(50) DEFAULT NULL,
  `CategoriaConsejal` varchar(50) DEFAULT NULL,
  `EstadoConsejal` varchar(50) DEFAULT NULL,
  `CorreoConsejal` varchar(50) DEFAULT NULL,
  `IdUniversidad` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdConsejal`),
  KEY `fk_id_universidad2` (`IdUniversidad`),
  CONSTRAINT `fk_id_universidad2` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of mantenimiento.tblconsejales
-- ----------------------------

-- ----------------------------
-- Table structure for mantenimiento.tbldeptos
-- ----------------------------
DROP TABLE IF EXISTS `mantenimiento.tbldeptos`;
CREATE TABLE `mantenimiento.tbldeptos` (
  `IdDepartamento` bigint(20) NOT NULL AUTO_INCREMENT,
  `NomDepto` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`IdDepartamento`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of mantenimiento.tbldeptos
-- ----------------------------
INSERT INTO `mantenimiento.tbldeptos` VALUES ('1', 'Atlántida');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('2', 'Colón');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('3', 'Comayagua');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('4', 'Copán');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('5', 'Cortés');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('6', 'Choluteca');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('7', 'El Paraíso');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('8', 'Francisco Morazán');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('9', 'Gracias a Dios');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('10', 'Intibucá');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('11', 'Islas de la Bahía');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('12', 'La Paz');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('13', 'Lempira');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('14', 'Ocotepeque');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('15', 'Olancho');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('16', 'Santa Bárbara');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('17', 'Valle');
INSERT INTO `mantenimiento.tbldeptos` VALUES ('18', 'Yoro');

-- ----------------------------
-- Table structure for mantenimiento.tbldictamenesestados
-- ----------------------------
DROP TABLE IF EXISTS `mantenimiento.tbldictamenesestados`;
CREATE TABLE `mantenimiento.tbldictamenesestados` (
  `IdEstadodictamen` bigint(20) NOT NULL AUTO_INCREMENT,
  `EstadoCTC` varchar(50) NOT NULL,
  PRIMARY KEY (`IdEstadodictamen`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of mantenimiento.tbldictamenesestados
-- ----------------------------
INSERT INTO `mantenimiento.tbldictamenesestados` VALUES ('1', 'FAVORABLE');
INSERT INTO `mantenimiento.tbldictamenesestados` VALUES ('2', 'DESFAVORABLE');

-- ----------------------------
-- Table structure for mantenimiento.tblestadossolicitudes
-- ----------------------------
DROP TABLE IF EXISTS `mantenimiento.tblestadossolicitudes`;
CREATE TABLE `mantenimiento.tblestadossolicitudes` (
  `IdEstado` bigint(20) NOT NULL AUTO_INCREMENT,
  `EstadoSolicitud` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`IdEstado`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of mantenimiento.tblestadossolicitudes
-- ----------------------------
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('1', 'REVISION DE DOCUMENTOS');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('2', 'PENDIENTE SUBSANACION DE DOCUMENTOS');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('3', 'SUBSANACION DE DOCUMENTOS REALIZADA');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('4', 'PRESENTAR DOCUMENTOS EN FISICO');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('5', 'AGENDADO PARA ADMISION DEL CES');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('6', 'ACUERDO DE ADMISION DEL CES');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('7', 'AGENDADO PARA CTC');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('8', 'DICTAMINADO POR EL CTC');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('9', 'ANALISIS CURRICULAR');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('10', 'ATENDER OBSERVACIONES Y/O CORRECCIONES');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('11', 'OBSERVACIONES Y/O CORRECCIONES ATENDIDAS');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('12', 'CORRECCION DE OPINION RAZONADA DGCA');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('13', 'OPINION RAZONADA EMITIDA');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('14', 'AGENDADO PARA APROBACION DEL CES');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('15', 'ACUERDO DE APROBACION DEL CES');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('16', 'REGISTRO DEL PLAN DE ESTUDIOS');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('17', 'ENTREGA DEL PLAN DE ESTUDIOS');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('18', 'SUBSANACION DE OPINION RAZONADA');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('19', 'PENDIENTE DE APROBACION DGAC');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('20', 'APROBADO POR DGAC');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('21', 'PENDIENTE APROBACION D E');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('22', 'APROBADO POR D E');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('23', 'PENDIENTE APROBACION SEC.ADJUNTA');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('24', 'APROBADO POR SEC.ADJUNTA');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('25', 'CORRECCION DE OPINION RAZONADA D E');
INSERT INTO `mantenimiento.tblestadossolicitudes` VALUES ('26', 'CORRECCION DE OPINION RAZONADA DSA');

-- ----------------------------
-- Table structure for mantenimiento.tblestadosusuarios
-- ----------------------------
DROP TABLE IF EXISTS `mantenimiento.tblestadosusuarios`;
CREATE TABLE `mantenimiento.tblestadosusuarios` (
  `IdEstado` bigint(20) NOT NULL AUTO_INCREMENT,
  `EstadoUsuario` varchar(10) NOT NULL,
  PRIMARY KEY (`IdEstado`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of mantenimiento.tblestadosusuarios
-- ----------------------------
INSERT INTO `mantenimiento.tblestadosusuarios` VALUES ('1', 'Activo');
INSERT INTO `mantenimiento.tblestadosusuarios` VALUES ('2', 'Inactivo');
INSERT INTO `mantenimiento.tblestadosusuarios` VALUES ('3', 'Bloqueado');
INSERT INTO `mantenimiento.tblestadosusuarios` VALUES ('4', 'Nuevo');

-- ----------------------------
-- Table structure for mantenimiento.tblgradosacademicos
-- ----------------------------
DROP TABLE IF EXISTS `mantenimiento.tblgradosacademicos`;
CREATE TABLE `mantenimiento.tblgradosacademicos` (
  `IdGrado` bigint(20) NOT NULL AUTO_INCREMENT,
  `NomGrado` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`IdGrado`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of mantenimiento.tblgradosacademicos
-- ----------------------------
INSERT INTO `mantenimiento.tblgradosacademicos` VALUES ('1', 'Tecnólogo');
INSERT INTO `mantenimiento.tblgradosacademicos` VALUES ('2', 'Licenciatura');
INSERT INTO `mantenimiento.tblgradosacademicos` VALUES ('3', 'Maestría');
INSERT INTO `mantenimiento.tblgradosacademicos` VALUES ('4', 'Especialidad');
INSERT INTO `mantenimiento.tblgradosacademicos` VALUES ('5', 'Doctorado');
INSERT INTO `mantenimiento.tblgradosacademicos` VALUES ('6', 'Doctorado en Medicina y K');

-- ----------------------------
-- Table structure for mantenimiento.tblmodalidades
-- ----------------------------
DROP TABLE IF EXISTS `mantenimiento.tblmodalidades`;
CREATE TABLE `mantenimiento.tblmodalidades` (
  `IdModalidad` bigint(20) NOT NULL AUTO_INCREMENT,
  `NomModalidad` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`IdModalidad`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of mantenimiento.tblmodalidades
-- ----------------------------
INSERT INTO `mantenimiento.tblmodalidades` VALUES ('1', 'Presencial');
INSERT INTO `mantenimiento.tblmodalidades` VALUES ('2', 'Distancia');
INSERT INTO `mantenimiento.tblmodalidades` VALUES ('3', 'Semi presencial con media');
INSERT INTO `mantenimiento.tblmodalidades` VALUES ('4', 'Virtual');

-- ----------------------------
-- Table structure for mantenimiento.tblmunicipios
-- ----------------------------
DROP TABLE IF EXISTS `mantenimiento.tblmunicipios`;
CREATE TABLE `mantenimiento.tblmunicipios` (
  `IdMunicipio` bigint(20) NOT NULL,
  `NomMunicipio` varchar(50) DEFAULT NULL,
  `IdDepartamento` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdMunicipio`),
  KEY `ID_DEPARTAMENTO` (`IdDepartamento`),
  CONSTRAINT `ID_DEPARTAMENTO` FOREIGN KEY (`IdDepartamento`) REFERENCES `mantenimiento.tbldeptos` (`IdDepartamento`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of mantenimiento.tblmunicipios
-- ----------------------------
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('1', 'Arizona', '1');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('2', 'El Porvenir', '1');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('3', 'Esparta', '1');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('4', 'Jutiapa', '1');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('5', 'La Ceiba', '1');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('6', 'La Masica', '1');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('7', 'San Francisco', '1');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('8', 'Tela', '1');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('9', 'Balfate', '2');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('10', 'Bonito Oriental', '2');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('11', 'Iriona', '2');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('12', 'Limón', '2');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('13', 'Sabá', '2');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('14', 'Santa Fé', '2');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('15', 'Santa Rosa de Aguán', '2');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('16', 'Sonaguera', '2');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('17', 'Tocoa', '2');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('18', 'Trujillo', '2');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('19', 'Ajuterique', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('20', 'Comayagua', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('21', 'El Rosario', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('22', 'Esquías', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('23', 'Humuya', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('24', 'La Libertad', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('25', 'La Trinidad', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('26', 'Lamaní', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('27', 'Las Lajas', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('28', 'Lejamaní', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('29', 'Meámbar', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('30', 'Minas de Oro', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('31', 'Ojos de Agua', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('32', 'San Jerónimo', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('33', 'San José de Comayagua', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('34', 'San Luis', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('35', 'San Sebastián', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('36', 'Siguatepeque', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('37', 'Taulabe', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('38', 'Villa de San Antonio', '3');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('39', 'Cabañas', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('40', 'Concepción', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('41', 'Copán Ruinas', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('42', 'Corquín', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('43', 'Cucuyagua', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('44', 'Dolores', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('45', 'Dulce Nombre', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('46', 'El Paraíso', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('47', 'Florida', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('48', 'La Jigua', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('49', 'La Unión', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('50', 'Nueva Arcadia', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('51', 'San Agustín', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('52', 'San Antonio', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('53', 'San Jerónimo', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('54', 'Cabañas', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('55', 'San José', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('56', 'San Juan de Opoa', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('57', 'San Nicolás', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('58', 'San Pedro de Copán', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('59', 'Santa Rita', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('60', 'Santa Rosa de Copán', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('61', 'Trinidad de Copán', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('62', 'Veracruz', '4');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('63', 'Choloma', '5');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('64', 'La Lima', '5');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('65', 'Omoa', '5');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('66', 'Pimienta', '5');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('67', 'Potrerillos', '5');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('68', 'Puerto Cortés', '5');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('69', 'San Antonio de Cortés', '5');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('70', 'San Francisco de Yojoa', '5');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('71', 'San Manuel', '5');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('72', 'San Pedro Sula', '5');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('73', 'Santa Cruz de Yojoa', '5');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('74', 'Villanueva', '5');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('75', 'Apacilagua', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('76', 'Apacilagua', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('77', 'Choluteca', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('78', 'Concepción de María', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('79', 'Duyure', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('80', 'El Corpus', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('81', 'El Triunfo', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('82', 'Marcovia', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('83', 'Morolica', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('84', 'Namasigüe', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('85', 'Orocuina', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('86', 'Pespire', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('87', 'San Antonio de Flores', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('88', 'San Isidro', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('89', 'San José', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('90', 'San Marcos de Colón', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('91', 'Santa Ana de Yusguare', '6');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('92', 'Alauca', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('93', 'Yuscarán', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('94', 'Danlí', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('95', 'El Paraíso', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('96', 'Guinope', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('97', 'Jacaleapa', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('98', 'Liure', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('99', 'Morocelí', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('100', 'Oropolí', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('101', 'Potrerillos', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('102', 'San Antonio de Flores', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('103', 'San Lucas', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('104', 'San Matías', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('105', 'Soledad', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('106', 'Teupasenti', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('107', 'Texiguat', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('108', 'Trojes', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('109', 'Vado Ancho', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('110', 'Yauyupe', '7');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('111', 'Alubarén', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('112', 'Cantarranas', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('113', 'Cedros', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('114', 'Curarén', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('115', 'Distrito Central', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('116', 'El Porvenir', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('117', 'Guaimaca', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('118', 'La Libertad', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('119', 'La Venta', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('120', 'Lepaterique', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('121', 'Maraita', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('122', 'Marale', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('123', 'Nueva Armenia', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('124', 'Ojojona', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('125', 'Orica', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('126', 'Reitoca', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('127', 'Sabanagrande', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('128', 'San Antonio de Oriente', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('129', 'San Buenaventura', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('130', 'San Ignacio', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('131', 'San Miguelito', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('132', 'Santa Ana', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('133', 'Santa Lucía', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('134', 'Talanga', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('135', 'Valle de Ángeles', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('136', 'Vallecillo', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('137', 'Villa de San Francisco', '8');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('138', 'Ahuas', '9');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('140', 'Brus Laguna', '9');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('141', 'Juan Francisco Bulnes', '9');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('142', 'Puerto Lempira', '9');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('143', 'Ramón Villeda Morales', '9');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('144', 'Wampusirpi', '9');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('145', 'Camasca', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('146', 'Colomoncagua', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('147', 'Concepción', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('148', 'Dolores', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('149', 'Intibucá', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('150', 'Jesús de Otoro', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('151', 'Magdalena', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('152', 'Masaguara', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('153', 'San Francisco de Opalaca\r\n', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('154', 'San Isidro', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('155', 'San Juan', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('156', 'San Marcos de Sierra', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('157', 'San Miguelito', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('158', 'Santa Lucía de Intibucá', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('159', 'Yamaranguila', '10');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('160', 'Guanaja', '11');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('161', 'José Santos Guardiola', '11');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('162', 'Roatán', '11');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('163', 'Útila', '11');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('164', 'Aguaqueterique', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('165', 'Cabañas', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('166', 'Cane', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('167', 'Chinacla', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('168', 'Guajiquiro', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('169', 'La Paz', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('170', 'Lauterique', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('171', 'Marcala', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('172', 'Mercedes de Oriente', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('173', 'Opatoro', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('174', 'San Antonio del Norte', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('175', 'San José', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('176', 'San Juan', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('177', 'San Pedro de Tutule', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('178', 'Santa Ana', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('179', 'Santiago de Puringla', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('180', 'Yarula', '12');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('181', 'Belén', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('182', 'Belén', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('183', 'Candelaria', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('184', 'Cololaca', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('185', 'Erandique', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('186', 'Gracias', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('187', 'Gualcince', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('188', 'Guarita', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('189', 'La Campa', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('190', 'La Iguala', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('191', 'La Unión', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('192', 'La Virtud', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('193', 'Las Flores', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('194', 'Lepaera', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('195', 'Mapulaca', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('196', 'Piraera', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('197', 'San Andrés', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('198', 'San Francisco', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('199', 'San Juan Guarita', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('200', 'San Manuel Colohete', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('201', 'San Marcos de Caiquín', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('202', 'San Sebastian', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('203', 'Santa Cruz', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('204', 'Talgua', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('205', 'Tambla', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('206', 'Valladolid', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('207', 'Virginia', '13');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('208', 'Belén Gualcho', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('209', 'Belén Gualcho', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('210', 'Concepción', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('211', 'Dolores Merendon', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('212', 'Fraternidad', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('213', 'La Encarnación', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('214', 'La Labor', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('215', 'Lucerna', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('216', 'Mercedes', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('217', 'Ocotepeque', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('218', 'San Fernando', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('219', 'San Francisco del Valle', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('220', 'San Jorge', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('221', 'San Marcos', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('222', 'Santa Fé', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('223', 'Sensenti', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('224', 'Sinuapa', '14');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('225', 'Campamento', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('226', 'Campamento', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('227', 'Catacamas', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('228', 'Concordia', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('229', 'Dulce Nombre de Culmí', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('230', 'El Rosario', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('231', 'Esquipulas del Norte', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('232', 'Gualaco', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('233', 'Guarizama', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('234', 'Guata', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('235', 'Guayape', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('236', 'Jano', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('237', 'La Unión', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('238', 'Mangulile', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('239', 'Manto', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('240', 'Patuca', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('241', 'Salamá', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('242', 'San Esteban', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('243', 'San Francisco de Becerra', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('244', 'San Francisco de la Paz', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('245', 'Santa Maria del Real', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('246', 'Silca', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('247', 'Yocón', '15');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('248', 'Arada', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('251', 'Azacualpa', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('252', 'Ceguaca', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('253', 'Chinda', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('254', 'Concepción del Norte', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('255', 'Concepción del Sur', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('256', 'El Nispero', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('257', 'Gualala', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('258', 'Ilama', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('259', 'Las Vegas', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('260', 'Macuelizo', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('261', 'Naranjito', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('262', 'Nueva Frontera', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('263', 'Nuevo Celilac', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('264', 'Petoa', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('265', 'Protección', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('266', 'Quimistán', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('267', 'San Francisco de Ojuera', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('268', 'San José de Colinas', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('269', 'San Luis', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('270', 'San Marcos', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('271', 'San Nicolás', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('272', 'San Pedro Zacapa', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('273', 'San Vicente Centenario', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('274', 'Santa Bárbara', '16');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('276', 'Alianza', '17');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('277', 'Amapala', '17');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('278', 'Aramecina', '17');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('279', 'Caridad', '17');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('280', 'Goascorán', '17');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('282', 'San Francisco de Coray', '17');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('283', 'San Lorenzo', '17');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('284', 'Langue', '17');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('285', 'Arenal', '18');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('286', 'El Negrito', '18');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('287', 'El Progreso', '18');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('288', 'Jocón', '18');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('289', 'Morazán', '18');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('290', 'Olanchito', '18');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('291', 'Sulaco', '18');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('292', 'Victoria', '18');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('293', 'Yorito', '18');
INSERT INTO `mantenimiento.tblmunicipios` VALUES ('294', 'Yoro', '18');

-- ----------------------------
-- Table structure for mantenimiento.tblsedes
-- ----------------------------
DROP TABLE IF EXISTS `mantenimiento.tblsedes`;
CREATE TABLE `mantenimiento.tblsedes` (
  `IdSedes` bigint(20) NOT NULL AUTO_INCREMENT,
  `NomSedes` text NOT NULL,
  `IdUniversidad` bigint(20) NOT NULL,
  PRIMARY KEY (`IdSedes`),
  KEY `ID_UNIVERSIDAD` (`IdUniversidad`),
  CONSTRAINT `ID_UNIVERSIDAD_S` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of mantenimiento.tblsedes
-- ----------------------------
INSERT INTO `mantenimiento.tblsedes` VALUES ('1', 'NOSE UNAH 1', '1');
INSERT INTO `mantenimiento.tblsedes` VALUES ('2', 'NOSE UNAH 2', '1');
INSERT INTO `mantenimiento.tblsedes` VALUES ('3', 'NOSE UTH 1', '1');
INSERT INTO `mantenimiento.tblsedes` VALUES ('4', 'NOSE UTH 2', '1');

-- ----------------------------
-- Table structure for mantenimiento.tbltiposolicitudes
-- ----------------------------
DROP TABLE IF EXISTS `mantenimiento.tbltiposolicitudes`;
CREATE TABLE `mantenimiento.tbltiposolicitudes` (
  `IdTiposolicitud` bigint(20) NOT NULL AUTO_INCREMENT,
  `NomTipoSolicitud` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`IdTiposolicitud`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of mantenimiento.tbltiposolicitudes
-- ----------------------------
INSERT INTO `mantenimiento.tbltiposolicitudes` VALUES ('1', 'INSTITUCIONAL');
INSERT INTO `mantenimiento.tbltiposolicitudes` VALUES ('2', 'ACADEMICO');
INSERT INTO `mantenimiento.tbltiposolicitudes` VALUES ('3', 'OTROS');

-- ----------------------------
-- Table structure for mantenimiento.tbluniversidadescentros
-- ----------------------------
DROP TABLE IF EXISTS `mantenimiento.tbluniversidadescentros`;
CREATE TABLE `mantenimiento.tbluniversidadescentros` (
  `IdUniversidad` bigint(20) NOT NULL AUTO_INCREMENT,
  `NomUniversidad` varchar(50) NOT NULL,
  `IdDepartamento` bigint(20) NOT NULL,
  `IdMunicipio` bigint(20) NOT NULL,
  PRIMARY KEY (`IdUniversidad`),
  KEY `ID_DEPARTAMENTO` (`IdDepartamento`),
  KEY `ID_MUNICIPIO` (`IdMunicipio`),
  CONSTRAINT `ID_DEPARTAMENTO_UNI` FOREIGN KEY (`IdDepartamento`) REFERENCES `mantenimiento.tbldeptos` (`IdDepartamento`) ON UPDATE CASCADE,
  CONSTRAINT `ID_MUNICIPIO_UNI` FOREIGN KEY (`IdMunicipio`) REFERENCES `mantenimiento.tblmunicipios` (`IdMunicipio`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of mantenimiento.tbluniversidadescentros
-- ----------------------------
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('1', 'UNAH', '8', '115');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('2', 'UPNFM', '8', '115');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('3', 'UNACIFOR', '3', '36');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('4', 'UNAG', '15', '227');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('5', 'UNPH', '8', '115');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('6', 'UDH', '8', '115');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('7', 'UNITEC', '8', '115');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('8', 'UJCV', '8', '115');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('9', 'UMH', '8', '115');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('10', 'UPH', '8', '115');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('11', 'UPI', '8', '115');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('12', 'USMAH', '5', '72');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('13', 'UCENM', '8', '115');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('14', 'EAP ZAMORANO', '8', '128');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('15', 'USAP', '5', '72');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('16', 'UNICAH', '8', '115');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('17', 'SMNSS', '8', '115');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('18', 'UJN', '5', '72');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('19', 'UNEV', '5', '72');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('20', 'UTH', '5', '72');
INSERT INTO `mantenimiento.tbluniversidadescentros` VALUES ('21', 'UCRISH', '5', '72');

-- ----------------------------
-- Table structure for proceso.tblacuerdoscesadmin
-- ----------------------------
DROP TABLE IF EXISTS `proceso.tblacuerdoscesadmin`;
CREATE TABLE `proceso.tblacuerdoscesadmin` (
  `IdAcuerdoadmin` bigint(20) NOT NULL AUTO_INCREMENT,
  `NumActaAdmin` varchar(25) DEFAULT NULL,
  `AcuerdoAdmision` varchar(25) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `IdSolicitud` bigint(20) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdAcuerdoadmin`),
  KEY `proceso.tblacuerdoscesadmin_ibfk_1` (`IdSolicitud`),
  KEY `proceso.tblacuerdoscesadmin_ibfk_2` (`IdUsuario`),
  CONSTRAINT `proceso.tblacuerdoscesadmin_ibfk_1` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE,
  CONSTRAINT `proceso.tblacuerdoscesadmin_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of proceso.tblacuerdoscesadmin
-- ----------------------------

-- ----------------------------
-- Table structure for proceso.tblacuerdoscesaprob
-- ----------------------------
DROP TABLE IF EXISTS `proceso.tblacuerdoscesaprob`;
CREATE TABLE `proceso.tblacuerdoscesaprob` (
  `IdAcuerdoaprob` bigint(20) NOT NULL AUTO_INCREMENT,
  `NumActaAprobacion` varchar(25) DEFAULT NULL,
  `AcuerdoAprobacion` varchar(25) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `IdSolicitud` bigint(20) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdAcuerdoaprob`),
  KEY `proceso.tblacuerdoscesaprob_ibfk_1` (`IdSolicitud`),
  KEY `proceso.tblacuerdoscesaprob_ibfk_2` (`IdUsuario`),
  CONSTRAINT `proceso.tblacuerdoscesaprob_ibfk_1` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE,
  CONSTRAINT `proceso.tblacuerdoscesaprob_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of proceso.tblacuerdoscesaprob
-- ----------------------------

-- ----------------------------
-- Table structure for proceso.tblbtcsolicitudes
-- ----------------------------
DROP TABLE IF EXISTS `proceso.tblbtcsolicitudes`;
CREATE TABLE `proceso.tblbtcsolicitudes` (
  `IdBtcsolicitud` bigint(20) NOT NULL AUTO_INCREMENT,
  `FechaSolicitud` datetime DEFAULT current_timestamp(),
  `Observacion` varchar(100) DEFAULT NULL,
  `IdSolicitud` bigint(20) DEFAULT NULL,
  `IdUniversidad` bigint(20) DEFAULT NULL,
  `IdCarrera` bigint(20) DEFAULT NULL,
  `IdEstado` bigint(20) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdBtcsolicitud`),
  KEY `ID_CARRERA_BTS` (`IdCarrera`),
  KEY `ID_ESTADO_BTS` (`IdEstado`),
  KEY `ID_SOLICITUD_BTS` (`IdSolicitud`),
  KEY `ID_UNIVERSIDAD_BTS` (`IdUniversidad`),
  KEY `ID_USUARIO_BTS` (`IdUsuario`),
  CONSTRAINT `ID_CARRERA_BTS` FOREIGN KEY (`IdCarrera`) REFERENCES `mantenimiento.tblcarreras` (`IdCarrera`) ON UPDATE CASCADE,
  CONSTRAINT `ID_ESTADO_BTS` FOREIGN KEY (`IdEstado`) REFERENCES `mantenimiento.tblestadossolicitudes` (`IdEstado`) ON UPDATE CASCADE,
  CONSTRAINT `ID_SOLICITUD_BTS` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE,
  CONSTRAINT `ID_UNIVERSIDAD_BTS` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE,
  CONSTRAINT `ID_USUARIO_BTS` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of proceso.tblbtcsolicitudes
-- ----------------------------

-- ----------------------------
-- Table structure for proceso.tbldictamenesuniversidades
-- ----------------------------
DROP TABLE IF EXISTS `proceso.tbldictamenesuniversidades`;
CREATE TABLE `proceso.tbldictamenesuniversidades` (
  `IdDicuni` bigint(20) NOT NULL AUTO_INCREMENT,
  `IdDictamen` bigint(20) NOT NULL,
  `IdUniversidad` bigint(20) NOT NULL,
  PRIMARY KEY (`IdDicuni`),
  UNIQUE KEY `ID_UNIVERSIDAD` (`IdUniversidad`),
  KEY `ID_DICTAMEN` (`IdDictamen`),
  CONSTRAINT `ID_DICTAMEN_CTC` FOREIGN KEY (`IdDictamen`) REFERENCES `documentos.tbldictamenesctc` (`IdDictamen`) ON UPDATE CASCADE,
  CONSTRAINT `ID_UNIVERSIDAD_CTC` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of proceso.tbldictamenesuniversidades
-- ----------------------------

-- ----------------------------
-- Table structure for proceso.tblopinionesconsejales
-- ----------------------------
DROP TABLE IF EXISTS `proceso.tblopinionesconsejales`;
CREATE TABLE `proceso.tblopinionesconsejales` (
  `IdOpinionconsejal` bigint(20) NOT NULL,
  `IdOpinionrazonada` bigint(20) DEFAULT NULL,
  `IdConsejal` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdOpinionconsejal`),
  KEY `ID_CONSEJAL` (`IdConsejal`),
  KEY `ID_OPINION_RAZONADA` (`IdOpinionrazonada`),
  CONSTRAINT `ID_CONSEJAL` FOREIGN KEY (`IdConsejal`) REFERENCES `mantenimiento.tblconsejales` (`IdConsejal`) ON UPDATE CASCADE,
  CONSTRAINT `ID_OPINION_RAZONADA` FOREIGN KEY (`IdOpinionrazonada`) REFERENCES `documentos.tblopinionesrazonadas` (`IdOpinionrazonada`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of proceso.tblopinionesconsejales
-- ----------------------------

-- ----------------------------
-- Table structure for proceso.tblseguimientos
-- ----------------------------
DROP TABLE IF EXISTS `proceso.tblseguimientos`;
CREATE TABLE `proceso.tblseguimientos` (
  `IdSeguimiento` bigint(20) NOT NULL AUTO_INCREMENT,
  `NumeroDeActa` varchar(50) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `IdSolicitud` bigint(20) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  `IdEstado` bigint(20) DEFAULT NULL,
  `IdDictamenctc` bigint(20) DEFAULT NULL,
  `IdOpinionrazonada` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdSeguimiento`),
  KEY `ID_ESTADO_SG` (`IdEstado`),
  KEY `ID_SOLICITUD_SG` (`IdSolicitud`),
  KEY `ID_USUARIO_SG` (`IdUsuario`),
  CONSTRAINT `ID_ESTADO_SG` FOREIGN KEY (`IdEstado`) REFERENCES `mantenimiento.tblestadossolicitudes` (`IdEstado`) ON UPDATE CASCADE,
  CONSTRAINT `ID_SOLICITUD_SG` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE,
  CONSTRAINT `ID_USUARIO_SG` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of proceso.tblseguimientos
-- ----------------------------

-- ----------------------------
-- Table structure for proceso.tblsesiones
-- ----------------------------
DROP TABLE IF EXISTS `proceso.tblsesiones`;
CREATE TABLE `proceso.tblsesiones` (
  `IdSesion` bigint(20) NOT NULL,
  `Sesion` varchar(20) NOT NULL,
  `IdSolicitud` bigint(20) NOT NULL,
  PRIMARY KEY (`IdSesion`),
  KEY `ID_SOLICITUD` (`IdSolicitud`),
  CONSTRAINT `ID_SOLICITUD_SE` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of proceso.tblsesiones
-- ----------------------------

-- ----------------------------
-- Table structure for proceso.tblsesionesctc
-- ----------------------------
DROP TABLE IF EXISTS `proceso.tblsesionesctc`;
CREATE TABLE `proceso.tblsesionesctc` (
  `IdSesion` bigint(20) NOT NULL AUTO_INCREMENT,
  `SesionCTC` varchar(20) NOT NULL,
  `IdSolicitud` bigint(20) NOT NULL,
  PRIMARY KEY (`IdSesion`),
  KEY `ID_SOLICITUD` (`IdSolicitud`),
  CONSTRAINT `IDSOLICITUCC` FOREIGN KEY (`IdSolicitud`) REFERENCES `proceso.tblsolicitudes` (`IdSolicitud`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of proceso.tblsesionesctc
-- ----------------------------

-- ----------------------------
-- Table structure for proceso.tblsolicitudes
-- ----------------------------
DROP TABLE IF EXISTS `proceso.tblsolicitudes`;
CREATE TABLE `proceso.tblsolicitudes` (
  `IdSolicitud` bigint(20) NOT NULL AUTO_INCREMENT,
  `NumReferencia` varchar(40) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `NombreCompleto` varchar(100) DEFAULT NULL,
  `CorreoElectronico` varchar(100) DEFAULT NULL,
  `FechaIngreso` datetime DEFAULT current_timestamp(),
  `FechaModificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CodigoPago` bigint(20) DEFAULT NULL,
  `NombreCarrera` varchar(255) DEFAULT '',
  `IdTiposolicitud` bigint(20) DEFAULT NULL,
  `IdCategoria` bigint(20) DEFAULT NULL,
  `IdCarrera` bigint(20) DEFAULT NULL,
  `IdGrado` bigint(20) DEFAULT NULL,
  `IdModalidad` bigint(20) DEFAULT NULL,
  `IdUniversidad` bigint(20) DEFAULT NULL,
  `IdDepartamento` bigint(20) DEFAULT NULL,
  `IdMunicipio` bigint(20) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  `IdEstado` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdSolicitud`),
  KEY `FK_CARRERA` (`IdCarrera`),
  KEY `FK_CATEGORIA` (`IdCategoria`),
  KEY `FK_DEPARTAMENTO` (`IdDepartamento`),
  KEY `FK_ESTADO_IDX` (`IdEstado`),
  KEY `FK_GRADO` (`IdGrado`),
  KEY `FK_MODALIDAD` (`IdModalidad`),
  KEY `FK_MUNICIPIO` (`IdMunicipio`),
  KEY `FK_TIPO_SOLICITUD` (`IdTiposolicitud`),
  KEY `FK_UNIVERSIDAD` (`IdUniversidad`),
  KEY `FK_USUARIO` (`IdUsuario`),
  CONSTRAINT `FK_CARRERA` FOREIGN KEY (`IdCarrera`) REFERENCES `mantenimiento.tblcarreras` (`IdCarrera`) ON UPDATE CASCADE,
  CONSTRAINT `FK_CATEGORIA` FOREIGN KEY (`IdCategoria`) REFERENCES `mantenimiento.tblcategorias` (`IdCategoria`) ON UPDATE CASCADE,
  CONSTRAINT `FK_DEPARTAMENTO` FOREIGN KEY (`IdDepartamento`) REFERENCES `mantenimiento.tbldeptos` (`IdDepartamento`) ON UPDATE CASCADE,
  CONSTRAINT `FK_ESTADO_IDX` FOREIGN KEY (`IdEstado`) REFERENCES `mantenimiento.tblestadossolicitudes` (`IdEstado`) ON UPDATE CASCADE,
  CONSTRAINT `FK_GRADO` FOREIGN KEY (`IdGrado`) REFERENCES `mantenimiento.tblgradosacademicos` (`IdGrado`) ON UPDATE CASCADE,
  CONSTRAINT `FK_MODALIDAD` FOREIGN KEY (`IdModalidad`) REFERENCES `mantenimiento.tblmodalidades` (`IdModalidad`) ON UPDATE CASCADE,
  CONSTRAINT `FK_MUNICIPIO` FOREIGN KEY (`IdMunicipio`) REFERENCES `mantenimiento.tblmunicipios` (`IdMunicipio`) ON UPDATE CASCADE,
  CONSTRAINT `FK_TIPO_SOLICITUD` FOREIGN KEY (`IdTiposolicitud`) REFERENCES `mantenimiento.tbltiposolicitudes` (`IdTiposolicitud`) ON UPDATE CASCADE,
  CONSTRAINT `FK_UNIVERSIDAD` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE,
  CONSTRAINT `FK_USUARIO` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of proceso.tblsolicitudes
-- ----------------------------

-- ----------------------------
-- Table structure for seguridad.tblbitacora
-- ----------------------------
DROP TABLE IF EXISTS `seguridad.tblbitacora`;
CREATE TABLE `seguridad.tblbitacora` (
  `IdBitacora` int(11) NOT NULL AUTO_INCREMENT,
  `FechaHora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `IdUsuario` bigint(20) NOT NULL,
  `IdObjeto` bigint(20) NOT NULL,
  `Accion` varchar(100) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  PRIMARY KEY (`IdBitacora`),
  KEY `ID_USUARIO` (`IdUsuario`),
  KEY `ID_OBJETO` (`IdObjeto`),
  CONSTRAINT `ID_OBJETO` FOREIGN KEY (`IdObjeto`) REFERENCES `seguridad.tblobjetos` (`IdObjeto`) ON UPDATE CASCADE,
  CONSTRAINT `ID_USUARIO` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2850 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of seguridad.tblbitacora
-- ----------------------------
INSERT INTO `seguridad.tblbitacora` VALUES ('2551', '2024-08-16 10:09:16', '12', '9', 'acceso denegado', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2552', '2024-08-16 10:09:18', '12', '10', 'acceso denegado', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2553', '2024-08-16 10:09:18', '12', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2554', '2024-08-16 10:09:20', '12', '21', 'accedio al modulo', 'Bitácora de Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2555', '2024-08-16 10:09:27', '12', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2556', '2024-08-16 10:09:31', '12', '4', 'acceso denegado', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2557', '2024-08-16 10:09:33', '12', '8', 'acceso denegado', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2558', '2024-08-16 10:09:35', '12', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2559', '2024-08-16 10:09:36', '12', '21', 'accedio al modulo', 'Bitácora de Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2560', '2024-08-16 10:09:43', '12', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2561', '2024-08-16 10:37:54', '12', '3', 'acceso denegado', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2562', '2024-08-16 10:37:54', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2563', '2024-08-16 10:37:55', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2564', '2024-08-16 10:37:56', '12', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2565', '2024-08-16 10:38:18', '12', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2566', '2024-08-16 10:38:20', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2567', '2024-08-16 10:38:22', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2568', '2024-08-16 10:38:23', '12', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2569', '2024-08-16 10:39:35', '12', '9', 'acceso denegado', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2570', '2024-08-16 10:39:39', '12', '10', 'acceso denegado', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2571', '2024-08-16 10:39:40', '12', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2572', '2024-08-16 10:39:44', '12', '21', 'accedio al modulo', 'Bitácora de Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2573', '2024-08-16 10:39:49', '12', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2574', '2024-08-16 10:40:39', '12', '3', 'acceso denegado', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2575', '2024-08-16 10:40:41', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2576', '2024-08-16 10:40:42', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2577', '2024-08-16 10:40:42', '12', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2578', '2024-08-16 10:46:38', '12', '11', 'acceso denegado', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2579', '2024-08-16 10:46:40', '12', '9', 'accedio al modulo', 'Informes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2580', '2024-08-16 10:46:57', '12', '8', 'acceso denegado', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2581', '2024-08-16 10:47:06', '12', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2582', '2024-08-16 10:51:52', '12', '10', 'acceso denegado', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2583', '2024-08-16 10:51:53', '12', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2584', '2024-08-16 10:51:54', '12', '8', 'acceso denegado', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2585', '2024-08-16 10:51:55', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2586', '2024-08-16 11:03:23', '12', '11', 'acceso denegado', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2587', '2024-08-16 11:03:24', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2588', '2024-08-16 11:03:25', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2589', '2024-08-16 11:03:26', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2590', '2024-08-16 11:03:31', '12', '11', 'acceso denegado', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2591', '2024-08-16 11:03:33', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2592', '2024-08-16 11:03:34', '12', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2593', '2024-08-16 11:03:36', '12', '8', 'acceso denegado', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2594', '2024-08-16 11:03:38', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2595', '2024-08-16 11:03:40', '12', '3', 'acceso denegado', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2596', '2024-08-16 11:03:42', '12', '3', 'acceso denegado', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2597', '2024-08-16 11:29:59', '12', '3', 'acceso denegado', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2598', '2024-08-16 11:30:01', '12', '11', 'acceso denegado', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2599', '2024-08-16 11:30:03', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2600', '2024-08-16 11:30:04', '12', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2601', '2024-08-16 11:30:16', '12', '3', 'acceso denegado', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2602', '2024-08-16 11:30:28', '12', '3', 'acceso denegado', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2603', '2024-08-16 11:30:52', '12', '3', 'acceso denegado', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2604', '2024-08-16 11:31:18', '12', '3', 'acceso denegado', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2605', '2024-08-16 11:39:55', '12', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2606', '2024-08-16 11:39:58', '12', '4', 'acceso denegado', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2607', '2024-08-16 11:40:00', '12', '8', 'acceso denegado', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2608', '2024-08-16 11:40:01', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2609', '2024-08-16 11:40:03', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2610', '2024-08-16 11:40:03', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2611', '2024-08-16 11:40:04', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2612', '2024-08-16 12:12:06', '15', '4', 'acceso denegado', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2613', '2024-08-16 12:12:08', '15', '11', 'acceso denegado', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2614', '2024-08-16 12:13:10', '15', '3', 'acceso denegado', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2615', '2024-08-16 12:13:12', '15', '11', 'acceso denegado', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2616', '2024-08-16 12:13:20', '15', '10', 'acceso denegado', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2617', '2024-08-16 12:13:21', '15', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2618', '2024-08-16 12:13:23', '15', '8', 'acceso denegado', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2619', '2024-08-16 12:13:24', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2620', '2024-08-16 12:13:25', '15', '3', 'acceso denegado', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2621', '2024-08-16 12:13:28', '15', '11', 'acceso denegado', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2622', '2024-08-16 12:13:29', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2623', '2024-08-16 12:13:30', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2624', '2024-08-16 12:13:40', '15', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2625', '2024-08-16 12:13:43', '15', '11', 'acceso denegado', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2626', '2024-08-16 12:13:43', '15', '17', 'accedio al modulo', 'Permisos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2627', '2024-08-16 12:13:53', '15', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2628', '2024-08-16 12:29:02', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2629', '2024-08-16 12:29:04', '15', '8', 'acceso denegado', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2630', '2024-08-16 13:07:00', '15', '18', 'accedio al modulo', 'Parámetros');
INSERT INTO `seguridad.tblbitacora` VALUES ('2631', '2024-08-16 13:07:02', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2632', '2024-08-16 13:07:19', '15', '18', 'accedio al modulo', 'Parámetros');
INSERT INTO `seguridad.tblbitacora` VALUES ('2633', '2024-08-16 13:07:20', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2634', '2024-08-16 13:54:40', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2635', '2024-08-16 13:54:42', '15', '8', 'acceso denegado', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2636', '2024-08-16 13:55:15', '15', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2637', '2024-08-16 13:55:20', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2638', '2024-08-16 14:20:20', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2639', '2024-08-16 15:37:31', '15', '9', 'acceso denegado', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2640', '2024-08-16 15:37:33', '15', '11', 'acceso denegado', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2641', '2024-08-16 15:37:34', '15', '17', 'accedio al modulo', 'Permisos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2642', '2024-08-16 15:37:42', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2643', '2024-08-16 17:03:19', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2644', '2024-08-16 17:08:02', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2645', '2024-08-16 17:08:33', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2646', '2024-08-16 17:08:35', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2647', '2024-08-16 17:08:48', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2648', '2024-08-16 17:12:44', '15', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2649', '2024-08-16 17:12:44', '15', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2650', '2024-08-16 17:15:59', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2651', '2024-08-16 17:15:59', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2652', '2024-08-16 17:19:36', '15', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2653', '2024-08-16 17:19:38', '15', '11', 'acceso denegado', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2654', '2024-08-16 17:19:39', '15', '17', 'accedio al modulo', 'Permisos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2655', '2024-08-16 17:19:46', '15', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2656', '2024-08-16 17:25:42', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2657', '2024-08-16 17:27:04', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2658', '2024-08-16 17:27:36', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2659', '2024-08-16 17:27:38', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2660', '2024-08-16 17:28:44', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2661', '2024-08-16 17:29:07', '15', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2662', '2024-08-16 17:29:07', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2663', '2024-08-16 17:33:49', '15', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2664', '2024-08-16 17:33:50', '15', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2665', '2024-08-16 17:33:51', '15', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2666', '2024-08-16 17:33:52', '15', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2667', '2024-08-16 17:34:40', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2668', '2024-08-16 17:36:02', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2669', '2024-08-16 17:39:30', '15', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2670', '2024-08-16 17:39:31', '15', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2671', '2024-08-16 17:39:32', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2672', '2024-08-16 17:39:33', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2673', '2024-08-16 17:39:34', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2674', '2024-08-16 17:43:10', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2675', '2024-08-16 17:43:13', '15', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2676', '2024-08-16 17:49:21', '15', '9', 'accedio al modulo', 'Informes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2677', '2024-08-16 17:54:53', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2678', '2024-08-16 17:54:58', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2679', '2024-08-16 17:55:18', '15', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2680', '2024-08-16 17:57:21', '15', '27', 'accedio al modulo', 'GradosAcademicos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2681', '2024-08-16 17:57:26', '15', '26', 'acceso denegado', 'Modalidades');
INSERT INTO `seguridad.tblbitacora` VALUES ('2682', '2024-08-16 17:57:29', '15', '22', 'acceso denegado', 'Departamentos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2683', '2024-08-16 17:57:32', '15', '23', 'accedio al modulo', 'Municipios');
INSERT INTO `seguridad.tblbitacora` VALUES ('2684', '2024-08-16 17:57:35', '15', '10', 'acceso denegado', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2685', '2024-08-16 17:57:38', '15', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2686', '2024-08-16 17:57:39', '15', '15', 'accedio al modulo', 'Usuarios');
INSERT INTO `seguridad.tblbitacora` VALUES ('2687', '2024-08-16 17:57:40', '15', '19', 'accedio al modulo', 'Roles');
INSERT INTO `seguridad.tblbitacora` VALUES ('2688', '2024-08-16 17:57:41', '15', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2689', '2024-08-16 17:57:42', '15', '16', 'accedio al modulo', 'Consejeros');
INSERT INTO `seguridad.tblbitacora` VALUES ('2690', '2024-08-16 17:57:44', '15', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2691', '2024-08-16 17:57:44', '15', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2692', '2024-08-16 17:57:45', '15', '24', 'accedio al modulo', 'Universidades');
INSERT INTO `seguridad.tblbitacora` VALUES ('2693', '2024-08-16 17:57:47', '15', '25', 'accedio al modulo', 'Carreras');
INSERT INTO `seguridad.tblbitacora` VALUES ('2694', '2024-08-16 17:57:49', '15', '28', 'accedio al modulo', 'Tipos De Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2695', '2024-08-16 17:57:51', '15', '8', 'acceso denegado', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2696', '2024-08-16 17:57:55', '15', '17', 'accedio al modulo', 'Permisos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2697', '2024-08-16 17:58:08', '15', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2698', '2024-08-16 17:58:10', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2699', '2024-08-16 17:58:11', '15', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2700', '2024-08-16 17:58:13', '15', '27', 'accedio al modulo', 'GradosAcademicos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2701', '2024-08-16 17:58:33', '15', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2702', '2024-08-16 17:59:55', '15', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2703', '2024-08-16 17:59:58', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2704', '2024-08-16 18:00:52', '15', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2705', '2024-08-16 18:04:09', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2706', '2024-08-16 18:04:14', '15', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2707', '2024-08-16 18:27:07', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2708', '2024-08-16 18:27:10', '15', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2709', '2024-08-16 18:27:12', '15', '27', 'accedio al modulo', 'GradosAcademicos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2710', '2024-08-16 18:30:05', '15', '22', 'accedio al modulo', 'Departamentos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2711', '2024-08-16 18:30:43', '15', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2712', '2024-08-16 18:30:44', '15', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2713', '2024-08-16 18:30:50', '15', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2714', '2024-08-16 18:30:51', '15', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2715', '2024-08-16 18:30:56', '15', '21', 'accedio al modulo', 'Bitácora de Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2716', '2024-08-16 18:32:01', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2717', '2024-08-16 18:32:05', '15', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2718', '2024-08-16 18:32:07', '15', '27', 'accedio al modulo', 'GradosAcademicos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2719', '2024-08-16 18:40:37', '15', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2720', '2024-08-16 18:40:38', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2721', '2024-08-16 18:42:16', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2722', '2024-08-16 18:45:28', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2723', '2024-08-16 18:46:25', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2724', '2024-08-16 18:46:59', '15', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2725', '2024-08-16 18:47:00', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2726', '2024-08-16 18:47:05', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2727', '2024-08-16 18:47:23', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2728', '2024-08-16 18:48:15', '15', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2729', '2024-08-16 18:48:17', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2730', '2024-08-16 18:48:56', '15', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2731', '2024-08-16 19:06:44', '15', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2732', '2024-08-16 19:06:48', '15', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2733', '2024-08-16 19:06:49', '15', '27', 'accedio al modulo', 'GradosAcademicos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2734', '2024-08-16 19:06:53', '15', '3', 'accedio al modulo', 'Nuevo Ingreso de Solicitud');
INSERT INTO `seguridad.tblbitacora` VALUES ('2735', '2024-08-16 19:08:40', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2736', '2024-08-16 19:08:47', '15', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2737', '2024-08-16 19:08:47', '15', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2738', '2024-08-16 19:08:49', '15', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2739', '2024-08-19 18:15:16', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2740', '2024-08-19 18:20:13', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2741', '2024-08-19 18:20:55', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2742', '2024-08-19 19:17:19', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2743', '2024-08-19 19:20:07', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2744', '2024-08-19 19:23:15', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2745', '2024-08-19 19:31:43', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2746', '2024-08-19 19:47:40', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2747', '2024-08-20 16:24:19', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2748', '2024-08-20 17:41:11', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2749', '2024-08-20 17:43:42', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2750', '2024-08-20 18:04:40', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2751', '2024-08-20 18:14:07', '12', '22', 'accedio al modulo', 'Departamentos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2752', '2024-08-20 18:30:45', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2753', '2024-08-20 18:38:51', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2754', '2024-08-20 18:42:06', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2755', '2024-08-20 18:50:01', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2756', '2024-08-20 18:50:02', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2757', '2024-08-20 19:20:09', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2758', '2024-08-20 19:22:14', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2759', '2024-08-20 19:39:24', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2760', '2024-08-20 19:56:50', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2761', '2024-08-20 21:27:10', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2762', '2024-08-20 21:29:33', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2763', '2024-08-20 21:29:35', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2764', '2024-08-20 21:31:31', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2765', '2024-08-20 21:54:48', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2766', '2024-08-20 21:56:39', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2767', '2024-08-20 21:56:43', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2768', '2024-08-20 22:06:12', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2769', '2024-08-20 22:32:18', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2770', '2024-08-20 22:36:00', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2771', '2024-08-20 22:36:15', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2772', '2024-08-20 22:36:22', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2773', '2024-08-21 16:08:59', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2774', '2024-08-21 16:19:04', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2775', '2024-08-22 15:45:44', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2776', '2024-08-22 15:45:46', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2777', '2024-08-22 15:45:51', '12', '30', 'accedio al modulo', 'Estado De Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2778', '2024-08-22 15:45:53', '12', '10', 'acceso denegado', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2779', '2024-08-22 15:45:55', '12', '22', 'accedio al modulo', 'Departamentos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2780', '2024-08-22 15:46:03', '12', '25', 'accedio al modulo', 'Carreras');
INSERT INTO `seguridad.tblbitacora` VALUES ('2781', '2024-08-22 21:34:43', '12', '22', 'accedio al modulo', 'Departamentos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2782', '2024-08-22 21:34:46', '12', '23', 'accedio al modulo', 'Municipios');
INSERT INTO `seguridad.tblbitacora` VALUES ('2783', '2024-08-22 21:34:48', '12', '24', 'acceso denegado', 'Universidades');
INSERT INTO `seguridad.tblbitacora` VALUES ('2784', '2024-08-22 21:34:51', '12', '25', 'accedio al modulo', 'Carreras');
INSERT INTO `seguridad.tblbitacora` VALUES ('2785', '2024-08-22 21:34:57', '12', '25', 'accedio al modulo', 'Carreras');
INSERT INTO `seguridad.tblbitacora` VALUES ('2786', '2024-08-22 21:35:02', '12', '26', 'accedio al modulo', 'Modalidades');
INSERT INTO `seguridad.tblbitacora` VALUES ('2787', '2024-08-22 21:35:03', '12', '27', 'acceso denegado', 'GradosAcademicos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2788', '2024-08-22 21:35:07', '12', '28', 'accedio al modulo', 'Tipos De Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2789', '2024-08-22 21:35:09', '12', '29', 'acceso denegado', 'Categorias De Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2790', '2024-08-22 21:35:13', '12', '22', 'acceso denegado', 'Departamentos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2791', '2024-08-22 21:35:15', '12', '23', 'accedio al modulo', 'Municipios');
INSERT INTO `seguridad.tblbitacora` VALUES ('2792', '2024-08-22 21:35:17', '12', '24', 'acceso denegado', 'Universidades');
INSERT INTO `seguridad.tblbitacora` VALUES ('2793', '2024-08-22 21:35:19', '12', '25', 'accedio al modulo', 'Carreras');
INSERT INTO `seguridad.tblbitacora` VALUES ('2794', '2024-08-22 21:35:21', '12', '26', 'accedio al modulo', 'Modalidades');
INSERT INTO `seguridad.tblbitacora` VALUES ('2795', '2024-08-22 21:35:23', '12', '27', 'acceso denegado', 'GradosAcademicos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2796', '2024-08-22 21:35:25', '12', '28', 'accedio al modulo', 'Tipos De Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2797', '2024-08-22 21:35:27', '12', '29', 'acceso denegado', 'Categorias De Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2798', '2024-08-22 21:35:29', '12', '30', 'acceso denegado', 'Estado De Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2799', '2024-08-22 21:35:32', '12', '11', 'acceso denegado', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2800', '2024-08-22 21:35:34', '12', '17', 'accedio al modulo', 'Permisos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2801', '2024-08-22 21:36:03', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2802', '2024-08-22 21:36:05', '12', '30', 'accedio al modulo', 'Estado De Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2803', '2024-08-22 21:36:10', '12', '29', 'accedio al modulo', 'Categorias De Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2804', '2024-08-22 21:43:10', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2805', '2024-08-22 21:43:11', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2806', '2024-08-22 21:43:14', '12', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2807', '2024-08-22 21:43:16', '12', '21', 'accedio al modulo', 'Bitácora de Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2808', '2024-08-22 21:43:24', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2809', '2024-08-23 18:52:41', '12', '25', 'accedio al modulo', 'Carreras');
INSERT INTO `seguridad.tblbitacora` VALUES ('2810', '2024-08-23 18:53:37', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2811', '2024-08-23 18:53:38', '12', '25', 'accedio al modulo', 'Carreras');
INSERT INTO `seguridad.tblbitacora` VALUES ('2812', '2024-08-23 19:50:16', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2813', '2024-08-24 14:41:55', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2814', '2024-08-24 15:41:40', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2815', '2024-08-24 15:49:40', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2816', '2024-08-24 15:49:40', '12', '4', 'accedio al modulo', 'Consultar Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2817', '2024-08-24 15:49:42', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2818', '2024-08-24 15:51:00', '12', '22', 'accedio al modulo', 'Departamentos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2819', '2024-08-24 15:56:28', '12', '23', 'accedio al modulo', 'Municipios');
INSERT INTO `seguridad.tblbitacora` VALUES ('2820', '2024-08-24 15:56:56', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2821', '2024-08-24 15:56:57', '12', '24', 'accedio al modulo', 'Universidades');
INSERT INTO `seguridad.tblbitacora` VALUES ('2822', '2024-08-24 16:05:18', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2823', '2024-08-24 16:05:19', '12', '25', 'accedio al modulo', 'Carreras');
INSERT INTO `seguridad.tblbitacora` VALUES ('2824', '2024-08-24 16:07:45', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2825', '2024-08-24 16:07:46', '12', '26', 'accedio al modulo', 'Modalidades');
INSERT INTO `seguridad.tblbitacora` VALUES ('2826', '2024-08-24 16:17:32', '12', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2827', '2024-08-24 16:17:32', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2828', '2024-08-24 16:17:34', '12', '27', 'accedio al modulo', 'GradosAcademicos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2829', '2024-08-24 16:18:51', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2830', '2024-08-24 16:18:52', '12', '28', 'accedio al modulo', 'Tipos De Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2831', '2024-08-24 16:29:18', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2832', '2024-08-24 16:29:19', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2833', '2024-08-24 16:34:40', '12', '29', 'accedio al modulo', 'Categorias De Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2834', '2024-08-24 16:38:40', '12', '10', 'accedio al modulo', 'Mantenimiento Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2835', '2024-08-24 16:38:42', '12', '30', 'accedio al modulo', 'Estado De Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2836', '2024-08-24 17:13:10', '12', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2837', '2024-08-24 17:13:11', '12', '18', 'accedio al modulo', 'Parametros');
INSERT INTO `seguridad.tblbitacora` VALUES ('2838', '2024-08-24 17:17:15', '12', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2839', '2024-08-24 17:17:16', '12', '17', 'accedio al modulo', 'Permisos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2840', '2024-08-24 17:23:26', '12', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2841', '2024-08-24 17:23:27', '12', '19', 'accedio al modulo', 'Roles');
INSERT INTO `seguridad.tblbitacora` VALUES ('2842', '2024-08-24 17:33:55', '12', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2843', '2024-08-24 17:33:57', '12', '20', 'accedio al modulo', 'Objetos');
INSERT INTO `seguridad.tblbitacora` VALUES ('2844', '2024-08-24 17:53:13', '12', '11', 'accedio al modulo', 'Seguridad');
INSERT INTO `seguridad.tblbitacora` VALUES ('2845', '2024-08-24 17:53:14', '12', '21', 'accedio al modulo', 'Bitácora de Sistema');
INSERT INTO `seguridad.tblbitacora` VALUES ('2846', '2024-08-24 17:54:47', '12', '9', 'accedio al modulo', 'Reportes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2847', '2024-08-24 17:55:21', '12', '15', 'accedio al modulo', 'Usuarios');
INSERT INTO `seguridad.tblbitacora` VALUES ('2848', '2024-08-24 18:16:56', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');
INSERT INTO `seguridad.tblbitacora` VALUES ('2849', '2024-08-24 18:18:57', '12', '8', 'accedio al modulo', 'Mantenimiento Solicitudes');

-- ----------------------------
-- Table structure for seguridad.tblhistcontraseñas
-- ----------------------------
DROP TABLE IF EXISTS `seguridad.tblhistcontraseñas`;
CREATE TABLE `seguridad.tblhistcontraseñas` (
  `IdHist` bigint(20) NOT NULL AUTO_INCREMENT,
  `IdUsuario` bigint(20) DEFAULT NULL,
  `Contraseña` varchar(255) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`IdHist`),
  KEY `IdUsuario` (`IdUsuario`),
  CONSTRAINT `ID_USUARIO_HIS` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of seguridad.tblhistcontraseñas
-- ----------------------------

-- ----------------------------
-- Table structure for seguridad.tblmsroles
-- ----------------------------
DROP TABLE IF EXISTS `seguridad.tblmsroles`;
CREATE TABLE `seguridad.tblmsroles` (
  `IdRol` bigint(20) NOT NULL AUTO_INCREMENT,
  `Rol` varchar(255) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `CreadoPor` varchar(255) DEFAULT '',
  `FechaModificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ModificadoPor` varchar(255) DEFAULT '',
  `IdUsuario` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdRol`),
  KEY `fk_roles2` (`Rol`),
  KEY `fk_creadopor` (`CreadoPor`),
  KEY `fk_modifcadopor` (`ModificadoPor`),
  KEY `fk_idUsuario` (`IdUsuario`),
  CONSTRAINT `fk_idUsuario` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of seguridad.tblmsroles
-- ----------------------------
INSERT INTO `seguridad.tblmsroles` VALUES ('1', 'Administrador', 'Permiso Absoluto', '2024-06-23 21:27:39', null, '2024-09-03 11:57:47', null, '10');
INSERT INTO `seguridad.tblmsroles` VALUES ('2', 'Secretaria', 'Notificación Mediante Correo De Recibido De Solicitudes', '2024-06-23 21:29:44', null, '2024-09-03 11:57:47', null, '10');
INSERT INTO `seguridad.tblmsroles` VALUES ('3', 'Auxiliar De Control Documental', 'Permiso a Módulo De Mantenimiento De Solicitudes', '2024-06-23 21:31:08', null, '2024-09-03 11:57:47', null, '10');
INSERT INTO `seguridad.tblmsroles` VALUES ('4', 'Encargado Del CES', 'Permiso a Módulo De Mantenimiento De Solicitudes,Consultas Y Asiganciones', '2024-06-23 21:32:13', null, '2024-09-03 11:57:47', null, '10');
INSERT INTO `seguridad.tblmsroles` VALUES ('5', 'Encargado Del CTC', 'Permiso a Módulo De Seguimiento Académico,Módulo Dictamen CTC', '2024-06-23 21:33:08', null, '2024-09-03 11:57:47', null, '10');
INSERT INTO `seguridad.tblmsroles` VALUES ('6', 'Analista Curricular', 'Permiso a Módulo De Seguimiento Académico,Módulo Dictamen CTC,Módulo De Mantenimiento De Solicitudes,Modulo Análisis Curricular', '2024-06-23 21:34:54', null, '2024-09-03 11:57:47', null, '10');
INSERT INTO `seguridad.tblmsroles` VALUES ('7', 'Jefes Departamento', 'Permiso a Formulario Ingreso Solicitud,Consulta Solicitudes,Seguimiento Académico,Mantenimiento Solicitudes,Reportes', '2024-06-23 21:36:40', null, '2024-09-03 11:57:47', null, '10');
INSERT INTO `seguridad.tblmsroles` VALUES ('8', 'Encargado De Digitalización', 'Permiso a Modulo De Mantenimiento De SolicitudesS', '2024-06-23 21:38:17', null, '2024-09-03 11:57:47', null, '10');
INSERT INTO `seguridad.tblmsroles` VALUES ('9', 'Instituto De Educación Superior', 'Permiso a Menú Vista Universidad,Formulario Ingreso De Solicitud, Consulta Solicitudes Universidad, Subsanación Documentos De La Solicitud,Dictamen CTC,Subir Adjuntos,Análisis Curricular,Emisión De Opinión Razonada,Plan De Estudios Registrado Firmado y Fo', '2024-06-23 21:40:49', null, '2024-09-03 11:57:47', null, '10');
INSERT INTO `seguridad.tblmsroles` VALUES ('10', 'Consejales', 'PARA MANTENIMIENTO USUARIO', '2024-07-28 19:52:36', null, '2024-09-03 11:57:47', null, '10');

-- ----------------------------
-- Table structure for seguridad.tblobjetos
-- ----------------------------
DROP TABLE IF EXISTS `seguridad.tblobjetos`;
CREATE TABLE `seguridad.tblobjetos` (
  `IdObjeto` bigint(20) NOT NULL AUTO_INCREMENT,
  `Objeto` varchar(50) NOT NULL,
  `TipoObjeto` varchar(50) DEFAULT '',
  `Descripcion` varchar(255) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `CreadoPor` varchar(50) DEFAULT NULL,
  `FechaModificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ModificadoPor` varchar(50) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`IdObjeto`),
  KEY `fk_usuariosobj` (`IdUsuario`),
  CONSTRAINT `fk_usuariosobj` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of seguridad.tblobjetos
-- ----------------------------
INSERT INTO `seguridad.tblobjetos` VALUES ('1', 'LOGIN INGRESAR', 'BOTON', 'CREADO PARA INICIO DE SESIÓN DE USUARIOS', '2024-06-21 19:54:59', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('2', 'LOGIN OLVIDASTE CONTRASEÑA', 'HREF', 'CREADO PARA RECUPERAR LA CONTRASEÑA OLVIDADA', '2024-06-21 20:00:52', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('3', 'INGRESO DE SOLICITUDES', 'BOTON', 'CREADO PARA LLENAR EL FORMULARIO INGRESO DE SOLICITUD', '2024-06-21 20:12:02', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('4', 'CONSULTAR SOLICTUDES', 'BOTON', 'CREADO PARA VER LAS CONSULTAS DE SOLICITUDES POR UNIVERSIDADES', '2024-06-21 20:13:04', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('5', 'GESTION DE SOLICITUDES', 'BOTON', 'CREADO PARA VER EL FORMULARIO INGRESO DE SOLICITUD EMPLEADO', '2024-06-21 20:15:19', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('6', 'CONSULTAR SOLICTUDES', 'BOTON', 'CREADO PARA VER LAS CONSULTA DE SOLICITUDES EMPLEDOS DES', '2024-06-21 20:16:11', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('7', 'SEGUIMIENTO ACADEMICO', 'BOTON', 'CREADO PARA CONSULTAR SOLICITUDES', '2024-06-21 20:57:13', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('8', 'MANTENIMIENTO SOLICITUDES', 'BOTON', 'CREADO PARA VER REVISION,ACUERDOS,DICTAMEN,ANALIS,ENTREGA DE PLAN DE ESTUDIOS', '2024-06-21 20:59:08', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('9', 'REPORTES ', 'BOTON', 'CREADO PARA GENERAR REPORTERIA', '2024-06-21 21:00:05', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('10', 'MANTENIMIENTO', 'BOTON', 'CREADO PARA MANEJAR EL CRUD A DEPARTAMENTOS,MUNICIPIOS,UNIVERSIDADES ETC.', '2024-06-21 21:01:45', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('11', 'SEGURIDAD', 'BOTON', 'CREADO PARA MANEJAR EL CRUD A USUARIOS,CONSEJALES,PERMISOS,ROLES ETC.', '2024-06-21 21:02:58', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('15', 'USUARIOS', 'HREF', 'PARA MANTENIMIENTO USUARIO', '2024-07-09 19:06:03', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('16', 'CONSEJALES', 'HREF', 'PARA MANTENIMIENTO CONSEJALES', '2024-07-09 19:07:01', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('17', 'PERMISOS', 'HREF', 'PARA MANTENIMIENTO PERMISOS', '2024-07-09 19:07:53', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('18', 'PARAMETROS', 'HREF', 'PARA MANTENIMIENTO PARAMETROS', '2024-07-09 19:08:57', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('19', 'ROLES', 'HREF', 'PARA MANTENIMIENTO ROLES', '2024-07-09 19:10:50', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('20', 'OBJETOS', 'HREF', 'PARA MANTENIMIENTO OBJETOS', '2024-07-09 19:11:07', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('21', 'BITACORA', 'HREF', 'PARA MANTENIMIENTO BITACORA', '2024-07-09 19:11:25', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('22', 'DEPARTAMENTOS', 'HREF', 'PARA MANTENIMIENTO DEPARTAMENTOS', '2024-07-09 19:54:40', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('23', 'MUNICIPIOS', 'HREF', 'PARA MANTENIMIENTO MUNICIPIOS', '2024-07-09 19:56:23', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('24', 'UNIVERSIDAD', 'HREF', 'PARA MANTENIMIENTO UNIVERSIDAD', '2024-07-09 20:13:01', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('25', 'CARRERAS', 'HREF', 'PARA MANTENIMIENTO CARRERAS', '2024-07-09 20:13:44', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('26', 'MODALIDAD', 'HREF', 'PARA MANTENIMIENTO MODALIDAD', '2024-07-09 20:14:19', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('27', 'GRADOSACADEMICOS', 'HREF', 'PARA MANTENIMIENTO GRADOS', '2024-07-09 20:15:08', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('28', 'TIPOSDESOLICITUD', 'HREF', 'PARA MANTENIMIENTO TIPO SOLICITUD', '2024-07-09 20:15:48', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('29', 'CATEGORIASOLICITUD', 'HREF', 'PARA MANTENIMIENTO CATEGORIA SOLICITUD', '2024-07-09 20:16:54', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('30', 'ESTADOSDESOLICITUD', 'HREF', 'PARA MANTENIMIENTO ESTADOS SOLICITUD', '2024-07-09 20:17:34', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('31', 'REVISION DE DOCUMENTACION', 'HREF', 'PARA REVISION DE DOCUMENTACION', '2024-08-02 18:40:14', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('32', 'ACUERDO DE ADMISION', 'HREF', 'PARA ACUERDO DE ADMISION', '2024-08-02 18:44:45', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('33', 'DICTAMEN CTC', 'HREF', 'PARA DICTAMEN CTC', '2024-08-02 18:49:35', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('34', 'ANALISIS CURRICULAR', 'HREF', 'Para analisis curricular', '2024-08-02 18:52:46', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('35', 'ACUERDO DE APROBACION', 'HREF', 'PARA ACUERDO DE APROBACION', '2024-08-02 18:54:22', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('36', 'ENTREGA PLAN DE ESTUDIO', 'HREF', 'PARA ENTREGA PLAN DE ESTUDIO', '2024-08-02 18:55:39', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('37', 'AGENDA SOLICITUDES AL CES', 'HREF', 'PARA AGENDA DE SOLICITUDES AL CES', '2024-08-02 18:58:55', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('38', 'ASIGNAR ACUERDO DE ADMISION CES', 'HREF', 'PARA ACUERDO DE ADMISION', '2024-08-02 19:02:34', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('39', 'AGENDA SOLICITUDES AL CTC', 'HREF', 'PARA AGENDA SOLICITUDES CTC', '2024-08-02 19:06:17', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('40', 'ASIGNAR DICTAMEN CTC', 'HREF', 'PARA DICTAMEN CTC', '2024-08-02 19:08:22', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('41', 'EMITIR OPINION RAZONADA', 'HREF', 'PARA EMITIR OPINION RAZONADA', '2024-08-02 19:13:06', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('42', 'REVISAR Y APROBAR OPINION RAZONADA', 'HREF', 'PARA REVISAR Y APROBAR OPINION RAZONADA', '2024-08-02 19:14:52', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('43', 'AGENDA SOLICITUDES AL CES (ACUERDO APROBACION)', 'HREF', 'PARA AGENDA SOLICITUD, ACUERDO APROBACION', '2024-08-02 19:18:48', null, '2024-09-03 11:58:14', null, '10');
INSERT INTO `seguridad.tblobjetos` VALUES ('44', 'ASIGNAR ACUERDO DE ADMISION CES (ACUERDO APROBACIO', 'HREF', 'PARA ASIGNAR ACUERDO DE ADMISION CES DE ACUERDO APROBACION', '2024-08-02 19:21:40', null, '2024-09-03 11:58:14', null, '10');

-- ----------------------------
-- Table structure for seguridad.tblparametros
-- ----------------------------
DROP TABLE IF EXISTS `seguridad.tblparametros`;
CREATE TABLE `seguridad.tblparametros` (
  `IdParametro` bigint(20) NOT NULL AUTO_INCREMENT,
  `Parametro` varchar(100) NOT NULL,
  `Valor` varchar(100) DEFAULT NULL,
  `IdUsuario` bigint(20) DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `CreadoPor` varchar(50) DEFAULT NULL,
  `FechaModificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ModificadoPor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`IdParametro`),
  KEY `ID_USUARIO_PM` (`IdUsuario`),
  CONSTRAINT `ID_USUARIO_PM` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of seguridad.tblparametros
-- ----------------------------
INSERT INTO `seguridad.tblparametros` VALUES ('1', 'Max_Login_Intentos', '3', '11', '2024-07-08 15:34:15', 'Edwin Aron Martinez', '2024-07-08 15:34:15', null);
INSERT INTO `seguridad.tblparametros` VALUES ('2', 'Max_Clave_Validacion', '1', '11', '2024-07-14 12:19:57', 'Edwin Aron Martinez', '2024-07-18 20:45:43', null);
INSERT INTO `seguridad.tblparametros` VALUES ('3', 'Max_Cod_Validacion', '1', '11', '2024-07-14 12:20:22', 'Edwin Aron Martinez', '2024-07-18 16:15:41', null);

-- ----------------------------
-- Table structure for seguridad.tblpermisos
-- ----------------------------
DROP TABLE IF EXISTS `seguridad.tblpermisos`;
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
  `ModificadoPor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`IdPermiso`),
  KEY `IdRol` (`IdRol`),
  KEY `fk_id_objeto` (`IdObjeto`),
  CONSTRAINT `fk_id_msroles` FOREIGN KEY (`IdRol`) REFERENCES `seguridad.tblmsroles` (`IdRol`) ON UPDATE CASCADE,
  CONSTRAINT `fk_id_objeto` FOREIGN KEY (`IdObjeto`) REFERENCES `seguridad.tblobjetos` (`IdObjeto`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of seguridad.tblpermisos
-- ----------------------------
INSERT INTO `seguridad.tblpermisos` VALUES ('36', '1', '17', '0', '0', '0', '1', '2024-07-21 13:54:42', null, '2024-08-12 17:54:06', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('68', '1', '3', '0', '0', '0', '0', '2024-08-02 18:34:02', null, '2024-08-02 18:34:02', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('69', '1', '4', '0', '0', '0', '0', '2024-08-02 18:34:02', null, '2024-08-02 18:34:02', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('70', '1', '7', '0', '0', '0', '0', '2024-08-02 18:34:02', null, '2024-08-02 18:34:02', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('71', '1', '8', '0', '0', '0', '0', '2024-08-02 18:34:02', null, '2024-08-02 18:34:02', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('72', '1', '9', '0', '0', '0', '0', '2024-08-02 18:34:02', null, '2024-08-02 18:34:02', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('73', '1', '10', '0', '0', '0', '0', '2024-08-02 18:34:02', null, '2024-08-02 18:34:02', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('75', '1', '21', '0', '0', '0', '0', '2024-08-02 18:43:07', null, '2024-08-02 18:43:07', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('76', '1', '31', '0', '0', '0', '0', '2024-08-02 18:56:42', null, '2024-08-02 18:56:42', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('77', '1', '32', '0', '0', '0', '0', '2024-08-02 18:57:06', null, '2024-08-02 18:57:06', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('78', '1', '33', '0', '0', '0', '0', '2024-08-02 19:04:52', null, '2024-08-02 19:04:52', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('79', '1', '34', '0', '0', '0', '0', '2024-08-02 19:11:16', null, '2024-08-02 19:11:16', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('80', '1', '35', '0', '0', '0', '0', '2024-08-02 19:16:10', null, '2024-08-02 19:16:10', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('81', '1', '36', '0', '0', '0', '0', '2024-08-02 19:24:08', null, '2024-08-02 19:24:08', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('82', '1', '15', '0', '0', '0', '0', '2024-08-04 13:34:35', null, '2024-08-04 13:34:35', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('83', '1', '11', '0', '0', '0', '0', '2024-08-04 13:35:05', null, '2024-08-04 13:35:05', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('84', '1', '1', '0', '0', '0', '0', '2024-08-06 12:33:42', null, '2024-08-06 12:33:42', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('85', '1', '2', '0', '0', '0', '0', '2024-08-06 12:33:42', null, '2024-08-06 12:33:42', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('86', '1', '5', '0', '0', '0', '0', '2024-08-06 12:34:16', null, '2024-08-06 12:34:16', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('87', '1', '6', '0', '0', '0', '0', '2024-08-06 12:34:16', null, '2024-08-06 12:34:16', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('88', '1', '16', '0', '0', '0', '0', '2024-08-06 12:34:41', null, '2024-08-06 12:34:41', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('89', '1', '18', '0', '0', '0', '0', '2024-08-06 12:34:41', null, '2024-08-06 12:34:41', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('90', '1', '19', '0', '0', '0', '0', '2024-08-06 12:34:41', null, '2024-08-06 12:34:41', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('91', '1', '20', '0', '0', '0', '0', '2024-08-06 12:34:41', null, '2024-08-06 12:34:41', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('92', '1', '25', '0', '0', '0', '0', '2024-08-06 23:29:08', null, '2024-08-06 23:29:08', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('93', '1', '22', '0', '0', '0', '0', '2024-08-10 20:16:06', null, '2024-08-10 20:16:06', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('94', '1', '24', '0', '0', '0', '0', '2024-08-10 20:16:06', null, '2024-08-10 20:16:06', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('95', '1', '26', '0', '0', '0', '0', '2024-08-10 20:16:06', null, '2024-08-10 20:16:06', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('96', '1', '27', '0', '0', '0', '0', '2024-08-10 20:16:06', null, '2024-08-10 20:16:06', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('97', '1', '28', '0', '0', '0', '0', '2024-08-10 20:16:06', null, '2024-08-10 20:16:06', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('98', '1', '29', '0', '0', '0', '0', '2024-08-10 20:16:06', null, '2024-08-10 20:16:06', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('99', '1', '30', '0', '0', '0', '0', '2024-08-10 20:16:06', null, '2024-08-10 20:16:06', null);
INSERT INTO `seguridad.tblpermisos` VALUES ('101', '1', '23', '0', '0', '0', '0', '2024-08-10 20:16:48', null, '2024-08-10 20:16:48', null);

-- ----------------------------
-- Table structure for seguridad.tblresetcontraseñas
-- ----------------------------
DROP TABLE IF EXISTS `seguridad.tblresetcontraseñas`;
CREATE TABLE `seguridad.tblresetcontraseñas` (
  `IdReset` int(11) NOT NULL AUTO_INCREMENT,
  `CorreoElectronico` varchar(100) NOT NULL,
  `CodigoValidacion` int(11) NOT NULL,
  `Token` varchar(200) NOT NULL,
  `FechaVencimiento` timestamp NOT NULL DEFAULT current_timestamp(),
  `ContraseñaTemp` varchar(11) NOT NULL,
  PRIMARY KEY (`IdReset`),
  KEY `fk_correo` (`CorreoElectronico`),
  CONSTRAINT `fk_correo` FOREIGN KEY (`CorreoElectronico`) REFERENCES `seguridad.tblusuarios_personal` (`CorreoElectronico`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of seguridad.tblresetcontraseñas
-- ----------------------------

-- ----------------------------
-- Table structure for seguridad.tblusuarios
-- ----------------------------
DROP TABLE IF EXISTS `seguridad.tblusuarios`;
CREATE TABLE `seguridad.tblusuarios` (
  `IdUsuario` bigint(20) NOT NULL AUTO_INCREMENT,
  `Contraseña` varchar(255) NOT NULL,
  `IdRol` bigint(20) DEFAULT NULL,
  `FechaUltimaConexion` datetime DEFAULT NULL,
  `FechaCreacion` datetime DEFAULT current_timestamp(),
  `CreadoPor` varchar(50) DEFAULT NULL,
  `FechaModificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ModificadoPor` varchar(50) DEFAULT NULL,
  `PrimerIngreso` bigint(20) DEFAULT NULL,
  `FechaVencimiento` datetime DEFAULT NULL,
  `RegistroClave` varchar(255) DEFAULT NULL,
  `EstadoUsuario` bigint(20) NOT NULL,
  `NumIntentos` bigint(20) DEFAULT 0,
  `IdUniversidad` bigint(20) NOT NULL,
  PRIMARY KEY (`IdUsuario`),
  KEY `fk_id_estado` (`EstadoUsuario`),
  KEY `fk_id_universidad` (`IdUniversidad`),
  KEY `fk_id_rol` (`IdRol`),
  CONSTRAINT `fk_id_estado` FOREIGN KEY (`EstadoUsuario`) REFERENCES `mantenimiento.tblestadosusuarios` (`IdEstado`) ON UPDATE CASCADE,
  CONSTRAINT `fk_id_rol` FOREIGN KEY (`IdRol`) REFERENCES `seguridad.tblmsroles` (`IdRol`) ON UPDATE CASCADE,
  CONSTRAINT `fk_id_universidad` FOREIGN KEY (`IdUniversidad`) REFERENCES `mantenimiento.tbluniversidadescentros` (`IdUniversidad`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of seguridad.tblusuarios
-- ----------------------------
INSERT INTO `seguridad.tblusuarios` VALUES ('1', '$10$D8f0RPyz.pVTAkdeH2RE8em8MY01Gg6m4t./fopYlk9TJtwdzW9w2', '1', '2024-08-07 22:43:44', '2024-06-23 19:21:57', 'Edwin Martinez', '2024-08-31 17:26:30', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('2', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '1', null, '2024-06-24 20:51:11', '1', '2024-08-31 17:24:08', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('3', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '3', null, '2024-06-24 20:53:15', '1', '2024-08-31 17:25:46', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('4', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '3', null, '2024-06-24 20:54:28', '1', '2024-08-31 17:26:08', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('5', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '5', null, '2024-06-24 20:55:17', '1', '2024-08-31 17:25:46', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('6', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '6', null, '2024-06-24 20:58:05', '1', '2024-08-31 17:25:46', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('7', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '7', null, '2024-06-24 20:58:51', '1', '2024-08-31 17:25:46', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('8', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '8', null, '2024-06-24 21:00:03', '1', '2024-08-31 17:25:46', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('9', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '10', null, '2024-06-24 21:01:59', '1', '2024-08-31 17:25:46', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('10', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '1', '2024-08-10 19:23:34', '2024-06-25 00:20:36', '11', '2024-08-31 17:25:46', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('11', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '1', null, '2024-06-27 12:47:55', '11', '2024-08-31 17:25:46', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('12', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '1', null, '2024-06-27 12:49:08', '11', '2024-08-31 17:25:46', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('13', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '1', null, '2024-06-27 12:50:39', '11', '2024-08-31 17:25:46', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('14', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '1', null, '2024-06-27 12:51:52', '11', '2024-08-31 17:25:46', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('15', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '1', null, '2024-06-27 12:52:55', '11', '2024-08-31 17:25:46', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('16', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '1', null, '2024-06-28 11:10:11', '11', '2024-08-31 17:25:46', null, null, null, null, '1', '0', '1');
INSERT INTO `seguridad.tblusuarios` VALUES ('17', '$2y$10$6qh1xOJsc78mzaRFMrrnzOljCQWSsGgpVm9AkelfCI.6bMkM/Wg5.', '1', '2024-08-07 22:43:44', '2024-06-23 19:21:57', '11', '2024-08-31 17:26:30', null, null, null, null, '1', '0', '1');

-- ----------------------------
-- Table structure for seguridad.tblusuarios_personal
-- ----------------------------
DROP TABLE IF EXISTS `seguridad.tblusuarios_personal`;
CREATE TABLE `seguridad.tblusuarios_personal` (
  `IdUsuarioPersonal` bigint(20) NOT NULL AUTO_INCREMENT,
  `IdUsuario` bigint(20) NOT NULL,
  `NombreUsuario` varchar(100) NOT NULL,
  `NumIdentidad` varchar(50) NOT NULL,
  `NumEmpleado` bigint(50) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `EmpleadoDes` bigint(50) NOT NULL,
  `Direccion` varchar(255) NOT NULL,
  `CorreoElectronico` varchar(100) NOT NULL,
  PRIMARY KEY (`IdUsuarioPersonal`),
  KEY `fk_id_usuario` (`IdUsuario`),
  KEY `CorreoElectronico` (`CorreoElectronico`),
  CONSTRAINT `fk_id_usuario` FOREIGN KEY (`IdUsuario`) REFERENCES `seguridad.tblusuarios` (`IdUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of seguridad.tblusuarios_personal
-- ----------------------------
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('1', '1', 'Administrador Prueba', '1234567891234', '1', 'Admin Prueba', '1', 'Calle Principal #123', 'usuario@unah.hn');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('2', '2', 'Secretaria DES', '0801200020330', '1', 'Secretaria', '1', 'Col.Loarque', 'secretaria@unah.hn');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('3', '3', 'Auxiliar Control', '0801200020331', '1', 'Auxiliar', '1', 'Col.Loarque', 'auxiliar@unah.hn');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('4', '4', 'Encargado CES', '0801200020332', '1', 'CES', '1', 'Col.Cao', 'ces@unah.hn');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('5', '5', 'Encargado CTC', '0801200020333', '1', 'CTC', '1', 'Col.Loarque', 'ctc@unah.hn');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('6', '6', 'Analista', '0801200020334', '1', 'Curricular', '1', 'Col.Loarque', 'analistac@unah.hn');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('7', '7', 'Jefe Departamento', '0801200020335', '1', 'Jefe', '1', 'Col.Cao', 'jefe@unah.hn');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('8', '8', 'Encargado Digitalización', '0801200020336', '1', 'Digitalización ', '1', 'Col.Loarque', 'digitalizacion@unah.hn');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('9', '9', 'Consejales ', '0801200020337', '1', 'Consejal', '1', 'Col.Loarque', 'consejales@unah.hn');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('10', '10', 'Edwin Aron Martinez', '0801199920332', '1', 'Adminstrador E', '1', 'Col.Cao', 'aron.martinez@unah.hn');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('11', '11', 'Kevin Antonio Castro', '0801200020338', '1', 'Adminstrador KC', '1', 'Col. Alemán', 'kacastroe@unah.hn');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('12', '12', 'Anthony Jafed Andino', '0801200020339', '1', 'Adminstrador A', '1', 'Col. Aleman', 'anthonyandino@unah.hn');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('13', '13', 'Danilo Edgardo Sosa', '0801200120331', '1', 'Adminstrador D', '1', 'Col.Los Girasoles', 'sosadanilo174@gmail.com');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('14', '14', 'Kevin Alexander Euceda', '0801200120332', '1', 'Adminstrador KE', '1', 'Col.Soledad', 'keucedaa@unah.hn');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('15', '15', 'JhonathanIsaacGuillena', '0801200120333', '1', 'AdminstradorIa', '1', 'Col.San José de la Vegaaaa', 'jhonathan.guillen@unah.hna');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('16', '16', 'SigaesAdmin', '0801200120334', '1', 'Administradorr', '1', 'UNAH', 'sigaesunah@gmail.comm');
INSERT INTO `seguridad.tblusuarios_personal` VALUES ('17', '17', 'KCastro', '0801200120335', '1', 'Castro99', '1', 'Col Aleman', 'kcastro0517@hotmail.com');
