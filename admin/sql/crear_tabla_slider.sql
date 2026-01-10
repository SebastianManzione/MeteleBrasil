-- Tabla para gestionar el slider
CREATE TABLE IF NOT EXISTS `slider` (
  `idSlider` int(11) NOT NULL AUTO_INCREMENT,
  `imagen` varchar(255) NOT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idSlider`),
  KEY `idx_orden` (`orden`),
  KEY `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Si la tabla ya existe sin columna 'orden', agregarla
ALTER TABLE `slider` ADD COLUMN IF NOT EXISTS `orden` int(11) NOT NULL DEFAULT 0 AFTER `imagen`;

-- Insertar imágenes existentes
INSERT INTO `slider` (`imagen`, `orden`, `activo`) VALUES
('slider4.jpg', 1, 1),
('slider2.jpg', 2, 1),
('slider3.jpg', 3, 1),
('slider1.jpg', 4, 1)
ON DUPLICATE KEY UPDATE orden=VALUES(orden);
