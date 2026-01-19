-- Asignar clases de servicio a los viajes existentes (IDs 2, 3, 4 de la ruta 8)

-- VIAJE 2: Rosario - Florianópolis - Río (20/02/2026) - Bus con múltiples clases
INSERT INTO viaje_clase_servicio (idViaje, idClaseServicio, asientos_totales, asientos_disponibles, precio_base, idMoneda) VALUES
-- 15 asientos semicama común (más económico)
(2, 1, 15, 15, 12500.00, 1), -- ARS 12,500
-- 20 asientos semicama panorámico
(2, 2, 20, 18, 15000.00, 1), -- ARS 15,000 (2 vendidos)
-- 10 asientos cama (más confort)
(2, 4, 10, 7, 22000.00, 1);  -- ARS 22,000 (3 vendidos)

-- VIAJE 3: Rosario - Florianópolis - Río (27/02/2026) - Bus con menos disponibilidad
INSERT INTO viaje_clase_servicio (idViaje, idClaseServicio, asientos_totales, asientos_disponibles, precio_base, idMoneda) VALUES
(3, 1, 15, 8, 12500.00, 1),  -- Semicama común (7 vendidos)
(3, 2, 20, 10, 15000.00, 1), -- Semicama panorámico (10 vendidos)
(3, 4, 10, 2, 22000.00, 1);  -- Cama (8 vendidos - casi full!)

-- VIAJE 4: Rosario - Florianópolis - Río (05/03/2026) - Bus completamente disponible
INSERT INTO viaje_clase_servicio (idViaje, idClaseServicio, asientos_totales, asientos_disponibles, precio_base, idMoneda) VALUES
(4, 1, 15, 15, 12500.00, 1),
(4, 2, 20, 20, 15000.00, 1),
(4, 4, 10, 10, 22000.00, 1);


-- Agregar tarifas por tipo de pasajero para cada clase
-- VIAJE 2 - Semicama Común (idViajeClase=1)
INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda) VALUES
(1, 1, 12500.00, 1), -- Adulto
(1, 2, 6250.00, 1),  -- Niño (50% descuento)
(1, 4, 0.00, 1);     -- Bebé (gratis)

-- VIAJE 2 - Semicama Panorámico (idViajeClase=2)
INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda) VALUES
(2, 1, 15000.00, 1), -- Adulto
(2, 2, 7500.00, 1),  -- Niño
(2, 4, 0.00, 1);     -- Bebé

-- VIAJE 2 - Cama (idViajeClase=3)
INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda) VALUES
(3, 1, 22000.00, 1), -- Adulto
(3, 2, 11000.00, 1), -- Niño
(3, 4, 0.00, 1);     -- Bebé

-- VIAJE 3 - Semicama Común (idViajeClase=4)
INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda) VALUES
(4, 1, 12500.00, 1),
(4, 2, 6250.00, 1),
(4, 4, 0.00, 1);

-- VIAJE 3 - Semicama Panorámico (idViajeClase=5)
INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda) VALUES
(5, 1, 15000.00, 1),
(5, 2, 7500.00, 1),
(5, 4, 0.00, 1);

-- VIAJE 3 - Cama (idViajeClase=6)
INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda) VALUES
(6, 1, 22000.00, 1),
(6, 2, 11000.00, 1),
(6, 4, 0.00, 1);

-- VIAJE 4 - Semicama Común (idViajeClase=7)
INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda) VALUES
(7, 1, 12500.00, 1),
(7, 2, 6250.00, 1),
(7, 4, 0.00, 1);

-- VIAJE 4 - Semicama Panorámico (idViajeClase=8)
INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda) VALUES
(8, 1, 15000.00, 1),
(8, 2, 7500.00, 1),
(8, 4, 0.00, 1);

-- VIAJE 4 - Cama (idViajeClase=9)
INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda) VALUES
(9, 1, 22000.00, 1),
(9, 2, 11000.00, 1),
(9, 4, 0.00, 1);
