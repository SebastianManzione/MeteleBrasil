-- =========================================
-- SISTEMA DE TRANSPORTE - PASAJES
-- Fecha: 2026-01-16
-- Branch: feature/cambios-grosos
-- =========================================

-- 1. TIPO DE TRANSPORTE (micro, avión, tren, barco)
CREATE TABLE IF NOT EXISTS `tipo_transporte` (
  `idTipoTransporte` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `icono` varchar(50) DEFAULT NULL COMMENT 'fa-bus, fa-plane, fa-train',
  `habilitado` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idTipoTransporte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tipo_transporte` (`idTipoTransporte`, `nombre`, `icono`) VALUES
(1, 'Micro/Bus', 'fa-bus'),
(2, 'Avión', 'fa-plane'),
(3, 'Tren', 'fa-train'),
(4, 'Barco/Ferry', 'fa-ship');

-- 2. PUNTOS DE PARADA/TERMINALES (origen/destino)
CREATE TABLE IF NOT EXISTS `terminal_transporte` (
  `idTerminal` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL COMMENT 'Terminal de Ómnibus, Aeropuerto Galeão, etc.',
  `direccion` varchar(500) DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `idPais` int(11) DEFAULT NULL,
  `idEstado` int(11) DEFAULT NULL,
  `ciudad` varchar(200) DEFAULT NULL,
  `codigo_iata` varchar(10) DEFAULT NULL COMMENT 'Para aeropuertos: GRU, GIG, EZE',
  `idTipoTransporte` int(11) NOT NULL COMMENT 'Qué tipo de transporte opera acá',
  `observaciones` text DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT 1,
  `fecha_alta` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idTerminal`),
  KEY `idx_tipo_transporte` (`idTipoTransporte`),
  KEY `idx_ciudad` (`ciudad`),
  FOREIGN KEY (`idTipoTransporte`) REFERENCES `tipo_transporte`(`idTipoTransporte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. EMPRESAS DE TRANSPORTE (operadores)
CREATE TABLE IF NOT EXISTS `empresa_transporte` (
  `idEmpresa` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL COMMENT 'LATAM, GOL, Pluma, Copetran, etc.',
  `idTipoTransporte` int(11) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idEmpresa`),
  FOREIGN KEY (`idTipoTransporte`) REFERENCES `tipo_transporte`(`idTipoTransporte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. RUTAS DE TRANSPORTE (equivalente a "servicio")
CREATE TABLE IF NOT EXISTS `ruta_transporte` (
  `idRuta` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(500) NOT NULL COMMENT 'Río-São Paulo, Buenos Aires-Montevideo',
  `nombre_en` varchar(500) DEFAULT NULL,
  `nombre_pt` varchar(500) DEFAULT NULL,
  `nombre_it` varchar(500) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `descripcion_en` text DEFAULT NULL,
  `descripcion_pt` text DEFAULT NULL,
  `descripcion_it` text DEFAULT NULL,
  `idTipoTransporte` int(11) NOT NULL,
  `idEmpresa` int(11) DEFAULT NULL,
  `idPrestador` int(11) DEFAULT NULL COMMENT 'Prestador que ofrece esta ruta',
  `duracion_estimada` varchar(50) DEFAULT NULL COMMENT '2h30min, 4h',
  `distancia_km` int(11) DEFAULT NULL,
  `foto_principal` varchar(255) DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT 1,
  `fecha_alta` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idRuta`),
  KEY `idx_tipo_transporte` (`idTipoTransporte`),
  KEY `idx_empresa` (`idEmpresa`),
  KEY `idx_prestador` (`idPrestador`),
  FOREIGN KEY (`idTipoTransporte`) REFERENCES `tipo_transporte`(`idTipoTransporte`),
  FOREIGN KEY (`idEmpresa`) REFERENCES `empresa_transporte`(`idEmpresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. PARADAS DE RUTA (origen/destino múltiples)
CREATE TABLE IF NOT EXISTS `ruta_paradas` (
  `idRutaParada` int(11) NOT NULL AUTO_INCREMENT,
  `idRuta` int(11) NOT NULL,
  `idTerminal` int(11) NOT NULL,
  `orden` int(11) NOT NULL COMMENT 'Orden de paradas: 1=origen, 2=intermedia, 3=destino',
  `es_origen` tinyint(1) DEFAULT 0 COMMENT 'Si puede ser punto de partida',
  `es_destino` tinyint(1) DEFAULT 0 COMMENT 'Si puede ser punto de llegada',
  `tiempo_desde_inicio` varchar(50) DEFAULT NULL COMMENT 'Cuánto tarda desde el origen',
  PRIMARY KEY (`idRutaParada`),
  KEY `idx_ruta` (`idRuta`),
  KEY `idx_terminal` (`idTerminal`),
  UNIQUE KEY `unique_ruta_terminal_orden` (`idRuta`, `idTerminal`, `orden`),
  FOREIGN KEY (`idRuta`) REFERENCES `ruta_transporte`(`idRuta`) ON DELETE CASCADE,
  FOREIGN KEY (`idTerminal`) REFERENCES `terminal_transporte`(`idTerminal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. VIAJES (equivalente a "salidas")
CREATE TABLE IF NOT EXISTS `viaje_transporte` (
  `idViaje` int(11) NOT NULL AUTO_INCREMENT,
  `idRuta` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_salida` time NOT NULL,
  `hora_llegada` time DEFAULT NULL,
  `asientos_totales` int(11) NOT NULL DEFAULT 40,
  `asientos_disponibles` int(11) NOT NULL DEFAULT 40,
  `idTerminalOrigen` int(11) NOT NULL COMMENT 'Terminal de salida para este viaje',
  `idTerminalDestino` int(11) NOT NULL COMMENT 'Terminal de llegada para este viaje',
  `numero_vuelo_bus` varchar(50) DEFAULT NULL COMMENT 'Número de vuelo/servicio',
  `observaciones` text DEFAULT NULL,
  `habilitado` tinyint(1) DEFAULT 1,
  `fecha_alta` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idViaje`),
  KEY `idx_ruta` (`idRuta`),
  KEY `idx_fecha` (`fecha`),
  KEY `idx_origen` (`idTerminalOrigen`),
  KEY `idx_destino` (`idTerminalDestino`),
  FOREIGN KEY (`idRuta`) REFERENCES `ruta_transporte`(`idRuta`),
  FOREIGN KEY (`idTerminalOrigen`) REFERENCES `terminal_transporte`(`idTerminal`),
  FOREIGN KEY (`idTerminalDestino`) REFERENCES `terminal_transporte`(`idTerminal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. TARIFAS POR VIAJE Y TRAMO
CREATE TABLE IF NOT EXISTS `viaje_tarifa` (
  `idViajeTarifa` int(11) NOT NULL AUTO_INCREMENT,
  `idViaje` int(11) NOT NULL,
  `idTerminalOrigen` int(11) NOT NULL COMMENT 'Origen de este tramo',
  `idTerminalDestino` int(11) NOT NULL COMMENT 'Destino de este tramo',
  `idTipoTarifa` int(11) NOT NULL COMMENT 'Adulto, Niño, Senior (tabla edades)',
  `precio` decimal(10,2) NOT NULL,
  `idMoneda` int(11) NOT NULL,
  `comisiona` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idViajeTarifa`),
  KEY `idx_viaje` (`idViaje`),
  KEY `idx_origen_destino` (`idTerminalOrigen`, `idTerminalDestino`),
  FOREIGN KEY (`idViaje`) REFERENCES `viaje_transporte`(`idViaje`) ON DELETE CASCADE,
  FOREIGN KEY (`idTerminalOrigen`) REFERENCES `terminal_transporte`(`idTerminal`),
  FOREIGN KEY (`idTerminalDestino`) REFERENCES `terminal_transporte`(`idTerminal`),
  FOREIGN KEY (`idMoneda`) REFERENCES `moneda`(`idMoneda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. IMÁGENES DE RUTAS
CREATE TABLE IF NOT EXISTS `ruta_transporte_img` (
  `idImg` int(11) NOT NULL AUTO_INCREMENT,
  `idRuta` int(11) NOT NULL,
  `ruta` varchar(500) NOT NULL,
  `orden` int(11) DEFAULT 1,
  PRIMARY KEY (`idImg`),
  KEY `idx_ruta` (`idRuta`),
  FOREIGN KEY (`idRuta`) REFERENCES `ruta_transporte`(`idRuta`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. RESERVAS DE TRANSPORTE (relación con sistema actual)
CREATE TABLE IF NOT EXISTS `reserva_transporte` (
  `idReservaTransporte` int(11) NOT NULL AUTO_INCREMENT,
  `idReserva` int(11) NOT NULL COMMENT 'FK a reservas (sistema actual)',
  `idViaje` int(11) NOT NULL,
  `idTerminalOrigen` int(11) NOT NULL,
  `idTerminalDestino` int(11) NOT NULL,
  `cantidad_pasajeros` int(11) NOT NULL,
  `precio_total` decimal(10,2) NOT NULL,
  `idMoneda` int(11) NOT NULL,
  `estado` varchar(50) DEFAULT 'pendiente',
  `fecha_reserva` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idReservaTransporte`),
  KEY `idx_reserva` (`idReserva`),
  KEY `idx_viaje` (`idViaje`),
  FOREIGN KEY (`idViaje`) REFERENCES `viaje_transporte`(`idViaje`),
  FOREIGN KEY (`idTerminalOrigen`) REFERENCES `terminal_transporte`(`idTerminal`),
  FOREIGN KEY (`idTerminalDestino`) REFERENCES `terminal_transporte`(`idTerminal`),
  FOREIGN KEY (`idMoneda`) REFERENCES `moneda`(`idMoneda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. PASAJEROS DE TRANSPORTE
CREATE TABLE IF NOT EXISTS `reserva_transporte_pasajeros` (
  `idPasajeroTransporte` int(11) NOT NULL AUTO_INCREMENT,
  `idReservaTransporte` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `documento` varchar(100) DEFAULT NULL,
  `idTipoTarifa` int(11) NOT NULL COMMENT 'Adulto, Niño, etc.',
  `asiento` varchar(10) DEFAULT NULL COMMENT 'Número de asiento si aplica',
  PRIMARY KEY (`idPasajeroTransporte`),
  KEY `idx_reserva_transporte` (`idReservaTransporte`),
  FOREIGN KEY (`idReservaTransporte`) REFERENCES `reserva_transporte`(`idReservaTransporte`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. AGREGAR CAMPO A CATEGORIA_SERVICIO PARA FILTRAR TRANSPORTE
ALTER TABLE `categoria_servicio` 
ADD COLUMN `es_transporte` tinyint(1) DEFAULT 0 COMMENT 'Si es categoría de transporte' AFTER `orden`;

-- Crear categoría de transporte
INSERT INTO `categoria_servicio` (`nombre`, `icono`, `es_transporte`, `habilitado`) 
VALUES ('Transporte', 'fa-bus', 1, 1);

-- =========================================
-- ÍNDICES ADICIONALES PARA PERFORMANCE
-- =========================================

CREATE INDEX idx_viaje_fecha_origen_destino ON viaje_transporte(fecha, idTerminalOrigen, idTerminalDestino);
CREATE INDEX idx_ruta_paradas_origen ON ruta_paradas(idRuta, es_origen);
CREATE INDEX idx_ruta_paradas_destino ON ruta_paradas(idRuta, es_destino);

-- =========================================
-- DATOS DE EJEMPLO (OPCIONAL)
-- =========================================

-- Terminal de ejemplo: Retiro, Buenos Aires
INSERT INTO `terminal_transporte` 
(`nombre`, `direccion`, `ciudad`, `idPais`, `idTipoTransporte`, `latitud`, `longitud`)
VALUES 
('Terminal de Retiro', 'Av. Ramos Mejía 1680', 'Buenos Aires', 1, 1, -34.588886, -58.373993),
('Aeropuerto Ezeiza', 'Autopista Tte. Gral. Pablo Riccheri', 'Buenos Aires', 1, 2, -34.822222, -58.535833);

-- Empresa de ejemplo
INSERT INTO `empresa_transporte` (`nombre`, `idTipoTransporte`)
VALUES ('Via Bariloche', 1), ('Aerolíneas Argentinas', 2);

-- Ruta de ejemplo: Buenos Aires - Mar del Plata
INSERT INTO `ruta_transporte` 
(`nombre`, `idTipoTransporte`, `idEmpresa`, `duracion_estimada`, `distancia_km`)
VALUES 
('Buenos Aires - Mar del Plata', 1, 1, '5h30min', 404);

