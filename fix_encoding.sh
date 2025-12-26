#!/bin/bash
# Fix UTF-8 encoding in production database

mysql -u u925692129_metelebrasil -pCambiar2026 u925692129_metelebrasil <<'EOSQL'
SET NAMES utf8mb4;

-- Fix admin_menu (label column) - Administración
UPDATE admin_menu SET label = CONVERT(CAST(CONVERT(label USING latin1) AS BINARY) USING utf8mb4) WHERE label REGEXP '├';

-- Fix servicio - service names and descriptions
UPDATE servicio SET nombre_servicio = CONVERT(CAST(CONVERT(nombre_servicio USING latin1) AS BINARY) USING utf8mb4) WHERE nombre_servicio REGEXP '├';
UPDATE servicio SET descripcion_servicio = CONVERT(CAST(CONVERT(descripcion_servicio USING latin1) AS BINARY) USING utf8mb4) WHERE descripcion_servicio REGEXP '├';
UPDATE servicio SET descripcion_corta = CONVERT(CAST(CONVERT(descripcion_corta USING latin1) AS BINARY) USING utf8mb4) WHERE descripcion_corta REGEXP '├';
UPDATE servicio SET observaciones = CONVERT(CAST(CONVERT(observaciones USING latin1) AS BINARY) USING utf8mb4) WHERE observaciones REGEXP '├';
UPDATE servicio SET documentacionViajero = CONVERT(CAST(CONVERT(documentacionViajero USING latin1) AS BINARY) USING utf8mb4) WHERE documentacionViajero REGEXP '├';

-- Fix categoria_servicio - category names
UPDATE categoria_servicio SET nombre_categoria_servicio = CONVERT(CAST(CONVERT(nombre_categoria_servicio USING latin1) AS BINARY) USING utf8mb4) WHERE nombre_categoria_servicio REGEXP '├';
UPDATE categoria_servicio SET descripcion_categoria_servicio = CONVERT(CAST(CONVERT(descripcion_categoria_servicio USING latin1) AS BINARY) USING utf8mb4) WHERE descripcion_categoria_servicio REGEXP '├';

SELECT 'UTF-8 encoding fix completed successfully' AS status;
EOSQL
