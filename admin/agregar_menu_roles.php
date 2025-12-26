<?php
require_once(__DIR__ . '/classes/conexion.php');

echo "=== MENÚS DE ADMINISTRACIÓN ===\n\n";

// Buscar menús relacionados con admin/config
$stmt = $GLOBALS['pdo']->query("
    SELECT id, label, route, parent_id, icon, sort_order 
    FROM admin_menu 
    WHERE label LIKE '%Administra%' OR label LIKE '%Config%' OR parent_id IS NULL
    ORDER BY id
");

$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($menus)) {
    echo "No se encontraron menús de administración. Mostrando todos los menús:\n\n";
    $stmt = $GLOBALS['pdo']->query("SELECT id, label, route, parent_id FROM admin_menu ORDER BY parent_id, sort_order");
    $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

foreach ($menus as $menu) {
    $parent = $menu['parent_id'] ? "Parent: {$menu['parent_id']}" : "RAÍZ";
    echo sprintf("ID: %-3s | %-30s | Route: %-35s | %s\n", 
        $menu['id'], 
        $menu['label'], 
        $menu['route'], 
        $parent
    );
}

echo "\n\n=== AGREGAR MENÚ DE ROLES Y PERMISOS ===\n";

// Buscar si ya existe
$stmt = $GLOBALS['pdo']->prepare("SELECT id FROM admin_menu WHERE route = 'rolesPermisos'");
$stmt->execute();
$existe = $stmt->fetch();

if ($existe) {
    echo "✓ El menú 'rolesPermisos' ya existe (ID: {$existe['id']})\n";
} else {
    echo "Agregando menú 'Roles y Permisos'...\n";
    
    // Buscar un parent apropiado (por ejemplo, si hay un menú de Administración)
    $stmt = $GLOBALS['pdo']->query("
        SELECT id FROM admin_menu 
        WHERE parent_id IS NULL AND (label LIKE '%Administra%' OR label LIKE '%Sistema%')
        ORDER BY id 
        LIMIT 1
    ");
    $parent = $stmt->fetch();
    
    $parentId = $parent ? $parent['id'] : null;
    
    // Insertar el nuevo menú
    $stmt = $GLOBALS['pdo']->prepare("
        INSERT INTO admin_menu (label, route, icon, color_class, parent_id, sort_order, enabled)
        VALUES ('Roles y Permisos', 'rolesPermisos', 'fas fa-user-shield', '', ?, 999, 1)
    ");
    $stmt->execute([$parentId]);
    $nuevoId = $GLOBALS['pdo']->lastInsertId();
    
    echo "✓ Menú agregado con ID: $nuevoId\n";
    
    // Asignar permiso solo a Admin (rol 1)
    $stmt = $GLOBALS['pdo']->prepare("
        INSERT INTO admin_menu_roles (menu_id, role_id) VALUES (?, 1)
    ");
    $stmt->execute([$nuevoId]);
    
    echo "✓ Permiso asignado al rol Administrador\n";
}

echo "\n✓ Listo! Accede a: admin/rolesPermisos.php\n";
?>
