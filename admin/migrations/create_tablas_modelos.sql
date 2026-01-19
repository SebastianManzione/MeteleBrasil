-- Tabla 1: Modelos de vehículos
CREATE TABLE IF NOT EXISTS modelo_vehiculo_transporte (
  idModelo INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(150) NOT NULL UNIQUE,
  tipo_transporte INT NOT NULL,
  capacidad_total INT NOT NULL,
  filas INT NOT NULL,
  columnas INT NOT NULL,
  descripcion TEXT,
  imagen_miniatura VARCHAR(255),
  distribucion_json JSON,
  habilitado BOOLEAN DEFAULT 1,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_modelo_tipo FOREIGN KEY (tipo_transporte) REFERENCES tipo_transporte(idTipo),
  INDEX idx_tipo (tipo_transporte),
  INDEX idx_habilitado (habilitado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla 2: Vehículos específicos
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

-- Tabla 3: Asientos por viaje
CREATE TABLE IF NOT EXISTS viaje_asiento (
  idViajeAsiento INT PRIMARY KEY AUTO_INCREMENT,
  idViaje INT NOT NULL,
  idVehiculo INT,
  numero_asiento VARCHAR(10) NOT NULL,
  fila INT NOT NULL,
  columna INT NOT NULL,
  tipo_asiento VARCHAR(50),
  estado ENUM('disponible', 'reservado', 'bloqueado', 'mantenimiento') DEFAULT 'disponible',
  idReservaTransporte INT,
  nombre_pasajero VARCHAR(255),
  documento_pasajero VARCHAR(50),
  precio_asiento DECIMAL(10,2),
  precio_extra DECIMAL(10,2),
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_asiento_viaje FOREIGN KEY (idViaje) REFERENCES viaje_transporte(idViaje),
  CONSTRAINT fk_asiento_vehiculo FOREIGN KEY (idVehiculo) REFERENCES vehiculo_transporte(idVehiculo),
  CONSTRAINT fk_asiento_reserva FOREIGN KEY (idReservaTransporte) REFERENCES reserva_transporte(idReservaTransporte),
  UNIQUE KEY unique_viaje_asiento (idViaje, numero_asiento),
  INDEX idx_viaje (idViaje),
  INDEX idx_estado (estado),
  INDEX idx_reserva (idReservaTransporte)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
