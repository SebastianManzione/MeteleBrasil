-- Agregar Terminal de Rosario y crear ruta internacional Rosario - Florianópolis - Rio de Janeiro
-- Fecha: 2026-01-16
-- Base de datos: metelebrasil_experimental

SET NAMES utf8mb4;

-- Insertar Terminal de Rosario si no existe
INSERT INTO terminal_transporte (nombre, direccion, latitud, longitud, idPais, idEstado, ciudad, codigo_iata, idTipoTransporte, observaciones, habilitado)
VALUES 
('Terminal de Ómnibus Mariano Moreno', 'Av. Cándido Carballo s/n', '-32.944411', '-60.639444', 10, NULL, 'Rosario', '', 1, 'Terminal principal de Rosario, Argentina', 1);

-- Obtener ID de la terminal recién insertada
SET @id_terminal_rosario = LAST_INSERT_ID();

-- Mostrar terminales disponibles
SELECT 'Terminales disponibles:' as info;
SELECT idTerminal, nombre, ciudad FROM terminal_transporte 
WHERE ciudad IN ('Rosario', 'Florianópolis', 'Río de Janeiro') 
ORDER BY ciudad, nombre;

-- Insertar nueva ruta internacional
INSERT INTO ruta_transporte (
    nombre, nombre_en, nombre_pt, nombre_it,
    descripcion, descripcion_en, descripcion_pt, descripcion_it,
    idTipoTransporte, idEmpresa, idPrestador,
    duracion_estimada, distancia_km, foto_principal, habilitado
) VALUES (
    'Rosario - Florianópolis - Río de Janeiro',
    'Rosario - Florianopolis - Rio de Janeiro',
    'Rosário - Florianópolis - Rio de Janeiro',
    'Rosario - Florianopolis - Rio de Janeiro',
    'Ruta internacional de larga distancia conectando Argentina y Brasil. Pasa por Florianópolis con opción de descenso intermedio.',
    'Long-distance international route connecting Argentina and Brazil. Passes through Florianopolis with intermediate stop option.',
    'Rota internacional de longa distância conectando Argentina e Brasil. Passa por Florianópolis com opção de parada intermediária.',
    'Percorso internazionale di lunga distanza che collega Argentina e Brasile. Passa per Florianopolis con opzione di fermata intermedia.',
    1,
    NULL,
    NULL,
    '2 días 6h 00min',
    2150,
    '',
    1
);

-- Obtener ID de la ruta recién insertada
SET @id_ruta_internacional = LAST_INSERT_ID();

-- Insertar paradas de la ruta
INSERT INTO ruta_paradas (idRuta, idTerminal, orden, es_origen, es_destino, tiempo_desde_inicio)
VALUES
-- Parada 1: Rosario (ORIGEN)
(@id_ruta_internacional, @id_terminal_rosario, 1, 1, 0, '0h'),
-- Parada 2: Florianópolis (INTERMEDIA - puede ser origen Y destino)
(@id_ruta_internacional, 52, 2, 1, 1, '1 día 8h 00min'),
-- Parada 3: Río de Janeiro (DESTINO)
(@id_ruta_internacional, 43, 3, 0, 1, '2 días 6h 00min');

-- Verificar paradas insertadas
SELECT 'Paradas configuradas:' as info;
SELECT 
    rp.orden,
    t.nombre as terminal,
    t.ciudad,
    CASE 
        WHEN rp.es_origen = 1 AND rp.es_destino = 1 THEN 'ORIGEN + DESTINO'
        WHEN rp.es_origen = 1 THEN 'ORIGEN'
        WHEN rp.es_destino = 1 THEN 'DESTINO'
        ELSE 'INTERMEDIA'
    END as tipo_parada,
    rp.tiempo_desde_inicio
FROM ruta_paradas rp
INNER JOIN terminal_transporte t ON rp.idTerminal = t.idTerminal
WHERE rp.idRuta = @id_ruta_internacional
ORDER BY rp.orden;

-- Resumen final
SELECT CONCAT('Ruta creada con ID: ', @id_ruta_internacional) as resultado;
SELECT '✅ Combinaciones posibles:' as nota;
SELECT '  • Rosario → Florianópolis' as opcion1;
SELECT '  • Rosario → Río de Janeiro' as opcion2;
SELECT '  • Florianópolis → Río de Janeiro' as opcion3;
