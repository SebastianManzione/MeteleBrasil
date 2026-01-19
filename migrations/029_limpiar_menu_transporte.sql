-- Limpieza y reorganización final del menú TRANSPORTE
-- Elimina duplicados y deja solo la estructura correcta
-- Fecha: 2026-01-19

-- Obtener IDs
SET @id_transporte = (SELECT id FROM admin_menu WHERE label = 'TRANSPORTE' LIMIT 1);
SET @id_catalogo = (SELECT id FROM admin_menu WHERE parent_id = @id_transporte AND label LIKE '%CATÁLOGO%' LIMIT 1);
SET @id_operaciones = (SELECT id FROM admin_menu WHERE parent_id = @id_transporte AND label LIKE '%OPERACIONES%' LIMIT 1);
SET @id_ventas = (SELECT id FROM admin_menu WHERE parent_id = @id_transporte AND label LIKE '%VENTAS%' LIMIT 1);

-- Eliminar duplicados (items que están bajo Viajes ID 45 pero ya están en OPERACIONES)
DELETE FROM admin_menu 
WHERE parent_id = 45 
  AND label IN ('Ver Viajes', 'Crear Viaje', 'Modelos', 'Vehículos', 'Clases de Servicio');

-- Actualizar Empresas si existe
UPDATE admin_menu 
SET parent_id = @id_catalogo, sort_order = 2 
WHERE parent_id = @id_transporte AND label = 'Empresas' AND id != 55 AND id != 56 AND id != 57;

-- Eliminar Hoteles de TRANSPORTE (pertenece a otro sistema)
DELETE FROM admin_menu WHERE parent_id = @id_transporte AND label = 'Hoteles';

-- Eliminar "Precios Segmentos" de nivel superior (pertenece bajo Viajes)
DELETE FROM admin_menu WHERE parent_id = @id_transporte AND label LIKE '%Precios%';

-- Reorganizar items bajo Viajes (45) a OPERACIONES
UPDATE admin_menu 
SET parent_id = @id_operaciones, sort_order = 5 
WHERE parent_id = 45 AND label LIKE '%Segmento%';

-- Actualizar sort_order de CATÁLOGO para que incluya Empresas
UPDATE admin_menu 
SET sort_order = 2 
WHERE parent_id = @id_catalogo AND label = 'Empresas';

UPDATE admin_menu 
SET sort_order = 3 
WHERE parent_id = @id_catalogo AND label = 'Modelos de Vehículos';

-- Resultado final
SELECT '' AS '';
SELECT '✅ Estructura limpia y reorganizada' AS resultado;
SELECT '' AS '';

SELECT 'MENÚ TRANSPORTE - ESTRUCTURA FINAL:' as seccion;
SELECT 
    CONCAT(REPEAT('  ', 
        CASE 
            WHEN id = @id_transporte THEN 0
            WHEN parent_id = @id_transporte THEN 1
            ELSE 2
        END
    ), label) AS estructura,
    route,
    sort_order
FROM admin_menu
WHERE id = @id_transporte 
   OR parent_id = @id_transporte 
   OR parent_id IN (@id_catalogo, @id_operaciones, @id_ventas)
ORDER BY 
    CASE 
        WHEN id = @id_transporte THEN 0
        WHEN parent_id = @id_transporte THEN 1 + sort_order / 1000
        ELSE 2 + parent_id / 1000 + sort_order / 1000000
    END;
