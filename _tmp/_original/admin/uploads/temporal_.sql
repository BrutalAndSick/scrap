DROP TABLE IF EXISTS `motivos_sap`;
CREATE TABLE `motivos_sap` (`id` int(11) NOT NULL auto_increment,`motivo` varchar(255) NOT NULL,`activo` int(11) NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

INSERT INTO `motivos_sap` VALUES ('1', 'Err BF AutomÃ¡tico', '1');
INSERT INTO `motivos_sap` VALUES ('2', 'Err rev captura', '1');
INSERT INTO `motivos_sap` VALUES ('3', 'Retrabaj mal coord', '1');
INSERT INTO `motivos_sap` VALUES ('4', 'COGI mal procesado', '1');
INSERT INTO `motivos_sap` VALUES ('5', 'Mal ajuste anterior', '1');
INSERT INTO `motivos_sap` VALUES ('6', 'Embarque miscelaneo', '1');
INSERT INTO `motivos_sap` VALUES ('7', 'Err BF - Parametros', '1');
INSERT INTO `motivos_sap` VALUES ('8', 'Mat mal etiquetado', '1');
INSERT INTO `motivos_sap` VALUES ('9', 'Nota crÃ©d mal proc', '1');
INSERT INTO `motivos_sap` VALUES ('10', 'BOM erroneo', '1');
INSERT INTO `motivos_sap` VALUES ('11', 'Cruce de material', '1');
INSERT INTO `motivos_sap` VALUES ('12', 'Cambio Ingenieria', '1');
INSERT INTO `motivos_sap` VALUES ('13', 'Corridas Piloto', '1');
