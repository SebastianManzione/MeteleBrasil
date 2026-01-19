-- Revertir a estructura simple y funcional
-- Problema: los submenús en 3 niveles no se renderean correctamente
-- Solución: estructura plana con 2 niveles máximo

SET @id_transporte = (SELECT id FROM admin_menu WHERE label = 'TRANSPORTE' LIMIT 1);

-- Eliminar los menús intermediarios que no funcionan
DELETE FROM admin_menu WHERE parent_id = @id_transporte AND label LIKE '%CATÁLOGO%';
DELETE FROM admin_menu WHERE parent_id = @id_transporte AND label LIKE '%OPERACIONES%';
DELETE FROM admin_menu WHERE parent_id = @id_transporte AND label LIKE '%VENTAS%';

-- Obtener IDs de los submenús que quedaban huérfanos
SET @id_catalogo = NULL;
SET @id_operaciones = NULL;
SET @id_ventas = NULL;

-- Reorganizar directamente bajo TRANSPORTE
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 1 WHERE label = 'Terminales' AND parent_id IS NOT NULL;
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 2 WHERE label = 'Empresas' AND parent_id IS NOT NULL;
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 3 WHERE label = 'Vehículos' AND parent_id IS NOT NULL;
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 4 WHERE label = 'Modelos de Vehículos' AND parent_id IS NOT NULL;
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 5 WHERE label = 'Rutas' AND parent_id IS NOT NULL;
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 6 WHERE label = 'Viajes' AND parent_id IS NOT NULL;
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 7 WHERE label = 'Clases de Butaca' AND parent_id IS NOT NULL;
UPDATE admin_menu SET parent_id = @id_transporte, sort_order = 8 WHERE label = 'Reservas' AND parent_id IS NOT NULL;

SELECT CONCAT('✅ Estructura simplificada. Total items bajo TRANSPORTE: ', COUNT(*)) as resultado
FROM admin_menu 
WHERE parent_id = @id_transporte;

SELECT 'Nueva estructura (plana, solo 2 niveles):' AS titulo;
SELECT 
    CONCAT('  ', label) as item,
    route,
    sort_order
FROM admin_menu
WHERE parent_id = @id_transporte
ORDER BY sort_order;
