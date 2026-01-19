-- Extiende sistema de reservas para soportar transporte
-- Compatible con reservas de servicios existentes

USE metelebrasil_experimental;

-- 1. Agregar campo tipo a reservas (para diferenciar servicio vs transporte vs paquete)
ALTER TABLE reservas 
ADD COLUMN tipo_reserva ENUM('servicio', 'transporte', 'paquete') DEFAULT 'servicio' AFTER idReserva;

-- 2. Tabla para items de transporte en reservas
CREATE TABLE IF NOT EXISTS reserva_transporte_items (
  idReservaTransporte INT PRIMARY KEY AUTO_INCREMENT,
  idReserva INT NOT NULL,
  idViaje INT NOT NULL,
  idOrigenParada INT NOT NULL COMMENT 'Terminal origen',
  idDestinoParada INT NOT NULL COMMENT 'Terminal destino',
  fecha_viaje DATE NOT NULL,
  hora_viaje TIME NOT NULL,
  cantidad_pasajeros INT NOT NULL DEFAULT 1,
  precio_total DECIMAL(10,2) NOT NULL,
  idMoneda INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idReserva) REFERENCES reservas(idReserva) ON DELETE CASCADE,
  FOREIGN KEY (idViaje) REFERENCES viaje_transporte(idViaje),
  FOREIGN KEY (idOrigenParada) REFERENCES ruta_paradas(idRutaParada),
  FOREIGN KEY (idDestinoParada) REFERENCES ruta_paradas(idRutaParada),
  FOREIGN KEY (idMoneda) REFERENCES moneda(idMoneda)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabla para pasajeros de transporte (con asientos)
CREATE TABLE IF NOT EXISTS reserva_transporte_pasajeros (
  idPasajero INT PRIMARY KEY AUTO_INCREMENT,
  idReservaTransporte INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  tipo_documento ENUM('DNI', 'Pasaporte', 'RG', 'CI') NOT NULL,
  numero_documento VARCHAR(50) NOT NULL,
  numero_asiento VARCHAR(10) NOT NULL COMMENT 'Ej: A1, B5, F12',
  idTipoTarifa INT NOT NULL COMMENT '1=Adulto, 2=Niño, 3=Senior, 4=Estudiante',
  precio_pagado DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (idReservaTransporte) REFERENCES reserva_transporte_items(idReservaTransporte) ON DELETE CASCADE,
  FOREIGN KEY (idTipoTarifa) REFERENCES tipo_tarifa_pasajero(idTipo),
  UNIQUE KEY unique_asiento_viaje (idReservaTransporte, numero_asiento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Índices para performance
CREATE INDEX idx_viaje_fecha ON reserva_transporte_items(idViaje, fecha_viaje);
CREATE INDEX idx_reserva_tipo ON reservas(tipo_reserva);

-- Verificación
SELECT 'Tablas creadas correctamente' as status;
SHOW TABLES LIKE 'reserva_transporte%';
