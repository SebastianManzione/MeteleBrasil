-- Insertar rutas de ejemplo para el sistema de transporte
-- Fecha: 2026-01-16
-- Base de datos: metelebrasil_experimental

-- Nota: Asegurarse que existen los IDs referenciados de terminales
-- Terminal 1: Terminal de Retiro (Bus)
-- Terminal 2: Aeropuerto Ezeiza (Avión)
-- Terminal 3: Estación Retiro (Tren)
-- Terminal 10: Terminal de Ómnibus de Mar del Plata (Bus)
-- Terminal 15: Terminal Pluma (Bus)

-- ================================
-- RUTAS DE BUS (idTipoTransporte = 1)
-- ================================

INSERT INTO ruta_transporte (
    nombre, nombre_en, nombre_pt, nombre_it,
    descripcion, descripcion_en, descripcion_pt, descripcion_it,
    idTipoTransporte, idEmpresa, idPrestador,
    duracion_estimada, distancia_km, foto_principal, habilitado
) VALUES 
-- Buenos Aires - Mar del Plata
(
    'Buenos Aires - Mar del Plata',
    'Buenos Aires - Mar del Plata',
    'Buenos Aires - Mar del Plata',
    'Buenos Aires - Mar del Plata',
    'Ruta directa desde Terminal Retiro hasta Mar del Plata con servicios cama ejecutivo y semi cama.',
    'Direct route from Retiro Terminal to Mar del Plata with executive and semi-sleeper services.',
    'Rota direta do Terminal Retiro a Mar del Plata com serviços executivo e semi-leito.',
    'Percorso diretto dal Terminal Retiro a Mar del Plata con servizi executivo e semi-cuccetta.',
    1, NULL, NULL,
    '5h 30min', 404, '', 1
),

-- Buenos Aires - Bariloche
(
    'Buenos Aires - Bariloche',
    'Buenos Aires - Bariloche',
    'Buenos Aires - Bariloche',
    'Buenos Aires - Bariloche',
    'Viaje nocturno desde Buenos Aires hasta San Carlos de Bariloche con servicios cama y semi cama.',
    'Overnight trip from Buenos Aires to San Carlos de Bariloche with sleeper and semi-sleeper services.',
    'Viagem noturna de Buenos Aires a San Carlos de Bariloche com serviços leito e semi-leito.',
    'Viaggio notturno da Buenos Aires a San Carlos de Bariloche con servizi cuccetta e semi-cuccetta.',
    1, NULL, NULL,
    '20h 00min', 1641, '', 1
),

-- Buenos Aires - Córdoba
(
    'Buenos Aires - Córdoba',
    'Buenos Aires - Córdoba',
    'Buenos Aires - Córdoba',
    'Buenos Aires - Córdoba',
    'Ruta directa a la ciudad de Córdoba con múltiples frecuencias diarias.',
    'Direct route to Córdoba city with multiple daily frequencies.',
    'Rota direta para a cidade de Córdoba com múltiplas frequências diárias.',
    'Percorso diretto per la città di Córdoba con multiple frequenze giornaliere.',
    1, NULL, NULL,
    '9h 30min', 710, '', 1
),

-- Buenos Aires - Mendoza
(
    'Buenos Aires - Mendoza',
    'Buenos Aires - Mendoza',
    'Buenos Aires - Mendoza',
    'Buenos Aires - Mendoza',
    'Ruta nocturna hacia Mendoza capital con servicios premium disponibles.',
    'Overnight route to Mendoza capital with premium services available.',
    'Rota noturna para Mendoza capital com serviços premium disponíveis.',
    'Percorso notturno verso Mendoza capitale con servizi premium disponibili.',
    1, NULL, NULL,
    '14h 00min', 1037, '', 1
);

-- ================================
-- RUTAS DE AVIÓN (idTipoTransporte = 2)
-- ================================

INSERT INTO ruta_transporte (
    nombre, nombre_en, nombre_pt, nombre_it,
    descripcion, descripcion_en, descripcion_pt, descripcion_it,
    idTipoTransporte, idEmpresa, idPrestador,
    duracion_estimada, distancia_km, foto_principal, habilitado
) VALUES 
-- Buenos Aires - Bariloche (Avión)
(
    'Buenos Aires (EZE) - Bariloche (BRC)',
    'Buenos Aires (EZE) - Bariloche (BRC)',
    'Buenos Aires (EZE) - Bariloche (BRC)',
    'Buenos Aires (EZE) - Bariloche (BRC)',
    'Vuelo directo desde Aeropuerto Internacional de Ezeiza hasta Aeropuerto de Bariloche.',
    'Direct flight from Ezeiza International Airport to Bariloche Airport.',
    'Voo direto do Aeroporto Internacional de Ezeiza ao Aeroporto de Bariloche.',
    'Volo diretto dall\'Aeroporto Internazionale di Ezeiza all\'Aeroporto di Bariloche.',
    2, NULL, NULL,
    '2h 15min', 1345, '', 1
),

-- Buenos Aires - Iguazú (Avión)
(
    'Buenos Aires (AEP) - Iguazú (IGR)',
    'Buenos Aires (AEP) - Iguazu (IGR)',
    'Buenos Aires (AEP) - Iguaçu (IGR)',
    'Buenos Aires (AEP) - Iguazú (IGR)',
    'Vuelo directo desde Aeroparque Jorge Newbery hasta Aeropuerto de Iguazú.',
    'Direct flight from Jorge Newbery Airport to Iguazú Airport.',
    'Voo direto do Aeroporto Jorge Newbery ao Aeroporto de Iguaçu.',
    'Volo diretto dall\'Aeroporto Jorge Newbery all\'Aeroporto di Iguazú.',
    2, NULL, NULL,
    '1h 50min', 1080, '', 1
);

-- ================================
-- RUTAS DE TREN (idTipoTransporte = 3)
-- ================================

INSERT INTO ruta_transporte (
    nombre, nombre_en, nombre_pt, nombre_it,
    descripcion, descripcion_en, descripcion_pt, descripcion_it,
    idTipoTransporte, idEmpresa, idPrestador,
    duracion_estimada, distancia_km, foto_principal, habilitado
) VALUES 
-- Retiro - Tigre (Tren)
(
    'Buenos Aires Retiro - Tigre',
    'Buenos Aires Retiro - Tigre',
    'Buenos Aires Retiro - Tigre',
    'Buenos Aires Retiro - Tigre',
    'Tren de cercanías línea Mitre ramal Tigre. Servicio frecuente durante todo el día.',
    'Suburban train Mitre line Tigre branch. Frequent service throughout the day.',
    'Trem suburbano linha Mitre ramal Tigre. Serviço frequente durante todo o dia.',
    'Treno suburbano linea Mitre ramo Tigre. Servizio frequente durante tutto il giorno.',
    3, NULL, NULL,
    '1h 10min', 32, '', 1
);

-- Mostrar IDs insertados
SELECT 'Rutas insertadas exitosamente' as mensaje;
SELECT idRuta, nombre, tipo_transporte_nombre, duracion_estimada, distancia_km 
FROM ruta_transporte r
LEFT JOIN tipo_transporte t ON r.idTipoTransporte = t.idTipoTransporte
ORDER BY r.idRuta DESC
LIMIT 10;
