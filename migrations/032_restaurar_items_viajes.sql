-- Restaurar items faltantes del menú de Viajes
-- Fecha: 2026-01-19

SET @id_transporte = (SELECT id FROM admin_menu WHERE label = 'TRANSPORTE' LIMIT 1);
SET @id_viajes = (SELECT id FROM admin_menu WHERE parent_id = @id_transporte AND label = 'Viajes' LIMIT 1);

-- Ver qué hay actualmente bajo Viajes
SELECT 'Items actuales bajo Viajes (ID ' + CAST(@id_viajes AS CHAR) + '):' AS status;
SELECT 
    CONCAT('  ID ', id, ': ', label, ' → ', route) as item,
    sort_order
FROM admin_menu
WHERE parent_id = @id_viajes
ORDER BY sort_order;

-- Restaurar items que faltaban
INSERT IGNORE INTO admin_menu (parent_id, label, route, icon, sort_order, enabled, created_at)
VALUES 
(@id_viajes, 'Ver Viajes', 'viajesTransporteLista.php', 'fas fa-list', 1, 1, NOW()),
(@id_viajes, 'Crear Viaje', 'viajeTransporteAlta.php', 'fas fa-plus', 2, 1, NOW());

-- Actualizar sort_order de Vehículos que quedó bajo Viajes
UPDATE admin_menu 
SET sort_order = 3 
WHERE parent_id = @id_viajes AND label = 'Vehículos';

SELECT '' AS '';
SELECT 'Items restaurados:' AS status;
SELECT 
    CONCAT('  ID ', id, ': ', label, ' → ', route) as item,
    sort_order
FROM admin_menu
WHERE parent_id = @id_viajes
ORDER BY sort_order;
