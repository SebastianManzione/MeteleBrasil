SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

DELETE FROM admin_menu WHERE id IN (47,48,49);

INSERT INTO admin_menu (id, label, route, icon, parent_id, sort_order, enabled, color_class) VALUES 
(47, 'Clases de Servicio', 'viajeClasesLista.php', 'fas fa-layer-group', 45, 3, 1, NULL),
(48, 'Modelos', 'modeloVehiculosLista.php', 'fas fa-cube', 45, 1, 1, NULL),
(49, 'Vehículos', 'vehiculosTransporteLista.php', 'fas fa-bus', 45, 2, 1, NULL);

INSERT IGNORE INTO admin_menu_roles (menu_id, role_id) VALUES
(47, 1), (47, 2), (47, 3), (47, 4), (47, 5),
(48, 1), (48, 2), (48, 3), (48, 4), (48, 5),
(49, 1), (49, 2), (49, 3), (49, 4), (49, 5);

SELECT id, label FROM admin_menu WHERE id >= 47 ORDER BY id;
