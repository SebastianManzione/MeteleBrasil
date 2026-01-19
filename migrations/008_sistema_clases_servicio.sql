-- Sistema de Clases de Servicio para Transportes
-- Permite definir diferentes tipos de asientos/servicios con precios diferenciados

-- Tabla 1: Catálogo de clases de servicio por tipo de transporte
CREATE TABLE IF NOT EXISTS clase_servicio_transporte (
    idClaseServicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    idTipoTransporte INT NOT NULL,
    icon VARCHAR(50) DEFAULT 'fa-chair',
    orden INT DEFAULT 0 COMMENT 'Orden de visualización (1=más económico, 5=más premium)',
    habilitado TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idTipoTransporte) REFERENCES tipo_transporte(idTipoTransporte) ON DELETE CASCADE,
    INDEX idx_tipo (idTipoTransporte)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar ejemplos de clases para BUSES
INSERT INTO clase_servicio_transporte (nombre, descripcion, idTipoTransporte, orden) VALUES
('Semicama Común', 'Asiento reclinable 140°, sin servicios adicionales', 1, 1),
('Semicama Panorámico', 'Asiento reclinable 140° con vista panorámica', 1, 2),
('Semicama Cafetera', 'Asiento reclinable 140° + servicio de café y snacks', 1, 3),
('Cama', 'Asiento reclinable 160°, más espacio y comodidad', 1, 4),
('Suite', 'Cabina privada con cama completa, baño privado y servicios premium', 1, 5);

-- Insertar ejemplos de clases para AVIONES
INSERT INTO clase_servicio_transporte (nombre, descripcion, idTipoTransporte, orden) VALUES
('Económica', 'Clase económica estándar', 2, 1),
('Premium Economy', 'Más espacio entre asientos y servicios mejorados', 2, 2),
('Business', 'Asientos reclinables, comida premium y acceso a salas VIP', 2, 3),
('Primera Clase', 'Máximo confort, privacidad y servicios exclusivos', 2, 4);

-- Insertar ejemplos de clases para TRENES
INSERT INTO clase_servicio_transporte (nombre, descripcion, idTipoTransporte, orden) VALUES
('Turista', 'Clase turista estándar', 3, 1),
('Preferente', 'Asientos más cómodos y servicios adicionales', 3, 2),
('Coche Cama', 'Cabinas con literas para viajes nocturnos', 3, 3);

-- Insertar ejemplos de clases para BARCOS
INSERT INTO clase_servicio_transporte (nombre, descripcion, idTipoTransporte, orden) VALUES
('Butaca', 'Asiento estándar en salón común', 4, 1),
('Camarote Compartido', 'Cabina compartida de 4 personas', 4, 2),
('Camarote Doble', 'Cabina privada para 2 personas', 4, 3),
('Camarote Suite', 'Suite con vista al mar y servicios premium', 4, 4);


-- Tabla 2: Servicios/Clases disponibles en cada viaje con inventario
CREATE TABLE IF NOT EXISTS viaje_clase_servicio (
    idViajeClase INT AUTO_INCREMENT PRIMARY KEY,
    idViaje INT NOT NULL,
    idClaseServicio INT NOT NULL,
    asientos_totales INT NOT NULL DEFAULT 0,
    asientos_disponibles INT NOT NULL DEFAULT 0,
    precio_base DECIMAL(10,2) NOT NULL COMMENT 'Precio para adulto',
    idMoneda INT NOT NULL,
    comisiona TINYINT DEFAULT 1,
    habilitado TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idViaje) REFERENCES viaje_transporte(idViaje) ON DELETE CASCADE,
    FOREIGN KEY (idClaseServicio) REFERENCES clase_servicio_transporte(idClaseServicio) ON DELETE CASCADE,
    FOREIGN KEY (idMoneda) REFERENCES moneda(idMoneda),
    INDEX idx_viaje (idViaje),
    INDEX idx_clase (idClaseServicio),
    UNIQUE KEY unique_viaje_clase (idViaje, idClaseServicio)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Tabla 3: Modificadores de precio por tipo de pasajero (adulto, niño, senior, bebé)
CREATE TABLE IF NOT EXISTS viaje_clase_tarifa (
    idViajeClaseTarifa INT AUTO_INCREMENT PRIMARY KEY,
    idViajeClase INT NOT NULL,
    idTipoTarifa INT NOT NULL COMMENT 'FK a tipos_tarifa (adulto, niño, senior, bebé, estudiante)',
    precio DECIMAL(10,2) NOT NULL,
    idMoneda INT NOT NULL,
    comisiona TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idViajeClase) REFERENCES viaje_clase_servicio(idViajeClase) ON DELETE CASCADE,
    FOREIGN KEY (idTipoTarifa) REFERENCES tipos_tarifa(idTipoTarifa),
    FOREIGN KEY (idMoneda) REFERENCES moneda(idMoneda),
    INDEX idx_viaje_clase (idViajeClase),
    UNIQUE KEY unique_viaje_clase_tipo (idViajeClase, idTipoTarifa)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Tabla 4: Asientos asignados en reservas (tracking de qué clase compró cada pasajero)
CREATE TABLE IF NOT EXISTS reserva_transporte_clase (
    idReservaTransporteClase INT AUTO_INCREMENT PRIMARY KEY,
    idReservaTransporte INT NOT NULL COMMENT 'FK a reserva_transporte',
    idViajeClase INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    precio_total DECIMAL(10,2) NOT NULL,
    idMoneda INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idReservaTransporte) REFERENCES reserva_transporte(idReservaTransporte) ON DELETE CASCADE,
    FOREIGN KEY (idViajeClase) REFERENCES viaje_clase_servicio(idViajeClase),
    FOREIGN KEY (idMoneda) REFERENCES moneda(idMoneda),
    INDEX idx_reserva (idReservaTransporte)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Vista auxiliar: Clases con disponibilidad por viaje
CREATE OR REPLACE VIEW v_viaje_clases_disponibles AS
SELECT 
    vcs.*,
    cs.nombre AS nombre_clase,
    cs.descripcion AS descripcion_clase,
    cs.orden AS orden_clase,
    cs.icon AS icon_clase,
    tt.nombre AS tipo_transporte,
    m.simbolo AS moneda_simbolo,
    CONCAT(vcs.asientos_disponibles, ' / ', vcs.asientos_totales) AS disponibilidad,
    ROUND((vcs.asientos_disponibles / vcs.asientos_totales) * 100, 0) AS porcentaje_disponible
FROM viaje_clase_servicio vcs
INNER JOIN clase_servicio_transporte cs ON vcs.idClaseServicio = cs.idClaseServicio
INNER JOIN viaje_transporte vt ON vcs.idViaje = vt.idViaje
INNER JOIN ruta_transporte rt ON vt.idRuta = rt.idRuta
INNER JOIN tipo_transporte tt ON rt.idTipoTransporte = tt.idTipoTransporte
INNER JOIN moneda m ON vcs.idMoneda = m.idMoneda
WHERE vcs.habilitado = 1 AND cs.habilitado = 1
ORDER BY vcs.idViaje, cs.orden;
