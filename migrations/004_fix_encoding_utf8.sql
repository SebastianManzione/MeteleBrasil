-- =========================================
-- FIX ENCODING UTF-8 - ACENTOS Y CARACTERES ESPECIALES
-- Fecha: 2026-01-16
-- =========================================

-- Arreglar tipos de transporte
UPDATE tipo_transporte SET nombre = 'Avión' WHERE idTipoTransporte = 2;
UPDATE tipo_transporte SET nombre = 'Micro/Bus' WHERE idTipoTransporte = 1;

-- Arreglar terminales con caracteres especiales
UPDATE terminal_transporte SET ciudad = 'Río de Janeiro' WHERE ciudad LIKE '%R_o de Janeiro%' OR ciudad LIKE 'R%o de Janeiro';
UPDATE terminal_transporte SET ciudad = 'São Paulo' WHERE ciudad LIKE '%S_o Paulo%' OR ciudad LIKE 'S%o Paulo';
UPDATE terminal_transporte SET ciudad = 'Asunción' WHERE ciudad LIKE '%Asunci_n%' OR ciudad LIKE 'Asunci%n';
UPDATE terminal_transporte SET ciudad = 'Florianópolis' WHERE ciudad LIKE '%Florian_polis%' OR ciudad LIKE 'Florian%polis';

UPDATE terminal_transporte SET nombre = 'Rodoviária Novo Rio' WHERE nombre LIKE '%Rodovi_ria%';
UPDATE terminal_transporte SET nombre = 'Rodoviária de Porto Alegre' WHERE nombre LIKE '%Rodovi_ria de Porto%';
UPDATE terminal_transporte SET nombre = 'Aeroporto Arturo Merino Benítez' WHERE nombre LIKE '%Ben_tez%';

-- Verificar
SELECT idTipoTransporte, nombre FROM tipo_transporte;
SELECT nombre, ciudad FROM terminal_transporte WHERE ciudad IN ('Río de Janeiro', 'São Paulo', 'Asunción', 'Florianópolis') ORDER BY ciudad;
