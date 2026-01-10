-- Agregar "Gestión Slider" en el menú de Administración
-- Primero buscar el ID del menú padre "Administración"
SET @parent_id = (SELECT id FROM admin_menu WHERE label = 'Administración' LIMIT 1);

-- Si no existe el padre "Administración", crearlo primero
INSERT INTO admin_menu (label, route, icon, parent_id, orden, color_class)
SELECT 'Administración', '#', 'fas fa-cogs', NULL, 90, NULL
WHERE NOT EXISTS (SELECT 1 FROM admin_menu WHERE label = 'Administración');

-- Actualizar variable con el ID correcto
SET @parent_id = (SELECT id FROM admin_menu WHERE label = 'Administración' LIMIT 1);

-- Insertar "Gestión Slider" como hijo de "Administración"
INSERT INTO admin_menu (label, route, icon, parent_id, orden, color_class)
VALUES ('Gestión Slider', 'sliderLista.php', 'fas fa-images', @parent_id, 10, NULL);

-- Obtener el ID del nuevo menú
SET @slider_menu_id = LAST_INSERT_ID();

-- Asignar permisos al rol admin (rol_id = 1)
INSERT INTO admin_menu_roles (menu_id, role_id)
VALUES (@slider_menu_id, 1);
