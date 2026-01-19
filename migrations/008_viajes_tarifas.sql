-- ===============================================
-- SISTEMA DE VIAJES Y TARIFAS DE TRANSPORTE
-- ===============================================

-- 1. VIAJES (salidas específicas de una ruta)
CREATE TABLE IF NOT EXISTS viaje_transporte (
  idViaje INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  idRuta INT(11) NOT NULL,
  fecha_salida DATE NOT NULL,
  hora_salida TIME NOT NULL,
  asientos_totales INT(11) NOT NULL,
  asientos_disponibles INT(11) NOT NULL,
  estado ENUM('programado', 'confirmado', 'en_curso', 'completado', 'cancelado') DEFAULT 'programado',
  observaciones TEXT,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_ruta (idRuta),
  KEY idx_fecha (fecha_salida),
  KEY idx_estado (estado),
  FOREIGN KEY (idRuta) REFERENCES ruta_transporte(idRuta) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. TIPOS DE PASAJERO/TARIFA
CREATE TABLE IF NOT EXISTS tipo_tarifa_pasajero (
  idTipoTarifa INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  descripcion VARCHAR(255),
  descuento_porcentaje DECIMAL(5,2) DEFAULT 0,
  requiere_documento TINYINT(1) DEFAULT 0,
  habilitado TINYINT(1) DEFAULT 1,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. TARIFAS POR SEGMENTO Y TIPO DE PASAJERO
CREATE TABLE IF NOT EXISTS viaje_tarifa (
  idTarifa INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  idViaje INT(11) NOT NULL,
  idOrigenParada INT(11) NOT NULL,
  idDestinoParada INT(11) NOT NULL,
  idTipoTarifa INT(11) NOT NULL,
  valor DECIMAL(10,2) NOT NULL,
  idMoneda INT(11),
  comisiona TINYINT(1) DEFAULT 1,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_viaje (idViaje),
  KEY idx_origen (idOrigenParada),
  KEY idx_destino (idDestinoParada),
  KEY idx_tipo (idTipoTarifa),
  FOREIGN KEY (idViaje) REFERENCES viaje_transporte(idViaje) ON DELETE CASCADE,
  FOREIGN KEY (idOrigenParada) REFERENCES ruta_paradas(idRutaParada),
  FOREIGN KEY (idDestinoParada) REFERENCES ruta_paradas(idRutaParada),
  FOREIGN KEY (idTipoTarifa) REFERENCES tipo_tarifa_pasajero(idTipoTarifa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. INSERTAR TIPOS DE TARIFA ESTÁNDAR
INSERT IGNORE INTO tipo_tarifa_pasajero (idTipoTarifa, nombre, descripcion, descuento_porcentaje, requiere_documento) VALUES
(1, 'Adulto', 'Tarifa completa para adultos', 0, 0),
(2, 'Niño', 'Tarifa reducida para niños (2-12 años)', 30, 1),
(3, 'Senior', 'Tarifa reducida para mayores de 60', 15, 1),
(4, 'Estudiante', 'Tarifa con descuento para estudiantes', 20, 1);

-- 5. VISTA PARA COMBINACIONES VÁLIDAS ORIGEN-DESTINO
-- (Comentada para evitar error si viaje_transporte no existe aún)
-- CREATE OR REPLACE VIEW viaje_combinaciones_posibles AS
-- SELECT DISTINCT
--   v.idViaje,
--   r.idRuta,
--   rp1.idRutaParada as idOrigenParada,
--   rp2.idRutaParada as idDestinoParada,
--   t1.nombre as origen_nombre,
--   t2.nombre as destino_nombre
-- FROM viaje_transporte v
-- JOIN ruta_transporte r ON v.idRuta = r.idRuta
-- JOIN ruta_paradas rp1 ON r.idRuta = rp1.idRuta AND rp1.es_origen = 1
-- JOIN ruta_paradas rp2 ON r.idRuta = rp2.idRuta AND rp2.es_destino = 1
-- JOIN terminal_transporte t1 ON rp1.idTerminal = t1.idTerminal
-- JOIN terminal_transporte t2 ON rp2.idTerminal = t2.idTerminal
-- WHERE rp1.orden < rp2.orden AND v.estado IN ('programado', 'confirmado');
