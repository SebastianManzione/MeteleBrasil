-- Tabla para relacionar modelos de vehículos con clases de servicio disponibles
-- Un modelo puede tener múltiples clases (ej: Semicama, Cama, Suite)

CREATE TABLE IF NOT EXISTS modelo_clases (
    idModeloClase INT AUTO_INCREMENT PRIMARY KEY,
    idModelo INT NOT NULL,
    idClaseServicio INT NOT NULL,
    capacidad_clase INT NOT NULL COMMENT 'Cantidad de asientos de esta clase en este modelo',
    orden INT DEFAULT 0 COMMENT 'Orden de visualización',
    habilitado TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idModelo) REFERENCES modelo_vehiculo_transporte(idModelo) ON DELETE CASCADE,
    FOREIGN KEY (idClaseServicio) REFERENCES clase_servicio_transporte(idClaseServicio) ON DELETE CASCADE,
    UNIQUE KEY unique_modelo_clase (idModelo, idClaseServicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar clases para Modelo 7: Mercedes Doble Piso Comfort (48 asientos)
-- Este modelo tiene 3 clases distribuidas
INSERT INTO modelo_clases (idModelo, idClaseServicio, capacidad_clase, orden) VALUES
(7, 2, 20, 1), -- Semicama Panorámico (20 butacas planta superior)
(7, 3, 15, 2), -- Semicama Cafetera (15 butacas planta inferior adelante)
(7, 4, 13, 3); -- Cama (13 butacas planta inferior atrás)

-- Insertar clases para otros modelos existentes (ejemplos)
-- Modelo 1: Mercedes Sprinter 14 (16 asientos) - solo una clase
INSERT INTO modelo_clases (idModelo, idClaseServicio, capacidad_clase, orden) VALUES
(1, 1, 16, 1); -- Semicama Común (todos los asientos)

-- Modelo 6: Marcopolo Doble Piso G7 (50 asientos) - modelo full confort
INSERT INTO modelo_clases (idModelo, idClaseServicio, capacidad_clase, orden) VALUES
(6, 2, 25, 1), -- Semicama Panorámico (25 planta superior)
(6, 4, 20, 2), -- Cama (20 planta inferior)
(6, 5, 5, 3);  -- Suite (5 cabinas VIP)
