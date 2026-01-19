-- Ajuste final de sort_order para estructura limpia
-- Fecha: 2026-01-19

SET @id_catalogo = (SELECT id FROM admin_menu WHERE label LIKE '%CATÁLOGO%' AND parent_id = 41 LIMIT 1);
SET @id_operaciones = (SELECT id FROM admin_menu WHERE label LIKE '%OPERACIONES%' AND parent_id = 41 LIMIT 1);
SET @id_ventas = (SELECT id FROM admin_menu WHERE label LIKE '%VENTAS%' AND parent_id = 41 LIMIT 1);

-- Ordenar CATÁLOGO
UPDATE admin_menu SET sort_order = 1 WHERE id = @id_catalogo;
UPDATE admin_menu SET sort_order = 1 WHERE parent_id = @id_catalogo AND label = 'Terminales';
UPDATE admin_menu SET sort_order = 2 WHERE parent_id = @id_catalogo AND label = 'Empresas';
UPDATE admin_menu SET sort_order = 3 WHERE parent_id = @id_catalogo AND label LIKE '%Vehículos%' AND label LIKE '%Modelos%';

-- Ordenar OPERACIONES
UPDATE admin_menu SET sort_order = 2 WHERE id = @id_operaciones;
UPDATE admin_menu SET sort_order = 1 WHERE parent_id = @id_operaciones AND label = 'Vehículos';
UPDATE admin_menu SET sort_order = 2 WHERE parent_id = @id_operaciones AND label = 'Rutas';
UPDATE admin_menu SET sort_order = 3 WHERE parent_id = @id_operaciones AND label = 'Viajes';
UPDATE admin_menu SET sort_order = 4 WHERE parent_id = @id_operaciones AND label = 'Clases de Butaca';

-- Ordenar VENTAS
UPDATE admin_menu SET sort_order = 3 WHERE id = @id_ventas;
UPDATE admin_menu SET sort_order = 1 WHERE parent_id = @id_ventas AND label = 'Reservas';

-- Mostrar resultado
SELECT CONCAT('✅ Menú reorganizado exitosamente') AS resultado;
SELECT '' as '';
SELECT 'ESTRUCTURA FINAL (Presiona F9 para ver):' AS titulo;
SELECT 
    CONCAT(
        REPEAT('  ', 
            CASE 
                WHEN id = 41 THEN 0
                WHEN parent_id = 41 THEN 1
                ELSE 2
            END
        ), 
        label,
        CASE 
            WHEN id IN (@id_catalogo, @id_operaciones, @id_ventas) THEN ' /'
            ELSE ''
        END
    ) AS item,
    sort_order
FROM admin_menu
WHERE id = 41 
   OR parent_id = 41 
   OR parent_id IN (@id_catalogo, @id_operaciones, @id_ventas)
ORDER BY 
    parent_id,
    sort_order;
