<?php
require_once('admin/classes/conexion.php');

// 1. Verificar que el menú existe
$stmt = $pdo->prepare('SELECT * FROM admin_menu WHERE id = 40');
$stmt->execute();
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if ($menu) {
    echo "✓ Menú encontrado:\n";
    echo "  - Label: {$menu['label']}\n";
    echo "  - Route: {$menu['route']}\n";
    echo "  - Parent ID: {$menu['parent_id']}\n";
    echo "  - Enabled: {$menu['enabled']}\n";
    echo "  - Icon: {$menu['icon']}\n\n";
} else {
    echo "✗ Menú con ID 40 NO encontrado en admin_menu\n";
    exit;
}

// 2. Verificar permisos en admin_menu_roles
$stmt = $pdo->prepare('SELECT * FROM admin_menu_roles WHERE menu_id = 40');
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($roles)) {
    echo "✗ NO hay permisos en admin_menu_roles para menu_id=40\n";
    echo "   Esto explica por qué no aparece en el sidebar.\n\n";
    echo "   Ejecutando INSERT para rol Admin (role_id=1)...\n";
    
    // Insertar permiso para admin
    $stmt = $pdo->prepare('INSERT INTO admin_menu_roles (menu_id, role_id) VALUES (40, 1)');
    $stmt->execute();
    echo "   ✓ Permiso agregado exitosamente\n";
} else {
    echo "✓ Permisos encontrados:\n";
    foreach ($roles as $r) {
        echo "  - menu_id: {$r['menu_id']}, role_id: {$r['role_id']}\n";
    }
}

// 3. Verificar padre
if ($menu['parent_id']) {
    $stmt = $pdo->prepare('SELECT label FROM admin_menu WHERE id = ?');
    $stmt->execute([$menu['parent_id']]);
    $padre = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($padre) {
        echo "\n✓ Padre encontrado: {$padre['label']} (ID: {$menu['parent_id']})\n";
        
        // Verificar que el padre también tenga permisos
        $stmt = $pdo->prepare('SELECT * FROM admin_menu_roles WHERE menu_id = ?');
        $stmt->execute([$menu['parent_id']]);
        $padreRoles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($padreRoles)) {
            echo "  ✗ ALERTA: El padre NO tiene permisos en admin_menu_roles\n";
            echo "  Agregando permiso para rol Admin...\n";
            $stmt = $pdo->prepare('INSERT INTO admin_menu_roles (menu_id, role_id) VALUES (?, 1)');
            $stmt->execute([$menu['parent_id']]);
            echo "  ✓ Permiso del padre agregado\n";
        }
    } else {
        echo "\n✗ ADVERTENCIA: Padre ID {$menu['parent_id']} no encontrado en admin_menu\n";
    }
}

echo "\n✓ Proceso completado. Recarga el sidebar para ver los cambios.\n";
?>
