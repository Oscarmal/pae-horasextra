/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50516
Source Host           : localhost:3306
Source Database       : pae_he_00

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2014-09-18 13:39:04
*/

SET FOREIGN_KEY_CHECKS=0;

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of he_empresas
-- ----------------------------
INSERT INTO `he_empresas` VALUES ('1', 'ISoluttion', 'IS', 'x', 'Intelligent Solution', 'Insurgentes Sur', 'MX', 'oscar.maldonado@isollution.mx', '2014-08-28 18:10:49', '1', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of he_personal
-- ----------------------------
INSERT INTO `he_personal` VALUES ('1', 'ADMINISTRADOR', 'DEL', 'SISTEMA', 'oscar.maldonado@isollution.mx', 'ADMINISTRADOR', '777', '1', '2014-08-28 18:00:42', '1', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of sis_accesos
-- ----------------------------
INSERT INTO `sis_accesos` VALUES ('1', '1', '1', '1', '0', '1', '1', '1', '0', '0', '0', '0');

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
INSERT INTO `sis_online` VALUES ('1', '1', '1410997104');
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of sis_usuarios
-- ----------------------------
INSERT INTO `sis_usuarios` VALUES ('1', 'admin', 'super', '1', '1', '2014-08-27 17:56:24', '1');
