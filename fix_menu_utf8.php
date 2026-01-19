<?php
/**
 * Script para reinsert correcto de menú items con UTF-8
 */

require_once __DIR__ . '/admin/classes/db.php';

echo "<h2>Reinsertando items de menú con UTF-8 correcto</h2>";
echo "<pre>";

// Items a reinsertar
$items = [
    [
        'id'         => 47,
        'label'      => 'Clases de Servicio',
        'route'      => 'viajeClasesLista.php',
        'icon'       => 'fas fa-layer-group',
        'parent_id'  => 45,
        'sort_order' => 3,
        'color_class' => null
    ],
    [
        'id'         => 48,
        'label'      => 'Modelos',
        'route'      => 'modeloVehiculosLista.php',
        'icon'       => 'fas fa-cube',
        'parent_id'  => 45,
        'sort_order' => 1,
        'color_class' => null
    ],
    [
        'id'         => 49,
        'label'      => 'Vehículos',
        'route'      => 'vehiculosTransporteLista.php',
        'icon'       => 'fas fa-bus',
        'parent_id'  => 45,
        'sort_order' => 2,
        'color_class' => null
    ]
];

foreach ($items as $item) {
    $sql = "INSERT INTO admin_menu 
            (id, label, route, icon, color_class, parent_id, sort_order, enabled)
            VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        $item['id'],
        $item['label'],
        $item['route'],
        $item['icon'],
        $item['color_class'],
        $item['parent_id'],
        $item['sort_order']
    ]);
    
    if ($result) {
        echo "✓ ID {$item['id']}: {$item['label']} - INSERTADO\n";
    } else {
        echo "✗ ID {$item['id']}: Error en insert\n";
    }
}

// Reinsertar permisos
echo "\n=== Asignando permisos a todos los roles ===\n";
$menuIds = [47, 48, 49];
$roleIds = [1, 2, 3, 4, 5];

foreach ($menuIds as $menuId) {
    foreach ($roleIds as $roleId) {
        $sql = "INSERT IGNORE INTO admin_menu_roles (menu_id, role_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$menuId, $roleId]);
    }
    echo "✓ Menu ID {$menuId}: Permisos asignados a roles 1-5\n";
}

// Verificar
echo "\n=== VERIFICACIÓN ===\n";
$check = $pdo->query("SELECT id, label, parent_id FROM admin_menu WHERE id IN (41,45,47,48,49) ORDER BY id");
$rows = $check->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    $indent = $row['parent_id'] ? '  └─ ' : '';
    echo "{$indent}ID {$row['id']}: {$row['label']}\n";
}

echo "</pre>";
echo "<p style='color: green; font-weight: bold;'>✓ Corrección completada. <a href='admin/index.php'>Ir al admin</a></p>";
?>
