-- ==============================================
-- SISTEMA DE MODELOS DE VEHÍCULOS CON MAPA DE ASIENTOS
-- Session: Enero 2026
-- ==============================================

-- 1. TABLA: Modelos de vehículos preconfigurados
CREATE TABLE IF NOT EXISTS modelo_vehiculo_transporte (
  idModelo INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(150) NOT NULL UNIQUE,
  tipo_transporte INT NOT NULL,
  capacidad_total INT NOT NULL,
  filas INT NOT NULL,
  columnas INT NOT NULL,
  descripcion TEXT,
  imagen_miniatura VARCHAR(255),
  distribucion_json JSON COMMENT 'Almacena configuración de filas/columnas y posiciones especiales',
  habilitado BOOLEAN DEFAULT 1,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  CONSTRAINT fk_modelo_tipo FOREIGN KEY (tipo_transporte) REFERENCES tipo_transporte(idTipo),
  INDEX idx_tipo (tipo_transporte),
  INDEX idx_habilitado (habilitado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. TABLA: Vehículos específicos (instancias de modelos)
CREATE TABLE IF NOT EXISTS vehiculo_transporte (
  idVehiculo INT PRIMARY KEY AUTO_INCREMENT,
  idModelo INT NOT NULL,
  patente VARCHAR(20) NOT NULL UNIQUE,
  idEmpresa INT,
  estado ENUM('activo', 'mantenimiento', 'retirado', 'inactivo') DEFAULT 'activo',
  fecha_alta DATE NOT NULL,
  fecha_baja DATE,
  observaciones TEXT,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  CONSTRAINT fk_vehiculo_modelo FOREIGN KEY (idModelo) REFERENCES modelo_vehiculo_transporte(idModelo),
  CONSTRAINT fk_vehiculo_empresa FOREIGN KEY (idEmpresa) REFERENCES empresa_transporte(idEmpresa),
  INDEX idx_modelo (idModelo),
  INDEX idx_empresa (idEmpresa),
  INDEX idx_estado (estado),
  INDEX idx_patente (patente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. TABLA: Disponibilidad de asientos por viaje
CREATE TABLE IF NOT EXISTS viaje_asiento (
  idViajeAsiento INT PRIMARY KEY AUTO_INCREMENT,
  idViaje INT NOT NULL,
  idVehiculo INT,
  numero_asiento VARCHAR(10) NOT NULL COMMENT 'Ej: A1, B2, C12',
  fila INT NOT NULL,
  columna INT NOT NULL,
  tipo_asiento VARCHAR(50) COMMENT 'normal, panoramico, ejecutivo, cama, etc (de clase_servicio_transporte)',
  estado ENUM('disponible', 'reservado', 'bloqueado', 'mantenimiento') DEFAULT 'disponible',
  idReservaTransporte INT,
  nombre_pasajero VARCHAR(255),
  documento_pasajero VARCHAR(50),
  precio_asiento DECIMAL(10,2),
  precio_extra DECIMAL(10,2) COMMENT 'Costo adicional si es panorámico/ejecutivo/etc',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  CONSTRAINT fk_asiento_viaje FOREIGN KEY (idViaje) REFERENCES viaje_transporte(idViaje),
  CONSTRAINT fk_asiento_vehiculo FOREIGN KEY (idVehiculo) REFERENCES vehiculo_transporte(idVehiculo),
  CONSTRAINT fk_asiento_reserva FOREIGN KEY (idReservaTransporte) REFERENCES reserva_transporte(idReservaTransporte),
  UNIQUE KEY unique_viaje_asiento (idViaje, numero_asiento),
  INDEX idx_viaje (idViaje),
  INDEX idx_estado (estado),
  INDEX idx_reserva (idReservaTransporte),
  INDEX idx_fila_columna (fila, columna)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==============================================
-- INSERCIÓN: 5 MODELOS PRECONFIGURADOS ARGENTINOS
-- ==============================================

INSERT INTO modelo_vehiculo_transporte 
(nombre, tipo_transporte, capacidad_total, filas, columnas, descripcion, distribucion_json, habilitado) VALUES

-- 1. CHEVALLIER KING PREMIUM (Micro Semicama de lujo)
('Chevallier King Premium', 1, 36, 12, 3, 
 'Micro semicama de lujo. Asientos reclinables, baño, TV, aire acondicionado. Muy popular en rutas largas Argentina.', 
 '{"tipo":"3_columnas","pasillo":"central","columnas":[1,2],"fila_2_columna":["vacio"],"especiales":{}}',
 1),

-- 2. MARCOPOLO PARADISO (Micro Ejecutivo)
('Marcopolo Paradiso 1350', 1, 40, 10, 4,
 'Micro ejecutivo brasileño. 40 asientos distribuidos, baño, aire, muy cómodo.',
 '{"tipo":"4_columnas","pasillo":"central","columnas":[2,3],"especiales":{}}',
 1),

-- 3. SCANIA K340 (Micro Estándar)
('Scania K340', 1, 50, 16, 3,
 'Micro estándar semi-cama. 50 asientos, baño, aire acondicionado.',
 '{"tipo":"3_columnas","pasillo":"central","columnas":[1,2],"especiales":{}}',
 1),

-- 4. BOEING 737 (Avión)
('Boeing 737-800', 2, 180, 30, 6,
 'Avión comercial. 6 asientos por fila (3-3 con pasillo central), 30 filas.',
 '{"tipo":"6_columnas","pasillo":"central","columnas":[3,4],"especiales":{"premium_cabina":"filas 1-5"}}',
 1),

-- 5. FERRY ESTÁNDAR (Barco)
('Ferry Estándar - Bac3000', 4, 400, 20, 20,
 'Ferry para travesías fluviales. Capacidad 400 pasajeros, múltiples cubiertas.',
 '{"tipo":"20_columnas","pasillo":"multiple","especiales":{"cubierta_1":"filas 1-10","cubierta_2":"filas 11-20"}}',
 1);

-- ==============================================
-- INSERCIÓN: Crear vehículos de ejemplo para cada modelo
-- ==============================================

-- Obtener IDs de empresas y modelos (después de insert)
-- Asumimos empresa_transporte con datos existentes

INSERT INTO vehiculo_transporte 
(idModelo, patente, idEmpresa, estado, fecha_alta) VALUES

-- 2 Chevallier King Premium
(1, 'AH 001 ER', NULL, 'activo', CURDATE()),
(1, 'AH 002 ER', NULL, 'activo', CURDATE()),

-- 2 Marcopolo
(2, 'AA 123 KK', NULL, 'activo', CURDATE()),
(2, 'AA 124 KK', NULL, 'mantenimiento', CURDATE()),

-- 1 Scania
(3, 'BA 555 BC', NULL, 'activo', CURDATE()),

-- 1 Boeing
(4, 'AR-123', NULL, 'activo', CURDATE()),

-- 1 Ferry
(5, 'FERRY-001', NULL, 'activo', CURDATE());

-- ==============================================
-- ÍNDICES ADICIONALES PARA PERFORMANCE
-- ==============================================

CREATE INDEX idx_modelo_habilitado ON modelo_vehiculo_transporte(habilitado, tipo_transporte);
CREATE INDEX idx_vehiculo_modelo_estado ON vehiculo_transporte(idModelo, estado);
CREATE INDEX idx_asiento_viaje_estado ON viaje_asiento(idViaje, estado);

-- ==============================================
-- VERIFICACIÓN
-- ==============================================

SELECT 'Tablas creadas exitosamente' AS status;
SELECT COUNT(*) as total_modelos FROM modelo_vehiculo_transporte;
SELECT COUNT(*) as total_vehiculos FROM vehiculo_transporte;
