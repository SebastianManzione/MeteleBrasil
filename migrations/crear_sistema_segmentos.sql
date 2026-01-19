-- =====================================================
-- FASE 1: Sistema de Segmentos con Precios por Tramo
-- Fecha: 19/01/2026
-- =====================================================

USE metelebrasil_experimental;

-- 1. Crear tabla de precios por segmento
DROP TABLE IF EXISTS viaje_segmento_precio;

CREATE TABLE viaje_segmento_precio (
  idSegmentoPrecio INT AUTO_INCREMENT PRIMARY KEY,
  idViaje INT NOT NULL,
  idOrigenParada INT NOT NULL COMMENT 'FK a ruta_paradas (idRutaParada)',
  idDestinoParada INT NOT NULL COMMENT 'FK a ruta_paradas (idRutaParada)',
  idClaseServicio INT NOT NULL,
  precio DECIMAL(10,2) NOT NULL,
  idMoneda INT NOT NULL,
  asientos_disponibles INT DEFAULT 0 COMMENT 'Asientos disponibles para este segmento',
  comisiona TINYINT(1) DEFAULT 1,
  habilitado TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (idViaje) REFERENCES viaje_transporte(idViaje) ON DELETE CASCADE,
  FOREIGN KEY (idOrigenParada) REFERENCES ruta_paradas(idRutaParada),
  FOREIGN KEY (idDestinoParada) REFERENCES ruta_paradas(idRutaParada),
  FOREIGN KEY (idClaseServicio) REFERENCES clase_servicio_transporte(idClaseServicio),
  FOREIGN KEY (idMoneda) REFERENCES moneda(idMoneda),
  
  INDEX idx_viaje_segmento (idViaje, idOrigenParada, idDestinoParada),
  INDEX idx_viaje_clase (idViaje, idClaseServicio),
  
  -- Validación: origen debe ser diferente de destino
  CHECK (idOrigenParada <> idDestinoParada)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Verificar paradas de Ruta 9 (Liniers-Costa Atlántica)
SELECT 'Paradas de Ruta 9 (Liniers-Costa):' as Info;
SELECT 
    rp.idRutaParada,
    rp.orden,
    t.nombre as terminal,
    rp.es_origen,
    rp.es_destino,
    rp.tiempo_desde_inicio
FROM ruta_paradas rp
JOIN terminal_transporte t ON rp.idTerminal = t.idTerminal
WHERE rp.idRuta = 9
ORDER BY rp.orden;

-- 3. Cargar precios de ejemplo para Viaje 5 (Ruta 9: Liniers-Costa del 20/01/2026)
-- IDs de paradas según la query anterior:
-- 21: Tapiales (origen+destino)
-- 25: Liniers (origen+destino)
-- 20: San Clemente (origen+destino)
-- 22: Las Toninas (origen+destino)
-- 23: Mar del Tuyú (origen+destino)
-- 24: San Bernardo (origen+destino)

-- Clase 1: Semicama Común (idClaseServicio=1, 30 asientos)
INSERT INTO viaje_segmento_precio (idViaje, idOrigenParada, idDestinoParada, idClaseServicio, precio, idMoneda, asientos_disponibles) VALUES
-- Desde Tapiales
(5, 21, 25, 1, 2000.00, 270, 30),   -- Tapiales → Liniers (30min, ARS 2,000)
(5, 21, 20, 1, 8000.00, 270, 30),   -- Tapiales → San Clemente (4h, ARS 8,000)
(5, 21, 22, 1, 9000.00, 270, 30),   -- Tapiales → Las Toninas (4h 15min, ARS 9,000)
(5, 21, 23, 1, 9500.00, 270, 30),   -- Tapiales → Mar del Tuyú (4h 30min, ARS 9,500)
(5, 21, 24, 1, 10000.00, 270, 30),  -- Tapiales → San Bernardo (4h 45min, ARS 10,000)

-- Desde Liniers
(5, 25, 20, 1, 7500.00, 270, 30),   -- Liniers → San Clemente (3h 45min, ARS 7,500)
(5, 25, 22, 1, 8500.00, 270, 30),   -- Liniers → Las Toninas (4h, ARS 8,500)
(5, 25, 23, 1, 9000.00, 270, 30),   -- Liniers → Mar del Tuyú (4h 15min, ARS 9,000)
(5, 25, 24, 1, 9500.00, 270, 30),   -- Liniers → San Bernardo (4h 30min, ARS 9,500)

-- Desde San Clemente
(5, 20, 22, 1, 1500.00, 270, 30),   -- San Clemente → Las Toninas (15min, ARS 1,500)
(5, 20, 23, 1, 2000.00, 270, 30),   -- San Clemente → Mar del Tuyú (30min, ARS 2,000)
(5, 20, 24, 1, 2500.00, 270, 30),   -- San Clemente → San Bernardo (45min, ARS 2,500)

-- Desde Las Toninas
(5, 22, 23, 1, 1000.00, 270, 30),   -- Las Toninas → Mar del Tuyú (15min, ARS 1,000)
(5, 22, 24, 1, 1500.00, 270, 30),   -- Las Toninas → San Bernardo (30min, ARS 1,500)

-- Desde Mar del Tuyú
(5, 23, 24, 1, 800.00, 270, 30);    -- Mar del Tuyú → San Bernardo (15min, ARS 800)

-- Clase 2: Semicama Panorámico (idClaseServicio=2, 4 asientos, +33% precio)
INSERT INTO viaje_segmento_precio (idViaje, idOrigenParada, idDestinoParada, idClaseServicio, precio, idMoneda, asientos_disponibles) VALUES
-- Desde Tapiales
(5, 21, 25, 2, 2700.00, 270, 4),    -- Tapiales → Liniers
(5, 21, 20, 2, 10700.00, 270, 4),   -- Tapiales → San Clemente
(5, 21, 22, 2, 12000.00, 270, 4),   -- Tapiales → Las Toninas
(5, 21, 23, 2, 12700.00, 270, 4),   -- Tapiales → Mar del Tuyú
(5, 21, 24, 2, 13400.00, 270, 4),   -- Tapiales → San Bernardo

-- Desde Liniers
(5, 25, 20, 2, 10000.00, 270, 4),   -- Liniers → San Clemente
(5, 25, 22, 2, 11400.00, 270, 4),   -- Liniers → Las Toninas
(5, 25, 23, 2, 12000.00, 270, 4),   -- Liniers → Mar del Tuyú
(5, 25, 24, 2, 12700.00, 270, 4),   -- Liniers → San Bernardo

-- Desde San Clemente
(5, 20, 22, 2, 2000.00, 270, 4),    -- San Clemente → Las Toninas
(5, 20, 23, 2, 2700.00, 270, 4),    -- San Clemente → Mar del Tuyú
(5, 20, 24, 2, 3400.00, 270, 4),    -- San Clemente → San Bernardo

-- Desde Las Toninas
(5, 22, 23, 2, 1400.00, 270, 4),    -- Las Toninas → Mar del Tuyú
(5, 22, 24, 2, 2000.00, 270, 4),    -- Las Toninas → San Bernardo

-- Desde Mar del Tuyú
(5, 23, 24, 2, 1100.00, 270, 4);    -- Mar del Tuyú → San Bernardo

-- Clase 4: Cama (idClaseServicio=4, 6 asientos, +67% precio)
INSERT INTO viaje_segmento_precio (idViaje, idOrigenParada, idDestinoParada, idClaseServicio, precio, idMoneda, asientos_disponibles) VALUES
-- Desde Tapiales
(5, 21, 25, 4, 3400.00, 270, 6),    -- Tapiales → Liniers
(5, 21, 20, 4, 13400.00, 270, 6),   -- Tapiales → San Clemente
(5, 21, 22, 4, 15000.00, 270, 6),   -- Tapiales → Las Toninas
(5, 21, 23, 4, 15900.00, 270, 6),   -- Tapiales → Mar del Tuyú
(5, 21, 24, 4, 16700.00, 270, 6),   -- Tapiales → San Bernardo

-- Desde Liniers
(5, 25, 20, 4, 12500.00, 270, 6),   -- Liniers → San Clemente
(5, 25, 22, 4, 14200.00, 270, 6),   -- Liniers → Las Toninas
(5, 25, 23, 4, 15000.00, 270, 6),   -- Liniers → Mar del Tuyú
(5, 25, 24, 4, 15900.00, 270, 6),   -- Liniers → San Bernardo

-- Desde San Clemente
(5, 20, 22, 4, 2500.00, 270, 6),    -- San Clemente → Las Toninas
(5, 20, 23, 4, 3400.00, 270, 6),    -- San Clemente → Mar del Tuyú
(5, 20, 24, 4, 4200.00, 270, 6),    -- San Clemente → San Bernardo

-- Desde Las Toninas
(5, 22, 23, 4, 1700.00, 270, 6),    -- Las Toninas → Mar del Tuyú
(5, 22, 24, 4, 2500.00, 270, 6),    -- Las Toninas → San Bernardo

-- Desde Mar del Tuyú
(5, 23, 24, 4, 1400.00, 270, 6);    -- Mar del Tuyú → San Bernardo

-- 4. Verificar datos cargados
SELECT 'RESUMEN DE SEGMENTOS CARGADOS:' as Info;
SELECT 
    COUNT(*) as total_segmentos,
    COUNT(DISTINCT idClaseServicio) as total_clases,
    SUM(asientos_disponibles) as total_asientos_disponibles,
    MIN(precio) as precio_minimo,
    MAX(precio) as precio_maximo,
    AVG(precio) as precio_promedio
FROM viaje_segmento_precio
WHERE idViaje = 5;

SELECT 'DETALLE POR CLASE:' as Info;
SELECT 
    cs.nombre as clase,
    COUNT(*) as cantidad_segmentos,
    SUM(vsp.asientos_disponibles) as asientos_disponibles,
    MIN(vsp.precio) as precio_min,
    MAX(vsp.precio) as precio_max
FROM viaje_segmento_precio vsp
JOIN clase_servicio_transporte cs ON vsp.idClaseServicio = cs.idClaseServicio
WHERE vsp.idViaje = 5
GROUP BY cs.nombre;

SELECT 'SEGMENTOS DESDE TAPIALES (ID Parada 21):' as Info;
SELECT 
    t_origen.nombre as origen,
    t_destino.nombre as destino,
    cs.nombre as clase,
    vsp.precio,
    vsp.asientos_disponibles
FROM viaje_segmento_precio vsp
JOIN ruta_paradas rp_origen ON vsp.idOrigenParada = rp_origen.idRutaParada
JOIN ruta_paradas rp_destino ON vsp.idDestinoParada = rp_destino.idRutaParada
JOIN terminal_transporte t_origen ON rp_origen.idTerminal = t_origen.idTerminal
JOIN terminal_transporte t_destino ON rp_destino.idTerminal = t_destino.idTerminal
JOIN clase_servicio_transporte cs ON vsp.idClaseServicio = cs.idClaseServicio
WHERE vsp.idViaje = 5 AND vsp.idOrigenParada = 21
ORDER BY cs.idClaseServicio, rp_destino.orden;

-- 5. Backup de tabla vieja (por las dudas)
CREATE TABLE viaje_clase_servicio_backup_20260119 AS 
SELECT * FROM viaje_clase_servicio;

SELECT '✅ MIGRACIÓN COMPLETADA - Sistema de segmentos creado!' as Status;
SELECT 'Total segmentos configurados: 45 (15 por cada una de las 3 clases)' as Detalle;
SELECT 'Próximo paso: Actualizar admin/classes/transporte.php con nuevas funciones' as NextStep;
