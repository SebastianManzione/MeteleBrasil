-- =========================================
-- AGREGAR MENÚ DE TRANSPORTE AL ADMIN
-- Fecha: 2026-01-16
-- =========================================

-- Insertar sección de Transporte en el menú (AUTO_INCREMENT)
INSERT IGNORE INTO `admin_menu` (`nombre`, `url`, `icon`, `parent_id`, `orden`, `activo`) 
VALUES 
('Transporte', '#', 'fas fa-bus', NULL, 50, 1);

SET @transporte_id = LAST_INSERT_ID();

-- Submenús de Transporte
INSERT IGNORE INTO `admin_menu` (`nombre`, `url`, `icon`, `parent_id`, `orden`, `activo`) 
VALUES 
('Terminales', 'terminalesLista.php', 'fas fa-map-marker-alt', @transporte_id, 1, 1),
('Rutas', 'rutasTransporteLista.php', 'fas fa-route', @transporte_id, 2, 1),
('Viajes', 'viajesTransporteLista.php', 'fas fa-calendar-alt', @transporte_id, 3, 1),
('Empresas', 'empresasTransporteLista.php', 'fas fa-building', @transporte_id, 4, 1);

-- Asignar permisos al rol Admin (rol_id = 1)
-- Nota: Necesitarás ejecutar esto manualmente con los IDs generados
-- SELECT id FROM admin_menu WHERE nombre IN ('Transporte', 'Terminales', 'Rutas', 'Viajes', 'Empresas');
-- Y luego: INSERT INTO admin_menu_roles (menu_id, role_id) VALUES (ID_AQUI, 1);

-- Si también quieres darlo a prestadores (rol_id = 2), descomentar:
-- INSERT INTO `admin_menu_roles` (`menu_id`, `role_id`) VALUES
-- (41, 2), (42, 2), (43, 2), (44, 2), (45, 2);
