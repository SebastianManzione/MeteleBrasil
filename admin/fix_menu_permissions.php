<?php
require_once('admin/classes/conexion.php');

echo "=== Verificación de permisos de menú ===\n\n";

// Contar registros en admin_menu
$stmt = $GLOBALS['pdo']->query('SELECT COUNT(*) as total FROM admin_menu');
$totalMenu = $stmt->fetch()['total'];
echo "Total ítems en admin_menu: $totalMenu\n";

// Contar registros en admin_menu_roles
$stmt = $GLOBALS['pdo']->query('SELECT COUNT(*) as total FROM admin_menu_roles');
$totalRoles = $stmt->fetch()['total'];
echo "Total registros en admin_menu_roles: $totalRoles\n\n";

if ($totalRoles === 0) {
    echo "⚠️ PROBLEMA DETECTADO: No hay permisos asignados!\n\n";
    echo "Asignando todos los ítems al rol Admin (rol_id=1)...\n";
    
    // Asignar todos los menús al rol admin
    $stmt = $GLOBALS['pdo']->query('SELECT id FROM admin_menu');
    $menus = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $inserted = 0;
    foreach ($menus as $menuId) {
        $stmt = $GLOBALS['pdo']->prepare('INSERT IGNORE INTO admin_menu_roles (menu_id, role_id) VALUES (?, 1)');
        $stmt->execute([$menuId]);
        $inserted++;
    }
    
    echo "✅ Asignados $inserted ítems de menú al rol Admin\n";
    
    // Verificar nuevamente
    $stmt = $GLOBALS['pdo']->query('SELECT COUNT(*) as total FROM admin_menu_roles');
    $totalRoles = $stmt->fetch()['total'];
    echo "Total registros ahora en admin_menu_roles: $totalRoles\n";
} else {
    echo "✅ Permisos existentes en la base de datos\n";
    
    // Mostrar distribución por rol
    $stmt = $GLOBALS['pdo']->query('SELECT role_id, COUNT(*) as total FROM admin_menu_roles GROUP BY role_id');
    echo "\nDistribución de permisos por rol:\n";
    while ($row = $stmt->fetch()) {
        echo "  - Rol {$row['role_id']}: {$row['total']} ítems\n";
    }
}
?>
