-- Crear tabla hoteles en metelebrasil
-- Los hoteles están vinculados a servicios turísticos

CREATE TABLE IF NOT EXISTS `hoteles` (
  `idHotel` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL COMMENT 'Nombre del hotel',
  `idServicio` int(11) DEFAULT NULL COMMENT 'Servicio turístico asociado',
  `direccion` varchar(500) DEFAULT NULL,
  `ciudad` varchar(200) DEFAULT NULL,
  `estado` varchar(200) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `codigo_postal` varchar(20) DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `sitio_web` varchar(255) DEFAULT NULL,
  `estrellas` tinyint(1) DEFAULT NULL COMMENT '1-5 estrellas',
  `descripcion` text DEFAULT NULL,
  `servicios` text DEFAULT NULL COMMENT 'Wifi, piscina, desayuno, etc.',
  `habilitado` tinyint(1) DEFAULT 1,
  `fecha_alta` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idHotel`),
  KEY `idx_servicio` (`idServicio`),
  KEY `idx_ciudad` (`ciudad`),
  FOREIGN KEY (`idServicio`) REFERENCES `servicio`(`idServicio`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
