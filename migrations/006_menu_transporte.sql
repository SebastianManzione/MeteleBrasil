-- Insertar menú de transporte en admin_menu
-- Este menú estará oculto por defecto y se mostrará con F9/F8
-- Fecha: 2026-01-16
-- Base de datos: metelebrasil_experimental

-- Buscar el último sort_order en el menú principal
SET @ultimo_orden = (SELECT IFNULL(MAX(sort_order), 0) FROM admin_menu WHERE parent_id IS NULL);

-- Insertar menú padre "TRANSPORTE" (oculto por defecto)
INSERT INTO admin_menu (parent_id, label, route, icon, sort_order, color_class, enabled, created_at)
VALUES 
(NULL, 'TRANSPORTE', '#', 'fas fa-bus', @ultimo_orden + 1, 'text-warning', 1, NOW());

-- Obtener ID del menú padre recién insertado
SET @id_transporte = LAST_INSERT_ID();

-- Insertar submenús de transporte
INSERT INTO admin_menu (parent_id, label, route, icon, sort_order, color_class, enabled, created_at)
VALUES 
-- Terminales
(@id_transporte, 'Terminales', 'terminalesLista.php', 'fas fa-map-marker-alt', 1, NULL, 1, NOW()),
-- Rutas
(@id_transporte, 'Rutas', 'rutasTransporteLista.php', 'fas fa-route', 2, NULL, 1, NOW()),
-- Empresas (para futuro)
(@id_transporte, 'Empresas', 'empresasTransporteLista.php', 'fas fa-building', 3, NULL, 1, NOW()),
-- Viajes (para futuro)
(@id_transporte, 'Viajes', 'viajesTransporteLista.php', 'fas fa-calendar-alt', 4, NULL, 1, NOW()),
-- Reservas de transporte (para futuro)
(@id_transporte, 'Reservas', 'reservasTransporteLista.php', 'fas fa-ticket-alt', 5, NULL, 1, NOW());

-- Verificar inserción
SELECT 
    m.id,
    CASE WHEN m.parent_id IS NULL THEN m.label ELSE CONCAT('  └─ ', m.label) END as menu_item,
    m.route,
    m.icon,
    m.sort_order
FROM admin_menu m
WHERE m.id = @id_transporte OR m.parent_id = @id_transporte
ORDER BY m.parent_id IS NULL DESC, m.sort_order;

-- Mensaje final
SELECT CONCAT('Menú TRANSPORTE creado con ID: ', @id_transporte) as resultado;
SELECT '⚠️  IMPORTANTE: Este menú está oculto por defecto.' as nota;
SELECT '✅ Presiona F9 para mostrar | F8 para ocultar' as instrucciones;
