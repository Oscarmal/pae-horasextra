/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50516
Source Host           : localhost:3306
Source Database       : pae_he_00

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2014-11-11 17:46:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cat_conceptos
-- ----------------------------
DROP TABLE IF EXISTS `cat_conceptos`;
CREATE TABLE `cat_conceptos` (
  `id_concepto` tinyint(2) NOT NULL AUTO_INCREMENT,
  `concepto` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `clave` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `valor` smallint(4) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_concepto`),
  KEY `i_clave` (`clave`),
  KEY `i_activo` (`activo`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of cat_conceptos
-- ----------------------------
INSERT INTO `cat_conceptos` VALUES ('1', 'SIMPLES', '1', '1', '0');
INSERT INTO `cat_conceptos` VALUES ('2', 'DOBLES', 'P016', '2', '1');
INSERT INTO `cat_conceptos` VALUES ('3', 'TRIPLES', 'P019', '3', '1');

-- ----------------------------
-- Table structure for cat_seguimiento
-- ----------------------------
DROP TABLE IF EXISTS `cat_seguimiento`;
CREATE TABLE `cat_seguimiento` (
  `id_cat_seguimiento` mediumint(7) NOT NULL AUTO_INCREMENT,
  `tipo` enum('ACCION','ESTATUS') COLLATE utf8_spanish_ci DEFAULT 'ACCION',
  `descripcion` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `siglas` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_cat_seguimiento`),
  KEY `i_tipo` (`tipo`),
  KEY `i_activo` (`activo`),
  KEY `i_usuario` (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of cat_seguimiento
-- ----------------------------
INSERT INTO `cat_seguimiento` VALUES ('1', 'ACCION', 'CAPTURADO', 'CAP', '2014-08-29 16:48:06', '1', '1');
INSERT INTO `cat_seguimiento` VALUES ('2', 'ACCION', 'VALIDADO', 'VAL', '2014-08-29 16:48:25', '1', '1');
INSERT INTO `cat_seguimiento` VALUES ('3', 'ACCION', 'AUTORIZADO', 'AUT', '2014-08-29 16:49:54', '1', '1');
INSERT INTO `cat_seguimiento` VALUES ('4', 'ACCION', 'ENVIADO', 'ENV', '2014-08-29 16:50:06', '1', '1');
INSERT INTO `cat_seguimiento` VALUES ('5', 'ESTATUS', 'EN PROCESO', 'PROC', '2014-08-29 16:50:25', '1', '1');
INSERT INTO `cat_seguimiento` VALUES ('6', 'ESTATUS', 'APROBADO', 'APRO', '2014-08-29 16:51:03', '1', '1');
INSERT INTO `cat_seguimiento` VALUES ('7', 'ESTATUS', 'RECHAZADO', 'RECH', '2014-08-29 16:51:18', '1', '1');
INSERT INTO `cat_seguimiento` VALUES ('8', 'ESTATUS', 'CANCELADO', 'CANC', '2014-08-29 16:52:39', '1', '1');
INSERT INTO `cat_seguimiento` VALUES ('9', 'ESTATUS', 'CERRADO', 'CERR', '2014-08-29 16:52:49', '1', '1');
INSERT INTO `cat_seguimiento` VALUES ('10', 'ESTATUS', 'REALIZADO', 'REAL', '2014-08-29 16:53:07', '1', '1');
INSERT INTO `cat_seguimiento` VALUES ('11', 'ESTATUS', 'PENDIENTE', 'PEND', '2014-08-29 16:53:22', '1', '1');

-- ----------------------------
-- Table structure for he_autorizaciones
-- ----------------------------
DROP TABLE IF EXISTS `he_autorizaciones`;
CREATE TABLE `he_autorizaciones` (
  `id_autorizacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_horas_extra` int(11) NOT NULL,
  `anio` smallint(4) DEFAULT NULL,
  `semana` tinyint(2) DEFAULT NULL,
  `horas` time DEFAULT NULL,
  `id_concepto` tinyint(2) DEFAULT NULL,
  `estatus` enum('ACEPTADO','RECHAZADO') COLLATE utf8_spanish_ci DEFAULT NULL,
  `xls` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_autorizacion`),
  KEY `i_id_concepto` (`id_concepto`),
  KEY `i_id_usuario` (`id_horas_extra`),
  KEY `i_activo` (`activo`),
  KEY `i_estaus` (`estatus`) USING BTREE,
  KEY `i_anio_semana` (`anio`,`semana`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=439 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of he_autorizaciones
-- ----------------------------
INSERT INTO `he_autorizaciones` VALUES ('409', '24', '2014', '52', '01:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141030-120219.xls', '4', '2014-10-24 10:53:40', '1');
INSERT INTO `he_autorizaciones` VALUES ('401', '39', '2014', '52', '01:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_03_20141021-145930.xls', '3', '2014-10-21 14:11:08', '1');
INSERT INTO `he_autorizaciones` VALUES ('402', '51', '2014', '52', '22:00:00', '0', 'RECHAZADO', 'HE_Horas-Extra_02_20141021-152124.xls', '1', '2014-10-21 14:12:47', '1');
INSERT INTO `he_autorizaciones` VALUES ('403', '48', '2014', '52', '05:00:00', '3', 'ACEPTADO', null, '3', '2014-10-21 14:30:37', '1');
INSERT INTO `he_autorizaciones` VALUES ('404', '60', '2014', '52', '01:00:00', '0', 'RECHAZADO', null, '3', '2014-10-21 14:30:45', '1');
INSERT INTO `he_autorizaciones` VALUES ('405', '60', '2014', '52', '03:00:00', '2', 'ACEPTADO', null, '3', '2014-10-21 14:30:45', '1');
INSERT INTO `he_autorizaciones` VALUES ('406', '29', '2014', '52', '07:00:00', '0', 'RECHAZADO', 'HE_Horas-Extra_02_20141021-151919.xls', '3', '2014-10-21 14:35:30', '1');
INSERT INTO `he_autorizaciones` VALUES ('407', '30', '2014', '52', '02:00:00', '0', 'RECHAZADO', 'HE_Horas-Extra_02_20141021-151919.xls', '3', '2014-10-21 15:18:52', '1');
INSERT INTO `he_autorizaciones` VALUES ('408', '30', '2014', '52', '08:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141021-151919.xls', '3', '2014-10-21 15:18:52', '1');
INSERT INTO `he_autorizaciones` VALUES ('410', '20', '2014', '52', '04:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141030-120219.xls', '1', '2014-10-29 11:51:16', '1');
INSERT INTO `he_autorizaciones` VALUES ('411', '21', '2014', '52', '06:00:00', '2', 'ACEPTADO', null, '1', '2014-10-29 11:54:05', '1');
INSERT INTO `he_autorizaciones` VALUES ('412', '23', '2014', '52', '06:00:00', '3', 'ACEPTADO', null, '1', '2014-10-29 11:55:22', '1');
INSERT INTO `he_autorizaciones` VALUES ('413', '25', '2014', '52', '02:00:00', '2', 'ACEPTADO', null, '1', '2014-10-29 11:58:38', '1');
INSERT INTO `he_autorizaciones` VALUES ('414', '26', '2014', '52', '03:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141030-120219.xls', '1', '2014-10-29 11:59:41', '1');
INSERT INTO `he_autorizaciones` VALUES ('415', '28', '2013', '43', '02:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141030-120219.xls', '1', '2014-10-29 12:00:29', '1');
INSERT INTO `he_autorizaciones` VALUES ('416', '27', '2014', '39', '01:00:00', '0', 'RECHAZADO', null, '1', '2014-10-29 12:00:54', '1');
INSERT INTO `he_autorizaciones` VALUES ('417', '27', '2014', '39', '02:00:00', '2', 'ACEPTADO', null, '1', '2014-10-29 12:00:54', '1');
INSERT INTO `he_autorizaciones` VALUES ('418', '27', '2014', '39', '01:00:00', '3', 'ACEPTADO', null, '1', '2014-10-29 12:00:54', '1');
INSERT INTO `he_autorizaciones` VALUES ('419', '22', '2014', '1', '04:00:00', '0', 'RECHAZADO', 'HE_Horas-Extra_02_20141030-120219.xls', '1', '2014-10-29 12:02:09', '1');
INSERT INTO `he_autorizaciones` VALUES ('420', '22', '2014', '1', '01:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141030-120219.xls', '1', '2014-10-29 12:02:09', '1');
INSERT INTO `he_autorizaciones` VALUES ('421', '34', '2014', '39', '03:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141103-101059.xls', '3', '2014-10-30 12:04:54', '1');
INSERT INTO `he_autorizaciones` VALUES ('422', '35', '2014', '40', '01:00:00', '2', 'ACEPTADO', null, '1', '2014-11-03 10:04:16', '1');
INSERT INTO `he_autorizaciones` VALUES ('423', '54', '2014', '36', '04:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141103-102011.xls', '3', '2014-11-03 10:10:51', '1');
INSERT INTO `he_autorizaciones` VALUES ('424', '70', '2014', '41', '01:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141104-120346.xls', '3', '2014-11-04 12:02:32', '1');
INSERT INTO `he_autorizaciones` VALUES ('425', '70', '2014', '41', '04:00:00', '3', 'ACEPTADO', 'HE_Horas-Extra_02_20141104-120346.xls', '3', '2014-11-04 12:02:32', '1');
INSERT INTO `he_autorizaciones` VALUES ('426', '40', '2012', '32', '02:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141104-120346.xls', '3', '2014-11-04 12:03:41', '1');
INSERT INTO `he_autorizaciones` VALUES ('427', '46', '2014', '40', '02:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141111-115339.xls', '3', '2014-11-04 12:05:15', '1');
INSERT INTO `he_autorizaciones` VALUES ('428', '76', '2013', '28', '02:00:00', '0', 'RECHAZADO', 'HE_Horas-Extra_02_20141111-115339.xls', '3', '2014-11-11 11:52:50', '1');
INSERT INTO `he_autorizaciones` VALUES ('429', '76', '2013', '28', '03:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141111-115339.xls', '3', '2014-11-11 11:52:50', '1');
INSERT INTO `he_autorizaciones` VALUES ('430', '76', '2013', '28', '02:00:00', '3', 'ACEPTADO', 'HE_Horas-Extra_02_20141111-115339.xls', '3', '2014-11-11 11:52:50', '1');
INSERT INTO `he_autorizaciones` VALUES ('431', '75', '2012', '10', '04:00:00', '0', 'RECHAZADO', 'HE_Horas-Extra_02_20141111-115339.xls', '3', '2014-11-11 11:53:03', '1');
INSERT INTO `he_autorizaciones` VALUES ('432', '75', '2012', '10', '01:00:00', '3', 'ACEPTADO', 'HE_Horas-Extra_02_20141111-115339.xls', '3', '2014-11-11 11:53:03', '1');
INSERT INTO `he_autorizaciones` VALUES ('433', '74', '2013', '19', '02:00:00', '0', 'RECHAZADO', 'HE_Horas-Extra_02_20141111-115339.xls', '3', '2014-11-11 11:53:20', '1');
INSERT INTO `he_autorizaciones` VALUES ('434', '74', '2013', '19', '09:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141111-115339.xls', '3', '2014-11-11 11:53:20', '1');
INSERT INTO `he_autorizaciones` VALUES ('435', '74', '2013', '19', '07:00:00', '3', 'ACEPTADO', 'HE_Horas-Extra_02_20141111-115339.xls', '3', '2014-11-11 11:53:20', '1');
INSERT INTO `he_autorizaciones` VALUES ('436', '73', '2012', '10', '01:00:00', '0', 'RECHAZADO', 'HE_Horas-Extra_02_20141111-115339.xls', '3', '2014-11-11 11:53:33', '1');
INSERT INTO `he_autorizaciones` VALUES ('437', '73', '2012', '10', '03:00:00', '2', 'ACEPTADO', 'HE_Horas-Extra_02_20141111-115339.xls', '3', '2014-11-11 11:53:33', '1');
INSERT INTO `he_autorizaciones` VALUES ('438', '73', '2012', '10', '04:00:00', '3', 'ACEPTADO', 'HE_Horas-Extra_02_20141111-115339.xls', '3', '2014-11-11 11:53:33', '1');

-- ----------------------------
-- Table structure for he_empresas
-- ----------------------------
DROP TABLE IF EXISTS `he_empresas`;
CREATE TABLE `he_empresas` (
  `id_empresa` smallint(4) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `siglas` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `rfc` varchar(18) COLLATE utf8_spanish_ci DEFAULT NULL,
  `razon` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `direccion` text COLLATE utf8_spanish_ci,
  `pais` varchar(15) COLLATE utf8_spanish_ci DEFAULT 'MX',
  `email` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_empresa`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of he_empresas
-- ----------------------------
INSERT INTO `he_empresas` VALUES ('1', 'ISolution', 'IS', 'x', 'Intelligent Solution', 'Insurgentes Sur', 'MX', 'oscar.maldonado@isollution.mx', '2014-08-28 18:10:49', '1', '1');
INSERT INTO `he_empresas` VALUES ('2', 'Otra empresa', 'Otra', 'x', 'Empresa de prueba', 'x', 'MX', 'x', '2014-10-21 11:53:26', '1', '1');
INSERT INTO `he_empresas` VALUES ('3', 'Tercer Empresa', 'Tercer', 'x', 'Tercer empresa INC.', 'x', 'MX', 'x', '2014-10-21 12:03:21', '1', '1');

-- ----------------------------
-- Table structure for he_horas_extra
-- ----------------------------
DROP TABLE IF EXISTS `he_horas_extra`;
CREATE TABLE `he_horas_extra` (
  `id_horas_extra` int(11) NOT NULL AUTO_INCREMENT,
  `id_personal` int(11) NOT NULL,
  `id_empresa` smallint(4) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `horas` time DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_horas_extra`),
  KEY `i_id_usuario` (`id_personal`),
  KEY `i_activo` (`activo`),
  KEY `i_id_personal` (`id_personal`),
  KEY `i_id_empresa` (`id_empresa`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of he_horas_extra
-- ----------------------------
INSERT INTO `he_horas_extra` VALUES ('20', '2', '2', '2014-10-08', '04:00:00', '2', '2014-10-10 12:15:50', '1');
INSERT INTO `he_horas_extra` VALUES ('21', '1', '1', '2014-10-05', '06:00:00', '1', '2014-10-10 12:15:59', '1');
INSERT INTO `he_horas_extra` VALUES ('22', '2', '2', '2014-10-10', '05:00:00', '2', '2014-10-10 12:16:22', '1');
INSERT INTO `he_horas_extra` VALUES ('23', '1', '1', '2014-09-25', '06:00:00', '1', '2014-10-10 12:16:33', '1');
INSERT INTO `he_horas_extra` VALUES ('24', '2', '2', '2014-09-29', '01:30:00', '2', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('25', '1', '1', '2014-09-25', '02:00:00', '1', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('26', '2', '2', '2014-09-01', '03:00:00', '2', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('27', '1', '1', '2014-09-25', '04:00:00', '1', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('28', '2', '2', '2013-09-19', '02:45:00', '2', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('29', '3', '2', '2014-09-25', '07:00:00', '3', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('30', '2', '2', '2014-09-29', '10:00:00', '2', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('31', '3', '2', '2014-09-25', '22:00:00', '3', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('32', '1', '1', '2014-09-11', '15:00:00', '1', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('33', '3', '2', '2014-09-23', '00:30:00', '3', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('34', '2', '2', '2014-09-25', '03:30:00', '2', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('35', '3', '2', '2014-09-30', '01:00:00', '3', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('36', '1', '1', '2014-10-07', '06:00:00', '1', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('37', '3', '2', '2014-10-09', '06:00:00', '3', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('38', '1', '1', '2014-09-25', '03:00:00', '1', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('39', '4', '3', '2014-09-18', '01:00:00', '4', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('40', '3', '2', '2012-08-12', '02:00:00', '3', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('41', '1', '1', '2014-09-18', '03:00:00', '1', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('42', '4', '3', '2014-10-06', '01:00:00', '4', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('43', '1', '1', '2014-09-08', '02:00:00', '1', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('44', '1', '1', '2014-09-23', '01:00:00', '1', '2014-10-10 12:16:41', '1');
INSERT INTO `he_horas_extra` VALUES ('45', '4', '3', '2014-09-30', '03:35:00', '4', '2014-10-10 12:31:56', '1');
INSERT INTO `he_horas_extra` VALUES ('46', '3', '2', '2014-10-04', '02:00:00', '3', '2014-10-10 14:30:57', '1');
INSERT INTO `he_horas_extra` VALUES ('47', '3', '2', '2014-10-07', '05:00:00', '3', '2014-10-10 14:34:24', '1');
INSERT INTO `he_horas_extra` VALUES ('48', '4', '3', '2014-09-30', '05:00:00', '4', '2014-10-10 14:34:58', '1');
INSERT INTO `he_horas_extra` VALUES ('49', '3', '2', '2014-09-29', '02:00:00', '3', '2014-10-10 14:35:39', '1');
INSERT INTO `he_horas_extra` VALUES ('50', '2', '2', '2014-08-05', '07:00:00', '2', '2014-10-10 14:36:51', '1');
INSERT INTO `he_horas_extra` VALUES ('51', '2', '2', '2014-10-10', '22:00:00', '2', '2014-10-10 17:49:20', '1');
INSERT INTO `he_horas_extra` VALUES ('52', '1', '1', '2014-10-07', '21:00:00', '1', '2014-10-10 17:50:00', '1');
INSERT INTO `he_horas_extra` VALUES ('53', '1', '1', '2014-10-08', '05:00:00', '1', '2014-10-10 17:52:15', '1');
INSERT INTO `he_horas_extra` VALUES ('54', '2', '2', '2014-09-03', '04:00:00', '2', '2014-10-13 10:40:11', '1');
INSERT INTO `he_horas_extra` VALUES ('55', '2', '2', '2014-09-24', '05:00:00', '2', '2014-10-13 10:41:11', '1');
INSERT INTO `he_horas_extra` VALUES ('56', '2', '2', '2014-09-04', '02:00:00', '2', '2014-10-13 10:42:26', '1');
INSERT INTO `he_horas_extra` VALUES ('57', '1', '1', '2014-10-01', '02:00:00', '1', '2014-10-20 13:38:26', '1');
INSERT INTO `he_horas_extra` VALUES ('58', '1', '1', '2014-09-29', '04:00:00', '1', '2014-10-20 13:50:19', '1');
INSERT INTO `he_horas_extra` VALUES ('59', '2', '2', '2014-09-28', '12:00:00', '2', '2014-10-20 13:50:50', '1');
INSERT INTO `he_horas_extra` VALUES ('60', '3', '3', '2014-10-07', '04:00:00', '3', '2014-10-21 12:07:12', '1');
INSERT INTO `he_horas_extra` VALUES ('61', '3', '3', '2014-09-02', '02:00:00', '3', '2014-10-21 12:07:27', '1');
INSERT INTO `he_horas_extra` VALUES ('62', '2', '2', '2014-10-14', '01:00:00', '2', '2014-10-21 13:01:05', '1');
INSERT INTO `he_horas_extra` VALUES ('63', '2', '2', '2014-08-05', '04:00:00', '2', '2014-10-21 13:01:12', '1');
INSERT INTO `he_horas_extra` VALUES ('64', '2', '2', '2014-09-26', '05:00:00', '2', '2014-10-21 13:01:21', '1');
INSERT INTO `he_horas_extra` VALUES ('65', '2', '2', '2014-07-18', '07:00:00', '2', '2014-10-21 13:01:29', '1');
INSERT INTO `he_horas_extra` VALUES ('66', '2', '2', '2014-07-14', '03:00:00', '2', '2014-10-21 13:01:42', '1');
INSERT INTO `he_horas_extra` VALUES ('67', '4', '2', '2014-09-18', '07:00:00', '4', '2014-10-21 13:03:03', '1');
INSERT INTO `he_horas_extra` VALUES ('68', '3', '2', '2014-08-12', '03:00:00', '3', '2014-10-21 13:16:28', '1');
INSERT INTO `he_horas_extra` VALUES ('69', '3', '3', '2014-10-15', '01:00:00', '3', '2014-10-21 14:33:53', '1');
INSERT INTO `he_horas_extra` VALUES ('70', '2', '2', '2014-10-07', '05:00:00', '2', '2014-11-04 11:55:56', '1');
INSERT INTO `he_horas_extra` VALUES ('71', '1', '1', '2014-04-11', '11:00:00', '1', '2014-11-11 11:09:46', '1');
INSERT INTO `he_horas_extra` VALUES ('72', '1', '1', '2014-11-11', '03:00:00', '1', '2014-11-11 11:10:04', '1');
INSERT INTO `he_horas_extra` VALUES ('73', '2', '2', '2012-03-11', '08:00:00', '2', '2014-11-11 11:51:44', '1');
INSERT INTO `he_horas_extra` VALUES ('74', '2', '2', '2013-05-11', '18:00:00', '2', '2014-11-11 11:51:55', '1');
INSERT INTO `he_horas_extra` VALUES ('75', '2', '2', '2012-03-11', '05:00:00', '2', '2014-11-11 11:52:06', '1');
INSERT INTO `he_horas_extra` VALUES ('76', '2', '2', '2013-07-11', '07:00:00', '2', '2014-11-11 11:52:17', '1');
INSERT INTO `he_horas_extra` VALUES ('77', '3', '2', '2012-06-11', '13:00:00', '3', '2014-11-11 11:54:06', '1');
INSERT INTO `he_horas_extra` VALUES ('78', '3', '2', '2013-10-11', '19:00:00', '3', '2014-11-11 11:54:17', '1');

-- ----------------------------
-- Table structure for he_personal
-- ----------------------------
DROP TABLE IF EXISTS `he_personal`;
CREATE TABLE `he_personal` (
  `id_personal` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(32) COLLATE utf8_spanish_ci DEFAULT NULL,
  `paterno` varchar(32) COLLATE utf8_spanish_ci DEFAULT NULL,
  `materno` varchar(32) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL,
  `puesto` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `empleado_num` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_empresa` smallint(4) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_personal`),
  KEY `i_empresa` (`id_empresa`),
  KEY `i_activo` (`activo`),
  KEY `i_puesto` (`id_empresa`,`puesto`),
  KEY `i_empleado_num` (`empleado_num`),
  KEY `fk_usuario` (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of he_personal
-- ----------------------------
INSERT INTO `he_personal` VALUES ('1', 'ADMINISTRADOR', 'DEL', 'SISTEMA', 'oscar.maldonado@isollution.mx', 'ADMINISTRADOR', '777', '1', '2014-08-28 18:00:42', '1', '1');
INSERT INTO `he_personal` VALUES ('2', 'USUARIO', 'REGULAR', null, 'x', 'USUARIO', '111', '2', '2014-09-11 10:31:12', '1', '1');
INSERT INTO `he_personal` VALUES ('3', 'SUPERVISOR', 'DE', 'EMPRESA', 'x', 'SUPERVISOR', '222', '2', '2014-10-21 12:01:54', '1', '1');
INSERT INTO `he_personal` VALUES ('4', 'INPLANT', 'DE', 'EMPRESA', 'x', 'GLOBAL', '333', '2', '2014-10-21 12:01:54', '1', '1');

-- ----------------------------
-- Table structure for he_seguimiento
-- ----------------------------
DROP TABLE IF EXISTS `he_seguimiento`;
CREATE TABLE `he_seguimiento` (
  `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_captura` int(11) DEFAULT NULL,
  `id_empresa` smallint(4) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `id_accion` mediumint(7) DEFAULT NULL,
  `id_estatus` mediumint(7) DEFAULT NULL,
  `doc` varchar(200) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_seguimiento`),
  KEY `i_captura` (`id_captura`),
  KEY `i_empresa` (`id_empresa`),
  KEY `i_estatus` (`id_accion`,`id_estatus`),
  KEY `i_usuario` (`id_usuario`),
  KEY `i_activo` (`activo`),
  KEY `fk_estatus` (`id_estatus`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of he_seguimiento
-- ----------------------------
INSERT INTO `he_seguimiento` VALUES ('1', '1', '1', '2014-08-29', '16:40:02', '1', '1', 'Documento', '1', '2014-08-29 16:40:17', '1');

-- ----------------------------
-- Table structure for sis_accesos
-- ----------------------------
DROP TABLE IF EXISTS `sis_accesos`;
CREATE TABLE `sis_accesos` (
  `id_acceso` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `mod1` tinyint(1) NOT NULL DEFAULT '0',
  `mod2` tinyint(1) NOT NULL DEFAULT '0',
  `mod3` tinyint(1) NOT NULL DEFAULT '0',
  `mod4` tinyint(1) NOT NULL DEFAULT '0',
  `mod5` tinyint(1) NOT NULL DEFAULT '0',
  `mod6` tinyint(1) NOT NULL DEFAULT '0',
  `mod7` tinyint(1) NOT NULL DEFAULT '0',
  `mod8` tinyint(1) NOT NULL DEFAULT '0',
  `mod9` tinyint(1) NOT NULL DEFAULT '0',
  `mod10` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_acceso`),
  KEY `i_usuario` (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of sis_accesos
-- ----------------------------
INSERT INTO `sis_accesos` VALUES ('1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '0', '0');
INSERT INTO `sis_accesos` VALUES ('2', '2', '1', '1', '0', '1', '0', '0', '0', '0', '0', '0');
INSERT INTO `sis_accesos` VALUES ('3', '3', '1', '1', '1', '1', '1', '0', '0', '0', '0', '0');
INSERT INTO `sis_accesos` VALUES ('4', '4', '1', '1', '1', '1', '1', '0', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for sis_logs
-- ----------------------------
DROP TABLE IF EXISTS `sis_logs`;
CREATE TABLE `sis_logs` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `tablename` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_table` int(11) DEFAULT NULL,
  `accion` enum('UPDATE','DELETE','INSERT') COLLATE utf8_spanish_ci DEFAULT NULL,
  `query` text COLLATE utf8_spanish_ci,
  `txt` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `url` text COLLATE utf8_spanish_ci,
  `timestamp` datetime DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_log`),
  KEY `id_usuario` (`id_usuario`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of sis_logs
-- ----------------------------
INSERT INTO `sis_logs` VALUES ('19', 'sis_online', '0', 'UPDATE', 'UPDATE sis_online SET \r\n				online=\'1409598412\' \r\n				WHERE id_usuario=\'1\'\r\n				LIMIT 1;', '', null, '2014-09-01 14:06:52', '1');

-- ----------------------------
-- Table structure for sis_online
-- ----------------------------
DROP TABLE IF EXISTS `sis_online`;
CREATE TABLE `sis_online` (
  `id_online` mediumint(4) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `online` int(12) DEFAULT NULL,
  PRIMARY KEY (`id_online`),
  KEY `i_usuario` (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sis_online
-- ----------------------------
INSERT INTO `sis_online` VALUES ('1', '1', '1411397612');
INSERT INTO `sis_online` VALUES ('2', '2', '1409598217');

-- ----------------------------
-- Table structure for sis_usuarios
-- ----------------------------
DROP TABLE IF EXISTS `sis_usuarios`;
CREATE TABLE `sis_usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `clave` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `grupo` enum('1','2','3','4') COLLATE utf8_spanish_ci DEFAULT '4',
  `id_personal` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_usuario`),
  KEY `i_grupo` (`grupo`),
  KEY `i_activo` (`activo`),
  KEY `i_personal` (`id_personal`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of sis_usuarios
-- ----------------------------
INSERT INTO `sis_usuarios` VALUES ('1', 'admin', 'super', '1', '1', '2014-08-27 17:56:24', '1');
INSERT INTO `sis_usuarios` VALUES ('2', 'usuario', 'usuario', '4', '2', '2014-09-11 10:32:00', '1');
INSERT INTO `sis_usuarios` VALUES ('3', 'super', 'super', '3', '3', '2014-10-21 12:02:38', '1');
INSERT INTO `sis_usuarios` VALUES ('4', 'inplant', 'inplant', '2', '4', '2014-10-13 12:58:36', '1');
