<?php
/**
 * Script para agregar menú de Transporte al admin
 * Ejecutar una sola vez: php agregar_menu_transporte.php
 */

require_once("classes/conexion.php");

echo "=== Agregando Menú de Transporte ===\n\n";

// 1. Insertar menú principal Transporte
$consultaPadre = "INSERT INTO admin_menu (nombre, url, icon, parent_id, orden, activo) 
                  VALUES ('Transporte', '#', 'fas fa-bus', NULL, 50, 1)";

try {
    $pdo->exec($consultaPadre);
    $transporteId = $pdo->lastInsertId();
    echo "✓ Menú 'Transporte' creado con ID: $transporteId\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate') !== false) {
        // Ya existe, obtener ID
        $stmt = $pdo->query("SELECT id FROM admin_menu WHERE nombre = 'Transporte' AND parent_id IS NULL");
        $transporteId = $stmt->fetchColumn();
        echo "⚠ Menú 'Transporte' ya existe con ID: $transporteId\n";
    } else {
        die("Error: " . $e->getMessage() . "\n");
    }
}

// 2. Insertar submenús
$submenus = [
    ['nombre' => 'Terminales', 'url' => 'terminalesLista.php', 'icon' => 'fas fa-map-marker-alt', 'orden' => 1],
    ['nombre' => 'Rutas', 'url' => 'rutasTransporteLista.php', 'icon' => 'fas fa-route', 'orden' => 2],
    ['nombre' => 'Viajes', 'url' => 'viajesTransporteLista.php', 'icon' => 'fas fa-calendar-alt', 'orden' => 3],
    ['nombre' => 'Empresas', 'url' => 'empresasTransporteLista.php', 'icon' => 'fas fa-building', 'orden' => 4]
];

$menuIds = [$transporteId]; // Para asignar permisos después

foreach ($submenus as $submenu) {
    $consulta = "INSERT INTO admin_menu (nombre, url, icon, parent_id, orden, activo) 
                 VALUES (:nombre, :url, :icon, :parent_id, :orden, 1)";
    
    try {
        $stmt = $pdo->prepare($consulta);
        $stmt->execute([
            'nombre' => $submenu['nombre'],
            'url' => $submenu['url'],
            'icon' => $submenu['icon'],
            'parent_id' => $transporteId,
            'orden' => $submenu['orden']
        ]);
        $submenuId = $pdo->lastInsertId();
        $menuIds[] = $submenuId;
        echo "✓ Submenú '{$submenu['nombre']}' creado con ID: $submenuId\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate') !== false) {
            $stmt = $pdo->prepare("SELECT id FROM admin_menu WHERE nombre = :nombre AND parent_id = :parent_id");
            $stmt->execute(['nombre' => $submenu['nombre'], 'parent_id' => $transporteId]);
            $submenuId = $stmt->fetchColumn();
            $menuIds[] = $submenuId;
            echo "⚠ Submenú '{$submenu['nombre']}' ya existe con ID: $submenuId\n";
        } else {
            echo "Error en {$submenu['nombre']}: " . $e->getMessage() . "\n";
        }
    }
}

// 3. Asignar permisos al rol Admin (role_id = 1)
echo "\n=== Asignando permisos al rol Admin ===\n";
foreach ($menuIds as $menuId) {
    $consulta = "INSERT IGNORE INTO admin_menu_roles (menu_id, role_id) VALUES (:menu_id, 1)";
    try {
        $stmt = $pdo->prepare($consulta);
        $stmt->execute(['menu_id' => $menuId]);
        echo "✓ Permiso asignado para menú ID: $menuId\n";
    } catch (PDOException $e) {
        echo "⚠ Error asignando permiso para menú $menuId: " . $e->getMessage() . "\n";
    }
}

echo "\n=== ¡Menú de Transporte instalado correctamente! ===\n";
echo "Podés acceder desde el admin a: terminalesLista.php\n";
?>
