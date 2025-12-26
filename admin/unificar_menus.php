<?php
require_once(__DIR__ . '/classes/conexion.php');

echo "=== UNIFICACIÓN DE MENÚS: adminMenuRoles.php ===\n\n";

// Buscar menús antiguos
$stmt = $GLOBALS['pdo']->query("
    SELECT id, label, route, parent_id FROM admin_menu 
    WHERE route IN ('menuEditor', 'rolesPermisos')
    ORDER BY id
");

$menus_antiguos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Menús encontrados:\n";
foreach ($menus_antiguos as $m) {
    echo "  ID {$m['id']}: {$m['label']} (route: {$m['route']})\n";
}

// Verificar si ya existe adminMenuRoles
$stmt = $GLOBALS['pdo']->prepare("SELECT id FROM admin_menu WHERE route = 'adminMenuRoles'");
$stmt->execute();
$existe_nuevo = $stmt->fetch();

if ($existe_nuevo) {
    echo "\n✓ El menú 'adminMenuRoles' ya existe (ID: {$existe_nuevo['id']})\n";
} else {
    echo "\nAgregando menú unificado 'Administración de Menú y Roles'...\n";
    
    // Buscar parent apropiado (Administración)
    $stmt = $GLOBALS['pdo']->query("
        SELECT id FROM admin_menu 
        WHERE (label LIKE '%Administra%' OR label LIKE '%Test%')
        AND parent_id IS NULL
        ORDER BY id 
        LIMIT 1
    ");
    $parent = $stmt->fetch();
    
    $parentId = $parent ? $parent['id'] : null;
    
    // Insertar nuevo menú
    $stmt = $GLOBALS['pdo']->prepare("
        INSERT INTO admin_menu (label, route, icon, color_class, parent_id, sort_order, enabled)
        VALUES ('Administración de Menú', 'adminMenuRoles', 'fas fa-cogs', '', ?, 1, 1)
    ");
    $stmt->execute([$parentId]);
    $nuevoId = $GLOBALS['pdo']->lastInsertId();
    
    echo "✓ Menú agregado con ID: $nuevoId\n";
    
    // Asignar permiso solo a Admin
    $stmt = $GLOBALS['pdo']->prepare("
        INSERT INTO admin_menu_roles (menu_id, role_id) VALUES (?, 1)
    ");
    $stmt->execute([$nuevoId]);
    
    echo "✓ Permiso asignado al rol Administrador\n";
}

echo "\n=== ACTUALIZANDO PERMISOS ===\n\n";

// Buscar IDs de los menús antiguos para migrar permisos
foreach ($menus_antiguos as $menu) {
    $stmt = $GLOBALS['pdo']->prepare("
        SELECT role_id FROM admin_menu_roles WHERE menu_id = ?
    ");
    $stmt->execute([$menu['id']]);
    $permisos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($permisos)) {
        echo "Migrando permisos de '{$menu['label']}':\n";
        
        // Asignar los mismos permisos al nuevo menú
        foreach ($permisos as $role_id) {
            // Verificar si ya existe
            $stmt = $GLOBALS['pdo']->prepare("
                SELECT COUNT(*) as cnt FROM admin_menu_roles 
                WHERE menu_id = (SELECT id FROM admin_menu WHERE route = 'adminMenuRoles')
                AND role_id = ?
            ");
            $stmt->execute([$role_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row['cnt'] == 0) {
                $stmt = $GLOBALS['pdo']->prepare("
                    INSERT INTO admin_menu_roles (menu_id, role_id)
                    SELECT id, ? FROM admin_menu WHERE route = 'adminMenuRoles'
                ");
                $stmt->execute([$role_id]);
                echo "  ✓ Permiso agregado para rol ID $role_id\n";
            }
        }
    }
}

echo "\n=== RESULTADO ===\n\n";
echo "✓ Nuevo menú unificado creado\n";
echo "✓ Permisos migrados de los menús antiguos\n";
echo "✓ Puedes eliminar las páginas antiguas:\n";
echo "  - admin/menuEditor.php\n";
echo "  - admin/rolesPermisos.php\n";
echo "\n✓ Accede a: admin/adminMenuRoles.php\n";
?>
