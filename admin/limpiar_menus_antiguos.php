<?php
require_once(__DIR__ . '/classes/conexion.php');

echo "=== ACTUALIZACIÓN DE MENÚS A adminMenuRoles.php ===\n\n";

// 1. Obtener ID del nuevo menú
$stmt = $GLOBALS['pdo']->prepare("SELECT id FROM admin_menu WHERE route = 'adminMenuRoles'");
$stmt->execute();
$nuevoMenu = $stmt->fetch(PDO::FETCH_ASSOC);
$nuevoMenuId = $nuevoMenu['id'] ?? null;

if (!$nuevoMenuId) {
    echo "✗ Error: No se encontró el menú 'adminMenuRoles'\n";
    exit;
}

echo "Menú unificado encontrado: ID $nuevoMenuId\n\n";

// 2. Obtener IDs de los menús antiguos
$stmt = $GLOBALS['pdo']->query("
    SELECT id, label, route FROM admin_menu 
    WHERE route IN ('menuEditor', 'rolesPermisos')
    ORDER BY id
");
$menusAntiguos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Menús a actualizar:\n";
foreach ($menusAntiguos as $m) {
    echo "  ID {$m['id']}: {$m['label']} (route: {$m['route']})\n";
}

// 3. Transferir permisos de menús antiguos al nuevo (si existen)
echo "\n=== Migrando permisos ===\n";

$permisosTransferidos = [];
foreach ($menusAntiguos as $menu) {
    $stmt = $GLOBALS['pdo']->prepare("
        SELECT role_id FROM admin_menu_roles WHERE menu_id = ?
    ");
    $stmt->execute([$menu['id']]);
    $permisos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($permisos)) {
        $stmt = $GLOBALS['pdo']->prepare("
            INSERT IGNORE INTO admin_menu_roles (menu_id, role_id) 
            VALUES (?, ?)
        ");
        
        foreach ($permisos as $roleId) {
            $stmt->execute([$nuevoMenuId, $roleId]);
            $permisosTransferidos[] = "Rol $roleId";
        }
        
        echo "✓ {$menu['label']}: Permisos transferidos (" . implode(", ", array_unique($permisosTransferidos)) . ")\n";
        $permisosTransferidos = [];
    }
}

// 4. Eliminar registros de permisos antiguos
echo "\n=== Eliminando permisos antiguos ===\n";

foreach ($menusAntiguos as $menu) {
    $stmt = $GLOBALS['pdo']->prepare("DELETE FROM admin_menu_roles WHERE menu_id = ?");
    $stmt->execute([$menu['id']]);
    $deleted = $stmt->rowCount();
    echo "✓ {$menu['label']}: $deleted registros eliminados\n";
}

// 5. Eliminar los menús antiguos
echo "\n=== Eliminando menús antiguos ===\n";

foreach ($menusAntiguos as $menu) {
    $stmt = $GLOBALS['pdo']->prepare("DELETE FROM admin_menu WHERE id = ?");
    $stmt->execute([$menu['id']]);
    echo "✓ {$menu['label']} (ID {$menu['id']}) eliminado\n";
}

echo "\n=== COMPLETADO ===\n";
echo "✓ Menú unificado 'Administración de Menú' activo (ID $nuevoMenuId)\n";
echo "✓ Menús antiguos eliminados de la BD\n";
echo "✓ Permisos migrados correctamente\n";
echo "\n✓ Accede a: http://localhost/metelebrasil_dev/admin/adminMenuRoles.php\n";
?>
