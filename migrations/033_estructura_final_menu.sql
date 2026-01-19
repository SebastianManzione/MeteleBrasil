-- Limpiar duplicados y estructura final correcta
-- El menú de Viajes debe tener un submenú con opciones

SET @id_transporte = (SELECT id FROM admin_menu WHERE label = 'TRANSPORTE' LIMIT 1);

-- Eliminar "Viajes" de nivel superior (quedó duplicado)
DELETE FROM admin_menu 
WHERE parent_id = @id_transporte AND label = 'Viajes' AND id != (SELECT MIN(id) FROM admin_menu WHERE parent_id = @id_transporte AND label = 'Viajes');

-- Estructura final:
-- TRANSPORTE
--   ├── Terminales
--   ├── Vehículos
--   ├── Modelos de Vehículos
--   ├── Rutas  
--   ├── Viajes (menú padre)
--   │   ├── Ver Viajes
--   │   ├── Crear Viaje
--   │   └── Vehículos (bajo Viajes)
--   ├── Clases de Butaca
--   └── Reservas

-- Reorganizar items planos bajo TRANSPORTE
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 1 WHERE parent_id = @id_transporte AND label = 'Terminales';
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 2 WHERE parent_id = @id_transporte AND label = 'Vehículos' AND id NOT IN (SELECT id FROM admin_menu WHERE parent_id IN (SELECT id FROM admin_menu WHERE label = 'Viajes'));
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 3 WHERE parent_id = @id_transporte AND label LIKE '%Modelos%';
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 4 WHERE parent_id = @id_transporte AND label = 'Rutas';
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 5 WHERE parent_id = @id_transporte AND label = 'Viajes';
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 6 WHERE parent_id = @id_transporte AND label LIKE '%Butaca%';
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 7 WHERE parent_id = @id_transporte AND label = 'Reservas';

SET @id_viajes = (SELECT id FROM admin_menu WHERE parent_id = @id_transporte AND label = 'Viajes' LIMIT 1);

-- Reorganizar items bajo Viajes
UPDATE admin_menu SET parent_id = @id_viajes, sort_order = 1 WHERE parent_id = @id_viajes AND label = 'Ver Viajes';
UPDATE admin_menu SET parent_id = @id_viajes, sort_order = 2 WHERE parent_id = @id_viajes AND label = 'Crear Viaje';
UPDATE admin_menu SET parent_id = @id_viajes, sort_order = 3 WHERE parent_id = @id_viajes AND label = 'Vehículos';

SELECT '' AS '';
SELECT '✅ Estructura limpia y final' AS resultado;
SELECT '' AS '';

SELECT 'MENÚ TRANSPORTE - ESTRUCTURA FINAL:' as titulo;
SELECT 
    CONCAT(
        REPEAT('  ', 
            CASE 
                WHEN id = @id_transporte THEN 0
                WHEN parent_id = @id_transporte THEN 1
                ELSE 2
            END
        ), 
        label
    ) AS estructura,
    route,
    sort_order
FROM admin_menu
WHERE id = @id_transporte 
   OR parent_id = @id_transporte 
   OR parent_id = @id_viajes
ORDER BY 
    CASE WHEN id = @id_transporte THEN 0 ELSE 1 END,
    parent_id,
    sort_order;
