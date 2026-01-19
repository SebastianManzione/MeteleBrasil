-- Reorganizar menú de TRANSPORTE
-- Estructura mejorada:
-- TRANSPORTE
--   ├── CATÁLOGO
--   │   ├── Terminales
--   │   ├── Empresas
--   │   └── Modelos de Vehículos
--   ├── OPERACIONES
--   │   ├── Vehículos
--   │   ├── Rutas
--   │   ├── Viajes
--   │   └── Clases de Butaca
--   ├── VENTAS
--   │   └── Reservas de Pasajes
--
-- Fecha: 2026-01-19
-- Base de datos: metelebrasil_experimental

-- Verificar que existen los menús actuales
SELECT 'Estructura actual:' as paso;
SELECT 
    id,
    parent_id,
    CASE WHEN parent_id IS NULL THEN label ELSE CONCAT('  └─ ', label) END as menu_item,
    route,
    sort_order
FROM admin_menu
WHERE label = 'TRANSPORTE' OR parent_id = (SELECT id FROM admin_menu WHERE label = 'TRANSPORTE')
ORDER BY parent_id IS NULL DESC, sort_order;

-- Obtener IDs
SET @id_transporte = (SELECT id FROM admin_menu WHERE label = 'TRANSPORTE' LIMIT 1);
SET @id_terminales = (SELECT id FROM admin_menu WHERE parent_id = @id_transporte AND label = 'Terminales' LIMIT 1);
SET @id_rutas = (SELECT id FROM admin_menu WHERE parent_id = @id_transporte AND label = 'Rutas' LIMIT 1);
SET @id_empresas = (SELECT id FROM admin_menu WHERE parent_id = @id_transporte AND label = 'Empresas' LIMIT 1);
SET @id_viajes = (SELECT id FROM admin_menu WHERE parent_id = @id_transporte AND label = 'Viajes' LIMIT 1);
SET @id_reservas = (SELECT id FROM admin_menu WHERE parent_id = @id_transporte AND label = 'Reservas' LIMIT 1);

-- Crear submenús de categoría (CATÁLOGO, OPERACIONES, VENTAS)
INSERT INTO admin_menu (parent_id, label, route, icon, sort_order, color_class, enabled, created_at)
VALUES 
(@id_transporte, '📦 CATÁLOGO', '#', 'fas fa-cube', 1, 'text-info', 1, NOW()),
(@id_transporte, '⚙️  OPERACIONES', '#', 'fas fa-cogs', 2, 'text-success', 1, NOW()),
(@id_transporte, '💰 VENTAS', '#', 'fas fa-money-bill', 3, 'text-danger', 1, NOW());

-- Obtener IDs de los nuevos submenús
SET @id_catalogo = (SELECT id FROM admin_menu WHERE parent_id = @id_transporte AND label = '📦 CATÁLOGO' LIMIT 1);
SET @id_operaciones = (SELECT id FROM admin_menu WHERE parent_id = @id_transporte AND label = '⚙️  OPERACIONES' LIMIT 1);
SET @id_ventas = (SELECT id FROM admin_menu WHERE parent_id = @id_transporte AND label = '💰 VENTAS' LIMIT 1);

-- Reorganizar items existentes a sus nuevas categorías
UPDATE admin_menu SET parent_id = @id_catalogo, sort_order = 1 WHERE id = @id_terminales;
UPDATE admin_menu SET parent_id = @id_catalogo, sort_order = 2 WHERE id = @id_empresas;

-- Crear Modelos de Vehículos bajo CATÁLOGO si no existe
INSERT IGNORE INTO admin_menu (parent_id, label, route, icon, sort_order, color_class, enabled, created_at)
VALUES 
(@id_catalogo, 'Modelos de Vehículos', 'modeloVehiculosLista.php', 'fas fa-shuttle-van', 3, NULL, 1, NOW());

-- Crear Vehículos bajo OPERACIONES si no existe
INSERT IGNORE INTO admin_menu (parent_id, label, route, icon, sort_order, color_class, enabled, created_at)
VALUES 
(@id_operaciones, 'Vehículos', 'vehiculosTransporteLista.php', 'fas fa-bus', 1, NULL, 1, NOW());

-- Reorganizar Rutas a OPERACIONES
UPDATE admin_menu SET parent_id = @id_operaciones, sort_order = 2 WHERE id = @id_rutas;

-- Reorganizar Viajes a OPERACIONES
UPDATE admin_menu SET parent_id = @id_operaciones, sort_order = 3 WHERE id = @id_viajes;

-- Crear Clases de Butaca bajo OPERACIONES si no existe
INSERT IGNORE INTO admin_menu (parent_id, label, route, icon, sort_order, color_class, enabled, created_at)
VALUES 
(@id_operaciones, 'Clases de Butaca', 'tipoButacaTransporte.php', 'fas fa-chair', 4, NULL, 1, NOW());

-- Reorganizar Reservas a VENTAS
UPDATE admin_menu SET parent_id = @id_ventas, sort_order = 1 WHERE id = @id_reservas;

-- Resultado final
SELECT CONCAT('✅ Menú reorganizado. ID TRANSPORTE: ', @id_transporte) as resultado;
SELECT '' as espacio;

SELECT 'Nueva estructura:' as paso;
SELECT 
    id,
    parent_id,
    CASE 
        WHEN parent_id IS NULL THEN label
        WHEN parent_id = @id_transporte THEN CONCAT('  │ ', label)
        ELSE CONCAT('  ├─ ', label)
    END as menu_item,
    route,
    sort_order
FROM admin_menu
WHERE id = @id_transporte 
   OR parent_id = @id_transporte 
   OR parent_id IN (@id_catalogo, @id_operaciones, @id_ventas)
ORDER BY 
    CASE 
        WHEN id = @id_transporte THEN 0
        WHEN parent_id = @id_transporte THEN sort_order + 1000
        ELSE parent_id * 100 + sort_order
    END,
    sort_order;
